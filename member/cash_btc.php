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
$coinid=preg_replace("#[^0-9-]#", "", $coinid)?preg_replace("#[^0-9-]#", "", $coinid):1;
if($coinid==1) $amount=preg_replace("#[^.0-9-]#", "", (floor($amount*100)/100));
else $amount=preg_replace("#[^.0-9-]#", "", $amount);
$fees=preg_replace("#[^.0-9-]#", "", $fees);
$address=safe_string($address);
$txid=safe_string($txid);
$paytype=preg_replace("#[^0-9-]#", "", $paytype);
$emailcode=preg_replace("#[^0-9-]#", "", $emailcode);
$vdcode=preg_replace("#[^0-9A-Za-z-]#", "", $vdcode);



$countpay=0;


$cfg_arrcoin=Getdeposit("",$cfg_ml->M_ID);

foreach ($cfg_arrcoin as $value){

	$coinhtml.="<li>".$value['0']."：<span>".(floor($value['1']*100)/100)."</span></li>";
	$freehtml.="<li>冻结：<span>".($value['2']/1)."</span></li>";
	$coinvol+=$value['4'];
}
	

$dsql->SetQuery("SELECT * FROM #@__payment WHERE enabled=1");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    if($row->cod==1){
		$payment[$row->rank] = array(
		'code' => $row->code,
		'name' => $row->name,
		'fee' => $row->fee,
		'account' => $row->description
		);
	}
	if($row->cod==4){
		$unionarr = explode(";",$row->config);
		$payuserid = explode(":",$unionarr['0']);
		$paykey = explode(":",$unionarr['1']);
		$unionpay[$row->rank] = array(
		'code' => $row->code,
		'name' => $row->name,
		'fee' => $row->fee,
		'account' => $row->description,
		'payuserid' => $payuserid['1'],
		'paykey' => $paykey['1']
		);
	}
	$countpay++;
}

$dsql->SetQuery("SELECT * FROM #@__btctype ");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	if($row->id == $coinid){
		if($row->feetype==1){
			$feeshow=($row->coinfee*$cfg_ml->M_TXFeePer);
			if($paytype!=""){
				$fees=($row->coinfee+$payment[$paytype]['fee'])*$amount*$cfg_ml->M_TXFeePer;
			}else{
				$fees=$row->coinfee*$amount*$cfg_ml->M_TXFeePer;
			}
		}elseif($row->feetype==2){
			$fees=$row->coinfee*$cfg_ml->M_TXFeePer*10/10;
			$feeshow=$fees." ".$row->cointype;
		}
		
		$coincards .= "<li class='thisTab'><a href='cash_btc.php?coinid=".$row->id ."'>".$row->cointype ."提现</a></li>";
		$cointype = $row->cointype;
		$cashcheck = $row->cashcheck;
		$cashnote = $row->cashnote;
	}else{
		if($row->coinsign==1) $coincards .= "<li><a href='cash_btc.php?coinid=".$row->id ."'>".$row->cointype ."提现</a></li>";
	}
}


