<?php 
require_once(dirname(__FILE__)."/../member/config.php");


/*
走势图数据
使用方法
在crontab中分别对应
运行时间 	5分钟 	15分钟 	30分钟 	1小时 	8小时 	1天
设置参数 1		3		6		12		96		288
*/
	if(!isset($argv['1']) || empty($argv['1'])){
		$arg = 1;
	}else{
		$arg = $argv['1'];
	}
	$tspan = 300*$arg;
	$tspan = $tspan."";
	$count = 100;
	$market = 1;
	

	$dsql->SetQuery("select coinid, moneyid from  ".$cfg_dbprefix."btcconvert");
	$dsql->Execute();
	while($row = $dsql->GetObject()){
		FunTline($row->coinid,$row->moneyid,$tspan,$count,$market);
	}
	
	
	

?>

