<?php


/**
 * @version        $Id: oldindex.php 1 9:23 2013-08-11
 */
$nowtime = time();
//自动生成HTML版

if(isset($_GET['upcache']) || !file_exists('oldindex.html'))
{
	require_once (dirname(__FILE__) . "/include/common.inc.php");
    require_once DEDEINC."/arc.partview.class.php";
	$showmune=1;
	$cvid = $cvid?$cvid:0;
	$dtype=explode("_",$_SERVER['QUERY_STRING']);
	$dtype1=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[0]);
	$dtype2=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[1]);
	
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
	$dsql->SetQuery("Select * From `#@__btcconvert` Where enabled=1");
	$dsql->Execute();
	$status = 0;
	while($rcv = $dsql->GetObject())
	{
		$dtypearr[$rcv->id]=array(
			'coinid'=>$rcv->coinid,
			'cointype'=>$rcv->cointype,
			'coinname'=>$rcv->coinname,
			'moneyid'=>$rcv->moneyid,
			'moneyname'=>$rcv->moneyname,
			'moneytype'=>$rcv->moneytype,
			'fee'=>rtrimandformat($rcv->fee, 10),
			'digits'=>rtrimandformat($rcv->digits, 10)
		);
		if($rcv->cointype==$dtype1 && $rcv->moneytype==$dtype2 && $status == 0){
			$cvid=$rcv->id;
			$status = 1;
		}else if($rcv->moneytype==$dtype1 && (!isset($dtype2) || empty($dtype2)) && $status == 0){
			$cvid=$rcv->id;
			$dtype2= $rcv->cointype;
			$status = 1;
		}
	}
	
	$typeinfolist = array();
	if($cvid=="" || $cvid == 0){
		if(count($cointypelist) > 0){
			foreach ($dtypearr as $tmpdtype){
				if($tmpdtype['moneyid'] == $cointypelist[0]['id'] && $tmpdtype['moneytype'] == $cointypelist[0]['cointype']){
					array_push($typeinfolist, $tmpdtype);
				}
			}
		}else{
			exit("error");	//错误处理
		}
		$cvid=0;
		$coinid=$typeinfolist[$cvid]['coinid'];
		$cointype=$typeinfolist[$cvid]['cointype'];
		$coinname=$typeinfolist[$cvid]['coinname'];
		$moneyid=$typeinfolist[$cvid]['moneyid'];
		$moneyname=$typeinfolist[$cvid]['moneyname'];
		$moneytype=$typeinfolist[$cvid]['moneytype'];
		$fee=$typeinfolist[$cvid]['fee'];
		$digits=$typeinfolist[$cvid]['digits'];
	}else{
		if(count($cointypelist) > 0){
			foreach ($dtypearr as $tmpdtype){
				if($tmpdtype['moneytype'] == $dtypearr[$cvid]['moneytype']){
					array_push($typeinfolist, $tmpdtype);
				}
			}
		}else{
			exit("error");	//错误处理
		}
		$coinid=$dtypearr[$cvid]['coinid'];
		$cointype=$dtypearr[$cvid]['cointype'];
		$coinname=$dtypearr[$cvid]['coinname'];
		$moneyid=$dtypearr[$cvid]['moneyid'];
		$moneyname=$dtypearr[$cvid]['moneyname'];
		$moneytype=$dtypearr[$cvid]['moneytype'];
		$fee=$dtypearr[$cvid]['fee'];
		$digits=$dtypearr[$cvid]['digits'];
	}
	
	foreach ($dtypearr as $key => $typenume){
		if($typenume['cointype'] == $cointype && $typenume['moneytype'] == $moneytype){
			$coinfee = $typenume['fee'];
		}
		if($typenume['cointype'] == $moneytype && $typenume['moneytype'] == $cointype){
			$moneyfee = $typenume['fee'];
		}
	}

	foreach($typeinfolist as $key => $typemune){
		/*if($key==$cvid) $convertName .= "<li><a class='show' href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a></li>";
		else $convertName .= "<li><a class='hide' href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a></li>";*/
		$rateAllArr = FunNewRate($typemune['coinid'],$typemune['moneyid']);
		/*if($key==$cvid) $convertName .= "<li class='coinshow'><a href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a><br>".$rateAllArr['last_rate']."</li>";
		else $convertName .= "<li class='coinhide'><a href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a><br>".$rateAllArr['last_rate']."</li>";*/
		if($typemune['cointype'] == $cointype) $convertName .= "<a class='btc8-abase btc8-avist' href='".$cfg_cmsurl."/oldindex.php?".$typemune['cointype']."_".$typemune['moneytype']."'>".$typemune['cointype']."/".$typemune['moneytype']."<br>".rtrimandformat($rateAllArr['last_rate'])."</a>";
		else $convertName .= "<a class='btc8-abase' href='".$cfg_cmsurl."/oldindex.php?".$typemune['cointype']."_".$typemune['moneytype']."'>".$typemune['cointype']."/".$typemune['moneytype']."<br>".rtrimandformat($rateAllArr['last_rate'])."</a>";
	}
	//echo $coinid;
	$rty = $dsql->GetOne("Select about From `#@__btctype` Where id='".$coinid."'");
	$about=$rty['about'];
	
    $GLOBALS['_arclistEnv'] = 'index';
	
	$coindeposit=0;
	$moneydeposit=0;

