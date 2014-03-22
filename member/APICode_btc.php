<?php
/*
  @version        $Id: buy_btc.php 1 8:38 2010年8月9日Z SZ $
 */
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
CheckTxPdw();
$menutype = 'mydede';
$menutype_son = 'op';
$myurl = $cfg_basehost.$cfg_member_dir.'/index.php?uid='.$cfg_ml->M_LoginID;
$moneycards = '';
$membertypes = '';


//过滤字符



if($action == "new") {
	$strCode = rechargeCode(1);
	$userIdCode = mchStrCode($cfg_ml->M_LoginID,'ENCODE',$strCode['0']['pwd']);
	$rsup = $dsql->ExecuteNoneQuery("Update #@__member Set face='".$userIdCode."',StrCode='".$strCode['0']['pwd']."' where mid='".$cfg_ml->M_ID."'"); 
	
	showJson("生成新的加密KEY成功",1);
	exit();
}
 


$tpl = new DedeTemplate();
$tpl->LoadTemplate(DEDEMEMBER.'/templets/APICode_BTC.htm');
$tpl->Display();


/**
 *  提示信息
 */
function showJson($msg,$ruslt){
			$msgArray=array(  
			'showMsg' => $msg, 
			'ruslt' => $ruslt,
			);
			$json_string = json_encode($msgArray);  
			echo $json_string;
		}



/**
 * 生成充值卡密码
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作
 * @return    string
 */
function rechargeCode($nums)
{
    $numLen=10;
	$pwdLen=18;
	$c=$nums;//生成1组卡号密码
	$sTempArr=range(0,9);
	$sNumArr=array_merge($sTempArr,range('A','Z'));
	$sPwdArr=array_merge($sTempArr,range('A','Z'));
	
	$cards=array();
	for($x=0;$x< $c;$x++){
	  $tempNumStr=array();
	  for($i=0;$i< $numLen;$i++){
		$tempNumStr[]=array_rand($sNumArr);
	  }
	  $tempPwdStr=array();
	  for($i=0;$i< $pwdLen;$i++){
		$tempPwdStr[]=$sPwdArr[array_rand($sPwdArr)];  
		$tempPwdStr2[]=$sPwdArr[array_rand($sPwdArr)];   
	  }
	  $cards[$x]['no']=implode('',$tempPwdStr2);
	  $cards[$x]['pwd']=implode('',$tempPwdStr);
	}
	array_unique($cards);
	//print_r($cards);
    return $cards;
}

/**
 *  加密函数
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作
 * @return    string
 */
function mchStrCode($string,$action='ENCODE',$key)
{
    //$key    = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
	//$key    = "e87drga49ae10f3c87";
    $string    = $action == 'ENCODE' ? $string : base64_decode($string);
    $len    = strlen($key);
    $code    = '';
    for($i=0; $i<strlen($string); $i++)
    {
        $k        = $i % $len;
        $code  .= $string[$i] ^ $key[$k];
    }
    $code = $action == 'DECODE' ? $code : base64_encode($code);
    return $code;
}