<?php
/**
 * @version        $Id: ajax_login.php 1 8:38 2013年7月9日Z
 */
require_once(dirname(__FILE__)."/config.php");
AjaxHead();
$userArray=array(  
    'username' => $cfg_ml->M_LoginID, 
	'userid' => $cfg_ml->M_ID, 
	'coin' => $arrcoin, 
	'msg' => $msg, 
	'order' => $orderarr
    );
$json_string = json_encode($userArray);  

if($myurl == '') 
{
	
echo $json_string;
	exit('');
}



$uid  = $cfg_ml->M_LoginID;





//读取余额

	$sql="Select c_deposit,c_freeze From #@__btccoin where coinid = ".$key." AND userid='".$cfg_ml->M_ID."' ;";
	$rcoin = $dsql->GetOne($sql);
	$coinshow = $rcoin['c_deposit']?floor($rcoin['c_deposit']*10000)/10000:"0";
	//if($coinshow>0) $htmlcoin .= "".$coinlist['coin']."：<span id='".$coinlist['coin']."'>".$coinshow."</span><br>";
	$arrcoin[] = array($coinlist , $coinshow , $rcoin['c_freeze']);





$credit = $cfg_ml->M_Credit;
$money  = $cfg_ml->M_Money;
$uid  = $cfg_ml->M_LoginID;
$profit = $money-$credit+$cfg_ml->M_NowPay;
$userArray=array(  
    'username' => $cfg_ml->M_LoginID, 
	'userid' => $cfg_ml->M_ID, 
	'coin' => $arrcoin, 
	'msg' => $msg, 
	'order' => $orderarr
    );

$json_string = json_encode($userArray);  
echo $json_string;



/**
 *  加密函数
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作 EN加密
 * @return    string
 */
function mchStrCode($string,$action='DECODE')
{
    $key    = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
    //$key    = "a87856749ae10f3c53";
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

