<?php
require_once 'jsonRPCClient.php';
$method = $_GET['method'];
$cointype = $_GET['cointype'];
$params = $_GET['params'];

$rpcname = 'expleeve';
$rpcpasswd = '122333_abc';
$ipandport = '127.0.0.1:8332';

$params = json_decode(mchStrCode($params, 'DECODE'), true);

if($cointype == "BTC"){
	$bitcoin = new jsonRPCClient('http://'.$rpcname.':'.$rpcpasswd.'@'.$ipandport.'/');

	if($method == 'getnewaddress'){
		$content = $bitcoin->getnewaddress($params[0]);
		$returncont = array(
			'r' => $content
		);
		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif ($method == 'getaddressesbyaccount') {
		$content = $bitcoin->getaddressesbyaccount($params[0]);
		$returncont = array(
			'r' => $content
		);
		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif($method == 'listsinceblock'){
		$content = $bitcoin->listsinceblock($params[0]);
		$returncont = array(
			'r' => $content
		);
		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif ($method == 'gettransaction'){
		$content = $bitcoin->gettransaction($params[0]);
		$returncont = array(
			'r' => $content
		);
		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif ($method == 'dealmark'){
		$content = $bitcoin->dealmark($params[0]);
		$returncont = array(
			'r' => $content
		);
		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif ($method == 'sendtoaddress') {
		$content = $bitcoin->sendtoaddress($params[0], $params[1], $params[2], $params[3]);
		$returncont = array(
			'r' => $content
		);
		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}
}

function mchStrCode($string,$action='ENCODE')
{
	//$key    = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
	$key    = "xs2dw96e23rfv3245dfw27cw45";
	$string    = $action == 'ENCODE' ? $string : base64_decode($string);
	$len    = strlen($key);
	$code    = '';
	for($i=0; $i < strlen($string); $i++)
	{
		$k        = $i % $len;
		$code  .= $string[$i] ^ $key[$k];
	}
	$code = $action == 'DECODE' ? $code : base64_encode($code);
	return $code;
}

?>