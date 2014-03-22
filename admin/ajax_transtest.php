<?php
/**
 * @version        $Id: ajax_trans.php 1 8:38 2013年8月29日Z
 *
 */
//require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once DEDEINC.'/rpcQuery.php';
/*require_once(dirname(__FILE__)."/jy.free-coin.org/member/config.php");
require_once(dirname(__FILE__)."/jy.free-coin.org/member/rpcQuery.php");*/



$dsql->SetQuery("SELECT id,cointype,lastblock,reccheck,recfee,feetype,coinhost FROM ".$cfg_dbprefix."btctype WHERE id=36");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	if($row->id != 1){
		$coinarray[$row->id]=array(
			'cointype' => $row->cointype,
			'lastblock' => $row->lastblock,
			'reccheck' => $row->reccheck,
			'recfee' => $row->recfee,
			'coinhost' => $row->coinhost,
			'feetype' => $row->feetype
		);
	}
}

	
foreach($coinarray as $keyid => $coinarr){//多币种循环

	
	$method="listsinceblock";
	$params=array();
	echo $coinarr["cointype"].$method;
	$trans=coinQuery($coinarr["cointype"],$method,$params);
	echo "lastblock:".$coinarr['lastblock'];
	echo "<pre>";
	print_r($trans);
	echo "</pre>";

	if(isset($trans['r'])){
		
		$btctrans=get_object_vars($trans['r']);
		
		foreach(array_reverse($btctrans['transactions']) as $value){//多条交易记录循环
			$transactions=get_object_vars($value);
			
			if(isset($transactions['txid'])){
			
			$row = $dsql->GetOne("Select * From ".$cfg_dbprefix."btctrans where txid='".$transactions['txid']."'");
				if(!is_array($row) && ($transactions['amount']-$coinfee)>0){
				$slq="insert into ".$cfg_dbprefix."btctrans(account,address,amount,fee,`con_ft`,blockhash,blockindex,blocktime,txid,time,timereceived,coinid) values('".$transactions['account']."','".$transactions['address']."','".$transactions['amount']."','".$transactions['fee']."','".$transactions['confirmations']."','".$transactions['blockhash']."','".$transactions['blockindex']."','".$transactions['blocktime']."','".$transactions['txid']."','".$transactions['time']."','".$transactions['timereceived']."','".$keyid."')";
				//print_r($transactions);
				$rsnew = $dsql->ExecuteNoneQuery($slq);
				
				}
				$upblock = $dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btctype Set lastblock='".$btctrans['lastblock']."' Where id='".$keyid."'");
			}
			
			//$rsnewid = $dsql->GetLastID(); 
			//记录最新的block
			$upblock = $dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btctype Set lastblock='".$transactions['blockhash']."' Where id='".$keyid."'");
		}
		
	}
}	

	
?>

