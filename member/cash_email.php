<?php
/**
 * @version        $Id: cash_email.php 1 8:38 2010年8月9日Z SZ $
 */
 
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'op';
$myurl = $cfg_basehost.$cfg_member_dir.'/index.php?uid='.$cfg_ml->M_LoginID;
$moneycards = '';
$membertypes = '';

	$sessName=$cfg_ml->M_LoginID.'vd';
	$userhash = GetEmailCode($sessName,"new");
	
	if(!CheckEmail($cfg_ml->fields['email']) )
    {
        //ShowMsg('你的邮箱格式有错误！', '-1');
		$ShowMsg='你的邮箱格式有错误！';
		$emailArray=array(  
		'ShowMsg' => $ShowMsg, 
		'userid' => $cfg_ml->M_ID, 
		'coin' => $coinid
		);

		$json_string = json_encode($emailArray);  
		echo $json_string;
        exit();
    }

	//$userhash = $_SESSION[$userip.$coinid.'vd'];

	$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath);
    $url = preg_replace("#http:\/\/#i", '', $url);
    $url = 'http://'.preg_replace("#\/\/#i", '/', $url);
    $mailtitle = "{$cfg_webname}--会员提款验证";
    /*$mailbody = '';
    $mailbody .= "尊敬的用户[{$cfg_ml->fields['uname']}]，您好：\r\n";
    $mailbody .= "您的提款验证码为：$userhash\r\n";
    $mailbody .= "请不要将提款验证码透露给他人，如果您没有进行提款操作请立即与网站管理员联系！\r\n\r\n";
    $mailbody .= "{$url}\r\n\r\n";*/
	$mailbody = file_get_contents(dirname(__FILE__).'/templets/code_mail.htm');
	$mailbody = str_replace("[username]","[{$cfg_ml->fields['uname']}]",$mailbody);
	$mailbody = str_replace("[webname]","[{$cfg_webname}]",$mailbody);
	$mailbody = str_replace("[emailcode]","{$userhash}",$mailbody);
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
			$mail->SMTPDebug  = 0;
			$mail->Debugoutput = 'txt';
			$mail->Host       = $cfg_smtp_server;
			$mail->Port       = $cfg_smtp_port;
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPAuth   = true;
			$mail->Username   = $cfg_smtp_usermail;
			$mail->Password   = $cfg_smtp_password;
			$mail->SetFrom($cfg_smtp_usermail, $cfg_webname);
			$mail->AddReplyTo($cfg_smtp_usermail, $cfg_webname);
			$mail->AddAddress($cfg_ml->fields['email'], $cfg_ml->fields['uname']);
			$mail->Subject = $mailtitle;
			$mail->MsgHTML(str_replace("\r\n","<br>",$mailbody));
			if(!$mail->Send()) {
			  ShowMsg('发送失败！', '/member');
				exit();
			}
		}
    }
    else
    {
        //@mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
		mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
    }
    $ShowMsg='成功发送邮件，请稍后登录你的邮箱进行接收验证码！';
	$emailArray=array(  
    'ShowMsg' => $ShowMsg, 
	'userid' => $cfg_ml->M_ID, 
	'coin' => $coinid
    );

$json_string = json_encode($emailArray);  
echo $json_string;
