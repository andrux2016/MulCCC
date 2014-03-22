<?php
/**
 * @version        $Id: index_do.php 1 8:24 2010年7月9日Z SZ $
 */
require_once(dirname(__FILE__)."/config.php");
if(empty($dopost)) $dopost = '';
if(empty($fmdo)) $fmdo = '';

/*********************
function check_email()
*******************/
if($fmdo=='sendMail')
{
    if(!CheckEmail($cfg_ml->fields['email']) )
    {
        ShowMsg('你的邮箱格式有错误！', '-1');
        exit();
    }
    if($cfg_ml->fields['spacesta'] != -10)
    {
        ShowMsg('你的帐号不在邮件验证状态，本操作无效！', '-1');
        exit();
    }
    $userhash = md5($cfg_cookie_encode.'--'.$cfg_ml->fields['mid'].'--'.$cfg_ml->fields['email']);
    $url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/member/index_do.php?fmdo=checkMail&mid={$cfg_ml->fields['mid']}&userhash={$userhash}&do=1";
    $url = preg_replace("#http:\/\/#i", '', $url);
    $url = 'http://'.preg_replace("#\/\/#i", '/', $url);
    $mailtitle = "{$cfg_webname}--会员邮件验证通知";
    /*$mailbody = '';
    $mailbody .= "尊敬的用户[{$cfg_ml->fields['uname']}]，您好：\r\n";
    $mailbody .= "欢迎注册成为[{$cfg_webname}]的会员。\r\n";
    $mailbody .= "要通过注册，还必须进行最后一步操作，请点击或复制下面链接到地址栏访问这地址：\r\n\r\n";
	$mailbody .= "<a href='{$url}'>点击这里</a>\r\n\r\n";
    $mailbody .= "{$url}\r\n\r\n";*/
	$mailbody = file_get_contents(dirname(__FILE__).'/templets/reg_mail.htm');
	$mailbody = str_replace("[username]","[{$cfg_ml->fields['uname']}]",$mailbody);
	$mailbody = str_replace("[webname]","[{$cfg_webname}]",$mailbody);
	$mailbody = str_replace("[codeurl]","{$url}",$mailbody);
  
    $headers = "From: ".$cfg_adminemail."\r\nReply-To: ".$cfg_adminemail;
    if($cfg_sendmail_bysmtp == 'Y' && !empty($cfg_smtp_server))
    {
        if(cfgsmtpssl=='N'){
			$mailtype = 'TXT';
			require_once(DEDEINC.'/mail.class.php');
			$smtp = new smtp($cfg_smtp_server,$cfg_smtp_port,true,$cfg_smtp_usermail,$cfg_smtp_password);
			$smtp->debug = false;
			$smtp->sendmail($cfg_ml->fields['email'],$cfg_webname ,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
		}else{
			require DEDEINC.'/class.phpmailer.php';
			$mail = new PHPMailer();
			$mail->IsSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug  = 0;
			//$mail->Debugoutput = 'html';
			$mail->Host       = $cfg_smtp_server;
			$mail->Port       = $cfg_smtp_port;
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPAuth   = true;
			$mail->Username   = $cfg_smtp_usermail;
			$mail->Password   = $cfg_smtp_password;
			$mail->SetFrom($cfg_smtp_usermail, $cfg_webname);
			$mail->AddReplyTo($cfg_smtp_usermail, $cfg_webname);
			//echo $mailbody;
			$mail->AddAddress($cfg_ml->fields['email'], $cfg_ml->fields['uname']);
			$mail->Subject = $mailtitle;
			$mail->MsgHTML(str_replace("\r\n","<br>",$mailbody));
			//$mail->AltBody = $mailbody;
			//$mail->AddAttachment('../images/loadinglit.gif');
			if(!$mail->Send()) {
			  ShowMsg('发送失败！', '/member');
				exit();
			} else {
			  ShowMsg('成功发送邮件，请稍后登录你的邮箱进行接收！', '/member');
				exit();
			}
		}
    }
    else
    {
        //@mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
		mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
		
    }

	ShowMsg('成功发送邮件，请稍后登录你的邮箱进行接收！', '/member');
    exit();
}
else if($fmdo=='checkMail')
{
    $mid = intval($mid);
    if(empty($mid))
    {
        ShowMsg('你的效验串不合法！', '-1');
        exit();
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE mid='{$mid}' ");
    $needUserhash = md5($cfg_cookie_encode.'--'.$mid.'--'.$row['email']);
    if($needUserhash != $userhash)
    {
        ShowMsg('你的效验串不合法！', '-1');
        exit();
    }
    if($row['spacesta'] != -10)
    {
        ShowMsg('你的帐号不在邮件验证状态，本操作无效！', '-1');
        exit();
    }
    $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET spacesta=0 WHERE mid='{$mid}' ");
    // 清除会员缓存
    $cfg_ml->DelCache($mid);
	$reward=preg_replace("#[^.0-9-]#", "", $cfg_rec_reward);
	$recoinid=GetCoinID(preg_replace("#[^a-zA-Z-]#", "", $cfg_rec_reward));
	if($row['jsuserid']!="0" && $row['jsuserid']!="" && $recoinid!="" && $reward>0){
		$dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."btcdeduct(newuserid,userid,deduct,dealtime,coinid) Values('".$mid."','".$row['jsuserid']."','".$reward."','".time()."','".$recoinid."')");
	}
    ShowMsg('操作成功，请重新登录系统！', 'login.php');
    exit();
}
/*********************
function Case_user()
*******************/
else if($fmdo=='user')
{

    //检查用户名是否存在
    if($dopost=="checkuser")
    {
        AjaxHead();
        $msg = '';
        $uid = trim($uid);
        if($cktype==0)
        {
            $msgtitle='用户笔名';
        }
        else
        {
            #api{{
            if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
            {
                $ucresult = uc_user_checkname($uid);
                if($ucresult > 0)
                {
                    echo "<font color='#4E7504'><b>√用户名可用</b></font>";
                }
                elseif($ucresult == -1)
                {
                    echo "<font color='red'><b>×用户名不合法</b></font>";
                }
                elseif($ucresult == -2)
                {
                    echo "<font color='red'><b>×包含要允许注册的词语</b></font>";
                }
                elseif($ucresult == -3)
                {
                    echo "<font color='red'><b>×用户名已经存在</b></font>";
                }
                exit();
            }
            #/aip}}            
            $msgtitle='用户名';
        }
        if($cktype!=0 || $cfg_mb_wnameone=='N') {
            $msg = CheckUserID($uid, $msgtitle);
        }
        else {
            $msg = CheckUserID($uid, $msgtitle, false);
        }
        if($msg=='ok')
        {
            $msg = "<font color='#4E7504'><b>√{$msgtitle}可以使用</b></font>";
        }
        else
        {
            $msg = "<font color='red'><b>×{$msg}</b></font>";
        }
        echo $msg;
        exit();
    }

    //检查email是否存在
    else  if($dopost=="checkmail")
    {
        AjaxHead();
        
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            $ucresult = uc_user_checkemail($email);
            if($ucresult > 0) {
                echo "<font color='#4E7504'><b>√可以使用</b></font>";
            } elseif($ucresult == -4) {
                echo "<font color='red'><b>×Email 格式有误！</b></font>";
            } elseif($ucresult == -5) {
                echo "<font color='red'><b>×Email 不允许注册！</b></font>";
            } elseif($ucresult == -6) {
                echo "<font color='red'><b>×该 Email 已经被注册！</b></font>";
            }
            exit();
        }
        #/aip}}    
        
        if($cfg_md_mailtest=='N')
        {
            $msg = "<font color='#4E7504'><b>√可以使用</b></font>";
        }
        else
        {
            if(!CheckEmail($email))
            {
                $msg = "<font color='#4E7504'><b>×Email格式有误</b></font>";
            }
            else
            {
                 $row = $dsql->GetOne("SELECT mid FROM `#@__member` WHERE email LIKE '$email' LIMIT 1");
                 if(!is_array($row)) {
                     $msg = "<font color='#4E7504'><b>√可以使用</b></font>";
                 }
                 else {
                     $msg = "<font color='red'><b>×Email已经被另一个帐号占用！</b></font>";
                 }
            }
        }
        echo $msg;
        exit();
    }

    //引入注册页面
    else if($dopost=="regnew")
    {
        $step = empty($step)? 1 : intval(preg_replace("/[^\d]/",'', $step));
        require_once(dirname(__FILE__)."/reg_new.php");
        exit();
    }
  /***************************
  //积分换金币
  function money2s() {  }
  ***************************/
    else if($dopost=="money2s")
    {
        CheckRank(0,0);
        if($cfg_money_scores==0)
        {
            ShowMsg('系统禁用了积分与金币兑换功能！', '-1');
            exit();
        }
        $money = empty($money) ? "" : abs(intval($money));
        if(empty($money))
        {
            ShowMsg('您没指定要兑换多少金币！', '-1');
            exit();
        }
        
        $needscores = $money * $cfg_money_scores;
        if($cfg_ml->fields['scores'] < $needscores )
        {
            ShowMsg('您积分不足，不能换取这么多的金币！', '-1');
            exit();
        }
        $litmitscores = $cfg_ml->fields['scores'] - $needscores;
        
        //保存记录
        $mtime = time();
        $inquery = "INSERT INTO `#@__member_operation`(`buyid` , `pname` , `product` , `money` , `mtime` , `pid` , `mid` , `sta` ,`oldinfo`)
           VALUES ('ScoresToMoney', '积分换金币操作', 'stc' , '0' , '$mtime' , '0' , '{$cfg_ml->M_ID}' , '0' , '用 {$needscores} 积分兑了换金币：{$money} 个'); ";
        $dsql->ExecuteNoneQuery($inquery);
        //修改积分与金币值
        $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET `scores`=$litmitscores, money= money + $money  WHERE mid='".$cfg_ml->M_ID."' ");
        
        // 清除会员缓存
        $cfg_ml->DelCache($cfg_ml->M_ID);
        ShowMsg('成功兑换指定量的金币！', 'operation.php');
        exit();
    }
}

