<?php
/*
@version        $Id: edit_baseinfo_btc.php 1 8:38 2010年8月9日Z SZ $
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if(!isset($dopost)) $dopost = '';


$cfg_arrcoin=Getdeposit("",$cfg_ml->M_ID);

foreach ($cfg_arrcoin as $value){

	if($value['0']=="CNY"){
		$coinhtml.="<li>".$value['0']."：<span>".(floor($value['1']*100)/100)."</span><span class='but'><a href='buy_btc.php' >充值</a></span></li>";
		$freehtml.="<li>冻结：<span>".($value['2']/1)."</span></li>";
	}else{
		$coinhtml.="<li>".$value['0']."：<span>".(floor($value['1']*100)/100)."</span></li>";
		$freehtml.="<li>冻结：<span>".($value['2']/1)."</span></li>";
	}
	$coinvol+=$value['4'];
}
	

//替换字符
$oldpwd=safe_string($oldpwd);
$userpwd=safe_string($userpwd);
$userpwdok=safe_string($userpwdok);
$txPwd=safe_string($txPwd);
$txPwdok=safe_string($txPwdok);
$safequestion=preg_replace("#[^0-9-]#", "", $safequestion);
$safeanswer=safe_string($safeanswer);
$newsafequestion=preg_replace("#[^0-9-]#", "", $newsafequestion);
$newsafeanswer=safe_string($newsafeanswer);
$vdcode=preg_replace("#[^0-9A-Za-z-]#", "", $vdcode);


$pwd2=(empty($pwd2))? "" : $pwd2;
$row=$dsql->GetOne("SELECT  * FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
$face = $row['face'];
$nowtxpwd = $row['txpwd'];
$nowpwd = $row['pwd'];
$show=$show?$show:"1" ;
if($nowtxpwd=="") $show="2";

if($dopost=='save')
{
    $svali = GetCkVdValue();
	if($show==0)//新用户添加密码
    {
       	if(strlen($txPwd) < $cfg_mb_pwdmin || strlen($txPwd) > 20)
		{
			showJson("你的提现密码长度应该不少于".$cfg_mb_pwdmin."个字符，不大于20个字符！","-1");
			exit();
		}   
		
		if($txPwd != $txPwdok)
		{
			showJson("两次输入的密码不一致！","-1");
			exit();
		} 
		//安全问题
        if($safequestion != 0 && $safeanswer != '')
        {
            if(strlen($newsafeanswer) > 30)
            {
                showJson('你的新安全问题的答案太长了，请保持在30字节以内！','-1');
                exit();
            }
            else
            {
                $addupquery .= ",safequestion='$safequestion',safeanswer='$safeanswer'";
            }
        }else{
			showJson('请填写安全问题的和答案！','-1');
            exit();
		}
		if($nowtxpwd ==""){
			$txpwd = md5($txPwd);
			//$txpwd = substr(md5($txPwd),5,20);
			if($txpwd==$nowpwd){
				showJson('请不要使用登录密码作为提现密码！','-1');
            	exit();
			}
			$query1 = "UPDATE `#@__member` SET txpwd='$txpwd',safequestion='$safequestion',safeanswer='$safeanswer' where mid='".$cfg_ml->M_ID."' ";
    		$dsql->ExecuteNoneQuery($query1);
			showJson('提交安全资料成功！','1');
            exit();
		}
		
    }   
	
	
    if(strtolower($vdcode) != $svali || $svali=='')
    {
        ReSETVdValue();
        showJson('验证码错误！','-1');
        exit();
    }
    if(!is_array($row) || $row['pwd'] != md5($oldpwd))
    {
        showJson('你输入的旧密码错误或没填写，不允许修改资料！','-1');
        exit();
    }
	
	if($show==1 && (strlen($userpwd) < $cfg_mb_pwdmin || strlen($userpwd) > 20))
    {
        showJson("你的新密码长度应该不少于".$cfg_mb_pwdmin."个字符，不大于20个字符！","-1");
        exit();
    }   
    if($show==1 && $userpwd != $userpwdok)
    {
        showJson($userpwd."-".$userpwdok.'你两次输入的新密码不一致！','-1');
        exit();
    }
	if($show==2 && $txPwd != $txPwdok)
    {
        showJson('你两次输入的新密码不一致！','-1');
        exit();
    }
    if($userpwd=='')
    {
        $pwd = $row['pwd'];
    }
    else
    {
        $pwd = md5($userpwd);
        $pwd2 = substr(md5($userpwd),5,20);
    }
	if($txPwd=='')
    {
        $txpwd = $row['txpwd'];
    }
    else
    {
        $txpwd = $txPwd;
        $txpwdok = md5($txPwd);
    }
    $addupquery = '';
    
    
    //修改安全问题
    if($show==3)
    {
        if(($row['safequestion'] != 0 && $row['safequestion'] != $safequestion) || (!empty($row['safeanswer']) && $row['safeanswer'] != $safeanswer))
        {
            showJson('你的旧安全问题及答案不正确，不能修改安全问题！','-1');
            exit();
        }

        //修改安全问题
        if($newsafequestion != 0 && $newsafeanswer != '')
        {
            if(strlen($newsafeanswer) > 30)
            {
                showJson('你的新安全问题的答案太长了，请保持在30字节以内！','-1');
                exit();
            }
            else
            {
                $addupquery .= ",safequestion='$newsafequestion',safeanswer='$newsafeanswer'";
            }
        }
    }
	//修改提现密码
    if($show==2 && $txpwdok != $row['txpwd'])
    {
        if(($row['safequestion'] != 0 && $row['safequestion'] != $safequestion) || (!empty($row['safeanswer']) && $row['safeanswer'] != $safeanswer))
        {
            showJson('你的旧安全问题及答案不正确，不能修改提现密码！','-1');
            exit();
        }

		if(strlen($txpwd) < $cfg_mb_pwdmin || strlen($txpwd) > 20)
        {
            showJson("你的新提现密码长度应该大于".$cfg_mb_pwdmin ."个字符，小于20个字符！","-1");
            exit();
        }   
			$addupquery .= ",txpwd='$txpwdok'";
    }
    
    $query1 = "UPDATE `#@__member` SET pwd='$pwd'{$addupquery} where mid='".$cfg_ml->M_ID."' ";
    $dsql->ExecuteNoneQuery($query1);

    //如果是管理员，修改其后台密码
    if($cfg_ml->fields['matt']==10 && $pwd2!="")
    {
        $query2 = "UPDATE `#@__admin` SET pwd='$pwd2' where id='".$cfg_ml->M_ID."' ";
        $dsql->ExecuteNoneQuery($query2);
    }
    // 清除会员缓存
    $cfg_ml->DelCache($cfg_ml->M_ID);
    showJson('成功更新你的安全资料！',1);
    exit();
}

/**
 *  提示信息
 */
function showJson($msg,$ruslt){
	$msgArray=array(  
	'showMsg' => $msg, 
	'ruslt' => $ruslt,
	);
	$json_string = json_encode($msgArray);  
	echo $json_string;
}

include(DEDEMEMBER."/templets/edit_baseinfo_btc.htm");