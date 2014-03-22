<?php
/**
 * @version        $Id: ajax_login.php 1 8:38 2013年7月9日Z
 */
require_once(dirname(__FILE__)."/config.php");
AjaxHead();

echo "xxx";
//
if($myurl != '') 
{
	$username=$cfg_ml->M_LoginID;
	
	
	/*
	$coinhost=$cointype?GetCoinHost($cointype):exit();
	if($coinhost==1){
		$params=mchStrCode(json_encode(array($username)),'ENCODE');//提交的参数，用户名
		if($action==1){		
			$handle = fopen("http://".$cfgcoinip."/payAddress.php?params=".$params."&method=getnewaddress&cointype=".$cointype, "rb"); 
			$contents = stream_get_contents($handle); 
			fclose($handle);
			$obj = json_decode(mchStrCode($contents,'DECODE'));
			$btcaddress=(array)$obj;
			$uaddress=$btcaddress['r'];
		}else{
			$handle = fopen("http://".$cfgcoinip."/payAddress.php?params=".$params."&method=getaddressesbyaccount&cointype=".$cointype, "rb"); 
			$contents = stream_get_contents($handle); 
			fclose($handle);
			$obj = json_decode(mchStrCode($contents,'DECODE'));
			$btcaddress=(array)$obj;
			if(!isset($btcaddress['r'][0])){
				$handle = fopen("http://".$cfgcoinip."/payAddress.php?params=".$params."&method=getnewaddress&cointype=".$cointype, "rb"); 
				$contents = stream_get_contents($handle); 
				fclose($handle);
				$obj = json_decode(mchStrCode($contents,'DECODE'));
				$btcaddress=(array)$obj;
				$uaddress=$btcaddress['r'];
			}else{
				$uaddress=$btcaddress['r'][0];
			}
		}
	}else{}*/
	echo "123";
		require_once DEDEINC.'/rpcQuery1.php';
		echo "234";
		if($action==1){		
			$btcaddress=coinQuery ($cointype,'getnewaddress',array($username));
			print_r($btcaddress);
			$uaddress=$btcaddress['r'];
		}else{
			$btcaddress=coinQuery ($cointype,'getaddressesbyaccount',array($username));
			if(!isset($btcaddress['r'][0])){
				$btcaddress=coinQuery ($cointype,'getnewaddress',array($username));
				$uaddress=$btcaddress['r'];
			}else{
				$uaddress=$btcaddress['r'][0];
			}
		}
	

$userArray=array(  
	'address' => $uaddress, 
);
$json_string = json_encode($userArray);  
echo $json_string;

	
}


		
