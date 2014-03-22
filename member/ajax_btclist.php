<?php
/**
 * @version        $Id: ajax_btclist.php 1 8:38 2013年7月24日Z
 */
require_once(dirname(__FILE__)."/config.php");
AjaxHead();
$coinid=$coinid?$coinid:"1";//BTC
$moneyid=$moneyid?$moneyid:"1";//CNY
$market=$market?$market:"1";//sz

//读取挂单
$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__BTCorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND dealtype=1 ORDER BY uprice DESC LIMIT 20");
$dsql->Execute();
while($rod = $dsql->GetObject())
{
	$ordersell[] = array(  
	'btccount' => $rod->btccount, 
    'uprice' => $rod->uprice,  
	'tprice' => $rod->tprice, 
	'dealtype' => $rod->dealtype,
	'ordertime' => $rod->ordertime,
    );
}
//读取挂单
$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__BTCorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND dealtype=0 ORDER BY uprice DESC LIMIT 20");
$dsql->Execute();
while($rod = $dsql->GetObject())
{
	$orderbuy[] = array(  
	'btccount' => $rod->btccount, 
    'uprice' => $rod->uprice,  
	'tprice' => $rod->tprice, 
	'dealtype' => $rod->dealtype,
	'ordertime' => $rod->ordertime,
    );
}
//读取成交
$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtime FROM #@__BTCdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' ORDER BY dealtime DESC LIMIT 20");
$dsql->Execute();
while($rdl = $dsql->GetObject())
{
	$dealarr[] = array(  
	'btccount' => $rdl->btccount, 
    'uprice' => $rdl->uprice, 
	'tprice' => $rdl->tprice,  
	'dealtime' => $rdl->dealtime,
    );
	echo $rdl->id;
}
    $listArr[]=array(  
    'orderbuy' => $orderbuy,  
	'ordersell' => $ordersell, 
	'deal' => $dealarr,  
    );
	$json_string = json_encode($listArr);  
	echo $json_string;

?>
