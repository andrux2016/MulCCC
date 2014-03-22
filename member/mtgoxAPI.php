<?php
/*
  @version        $Id: buy_btc.php 1 8:38 2010年8月9日Z SZ $
 */
require_once(dirname(__FILE__).'/config.php');


$row = $dsql->GetOne("Select tid From `#@__qhmtgox` Order By tid Desc");
if($row['tid']){
	$url = ("https://data.mtgox.com/code/data/getTrades.php?since=".$row['tid']);
}else{
	$url = ("https://data.mtgox.com/code/data/getTrades.php");
	exit();
}

$curlObj = curl_init();
curl_setopt($curlObj, CURLOPT_URL,$url);
curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curlObj);
$mtarray=json_decode( $result);  

foreach($mtarray as $key=>$value){
	$mtlist=(array)$value;
	$dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."qhmtgox(tid,date,price,amount,price_int,amount_int,price_currency,item,trade_type) Values('".$mtlist['tid']."',".$mtlist['date'].",'".$mtlist['price']."','".$mtlist['amount']."','".$mtlist['price_int']."','".$mtlist['amount_int']."','".$mtlist['price_currency']."','".$mtlist['item']."','".$mtlist['trade_type']."')");
	//Array ( [date] => 1279408157 [price] => 0.04951 [amount] => 20 [price_int] => 4951 [amount_int] => 2000000000 [tid] => 1 [price_currency] => USD [item] => BTC [trade_type] => ) 

	print_r($mtlist);
	echo "<br>";
}
?>