/*********************
function login()
*******************/

else if($fmdo=='login')
{
    function showJson($msg,$ruslt){
			$userArray=array(  
			'showMsg' => $msg, 
			'ruslt' => $ruslt,
			);
		
			$json_string = json_encode($userArray);  
			echo $json_string;
		}
	
	//用户登录
    if($dopost=="login")
    {
        exit();
		if(!isset($vdcode))
        {
            $vdcode = '';
        }
        $svali = GetCkVdValue();

        if(preg_match("/2/",$safe_gdopen)){
            if(strtolower($vdcode)!=$svali || $svali=='')
            {
                ResetVdValue();
				
                if($gourl=="json"){
					//showJson(strtolower($vdcode)."-".GetCkVdValue(),'f');
				}
				else {
					ShowMsg('验证码错误！', '-1');
					exit();
				}
            }
            
        }
        if(CheckUserID($userid,'',false)!='ok')
        {
            if($gourl=="json") showJson('你输入的用户名 {$userid} 不合法！','f');
			else ShowMsg("你输入的用户名 {$userid} 不合法！","-1");
            exit();
        }
        if($pwd=='')
        {
            if($gourl=="json") showJson('密码不能为空！','f');
			else ShowMsg("密码不能为空！","-1",0,2000);
            exit();
        }

        //检查帐号
        $rs = $cfg_ml->CheckUser($userid,$pwd);  
        
        
        if($rs==0)
        {
            if($gourl=="json") showJson('用户名不存在！','f');
				else ShowMsg("用户名不存在！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-1) {
            if($gourl=="json") showJson('密码错误！','f');
				else ShowMsg("密码错误！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-2) {
            if($gourl=="json") showJson('管理员帐号不允许从前台登录！','f');
				else ShowMsg("管理员帐号不允许从前台登录！", "-1", 0, 2000);
            exit();
        }
        else
        {
            // 清除会员缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
            if(empty($gourl) || preg_match("#action|_do#i", $gourl))
            {
                if($gourl=="json") showJson('成功登录！','t');
				else ShowMsg("成功登录，5秒钟后转向系统主页...",$cfg_basehost,0,2000);
            }
            else
            {
                $gourl = str_replace('^','&',$gourl);
                //ShowMsg("成功登录，现在转向指定页面...",$gourl,0,2000);
				if($gourl=="json") showJson('成功登录！','t');
				else ShowMsg("成功登录，现在转向指定页面...",$cfg_basehost,0,2000);
				
            }
            exit();
        }
		
    }

    //退出登录
    else if($dopost=="exit")
    {
        $cfg_ml->ExitCookie();
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            $ucsynlogin = uc_user_synlogout();
        }
        #/aip}}
        ShowMsg("成功退出登录！",$cfg_basehost,0,2000);
        exit();
    }
}
/*********************
function moodmsg()
*******************/
else if($fmdo=='moodmsg')
{
    //用户登录
    if($dopost=="sendmsg")
    {
        if(!empty($content))
        {
        $ip = GetIP();
        $dtime = time();
          $ischeck = ($cfg_mb_msgischeck == 'Y')? 0 : 1;
          if($cfg_soft_lang == 'gb2312')
          {
              $content = utf82gb(nl2br($content));
          } 
          $content = cn_substrR(HtmlReplace($content,1),360);
          //对表情进行解析
          $content = addslashes(preg_replace("/\[face:(\d{1,2})\]/is","<img src='".$cfg_memberurl."/templets/images/smiley/\\1.gif' style='cursor: pointer; position: relative;'>",$content));
          
            $inquery = "INSERT INTO `#@__member_msg`(`mid`,`userid`,`ip`,`ischeck`,`dtime`, `msg`)
                   VALUES ('{$cfg_ml->M_ID}','{$cfg_ml->M_LoginID}','$ip','$ischeck','$dtime', '$content'); ";
            $rs = $dsql->ExecuteNoneQuery($inquery);
            if(!$rs)
            {
                $output['type'] = 'error';
                $output['data'] = '更新失败,请重试.';
                exit();
            }
            $output['type'] = 'success';
            if($cfg_soft_lang == 'gb2312')
            {
              $content = utf82gb(nl2br($content));
            } 
            $output['data'] = stripslashes($content);
            exit(json_encode($output));
        }
    }
}
else
{
    ShowMsg("本页面禁止返回!","index.php");
}

function GetCoinID($cointype){
	global $dsql;
	$row = $dsql->GetOne("SELECT id FROM `#@__btctype` WHERE cointype='{$cointype}' ");
	if(is_array($row)){
		return $row['id'];
	}else{
		return "";
	}
}