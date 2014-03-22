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
//$userip=preg_replace("#[^0-9a-zA-Z-]#", "0btc0",$cfg_ml->M_LoginID);
//session_id($userip);
//session_start();
$sessErr=$cfg_ml->M_LoginID.'err';
$sessName=$cfg_ml->M_LoginID.'vd';
$sessCode=GetEmailCode($sessName,"achieve");

//echo $_SESSION[$userip.$coinid.'vd'];
//echo "1";
//过滤字符
$coinid=preg_replace("#[^0-9-]#", "", $coinid)?preg_replace("#[^0-9-]#", "", $coinid):1;
$amount=preg_replace("#[^.0-9-]#", "", $amount);
$fees=preg_replace("#[^.0-9-]#", "", $fees);
$address=safe_string($address);
$txid=safe_string($txid);
$paytype=preg_replace("#[^0-9-]#", "", $paytype);
$emailcode=preg_replace("#[^0-9-]#", "", $emailcode);
$vdcode=preg_replace("#[^0-9A-Za-z-]#", "", $vdcode);


$dsql->SetQuery("SELECT * FROM #@__btctype");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	/*if($row->id == $coinid){
		if($row->feetype==1){
			$fees=$row->coinfee*$amount;
			$feeshow=($row->coinfee*100)."%";
		}elseif($row->feetype==2){
			$fees=$row->coinfee*10/10;
			$feeshow=$fees." ".$row->cointype;
		}
		//$coincards .= "<li class='thisTab'><a href='cards_btc.php?coinid=".$row->id ."'>".$row->cointype ."充值码</a></li>";
		$cointype = $row->cointype;
		$cashcheck = $row->cashcheck;*/
	$coinTypeArr[$row->id] = $row->cointype;
	if($row->coinsign==1){
		$rcoin=$dsql->GetOne("SELECT c_deposit FROM `#@__btccoin` WHERE coinid='".$row->id."' AND userid=".$cfg_ml->M_ID);
		$c_deposit[$row->id]=floor($rcoin['c_deposit']*10000)/10000;
		$coinselect .= "<option value='".$row->id ."'>".$row->cointype ." 可用余额:".$c_deposit[$row->id]."</option>";
	}
		//$coindeposit[$row->id]=array($row['c_deposit']);
	/*}else{
		if($row->coinsign==1) $coincards .= "<li><a href='cards_btc.php?coinid=".$row->id ."'>".$row->cointype ."充值码</a></li>";
	}*/
}


