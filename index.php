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
//	$dtype=$_SERVER['QUERY_STRING'];
	
	//交易类型$cointypelist
	$dsql->SetQuery("Select id, cointype, coinname, coinhost From `#@__btctype` Where coinsign = 1");
	$dsql->Execute();
	while ($rcv = $dsql->GetObject()){
		if($rcv->coinhost == 1){
			$cointypelist[$rcv->id] = array(
				'id' => $rcv->id,
				'cointype' => $rcv->cointype,
				'coinname' => $rcv->coinname
			);
		}
	}
//	if(!isset($dtype) || empty($dtype)){
//		$dtype = $cointypelist[0]['cointype'];
//	}
	
foreach($cointypelist as $k => $v){
	$dsql->SetQuery("Select * From `#@__btcconvert` Where enabled=1 and moneytype = '". $cointypelist[$k]['cointype']."'");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$coinid = $rcv->coinid;
		$moneyid = $rcv->moneyid;
		$cointypelist[$k]["status"][$coinid]=array(
			'coinid'=>$rcv->coinid,
			'cointype'=>$rcv->cointype,
			'coinname'=>$rcv->coinname,
			'moneyid'=>$rcv->moneyid,
			'moneyname'=>$rcv->moneyname,
			'moneytype'=>$rcv->moneytype,
		);
	}
	
	foreach($cointypelist[$k]["status"] as $key=>$tmpdtypearr){
		
		$rcv = $dsql->GetOne("SELECT sum(btccount) as count, sum(tprice) as total FROM #@__btcdeal where market='1' and coinid =".$tmpdtypearr['coinid']." and moneyid=".$tmpdtypearr['moneyid']." AND dealtime>".strtotime("-1 day"));
		$cointypelist[$k]["status"][$key]['count'] = $rcv['count']? rtrimandformat($rcv['count'], 10) : 0;
		$cointypelist[$k]["status"][$key]['total'] = $rcv['total']? rtrimandformat($rcv['total'], 10) : 0;
//		$rcv = $dsql->GetOne("SELECT sum(btccount) as count, sum(tprice) as total FROM #@__btcdeal where market='1' and coinid =".$tmpdtypearr['coinid']." and moneyid=".$tmpdtypearr['moneyid']." AND dealtime>".strtotime("-2 day")." AND dealtime<".strtotime("-1 day"));
//		$thedaybeforeyesterday = $rcv['total']?rtrimandformat($rcv['total'], 10) : 0;
//		$cointypelist[$k]["status"][$key]['updown'] = $thedaybeforeyesterday == 0? '0%' : ((($cointypelist[$k]["status"][$key]['total'] - $thedaybeforeyesterday)/$thedaybeforeyesterday) * 100).'%';
		$rcv = $dsql->GetOne("select uprice from #@__btcdeal where market = '1' and coinid =".$tmpdtypearr['coinid']." and moneyid=".$tmpdtypearr['moneyid']." AND dealtime <". strtotime(date('Y-m-d')." 00:00:00".'-1 day') . " order by dealtime desc Limit 0,1");
		$yesterday = $rcv['uprice']?rtrimandformat($rcv['uprice'], 10) : 0;
		$rcv = $dsql->GetOne("select uprice from #@__btcdeal where market = '1' and coinid =".$tmpdtypearr['coinid']." and moneyid=".$tmpdtypearr['moneyid']." AND dealtime >". strtotime(date('Y-m-d')." 00:00:00".'-1 day'). " order by dealtime desc Limit 0,1");
		$now = $rcv['uprice']?rtrimandformat($rcv['uprice'], 10) : 0;
		$cointypelist[$k]["status"][$key]['updown'] = $yesterday == 0? ($now == 0 ? '0%': '100%') : ($now == 0 ? 0 : ((($now - $yesterday)/$yesterday) * 100)).'%';
	}
	
	foreach ($cointypelist[$k]["status"] as $key=> $tmpdtypearr){
		$coinid = $tmpdtypearr['coinid'];
		$moneyid = $tmpdtypearr['moneyid'];
		$tikarr = FunNewRate($coinid,$moneyid);
		$cointypelist[$k]["status"][$key]['newrate'] = $tikarr? rtrimandformat($tikarr['last_rate'], 10) : 0;
	}
	
//	foreach ($cointypelist[$k]["status"] as $key=>$tmpdtypearr){
//		$dsql->SetQuery("select avg from `#@__statistics` Where coinid = ". $tmpdtypearr['coinid']." and moneyid = ". $tmpdtypearr['moneyid'] . " order by datetime desc limit 0 , 6");
//		$dsql->Execute();
//		$flot = array();
//		for($i=0; $i < 7; $i++){
//			$rcv = $dsql->GetObject();
//			$flot[$i] = $rcv? number_format($rcv->avg,2) : 0;
//		}
//		$cointypelist[$k]["status"][$key]['flot'] = $flot;
//	}
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