<?php


/**
 * @version        $Id: index.php 1 9:23 2013-08-11
 */
$nowtime = time();
//自动生成HTML版


if(isset($_GET['upcache']) || !file_exists('index.html'))
{
	require_once (dirname(__FILE__) . "/include/common.inc.php");
    require_once DEDEINC."/arc.partview.class.php";
	$showmune=0;
	$cvid = $cvid?$cvid:0;
	$dtype="BTC";//$_SERVER['QUERY_STRING'];
	
	//交易类型$cointypelist
	$dsql->SetQuery("Select id, cointype, coinname, coinhost From `#@__btctype` Where coinsign = 1");
	$dsql->Execute();
	$count = 0;
	while ($rcv = $dsql->GetObject()){
		if($rcv->coinhost == 1){
			$cointypelist[$count] = array(
				'id' => $rcv->id,
				'cointype' => $rcv->cointype,
				'coinname' => $rcv->coinname
			);
			$count++;
		}
	}
	if(!isset($dtype) || empty($dtype)){
		$dtype = $cointypelist[0]['cointype'];
	}
	
	if($dtype == "BTC"){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "http://www.cryptocoincharts.info/v2/api/listCoins");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$rawData = curl_exec($curl);
		curl_close($curl);
		
		// decode to array
		$data = json_decode($rawData);
		
		// show data
		$status = 0;
		foreach ($data as $row){
//			$curl = curl_init();
//			curl_setopt($curl, CURLOPT_URL, "http://www.cryptocoincharts.info/v2/api/tradingPair/" .$row->name . "_BTC");
//			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//			$rawData = curl_exec($curl);
//			curl_close($curl);
			// decode to array
//			$data2 = json_decode($rawData);
			
			$dtypearr[$status] = array(
				'cointype'=>$row->name,
				'coinname'=>$row->name,
				'moneyname'=>'BTC',
				'moneytype'=>'BTC',
				'website'=>$row->website,
				'price_btc'=>$row->price_btc,
//				'price_before_24h'=>$data2->price_before_24h,
				'price_before_24h'=>$row->price_btc,
				'volume_btc'=>$row->volume_btc,
//				'total'=>$row->volume_btc*$data2->price_before_24h,
				'total'=>$row->volume_btc*$row->price_btc,
//				'latest_trade'=>$data2->latest_trade
				'latest_trade'=>date('Y-m-d H:i:s'),
			);
			$status++;
		} 
			
	}else{
	
		$dsql->SetQuery("Select * From `#@__btcconvert` Where enabled=1 and moneytype = '". $dtype."'");
		$dsql->Execute();
		$status = 0;
		while($rcv = $dsql->GetObject())
		{
			$coinid = $rcv->coinid;
			$moneyid = $rcv->moneyid;
			$dtypearr[$status]=array(
				'coinid'=>$rcv->coinid,
				'cointype'=>$rcv->cointype,
				'coinname'=>$rcv->coinname,
				'moneyid'=>$rcv->moneyid,
				'moneyname'=>$rcv->moneyname,
				'moneytype'=>$rcv->moneytype,
			);
			$status++;
		}
		
		foreach($dtypearr as $key=>$tmpdtypearr){
			
			$rcv = $dsql->GetOne("SELECT sum(btccount) as count, sum(tprice) as total FROM #@__btcdeal where market='1' and coinid =".$tmpdtypearr['coinid']." and moneyid=".$tmpdtypearr['moneyid']." AND dealtime>".strtotime("-1 day"));
			$dtypearr[$key]['count'] = $rcv->count? $rcv->count : 0;
			$dtypearr[$key]['total'] = $rcv->total? $rcv->total : 0;
		}
		
		foreach ($dtypearr as $key=> $tmpdtypearr){
			$coinid = $tmpdtypearr['coinid'];
			$moneyid = $tmpdtypearr['moneyid'];
			$tikarr = FunNewRate($coinid,$moneyid);
			$dtypearr[$key]['newrate'] = $tikarr? $tikarr : 0;
		}
		
		foreach ($dtypearr as $key=>$tmpdtypearr){
			$dsql->SetQuery("select avg from `#@__statistics` Where coinid = ". $tmpdtypearr['coinid']." and moneyid = ". $tmpdtypearr['moneyid'] . " order by datetime desc limit 0 , 6");
			$dsql->Execute();
			$flot = array();
			for($i=0; $i < 7; $i++){
				$rcv = $dsql->GetObject();
				$flot[$i] = $rcv? number_format($rcv->avg,2) : 0;
			}
			$dtypearr[$key]['flot'] = $flot;
		}
	}
    $row = $dsql->GetOne("Select * From `#@__homepageset`");
    $row['templet'] = MfTemplet($row['templet']);
    $pv = new PartView();
    $pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/" . $row['templet']);
    $row['showmod'] = isset($row['showmod'])? $row['showmod'] : 0;
    if ($row['showmod'] == 1)
    {
        $pv->SaveToHtml(dirname(__FILE__).'/index.html');
        include(dirname(__FILE__).'/index.html');
        exit();
    } else { 
        $pv->Display();
        exit();
    }
}
else
{
	header('HTTP/1.1 301 Moved Permanently');
    header('Location:index.html');
}





?>