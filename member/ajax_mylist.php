<?php
/**
 * @version        $Id: ajax_btclist.php 1 8:38 2013年7月24日Z
 */
require_once(dirname(__FILE__)."/config.php");
AjaxHead();
$coinid=$coinid?$coinid:"1";//BTC
$moneyid=$moneyid?$moneyid:"1";//CNY
$market=$market?$market:"1";//sz


    $listArr[]=array(  
    'myorder' => $myorder,  
	'mydeal' => $mydeal, 
    );
	$json_string = json_encode($listArr);  
	echo $json_string;

?>
