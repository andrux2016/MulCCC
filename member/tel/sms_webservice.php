<?php
header ( "Content-Type: text/html; charset=utf-8" );

//在php.ini中开启extension=php_soap.dll
try {

	$client = new SoapClient ( "http://106.ihuyi.com/webservice/sms.php?WSDL", array ('trace' => 1, 'uri' => 'http://106.ihuyi.com/' ) );	

	/*echo ("SOAP服务器提供的开放函数:");
	echo ('<pre>');
	print_r( $client->__getFunctions () );
	echo ('</pre>');
	echo ("SOAP服务器提供的Type:");
	echo ('<pre>');
	print_r( $client->__getTypes () );
	echo ('</pre>');*/

	$data['account'] = $_POST['user'];
	$data['password'] =$_POST['pw'];
	$data['content'] = '您的验证码是：8569。请不要把验证码泄露给其他人。';	
	$data['mobile'] = $_POST['phone'];
	
	$out = $client->Submit($data);
	echo ('<pre>');
	print_r($out);
	echo ('</pre>');
	
} catch (SoapFault $fault){
	echo "Error: ",$fault->faultcode,", string: ",$fault->faultstring;
}

?>
