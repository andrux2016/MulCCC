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
	
	if (!isset($_COOKIE["DedeUserID"])) 
	{ 
		//Header("HTTP/1.1 303 See Other"); 
		//Header("Location:".$cfg_weburl); 
		//exit; 
	} 
	$showmune=3;
	$cvid = $cvid?$cvid:1;
	$dtype=explode("_",$_SERVER['QUERY_STRING']);
	
	$dtype1=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[0]);
	$dtype2=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[1]);
	//$rcvid = $dsql->GetOne("Select id From `#@__btcconvert` Where coinid=$dtype1 AND moneyid=$dtype2");
	
	$dsql->SetQuery("Select * From `#@__btcconvert` Where enabled=1");
	$dsql->Execute();
	while($rcv = $dsql->GetObject())
	{
		$dtypearr[$rcv->id]=array(
			'coinid'=>$rcv->coinid,
			'cointype'=>$rcv->cointype,
			'coinname'=>$rcv->coinname,
			'moneyid'=>$rcv->moneyid,
			'moneyname'=>$rcv->moneyname,
			'moneytype'=>$rcv->moneytype,
			'fee'=>$rcv->fee,
			'digits'=>$rcv->digits
		);
		if($rcv->cointype==$dtype1 && $rcv->moneytype==$dtype2){
			$cvid=$rcv->id;
		}
	}
	if($cvid=="") $cvid=1;
			$coinid=$dtypearr[$cvid]['coinid'];
			$cointype=$dtypearr[$cvid]['cointype'];
			$coinname=$dtypearr[$cvid]['coinname'];
			$moneyid=$dtypearr[$cvid]['moneyid'];
			$moneyname=$dtypearr[$cvid]['moneyname'];
			$moneytype=$dtypearr[$cvid]['moneytype'];
			$fee=$dtypearr[$cvid]['fee'];
			$digits=$dtypearr[$cvid]['digits'];
	foreach($dtypearr as $key => $typemune){
		/*if($key==$cvid) $convertName .= "<li><a class='show' href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a></li>";
		else $convertName .= "<li><a class='hide' href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a></li>";*/
		
		$rateAllArr = FunNewRate($typemune['coinid'],$typemune['moneyid']);
		//if($key==$cvid) $convertName .= "<li class='coinshow'><a href='".$cfg_cmsurl."/trade.php?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a><br>".$rateAllArr['last_rate']."</li>";
		//else $convertName .= "<li class='coinhide'><a href='".$cfg_cmsurl."/trade.php?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a><br>".$rateAllArr['last_rate']."</li>";
		if($key==$cvid) $convertName .= "<li class='coinshow cur'><a class='otherBoxTitle' href='".$cfg_cmsurl."/stock.php?".$typemune['cointype']."_".$typemune['moneytype']."'>".$typemune['cointype']."行情</a></li>";
		else $convertName .= "<li class='coinhide'><a class='otherBoxTitle' href='".$cfg_cmsurl."/stock.php?".$typemune['cointype']."_".$typemune['moneytype']."'>".$typemune['cointype']."行情</a></li>";

	}
	//echo $coinid;
	$rty = $dsql->GetOne("Select about From `#@__btctype` Where id='".$coinid."'");
	$about=$rty['about'];
	
    $GLOBALS['_arclistEnv'] = 'index';
	
	$coindeposit=0;
	$moneydeposit=0;
	
