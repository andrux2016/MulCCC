<?php
header("Content-type:text/html; charset=gb2312"); 

//提交地址
/*if($_POST['test'] == '1')
{
	$form_url = 'https://pay.ips.net.cn/ipayment.aspx'; //测试
}
else
{*/
	$form_url = 'http://kq-shop.com/plugins/payment/pay.ips3.php'; //正式   客趣商城转接接口，请勿修改！ 
/*}*/


//这里填写或者提交环迅的商户id和商户证书密钥

//商户号
//$Mer_code2 = $_POST['Mer_code'];
$Mer_code2 = "024454";

//商户证书：登陆http://merchant.ips.com.cn/商户后台下载的商户证书内容
//$Mer_key2 = $_POST['Mer_key'];
$Mer_key2 = "52632211099462864512673941301608095160170711534054159547969080938046669333195140735754845829019088249738571545181925518410184931";


//以下账户ID和密钥是客趣商城转接商户，请勿修改，否则无法接入
$Mer_code="10085";
$Mer_key = "bd4ded6a7e9ebbf7f94513b0f288fcc8";
//商户订单号
//$Billno = $_POST['Billno'];
$Billno = $_POST['Billno'];
//$Billno = date('YmdHis') . mt_rand(100000,999999);



//订单金额(保留2位小数)
//$Amount = number_format($_POST['Amount'], 2, '.', '');
$Amount = $_POST['amount']?number_format($_POST['amount'], 2, '.', ''):number_format($_POST['Amount'], 2, '.', '');

//订单日期
//$Date = $_POST['Date'];
$Date = date('Ymd');

//币种
//$Currency_Type = $_POST['Currency_Type'];
$Currency_Type = "RMB";

//支付卡种
//$Gateway_Type = $_POST['Gateway_Type'];
$Gateway_Type = "01";

//银行编号
//$Bankco=$_POST['Bankco'];
$Bankco=$_POST['account']?$_POST['account']:$_POST['Bankco'];

//语言
//$Lang = $_POST['Lang'];
$Lang = "GB";

//支付结果成功返回的商户URL
//$Merchanturl = $_POST['Merchanturl'];
//$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url = 'https://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('redirect', 'OrderReturn', $url);
$Merchanturl = $url;


//支付结果失败返回的商户URL
//$FailUrl = $_POST['FailUrl'];
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('redirect', 'OrderFail', $url);
$FailUrl = $url;


//支付结果错误返回的商户URL
$ErrorUrl = "";
/*$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('redirect', 'OrderErr', $url);
$ErrorUrl = $url;*/

//商户数据包
//$Attach = $_POST['Attach'];
$Attach = "123";

//显示金额
//$DispAmount = $_POST['DispAmount'];
$DispAmount = $Amount;

//订单支付接口加密方式
//$OrderEncodeType = $_POST['OrderEncodeType'];
$OrderEncodeType = "5";

//交易返回接口加密方式 
//$RetEncodeType = $_POST['RetEncodeType'];
$RetEncodeType = "17";

//返回方式
//$Rettype = $_POST['Rettype'];
$Rettype = 1;

//Server to Server 返回页面URL
//$ServerUrl = $_POST['ServerUrl'];
//商户返回地址
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('orderpay', 'OrderReturn', $url);
$ServerUrl = $url;

//OrderEncodeType设置为5，且在订单支付接口的Signmd5字段中存放MD5摘要认证信息。
//交易提交接口MD5摘要认证的明文按照指定参数名与值的内容连接起来，将证书同时拼接到参数字符串尾部进行md5加密之后再转换成小写，明文信息如下：
//billno+【订单编号】+ currencytype +【币种】+ amount +【订单金额】+ date +【订单日期】+ orderencodetype +【订单支付接口加密方式】+【商户内部证书字符串】
//例:(billno000001000123currencytypeRMBamount13.45date20031205orderencodetype5GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)
//订单支付接口的Md5摘要，原文=订单号+金额+日期+支付币种+商户证书 
$orge = 'billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key ;
//echo '明文:'.$orge ;
//$SignMD5 = md5('billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key);
$SignMD5 = md5($orge) ;
//echo '密文:'.$SignMD5 ;
//sleep(20);





//这里可以加入提交支付时候的订单记录

//insert into table......

//提交支付订单记录处理完毕






?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=gb2312" />
  </head>
  <body>
 <!--说明：请勿修改下面的表单，为了在客趣商城转接这个环迅接口，我在环迅官方接口表单中加了一个Mer_key参数，其他的都是参照环迅官方接口技术文档执行-->
  
    <form action="<?php echo $form_url ?>" method="post" id="frm1">
      <input type="hidden" name="Mer_code" value="<?php echo $Mer_code2 ?>">
      <input type="hidden" name="Mer_key" value="<?php echo $Mer_key2 ?>">
      <input type="hidden" name="Billno" value="<?php echo $Billno ?>">
      <input type="hidden" name="Amount" value="<?php echo $Amount ?>" >
      <input type="hidden" name="Date" value="<?php echo $Date ?>">
      <input type="hidden" name="Currency_Type" value="<?php echo $Currency_Type ?>">
      <input type="hidden" name="Gateway_Type" value="<?php echo $Gateway_Type ?>">
      <input type="hidden" name="Lang" value="<?php echo $Lang ?>">
      <input type="hidden" name="Merchanturl" value="<?php echo $Merchanturl ?>">
      <input type="hidden" name="FailUrl" value="<?php echo $FailUrl ?>">
      <input type="hidden" name="ErrorUrl" value="<?php echo $ErrorUrl ?>">
      <input type="hidden" name="Attach" value="<?php echo $Attach ?>">
      <input type="hidden" name="DispAmount" value="<?php echo $DispAmount ?>">
      <input type="hidden" name="OrderEncodeType" value="<?php echo $OrderEncodeType ?>">
      <input type="hidden" name="RetEncodeType" value="<?php echo $RetEncodeType ?>">
      <input type="hidden" name="Rettype" value="<?php echo $Rettype ?>">
      <input type="hidden" name="ServerUrl" value="<?php echo $ServerUrl ?>">
      <input type="hidden" name="Bankco" value="<?php echo $Bankco ?>">
      <input type="hidden" name="SignMD5" value="<?php echo $SignMD5 ?>">
      <INPUT TYPE="hidden" name="DoCredit" value="1">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
    
  </body>
</html>
