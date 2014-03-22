<?php
/**
 * @version        $Id: index_do.php 1 8:24 2010年7月9日Z SZ $
 */

    //$userhash = md5($cfg_cookie_encode.'--'.$cfg_ml->fields['mid'].'--'.$cfg_ml->fields['email']);
    //$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/member/index_do.php?fmdo=checkMail&mid={$cfg_ml->fields['mid']}&userhash={$userhash}&do=1";
	$webname="1111";
    $url = preg_replace("#http:\/\/#i", '', $url);
    $url = 'http://'.preg_replace("#\/\/#i", '/', $url);
    $mailtitle = "1111111";
    $mailbody = '11111';
    //$mailbody .= "尊敬的用户，您好：\r\n";
    //$mailbody .= "欢迎注册成为[]的会员。\r\n";
    //$mailbody .= "要通过注册，还必须进行最后一步操作，请点击或复制下面链接到地址栏访问这地址：\r\n\r\n";
    //$mailbody .= "\r\n\r\n";
  
    $headers = "From: 6808525@qq.com\r\nReply-To: 6808525@qq.com";
    //if($cfg_sendmail_bysmtp == 'Y' && !empty($cfg_smtp_server))
    //{
        /*$mailtype = 'TXT';
        require_once('../include/mail.class.php');
        $smtp = new smtp("smtp.126.com",25,1,"btcszcom@126.com","btcsz.com");
		print_r($smtp);
        $smtp->debug = false;
        $smtp->sendmail("2062092@qq.com",$webname ,"btcszcom@126.com", $mailtitle, $mailbody, $mailtype);*/
		
		echo $mailtitle.$mailbody.$headers;
		mail("2062092@qq.com", $mailtitle, $mailbody, $headers);
    //}
    //else
    //{
       // @mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
    //}
    //ShowMsg('成功发送邮件，请稍后登录你的邮箱进行接收！', '/member');
	//echo "<br>".$cfg_ml->fields['email'],$webname ,"btcszcom@126.com", $mailtitle, $mailbody, $mailtype;
	echo "<br>".$cfg_smtp_server.$cfg_smtp_port.true.$cfg_smtp_usermail.$cfg_smtp_password;
    exit();
