<?php
/**
 * @version        $Id: ajax_login.php 1 8:38 2013年7月9日Z
 */
require_once(dirname(__FILE__)."/config.php");
AjaxHead();

if($myurl != '')
{
	$username=$cfg_ml->M_LoginID;
	require_once DEDEINC.'/rpcQuery.php';
	if($action==1){
		$btcaddress=coinQuery ($cointype,'getnewaddress',array($username));
		$uaddress=$btcaddress['r'];
	}else{
		$btcaddress=coinQuery ($cointype,'getaddressesbyaccount',array($username));
		if(!isset($btcaddress)){
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



