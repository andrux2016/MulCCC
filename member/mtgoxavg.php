<?php
/*
*抓取url
*return array
*/
function catch_url($url){
	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL,$url);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curlObj);
	return $result;
}
/*
*得到mtgox平均价
*return number
*/
function mtgoxavg($result){
	$mtarray=json_decode($result);
	$mtarray=(array)$mtarray;
	$showarray=(array)$mtarray['data'];
	$avgarray=(array)$showarray['avg'];
	return $avgarray['value'];
}
//$result=catch_url("https://data.mtgox.com/api/2/BTCUSD/money/ticker");
//$mtgoxavg=mtgoxavg($result);
?>