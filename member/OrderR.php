<?php
header("Content-type:text/html; charset=gb2312"); 

$i=1;
require_once(dirname(__FILE__).'/config.php');
	$dsql->SetQuery("SELECT * FROM btc_btcautobill ");
$dsql->Execute();
while($row = $dsql->GetObject())
{
		echo $i++;
		echo "-".$row->billno."-".$row->url;
		echo "<br>";
}

?>
