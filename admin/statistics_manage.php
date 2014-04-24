<?php
/**
 * 对账管理
 *
 * @version        $Id: member_techarge_cards.php 1 14:14 2013年8月20日 SZ $
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
require_once(DEDEINC.'/datalistcp.class.php');

//币种
$dsql->SetQuery("SELECT id,cointype,coinfee FROM #@__btctype where coinsign = 1");
$dsql->Execute();
$count = 0;
while($rcv = $dsql->GetObject()){
	$cointypelist[$count] = array(
		'id' => $rcv->id,
		'cointype' => $rcv->cointype,
		'coinfee' => $rcv->coinfee
	);
	$count++;
}

foreach($cointypelist as $k => $v){

	$rcv = $dsql->GetOne("select sum(c_deposit) deposit, sum(c_freeze) freeze from #@__btccoin where coinid = ". $v['id']);
	$cointypelist[$k]['deposit'] = $rcv['deposit']? $rcv['deposit'] : 0;
	$cointypelist[$k]['freeze'] = $rcv['freeze']? $rcv['freeze'] : 0;
}


$dlist = new DataListCP();

$tplfile = DEDEADMIN."/templets/statistics_manage.htm";

//这两句的顺序不能更换
$dlist->SetTemplate($tplfile);      //载入模板
$dlist->SetSource($sql);            //设定查询SQLexit('dd');
$dlist->Display();


