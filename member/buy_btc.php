<?php
/**
 * @version        $Id: buy_btc.php 1 8:38 2010年8月9日Z SZ $
 */
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
CheckTxPdw();
$menutype = 'mydede';
$menutype_son = 'op';
$myurl = $cfg_basehost.$cfg_member_dir.'/index.php?uid='.$cfg_ml->M_LoginID;
$moneycards = '';

//产生订单号
$year_code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
$tx_sn = strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('d',rand(0,99)).$year_code[intval(date('Y'))-2013];

$row=$dsql->GetOne("SELECT  * FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
$nowtxpwd = $row['txpwd'];
if($nowtxpwd==""){
	ShowMsg("请先更新账户安全信息！","editbaseinfo.php");
	exit();
}



$cfg_arrcoin=Getdeposit("",$cfg_ml->M_ID);

foreach ($cfg_arrcoin as $value){

	$coinhtml.="<li>".$value['0']."：<span>".(floor($value['1']*100)/100)."</span></li>";
	$freehtml.="<li>冻结：<span>".($value['2']/1)."</span></li>";
	$coinvol+=$value['4'];
}
	
//过滤字符
$coinid=preg_replace("#[^0-9-]#", "", $coinid)?preg_replace("#[^0-9-]#", "", $coinid):1;
$amount=preg_replace("#[^.0-9-]#", "", $amount);
$fees=preg_replace("#[^.0-9-]#", "", $fees);
$address=safe_string($address);
$txid=safe_string($txid);
$paytype=preg_replace("#[^0-9-]#", "", $paytype);
$emailcode=preg_replace("#[^0-9-]#", "", $emailcode);
$vdcode=preg_replace("#[^0-9A-Za-z-]#", "", $vdcode);

$dsql->SetQuery("SELECT * FROM #@__btctype WHERE coinsign=1");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	if($row->id == $coinid){
		$coincards .= "<li class='thisTab'><a href='buy_btc.php?coinid=".$row->id ."'>".$row->cointype ."充值</a></li>";
		$cointype = $row->cointype;
		$reccheck = $row->reccheck;
		$recfee = $row->recfee;
		$feetype = $row->feetype;
		$buynote = $row->buynote;
	}else{
		$coincards .= "<li><a href='buy_btc.php?coinid=".$row->id ."'>".$row->cointype ."充值</a></li>";
	}
	
}

if($action=="do"){
	$svali = GetCkVdValue();
	
	
	
	if(strtolower($vdcode)!=$svali || $svali=="")
	{
		if($op==""){
		ShowMsg("验证码错误！","-1");
		exit();
		}
	}
	if($op=="" && empty($txid))
	{
		ShowMsg("交易单号为空！","-1");
		exit();
	}
	if(empty($coinid))
	{
		ShowMsg("信息有误！","-1");
		exit();
	}
	if($op!="" && empty($paytype))
	{
		ShowMsg("请选择充值渠道！","-1");
		exit();
	}
	$row = $dsql->GetOne("Select * From #@__btcrecharge where txid='$txid'");
	if(!is_array($row)){
		$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btcrecharge(userid,amount,fee,coinid,address,txid,paytype,dealmark,checked,rcgtime) values('".$cfg_ml->M_ID."','$amount','$fee','$coinid','$address','$txid','$paytype','0',$reccheck,'".time()."')");
		$ShowMsg="充值申请单已提交！<a href='operation_btc.php'>查看充值记录</a>";
	}else{
		ShowMsg("单号重复！","-1");
		exit();
	}

	
	if($op!=""){
		$MsgArray=array(  
		'ShowMsg' => $ShowMsg
    	);

		$json_string = json_encode($MsgArray);  
		exit($json_string);
	}
}

$dsql->SetQuery("SELECT txid,address,amount,con_ft,time FROM #@__btctrans WHERE account='".$cfg_ml->M_UserName."' AND coinid='".$coinid."' AND con_ft<$cfgconfirmations");
$dsql->Execute();
while($row = $dsql->GetObject())
{  
	$transhtml .= "<tr >
    <td></td>
    <td title='{$row->txid}'>{$row->address}</td>
    <td>".($row->amount/1)." {$cointype}</td>
	<td>{$row->con_ft}</td>
	<td>".date('Y-m-d H:i:s',$row->time)."</td>
    </tr>
    ";
}

$dsql->SetQuery("SELECT * FROM #@__payment WHERE enabled=1 AND cod=1");
$dsql->Execute();
while($row = $dsql->GetObject())
{
   if($row->cod==1){
		$alshow = 1;
		$payment[$row->rank] = array(
		'code' => $row->code,
		'name' => $row->name,
		'fee' => $row->fee,
		'account' => $row->description,
		'online' => $row->online,
		'cod' => $row->cod
		);
	}elseif($row->cod==4){
		$configarr = json_decode($row->config, true);
		$payment[$row->rank] = array(
		'code' => $row->code,
		'name' => $row->name,
		'fee' => $row->fee,
		'online' => $row->online,
		'account' => $configarr['bankco'],
		'bankid' => $configarr['bankid'],
		'key' => $configarr['key'],
		'cod' => $row->cod
		);
	}
	$lastrank=$row->rank;
}


$txidcode=rand(10000,99999);

/*if($cointype=='CNY'){
	$tempshow=$cointype;
}else{
	$tempshow="BTC";
}*/

$tpl = new DedeTemplate();
$tpl->LoadTemplate(DEDEMEMBER.'/templets/buy_BTC.htm');
//$tpl->LoadTemplate(DEDEMEMBER.'/templets/buy_'.$cointype.'.htm');
$tpl->Display();