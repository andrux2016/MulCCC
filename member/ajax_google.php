<div id="googleDiv" style="float:left; margin-bottom:30px; margin-top:20px">
<ul style="float:left;">
<?php
/**
 * @version        $Id: ajax_trans.php 1 8:38 2013年8月29日Z
 */
require_once(dirname(__FILE__)."/config.php");


require_once('GoogleAuthenticator.php');

$ga = new PHPGangsta_GoogleAuthenticator();

if($cfg_ml->M_LoginID==""){
echo "登录超时！";
}else{
	if($cfg_ml->M_Google==""){
		$showhtml="<li style='border-bottom:1px solid #000000;'><strong>添加谷歌身份验证器</strong></li>";
		$secret = $ga->createSecret();
		$qrCodeUrl = $ga->getQRCodeGoogleUrl($cfg_webname.":".$cfg_ml->M_LoginID, $secret, $cfg_basehost);
		$showhtml.="<li style='float:left'><img src='".$qrCodeUrl."' /><br>密钥：<input  value='".$secret."' disabled='disabled' /><input id='secret' value='".$secret."' type='hidden' /></li>";
		$showhtml.="<li style='float:left; margin-left:10px;width:260px'>请先扫瞄二维码或手工输入密钥将生成的验证码填写到输入框<br>";
		$showhtml.="设备名称：<br><input name='webname' value='".$cfg_webname.":".$cfg_ml->M_LoginID."' disabled='disabled' /><br>";
		$showhtml.="验证码：<br><input id='userCode' type='text' /><br><input type='button' onclick='googleSub();' value=' 提  交 ' />";
		$showhtml.="<br><span id='msgDiv'></span></li>";
	}else{
		echo "您已经开通goole验证功能！";
	}
}

echo $showhtml;
?>
</ul>

</div>