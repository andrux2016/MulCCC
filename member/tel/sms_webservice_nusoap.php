<?php
header ( "Content-Type: text/html; charset=utf-8" );

//2种模式
//1.in WSDL mode
//2.in non-WSDL mode

require_once("nusoap/nusoap.php");

//$client = new soapclient('http://106.ihuyi.com/webservice/sms.php?WSDL',true);
//$client = new soapclient('http://106.ihuyi.com/webservice/sms.php?WSDL','wsdl');
//print_r($client);
$client = new nusoap_client('http://106.ihuyi.com/webservice/sms.php?WSDL','wsdl');

$err = $client->getError();
if ($err) {
    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = false;
$client->xml_encoding = 'UTF-8';
$parameters=array('account'=>'用户名','password'=>'密码','content'=>'您的验证码是：2568。请不要把验证码泄露给其他人。','mobile'=>'手机号码');


echo ('<pre>');
print_r($client->call('Submit',$parameters));
echo ('</pre>');


//代理
//$proxy=$client -> getProxy(); //创建代理对象 (soap_proxy 类 )
//echo ('<pre>');
//print_r($proxy->Submit($parameters)); //直接调用 WEB 服务
//echo ('</pre>');


//服务器出错，调试代码
//echo '<h2>Request</h2><pre>' . $client->request. '</pre>';
//echo '<h2>Response</h2><pre>' .$client->response. '</pre>';
//echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
?>
