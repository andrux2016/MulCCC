<?php
    require_once '../jsonRPCClient.php';
 
  $bitcoin = new jsonRPCClient('http://bitadmin:bitpasswd211@127.0.0.1:8332/');
  $method="getnewaddress";
  $params[]="test";
  echo "<pre>\n";
   print_r($bitcoin->getinfo());
  //print_r($bitcoin->$method($params));
  echo "</pre>";
 require_once("../../data/rpcQuery.php");
 $cointype="BTC";
 $method="getnewaddress";
 $params=array("swerq");
 $test=rpcQuery($cointype,$method,$params);
 //print_r($test);
?>