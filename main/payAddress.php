<?php
require_once 'JsonRpcClient.php';

//数据库配置
$mysql_servername = "localhost";
$mysql_username = "root";
$mysql_password = "";
$mysql_database = "data";
$mysql_table = "pay_address";

$method = $_GET['method'];
$cointype = $_GET['cointype'];
$params = $_GET['params'];

$conn = mysql_connect($mysql_servername, $mysql_username, $mysql_password);
$sql = "select ip, port, rpcname, rpcpasswd from ".$mysql_database.".".$mysql_table." where cointype = '" . $cointype . "'";

$result = mysql_query($sql, $conn);

if(!isset($result) && empty($result)){
	mysql_close($conn);
	exit;
}
$row = mysql_fetch_assoc($result);

if(isset($row) && !empty($row)){
	$rpcname = $row['rpcname'];
	$rpcpasswd = $row['rpcpasswd'];
	$ipandport = $row['ip'].":".$row['port'];
}else{
	mysql_free_result($result);
	mysql_close($conn);
	exit;
}
	mysql_free_result($result);
	mysql_close($conn);

//$params = json_decode(mchStrCode($params, 'DECODE'), true);
$params = json_decode($params);

if(isset($cointype) && !empty($cointype)){
	$bitcoin = new jsonRPCClient('http://'.$rpcname.':'.$rpcpasswd.'@'.$ipandport.'/');

	if($method == 'getnewaddress'){
		$content = $bitcoin->getnewaddress($params[0]);
		$returncont = array(
			'r' => $content
		);
//		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif ($method == 'getaddressesbyaccount') {
		$content = $bitcoin->getaddressesbyaccount($params[0]);
		$returncont = array(
			'r' => $content
		);
//		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif($method == 'listsinceblock'){
		$content = $bitcoin->listsinceblock($params[0]);
		$returncont = array(
			'r' => $content
		);
//		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}elseif ($method == 'gettransaction'){
		$content = $bitcoin->gettransaction($params[0]);
		$returncont = array(
			'r' => $content
		);
//		echo mchStrCode(json_encode($returncont), 'ENCODE');
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
//		echo mchStrCode(json_encode($returncont), 'ENCODE');
	}
	echo json_encode($returncont);
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