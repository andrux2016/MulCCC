<?php
     require_once 'jsonRPCClient.php';
 
  $bitcoin = new jsonRPCClient('http://bitadmin:bitpasswd211@127.0.0.1:8332/');
 
  echo "<pre>\n";
  print_r($bitcoin->getinfo());
  echo "</pre>";
?>