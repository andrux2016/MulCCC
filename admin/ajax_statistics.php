<?php
/**
 * @version        $Id: ajax_trans.php 1 8:38 2013年8月29日Z
 * 每晚12点执行 查询统计信息
 */
require_once(dirname(__FILE__).'/../include/common.inc.php');


$dsql->SetQuery("Select * From `#@__btcconvert` Where enabled=1");
$dsql->Execute();
$status = 0;
while($rcv = $dsql->GetObject())
{
	$dtypearr[$status]=array(
		'coinid'=>$rcv->coinid,
		'cointype'=>$rcv->cointype,
		'coinname'=>$rcv->coinname,
		'moneyid'=>$rcv->moneyid,
		'moneyname'=>$rcv->moneyname,
		'moneytype'=>$rcv->moneytype,
	);
	$status++;
}

foreach($dtypearr as $key=>$tmpdtypearr){
	
	$rcv = $dsql->GetOne("SELECT sum(btccount) as count, sum(tprice) as total FROM #@__btcdeal where market='1' and coinid =".$tmpdtypearr['coinid']." and moneyid=".$tmpdtypearr['moneyid']." AND dealtime>".strtotime("-1 day"));
	$count = $rcv->count? $rcv->count : 0;
	$total = $rcv->total? $rcv->total : 0;
	$avg = ($total == 0 || $count == 0)? 0 : $count;
	$rcv = $dsql->GetOne("SELECT avg FROM #@__statistics where coinid =".$tmpdtypearr['coinid']." and moneyid=".$tmpdtypearr['moneyid']." order by datetime desc ");
	$oldavg = $rcv->avg? $rcv->avg : 0;
	$percent = $oldavg == 0? 0 : (($avg - $oldavg)/$oldavg);
	$slq="insert into ".$cfg_dbprefix."statistics(datetime,coinid,moneyid,count,total,avg,percent) values(".strtotime("now").", ".$tmpdtypearr['coinid'].", ".$tmpdtypearr['moneyid'].", ".$count.", ".$total.", ".$avg.", ".$percent.")";
	$rsnew = $dsql->ExecuteNoneQuery($slq);
}



?>

