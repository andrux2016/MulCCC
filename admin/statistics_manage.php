<?php
/**
 * 对账管理
 *
 * @version        $Id: member_techarge_cards.php 1 14:14 2013年8月20日 SZ $
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
require_once(DEDEINC.'/datalistcp.class.php');
require_once DEDEINC.'/rpcQuery.php';

$show = $show?$show : 0;
if($show > 3 || $show < 0){
	$show = 0;
}

//币种
$dsql->SetQuery("SELECT id,cointype,coinfee FROM #@__btctype where coinsign = 1");
$dsql->Execute();
while($rcv = $dsql->GetObject()){
	$cointypelist[$rcv->id] = array(
		'id' => $rcv->id,
		'cointype' => $rcv->cointype,
		'coinfee' => $rcv->coinfee
	);
}



//foreach($cointypelist as $k => $v){
//
//	$rcv = $dsql->GetOne("select sum(c_deposit) deposit, sum(c_freeze) freeze from #@__btccoin where coinid = ". $v['id']);
//	$cointypelist[$k]['deposit'] = $rcv['deposit']? $rcv['deposit'] : 0;
//	$cointypelist[$k]['freeze'] = $rcv['freeze']? $rcv['freeze'] : 0;
//}
//总账对账
if($show == 0){
	//所有客户该币种充值额之和   网站收取的该币种充值手续费之和
	$dsql->SetQuery("select sum(amount) total_amount, sum(fee) total_fee, coinid from #@__btcrecharge where dealmark = 1 group by coinid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->coinid]['inamount'] = $rcv->total_amount;
		$cointypelist[$rcv->coinid]['inoutfee'] = $rcv->total_fee;
	}
	
	//所有客户该币种提现额之和  网站收取的该币种提现手续费之和
	$dsql->SetQuery("select sum(amount) total_amount, sum(fee) total_fee, coinid from #@__btccash where dealmark = 1 group by coinid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->coinid]['outamount'] = $rcv->total_amount;
		$cointypelist[$rcv->coinid]['inoutfee'] = $cointypelist[$rcv->coinid]['inoutfee'] + $rcv->total_fee;
	}
	
	//所有客户该币种余额之和
	$dsql->SetQuery("select sum(c_deposit) deposit, sum(c_freeze) freeze, coinid from #@__btccoin group by coinid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->coinid]['deposit'] = $rcv->deposit;
		$cointypelist[$rcv->coinid]['freeze'] = $rcv->freeze;
	}
	
	//网站收取的该币种交易手续费之和
	$dsql->SetQuery("select sum(bbkage) buyfee, sum(sbkage) sellfee, moneyid from #@__btcdeal group by moneyid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->moneyid]['tradefee'] = $rcv->buyfee + $rcv->sellfee;
	}
	
}else if($show == 1){
	//网站收取的该币种充值手续费之和
	$dsql->SetQuery("select sum(fee) total_fee, coinid from #@__btcrecharge where dealmark = 1 group by coinid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->coinid]['inoutfee'] = $rcv->total_fee;
	}
	
	//网站收取的该币种提现手续费之和
	$dsql->SetQuery("select sum(fee) total_fee, coinid from #@__btccash where dealmark = 1 group by coinid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->coinid]['inoutfee'] = $cointypelist[$rcv->coinid]['inoutfee'] + $rcv->total_fee;
	}
	
	//所有客户该币种余额之和
	$dsql->SetQuery("select sum(c_deposit) deposit, sum(c_freeze) freeze, coinid from #@__btccoin group by coinid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->coinid]['deposit'] = $rcv->deposit;
		$cointypelist[$rcv->coinid]['freeze'] = $rcv->freeze;
	}
	
	//网站收取的该币种交易手续费之和
	$dsql->SetQuery("select sum(bbkage) buyfee, sum(sbkage) sellfee, moneyid from #@__btcdeal group by moneyid");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$cointypelist[$rcv->moneyid]['tradefee'] = $rcv->buyfee + $rcv->sellfee;
	}

	//钱包中总余额
	foreach($cointypelist as $k => $v){
		$method="getinfo";
		$params=1;
		$trans=coinQuery ($cointypelist[$k]['cointype'],$method,$params);
		if(isset($trans['r'])){
			$btctrans=get_object_vars($trans['r']);
			$cointypelist[$k]['balance'] = $btctrans['balance']?$btctrans['balance']:0;
		}
	}
}else if($show == 2){

}else if($show == 3){

}

$dlist = new DataListCP();

$tplfile = DEDEADMIN."/templets/statistics_manage.htm";

//这两句的顺序不能更换
$dlist->SetTemplate($tplfile);      //载入模板
$dlist->SetSource($sql);            //设定查询SQLexit('dd');
$dlist->Display();


