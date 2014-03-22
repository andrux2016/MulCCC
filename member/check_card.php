<?php 
/**
 * @version        $Id: check_card.php 1 8:38 2013年9月9日Z SZ $
 */
 
require_once(dirname(__FILE__)."/config.php");
$svali = GetCkVdValue();


if(strtolower($vdcode)!=$svali || $svali=="")
{
    showJson("验证码错误！","-1");
    exit();
}

//$cardid = preg_replace("#[^0-9A-Za-z-]#", "", $cardid);
if(empty($cardid))
{
    showJson("卡号为空！","-1");
    exit();
}
$cardid=preg_replace("#[^-0-9A-Za-z-]#", "", $cardid);

$strArr=explode("-",$cardid);
$ctid=$strArr[2].$strArr[5];
$password=$strArr[4].$strArr[3];
if($strArr[0]!=$cfg_web_mark)
{
    showJson("充值卡密码错误","-1");
    exit();
}

$rcoin = $dsql->GetOne("SELECT id FROM #@__btctype where cointype='".$strArr[1]."'");
if(!is_array($rcoin))
{
    showJson("充值卡密码错误","-1");
    exit();
}

$row = $dsql->GetOne("SELECT * FROM #@__moneycard_record WHERE ctid='$ctid' AND cardid='$password' AND coinid='".$rcoin['id']."'");

if(!is_array($row))
{
    showJson("充值卡密码错误","-1");
    exit();
}

if($row['isexp']==-1)
{
    showJson("此卡号已经失效，不能再次使用！","-1");
    exit();
}
if($row['mtime']<time())
{
    showJson("此卡号已经过期！","-1");
    exit();
}


$hasMoney = $row['money'];
$dsql->ExecuteNoneQuery("UPDATE #@__moneycard_record SET uid='".$cfg_ml->M_ID."',isexp='-1',utime='".time()."' WHERE ctid='$ctid' ");

$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btcrecharge(userid,amount,fee,coinid,address,txid,paytype,dealmark,checked,adduser,rcgtime) values('".$cfg_ml->M_ID."','$hasMoney','0','1','充值卡','$cardid','3','1','1','1','".time()."')");

	//$rnew = $dsql->ExecuteNoneQuery2("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$cfg_ml->M_ID."','1','CNY','".$hasMoney."','0','". time() ."')");

$rcoin=$dsql->ExecuteNoneQuery("UPDATE #@__btccoin SET c_deposit=c_deposit+$hasMoney,edittime = '". time() ."' WHERE coinid='".$rcoin['id']."' AND userid='".$cfg_ml->M_ID."'");
if($rcoin==0){
	$query = "Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$cfg_ml->M_ID."','".$rcoin['id']."','".$strArr[1]."','".$hasMoney."','0','". time() ."')";
	$rnew = $dsql->ExecuteNoneQuery2($query);
}

showJson("充值成功，你本次增加的金额为：".($hasMoney/1)." ".$strArr[1]."！",1);

/**
 *  提示信息
 */
function showJson($msg,$ruslt){
			/*$userArray=array(  
			'showMsg' => $msg, 
			'ruslt' => $ruslt,
			);
		
			$json_string = json_encode($userArray);  
			echo $json_string;*/
			echo $msg;
		}

/**
 *  加密函数
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作EN加密
 * @return    string
 */
function mchStrCode($string,$action='DECODE')
{
    //$key    = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
    $key    = "e87drga49ae10f3c87";
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