//用户余额
	$cfg_arrcoin=Getdeposit("",$_COOKIE["DedeUserID"],$moneyid);
	foreach ($cfg_arrcoin as $value){
		if($value['1']>0 || $value['2']>0){
			if($value['0']=="CNY") $coinhtml.="<div style='width:100%'>".$value['0'].":<font color='#FF0000'>".(floor($value['1']*100)/100)."</font>&nbsp;&nbsp;冻结：".($value['2']/1)."</div>";
			else $coinhtml.="<div style='width:100%'>".$value['0'].":<font color='#FF0000'>".(floor($value['1']*10000)/10000)."</font>&nbsp;&nbsp;冻结：".($value['2']/1)."</div>";
		}
		//$coinhtml.="<div><label class='fleft'>".$value['0']."余额：</label><span class='coininfo'>".($value['1']/1)."</span><span>冻结：".($value['2']/1)."</span></div>";
		if($value['0']==$cointype){
			if($value['0']=="CNY") $coindeposit=(floor($value['1']*100)/100);
			else $coindeposit=(floor($value['1']*10000)/10000);
		}
		if($value['0']==$moneytype){
			if($value['0']=="CNY") $moneydeposit=(floor($value['1']*100)/100);
			else $moneydeposit=(floor($value['1']*10000)/10000);
		}
		$coinvol+=$value['4'];
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
	$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' AND dealtype=1 ORDER BY uprice LIMIT 100");
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		$ordersell[$rod->uprice] = array(  
			'vol' => $ordersell[$rod->uprice]['vol']+$rod->btccount*1, 
			'rate' => $rod->uprice/1,  
			'count' => $ordersell[$rod->uprice]['count']+1
		);
	}
	foreach($ordersell as $k=>$v){
		$listsell[] = array(  
			'symbol_l' => $v['vol'], 
			'rate' => $v['rate'], 
			'symbol_r' => $v['rate']*$v['vol'], 
			'count' => $v['count']
		);
	}
	//读取挂单
	$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' AND dealtype=0 ORDER BY uprice DESC LIMIT 100");
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		$orderbuy[$rod->uprice] = array(  
			'vol' => $orderbuy[$rod->uprice]['vol']+$rod->btccount/1, 
			'rate' => $rod->uprice/1,  
			'count' => $orderbuy[$rod->uprice]['count']+1
		);
	}
	foreach($orderbuy as $k=>$v){
		$listbuy[] = array(  
			'symbol_l' => $v['vol'], 
			'rate' => $v['rate'], 
			'symbol_r' => $v['rate']*$v['vol'], 
			'count' => $v['count']
		);
	}
	
	$ask_bid_list= json_encode($listsell).",".json_encode($listbuy);  



	//读取我的挂单
$orderArr=FunMyOrder($_COOKIE["DedeUserID"],$coinid,$moneyid,20);

foreach ($orderArr as $value){
	$dtype = $value['dealtype'] == 1 ?  "<font color='#FF0000'>卖</font>" : "<font color='#009900'>买</font>";
	$ordershow .= "<ul><span style=\"width:27%;\">".date('Y-m-d H:i:s',$value['ordertime'])."</span><span style=\"width:13%;\">". $dtype ."</span><span style=\"width:20%;\">".($value['uprice']/1)."</span><span style=\"width:20%;\">".($value['btccount']/1)."</span><span style=\"width:12%;\"><a class='trash' href='#' title='撤单' onclick='_page.obj.cancel_order(\"".$dtypearr[$value['coinid']]['cointype']."_".$dtypearr[$value['moneyid']]['moneytype']."\",\"".$value['oid']."\");'>撤单</a></span></ul>";
	
	
}


//读取我的成交记录
$dealArr=FunMyDeal($_COOKIE["DedeUserID"],$coinid,$moneyid,20);
//print_r($dealArr);
foreach ($dealArr as $value){
	$dtype = $value['dealtype']== 1 ?  "<font color='#FF0000'>卖</font>" : "<font color='#009900'>买</font>";
	$dealshow .= "<ul><span style=\"width:24%;\">".date('Y-m-d H:i:s',$value['dealtime'])."</span><span style=\"width:12%;\">". $dtype ."</span><span style=\"width:18%;\">".(round($value['uprice']*10000)/10000)."</span><span style=\"width:18%;\">".(round($value['btccount']*10000)/10000)."</span><span style=\"width:20%;\">".(round($value['uprice']*$value['btccount']*10000)/10000)."</span></ul>";
}

$tikarr = FunNewRate($coinid,$moneyid);

//读取成交

	$dsql->SetQuery("SELECT id,btccount,uprice,dealtype,dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' ORDER BY dealtime DESC LIMIT 20");
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		if($rod->dealtype==0) $orderT="<font color='#009900'>买入</font>";
		else  $orderT="<font color='#FF0000'>卖出</font>";
		$dealarr[]=array(  
			'date' => $rod->dealtime, 
			'rate' => $rod->uprice/1, 
			'amount_l' => $rod->btccount/1, 
			'amount_r' => $rod->uprice*$rod->btccount/1, 
			'order' => $orderT, 
			'ticket' => $rod->id 
		);
	}

	$history_list = json_encode($dealarr);  

	
    $row = $dsql->GetOne("Select * From `#@__homepageset`");
    $row['templet'] = MfTemplet($row['templet']);
    $pv = new PartView();
    //$pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/" . $row['templet']);
	$pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/default/stock.htm");
    //$row['showmod'] = isset($row['showmod'])? $row['showmod'] : 0;
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