if($action == "email") {
	/*$txamount=$amount-$fees;
	$svali = GetCkVdValue();
	if(strtolower($vdcode)!=$svali || $svali=="")
	{
		ResetVdValue();
		showJson("验证码错误！","-1");
		exit();
	}
	if($cointype=="FEC" && $amount>50000)
	{
		showJson("提现超出限额！","-1");
		exit();
	}
	if($cointype=="CNY" && $amount<100)
	{
		showJson("提现不得低于100！","-1");
		exit();
	}
	if(empty($address))
	{
		showJson("请输入转账地址！","-1");
		exit();
	}*/
	if(empty($amount))
	{
		showJson("请输入提款金额！","-1");
		exit();
	}
	if(empty($coinid))
	{
		showJson("请选择币种类型！","-1");
		exit();
	}
	//session_start();
	if(GetPwErrNums($sessErr,"achieve")>5)
	{
		showJson("错误次数过多，稍后再试！","-1");
		exit();
	}
	
	//unset($_SESSION[$userip.'err']);
	showJson("发送验证邮件成功","1");
	exit();
}
if($action == "do") {
	/*$txamount=$amount-$fees;
	$svali = GetCkVdValue();
	if(strtolower($vdcode)!=$svali || $svali=="")
	{
		ResetVdValue();
		showJson("验证码错误！","-1");
		exit();
	}*/

	if(GetPwErrNums($sessErr,"achieve")>5)
	{
		showJson("错误次数过多，稍后再试！","-1");
		exit();
	}
	/*if(empty($address))
	{
		showJson("请输入转账地址！","-1");
		exit();
	}if($cointype=="FEC" && $amount>50000)
	{
		showJson("提现超出限额！","-1");
		exit();
	}if($cointype=="CNY" && $amount<100)
	{
		showJson("提现不得低于100！","-1");
		exit();
	}*/
	
	if($amount<=0)
	{
		showJson("请输入提款金额！","-1");
		exit();
	}
	if(empty($coinid))
	{
		showJson("请选择币种类型！","-1");
		exit();
	}
	if($cfg_ml->M_Google==""){
		if(empty($emailcode))
		{
			showJson("请输入Email验证码！","-1");
			exit();
		}
			
		if(!isset($sessCode)){
			showJson('Email验证码过期！',"-1"); 
			exit();
		}
		if($emailcode!=$sessCode){
			
			//$_SESSION[$userip.'err']=$_SESSION[$userip.'err']+1;
			$errtimes=6-GetPwErrNums($sessErr,"add");
			showJson("Email验证码错误！您还有".$errtimes."次机会","-1");
			exit();
		}
	}else{
		if(empty($emailcode))
		{
			showJson("请输入google验证码！","-1");
			exit();
		}
		require_once 'GoogleAuthenticator.php';
		
		$ga = new PHPGangsta_GoogleAuthenticator();
		$userCode=$emailcode;
		$secret = $cfg_ml->M_Google;
		$checkResult = $ga->verifyCode($secret, $userCode, 2);    // 2 = 2*30sec clock tolerance
		if (!$checkResult) {
			showJson('错误！请查看您的手机时间是否正确！',"-1");
			exit();
		}
	}
	
	$row=$dsql->GetOne("SELECT txpwd FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
	if(md5($txpwd)!=$row['txpwd'])
	{
		//$_SESSION[$userip.'err']=$_SESSION[$userip.'err']+1;
		//$errtimes=6-$_SESSION[$userip.'err'];
		$errtimes=6-GetPwErrNums($sessErr,"add");
		showJson("提现密码错误！您还有".$errtimes."次机会","-1");
		exit();
	}
	GetPwErrNums($sessErr,"unset");
	GetPwErrNums($sessName,"unset");
	//unset($_SESSION[$userip.$coinid.'vd']);
	//unset($_SESSION[$userip.'err']);
	
	
	$sql="Select c_deposit,cointype From #@__btccoin where coinid = ".$coinid." AND userid='".$cfg_ml->M_ID."' ;";
	$rcoin = $dsql->GetOne($sql);
	if($rcoin['c_deposit']>=($amount)){
		//扣除金额
		$rsup = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$amount where coinid = ".$coinid." AND userid='".$cfg_ml->M_ID."'"); 
		 //生成充值码
		$mtime=3530742291;
		$rechargeCodes = rechargeCode(1);
		$ctid=$rechargeCodes[0]['no'];
		$cardid=$rechargeCodes[0]['pwd'];
		$query = "INSERT INTO #@__moneycard_record(ctid,mid,cardid,coinid,isexp,money,stime,mtime,cardnote) VALUES('{$ctid}','".$cfg_ml->M_ID."','{$cardid}','$coinid','1','{$amount}','".time()."','{$mtime}','{$cardnote}');";
		$dsql->ExecuteNoneQuery($query);
		
		$ShowMsg='成功生成充值码！'; 
		
		//$ShowMsg.='<a href=\'cardslist.php\'>查看提现记录</a>'; 
	}else{
		showJson('余额不足！',-1);
		exit();
	}  
	showJson($ShowMsg,1);
	exit();
}
 
 

 

$sql = "Select * From #@__moneycard_record Where mid='".$cfg_ml->M_ID."' Or uid='".$cfg_ml->M_ID."' ORDER BY `stime` DESC limit 20";
$dsql->SetQuery($sql);
$dsql->Execute();
while($rlist = $dsql->GetObject())
{
	$ctype="";
	if($rlist->mid == $cfg_ml->M_ID){
		$ctype = "生成";
		$ctime = date("Y-m-d ",$rlist->stime);
	}
	if($rlist->uid == $cfg_ml->M_ID){
		$ctype .= "使用";
		$ctime = date("Y-m-d",$rlist->utime);
	}
	if($rlist->isexp ==-1){
    $isexp="已使用";
    }elseif($rlist->mtime<time()){ 
	$isexp="<font color=#FF0000>过期</font>";
	}else{
	$isexp="<font color=#00CC00>有效</font>";
	}
	$CardStr=$cfg_web_mark."-".$coinTypeArr[$rlist->coinid]."-".substr($rlist->ctid,0,5)."-".substr($rlist->cardid,5,10)."-".substr($rlist->cardid,0,5)."-".substr($rlist->ctid,5,10);
	
	$cardslist.="<tr>";
	$cardslist.="<td style='border-bottom:1px solid #666666;' align='center' height='20'>". $ctime ."</td>";
	$cardslist.="<td style='border-bottom:1px solid #666666;' align='center'>". $coinTypeArr[$rlist->coinid] ."</td>";
	$cardslist.="<td style='border-bottom:1px solid #666666;' align='center'>". $rlist->money/1 ."</td>";
	$cardslist.="<td style='border-bottom:1px solid #666666;' align='center'>". $ctype ."</td>";
	$cardslist.="<td style='border-bottom:1px solid #666666;' align='center'>". $isexp."</td>";
	$cardslist.="<td style='border-bottom:1px solid #666666;' align='center'>".$CardStr."</td>";
	$cardslist.="</tr>";
}

$tpl = new DedeTemplate();
$tpl->LoadTemplate(DEDEMEMBER.'/templets/cards_BTC.htm');
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
	$pwdLen=10;
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
function mchStrCode($string,$action='ENCODE')
{
    //$key    = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
	$key    = "e87drga49ae10f3c87";
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