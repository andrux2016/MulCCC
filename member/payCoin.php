<?php
/*
@version        $Id: btc_deal.php 1 8:38 2013年8月8日Z 
 */

$mysql_server_name='localhost';
$mysql_username='root';
$mysql_password='';
$mysql_database='btcdata';
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
//$sql='CREATE DATABASE mycounter DEFAULT CHARACTER SET gbk COLLATE gbk_chinese_ci;';
//mysql_query($sql);
//$sql='CREATE TABLE `counter` (`id` INT(255) UNSIGNED NOT NULL AUTO_INCREMENT ,`count` INT(255) UNSIGNED NOT NULL DEFAULT 0,PRIMARY KEY ( `id` ) ) TYPE = innodb;';
//mysql_select_db($mysql_database,$conn);
//$sql='Select c_deposit,coinid From btc_btccoin where userid = ".$cfg_ml->M_ID." AND coinid='1';';
//$result=mysql_query($sql);

$query_cp = "SELECT * FROM btc_btccoin ORDER BY id DESC";
$cp = mysql_query($query_cp, $conn) or die(mysql_error());
while ($rs=mysql_fetch_array($result)){
echo "1";
}

mysql_close($conn);
echo "Hello!数据库mycounter已经成功建立！";

?>