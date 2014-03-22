<?php

//%APPDATA%\Bitcoin\
   /*require_once 'jsonRPCClient.php';
 
 $bitcoin = new jsonRPCClient('http://u:p@127.0.0.1:38888/');
 
	 
  echo "<pre>\n";
  print_r($bitcoin -> getinfo());
  echo "</pre>";*/
 
  /* try {
        //$username = "78c1240d1ec28f0fb45805aafb441ccb461a458b70c9309b8063b789cb6ac504-000";
		$account = "aaa";
		$address="14zVkwQykGBg6ub4YffNPfNVB71e2Fj2TJ";
		$amount="0.01";
		$sendTo[]=array(
		Key=>$address,
		Value=>$amount
		);
		//print_r($sendTo);
        if(isset($_SESSION['sendaddress']))
            $sendaddress = $_SESSION['sendaddress'];
			
        else {
            //$sendaddress = $bitcoin->getnewaddress($username);
            //$_SESSION['sendaddress'] = $sendaddress;
			//$balance = $bitcoin->getaddressesbyaccount($username);//$username = "aaa";  Array ( [0] => 1AQVphtg2AeTjd1mjnmmt6pgDSEtXy2erg ) 
			//$balance = $bitcoin->getaccountaddress($username);//$username = "aaa";  1AQVphtg2AeTjd1mjnmmt6pgDSEtXy2erg
			//$balance = $bitcoin->getbalance($username);
			$balance = $bitcoin->getreceivedbyaccount ($account, $minconf=1);

			//$balance = $bitcoin->sendtoaddress ($address, $amount, $comment=NULL, $comment_to=NULL);
			//$balance = $bitcoin->sendmany ($fromAccount, $sendTo, $minconf=1, $comment=NULL);
			

			$balance = $bitcoin->help("sendmany");
			echo "<pre>\n";
			print_r($balance);
			echo "</pre>";
			//$balance = $bitcoin->sendtoaddress ($address, $amount,$comment="aa", $comment_to="bb");
        }
        //$balance = $bitcoin->getbalance($username);
    }
    catch (Exception $e) {
        die("<p>Server error! Please contact the admin.</p>");
    }*/
	
	
	require_once '../include/rpcQuery.php';
				//$username=$cfg_ml->M_LoginID;
				$btcadress=rpcQuery ('FEC','getaddressesbyaccount',array("aaa"));

				/*if(!isset($btcadress['r'][0])){
					$btcadress=rpcQuery ('FEC','getnewaddress',array("aaa"));
					$uadress=$btcadress['r'];
				}else{
					$uadress=$btcadress['r'][0];
				}*/
				echo $btcadress['r'][0];
?>