<?php
/**
 * @version        $Id: ajax_trans.php 1 8:38 2013年8月29日Z
 *
 */
require_once(dirname(__FILE__)."/config.php");
require_once DEDEINC.'/rpcQuery.php';
/*require_once(dirname(__FILE__)."/jy.free-coin.org/member/config.php");
require_once(dirname(__FILE__)."/jy.free-coin.org/member/rpcQuery.php");*/



$dsql->SetQuery("SELECT id,cointype,lastblock,reccheck,recfee,feetype FROM ".$cfg_dbprefix."btctype WHERE coinsign=1");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	if($row->id != 1){
		$coinarray[$row->id]=array(
			'cointype' => $row->cointype,
			'lastblock' => $row->lastblock,
			'reccheck' => $row->reccheck,
			'recfee' => $row->recfee,
			'feetype' => $row->feetype
		);
	}
}

	
foreach($coinarray as $keyid => $coinarr){//多币种循环
	
	$method="listsinceblock";
	$params=array($coinarr["lastblock"]);
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
			//$upblock = $dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btctype Set lastblock='".$transactions['blockhash']."' Where id='".$keyid."'");
		}
		
	}
}	
		//echo $cfgconfirmations;
		


$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."btctrans WHERE dealmark='0'");
$dsql->Execute();
while($rtrans = $dsql->GetObject())
{
	
	$ruser = $dsql->GetOne("Select mid From #@__member where userid='".$rtrans->account."'");
	if($coinarray[$rtrans->coinid]['feetype']==1){//手续费类型
		$coinfee=$coinarray[$rtrans->coinid]['recfee']*$rtrans->amount;
	}else{
		$coinfee=$coinarray[$rtrans->coinid]['recfee'];
	}
	$row = $dsql->GetOne("Select * From #@__btcrecharge where txid='".$rtrans->txid."'");
	if(!is_array($row) && ($rtrans->amount-$coinfee)>0){
		$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btcrecharge(userid,amount,fee,coinid,address,txid,paytype,dealmark,checked,rcgtime) values('".$ruser['mid']."','".($rtrans->amount-$coinfee)."','".$coinfee."','".$rtrans->coinid."','".$rtrans->address."','".$rtrans->txid."','0','0','0','".time()."')");
	}
	
	if($rtrans->con_ft<$cfgconfirmations){
		$method="gettransaction";
		$params=array($rtrans->txid);
		
		$trans=coinQuery ($coinarray[$rtrans->coinid]['cointype'],$method,$params);
		if(isset($trans['r'])){
			$btctrans=get_object_vars($trans['r']);
			//$details=get_object_vars($btctrans['details'][0]);
			
			$rsup = $dsql->ExecuteNoneQuery("Update #@__btctrans Set blockhash='".$btctrans['blockhash']."',blockindex='".$btctrans['blockindex']."',blocktime='".$btctrans['blocktime']."',con_ft='".$btctrans['confirmations']."' where id = '".$rtrans->id."'"); 
		}
	}elseif(isset($rtrans->blockhash)){
		//echo "<br>";
		//echo $rtrans->account;
		//$upblock = $dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btctype Set lastblock='".$rtrans->blockhash."' Where id='".$rtrans->coinid."'");
		
		$rsup = $dsql->ExecuteNoneQuery("Update #@__btctrans Set dealmark=1 where id = '".$rtrans->id."'"); 
		$rsup = $dsql->ExecuteNoneQuery("Update #@__btcrecharge Set dealmark=1,checked='".$coinarray[$rtrans->coinid]['reccheck']."' where txid = '".$rtrans->txid."'"); 
		
	}
}
?>

