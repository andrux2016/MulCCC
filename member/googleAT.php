<?php


require_once('config.php');
require_once 'GoogleAuthenticator.php';

if($cfg_ml->M_LoginID=="") 
{
showJson('请先登录！',-1);
	exit();
}

$ga = new PHPGangsta_GoogleAuthenticator();

if($userCode!=""){
	$secret = preg_replace("#[^0-9A-Za-z-]#", "", $secret);
	$secret = $cfg_ml->M_Google?$cfg_ml->M_Google:$secret;
	$checkResult = $ga->verifyCode($secret, $userCode, 2);    // 2 = 2*30sec clock tolerance
	if ($checkResult) {
		$msg='验证码正确！';
		if($cfg_ml->M_Google==""){
			$rsup = $dsql->ExecuteNoneQuery("Update #@__member Set google='".$secret."' where mid = '".$cfg_ml->M_ID."'"); 
			$msg.='开通google验证功能成功！';
		}
		showJson($msg,1);
		exit();
	} else {
		showJson('错误！请查看您的手机时间是否正确！',-1);
		exit();
	}
}else{
	showJson('请填写验证码！',-1);
	exit();
}
function showJson($msg,$ruslt){
	$msgArray=array(  
	'showMsg' => $msg, 
	'ruslt' => $ruslt,
	);
	$json_string = json_encode($msgArray);  
	echo $json_string;
}
?>