if($action == "email") {
	$txamount=$amount-$fees;
	$svali = GetCkVdValue();
	
	
	if(strtolower($vdcode)!=$svali || $svali=="")
	{
		ResetVdValue();
		showJson("验证码错误！","-1");
		exit();
	}
	if($amount>50000)
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
	}
	if(empty($amount))
	{
		showJson("请输入提款金额！","-1");
		exit();
	}
	if(empty($coinid))
	{
		showJson("信息有误！","-1");
		exit();
	}
	session_start();
	if($_SESSION[$cfg_ml->M_LoginID.'err']>5)
	{
		showJson("错误次数过多，稍后再试！","-1");
		exit();
	}
	
	//unset($_SESSION[$cfg_ml->M_LoginID.'err']);
	showJson($txamount."发送邮件申请成功","1");
	exit();
}
if($action == "do") {
	$txamount=$amount-$fees;
	$svali = GetCkVdValue();
	if(strtolower($vdcode)!=$svali || $svali=="")
	{
		ResetVdValue();
		showJson("验证码错误！","-1");
		exit();
	}
	
		@session_id(preg_replace("#[^0-9a-zA-Z-]#", "0btc0",$cfg_ml->M_LoginID));
		@session_start();

	if($_SESSION[$cfg_ml->M_LoginID.'err']>5)
	{
		showJson("错误次数过多，稍后再试！","-1");
		exit();
	}
	if(empty($address))
	{
		showJson("请输入转账地址！","-1");
		exit();
	}
	if(empty($amount))
	{
		showJson("请输入提款金额！","-1");
		exit();
	}
	if($cointype=="BTC" && $amount<0.01)
	{
		showJson("提现最小限额0.01！","-1");
		exit();
	}
	if($cointype=="CNY" && $amount<100)
	{
		showJson("提现不得低于100！","-1");
		exit();
	}
	if(empty($coinid))
	{
		showJson("信息有误！","-1");
		exit();
	}
//	if($cfg_ml->M_Tel!=""){
//		if(empty($emailcode))
//		{
//			showJson("请输入短信验证码！","-1");
//			exit();
//		}
//		$sessName=$cfg_ml->M_Tel.'tel';
//		$telCode=GetEmailCode($sessName);
//		if(!isset($telCode)){
//			$out=array('code'=>0,'msg'=>'短信验证码过期！');
//			showJson('短信验证码过期！'); 
//			exit();
//		}
//		if($telCode!=$emailcode){
//			GetPwErrNums($cfg_ml->M_Tel.'err',"add");
//			//$_SESSION[$userip.'err']=$_SESSION[$userip.'err']+1;
//			$errtimes=5-GetPwErrNums($cfg_ml->M_Tel.'err');
//			$out=array('code'=>0,'msg'=>"短信验证码错误！您还有".$errtimes."次机会","-1");
//			showJson($telCode."-".$emailcode."短信验证码错误！您还有".$errtimes."次机会","-1"); 
//			exit();
//		}
//		GetPwErrNums($sessName,"unset");
//		GetPwErrNums($telNumber.'err',"unset");
//	}elseif($cfg_ml->M_Google!=""){
//		if(empty($emailcode))
//		{
//			showJson("请输入google验证码！","-1");
//			exit();
//		}
//		require_once 'GoogleAuthenticator.php';
//		
//		$ga = new PHPGangsta_GoogleAuthenticator();
//		$userCode=$emailcode;
//		$secret = $cfg_ml->M_Google;
//		$checkResult = $ga->verifyCode($secret, $userCode, 2);    // 2 = 2*30sec clock tolerance
//		if (!$checkResult) {
//			showJson('错误！请查看您的手机时间是否正确！',"-1");
//			exit();
//		}
//	}else{
//		if(empty($emailcode))
//		{
//			showJson("请输入Email验证码！","-1");
//			exit();
//		}
//		$ecode=GetPwErrNums($cfg_ml->M_LoginID.'vd');
//		if(!isset($ecode)){
//			showJson('Email验证码过期！',"-1"); 
//			exit();
//		}
//		if($emailcode!=$ecode){
//			$userip=$cfg_ml->M_LoginID;
//			GetPwErrNums($userip.'err',"add");
//			//$_SESSION[$userip.'err']=$_SESSION[$userip.'err']+1;
//			$errtimes=5-GetPwErrNums($userip.'err');;
//			showJson("Email验证码错误！您还有".$errtimes."次机会","-1");
//			exit();
//		}
//	}
	
	$row=$dsql->GetOne("SELECT txpwd FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
	if(md5($txpwd)!=$row['txpwd'])
	{
		$userip=$cfg_ml->M_LoginID;
		//$_SESSION[$userip.'err']=$_SESSION[$userip.'err']+1;
		GetPwErrNums($userip.'err',"add");
		$errtimes=6-GetPwErrNums($userip.'err');
		showJson("提现密码错误！您还有".$errtimes."次机会","-1");
		exit();
	}
	GetPwErrNums($userip.'err',"unset");
	GetPwErrNums($cfg_ml->M_LoginID,"unset");
	//unset($_SESSION[$cfg_ml->M_LoginID.$coinid.'vd']);
	//unset($_SESSION[$cfg_ml->M_LoginID.'err']);
	
	/*if($cards){
		
	}*/
	
	$sql="Select c_deposit,cointype From #@__btccoin where coinid = ".$coinid." AND userid='".$cfg_ml->M_ID."' ;";
	$rcoin = $dsql->GetOne($sql);
	if($rcoin['c_deposit']>=($amount)){
		//扣除金额
		$rsup = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$amount where coinid = ".$coinid." AND userid='".$cfg_ml->M_ID."'"); 
		//记录提现
		if($cashcheck==1) $checktime=time();
		$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btccash(userid,amount,fee,coinid,paytype,address,txid,dealmark,checked,checktime,cashtime) values('".$cfg_ml->M_ID."','$txamount','$fees','$coinid','$paytype','$address','$txid','0','$cashcheck','$checktime','".time()."')");
		$rsnewid = $dsql->GetLastID(); 
		
		$ShowMsg='成功提交提现申请！'; 
		
		
		if($cointype!='CNY' && $cashcheck=='1'){
			
			require_once DEDEINC.'/rpcQuery.php';
			$method="sendtoaddress";
			//$params=array($address,($txamount),$cfg_ml->M_LoginID,$cfg_webname);
			$params=array($address,floatval($txamount),$cfg_ml->M_LoginID,$cfg_webname);
			
			$balance=rpcQuery ($cointype,$method,$params);
			if(isset($balance['r'])){
				$rsup = $dsql->ExecuteNoneQuery("Update #@__btccash Set dealmark=1,adduser=1,txid='".$balance['r']."' where id = '".$rsnewid."'"); 
				$ShowMsg='成功提现 <font color=\'#FF0000\'>'.$txamount."</font> ".$cointype.' (手续费：'.$fees.')，请稍后查询您的账户！'; 
			}else{
				$ShowMsg.='等待管理员确认！'; 
			}
		}
		$ShowMsg.='<a href=\'operation_cash.php\'>查看提现记录</a>'; 
	}else{
		showJson('余额不足！',-1);
		exit();
	}  
	showJson($ShowMsg,1);
	exit();
}






$tpl = new DedeTemplate();

$tpl->LoadTemplate(DEDEMEMBER.'/templets/cash_BTC.htm');
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