if($_COOKIE["DedeUserID"]!="" || !isset($_COOKIE["DedeUserID"])){
	//用户余额
	$cfg_arrcoin=Getdeposit("",$_COOKIE["DedeUserID"],$moneyid);
	for($i=0 ; $i<$digits ; $i++){
		$dignum=$dignum*10;
	}
	foreach ($cfg_arrcoin as $value){
						
		if($value['0']=="CNY") $coinCNY = (floor($value['1']*$dignum)/$dignum);
				
		else $coinhtml.="<div class='row-btc'>".$value['0'].":".(floor($value['1']*$dignum)/$dignum)."</div>";
		//$coinhtml.="<div><label class='fleft'>".$value['0']."余额：</label><span class='coininfo'>".($value['1']/1)."</span><span>冻结：".($value['2']/1)."</span></div>";
		if($value['0']==$cointype) $coindeposit=rtrimandformat(floor($value['1']*$dignum)/$dignum, 10);
		if($value['0']==$moneytype) $moneydeposit=rtrimandformat(floor($value['1']*$dignum)/$dignum, 10);
		$coinvol+=$value['4'];
	}
	
	
	
}
	
/*
走势图数据
*/
//echo date('Y-m-d H:i:s',"1375429116");

	$tspan=$tspan?$tspan:"300";
	$numbers =$count?$count:100;
	//$numbers =100;
	
	$dsql->SetQuery("SELECT * FROM #@__btctline WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' AND tspan='".$tspan."' ORDER BY E_time DESC LIMIT $numbers");
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		$tlinearr[]=array(  
			($rod->E_time+28800)*1000,
			$rod->R_open/1, 
			$rod->R_high/1, 
			$rod->R_low/1, 
			$rod->R_close/1, 
			$rod->volume/1 
		);
	}
	if(!is_array($tlinearr)){
		for($i=0;$i<100;$i++){
			$tlinearr[]=array(  
			((time()-$i*300)+28800)*1000,
			0, 
			0, 
			0, 
			0, 
			0 
			);
		}
	}

	$time_line = str_replace("\"","",json_encode(array_reverse($tlinearr)));  
	
	//读取挂单
	$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' AND dealtype=1 ORDER BY uprice desc LIMIT 10");
	$dsql->Execute();
	$status = 0;
	while($rod = $dsql->GetObject())
	{
		$ordersell[$status] = array(  
			'vol' => $ordersell[$rod->uprice]['vol']+$rod->btccount*1, 
			'rate' => $rod->uprice/1,  
			'count' => $ordersell[$rod->uprice]['count']+1
		);
		$status++;
	}
	foreach($ordersell as $k=>$v){
		$listsell[] = array(  
			'symbol_l' => rtrimandformat($v['vol'], 10), 
			'rate' => rtrimandformat($v['rate'],10), 
			'symbol_r' => rtrimandformat($v['rate']*$v['vol'],10),
			'count' => rtrimandformat($v['count'],10)
		);
	}
	//读取挂单
	$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' AND dealtype=0 ORDER BY uprice desc LIMIT 10");
	$dsql->Execute();
	$status = 0;
	while($rod = $dsql->GetObject())
	{
		$orderbuy[$status] = array(  
			'vol' => $orderbuy[$rod->uprice]['vol']+$rod->btccount/1, 
			'rate' => $rod->uprice/1,  
			'count' => $orderbuy[$rod->uprice]['count']+1
		);
		$status++;
	}
	foreach($orderbuy as $k=>$v){
		$listbuy[] = array(  
			'symbol_l' => rtrimandformat($v['vol'], 10), 
			'rate' => rtrimandformat($v['rate'],10), 
			'symbol_r' => rtrimandformat($v['rate']*$v['vol'],10), 
			'count' => rtrimandformat($v['count'], 10)
		);
	}
	
	$ask_bid_list= json_encode($listsell).",".json_encode($listbuy);  

$tikarr = FunNewRate($coinid,$moneyid);
//读取成交

	$dsql->SetQuery("SELECT id,btccount,uprice,dealtype,dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' ORDER BY id DESC LIMIT 20");
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		if($rod->dealtype==0) $orderT="<font color='#009900'>买入</font>";
		else  $orderT="<font color='#FF0000'>卖出</font>";
		$dealarr[]=array(  
			'date' => $rod->dealtime, 
			'rate' => $rod->uprice/1, 
			'amount_l' => $rod->btccount/1, 
			'amount_r' => rtrimandformat($rod->uprice*$rod->btccount/1 ,10),
			'order' => $orderT, 
			'ticket' => $rod->id 
		);
		$btchtml.="<tr><td>".$rod->dealtime."</td><td>".$orderT."</td><td>￥".del0($rod->uprice)."</td><td>฿".del0($rod->btccount)."</td><td>￥".del0($rod->uprice*$rod->btccount)."</td></tr>";
	}

	$history_list = json_encode($dealarr);  


$row = $dsql->GetOne("Select * From `#@__homepageset`");
    $row['templet'] = MfTemplet($row['templet']);
    $pv = new PartView();
    $pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/default/oldindex.htm");
    //$row['showmod'] = isset($row['showmod'])? $row['showmod'] : 0;
    
        $pv->Display();
        exit();
}
else
{
	header('HTTP/1.1 301 Moved Permanently');
    header('Location:oldindex.html');
}


?>