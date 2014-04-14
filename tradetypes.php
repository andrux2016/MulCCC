<?php
/**
 * @version        $Id: index.php 1 9:23 2013-08-11
 */
$nowtime = time();
//自动生成HTML版

	require_once (dirname(__FILE__) . "/member/config.php");
	CheckRank(0,0);
    require_once DEDEINC."/arc.partview.class.php";
	$showmune=1;
	$cvid = $cvid?$cvid:0;
	//$dtype=explode("_",$_SERVER['QUERY_STRING']);
	$dtype=$symbol?explode("_",$symbol): "";
	$dtype1=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[0]);
	$dtype2=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[1]);
	$type=$type?$type:"buy";
	//$rcvid = $dsql->GetOne("Select id From `#@__btcconvert` Where coinid=$dtype1 AND moneyid=$dtype2");
	
	$dsql->SetQuery("select * from `#@__btctype` where coinsign = 1 and coinhost = 1");
	$dsql->Execute();
	$status = 0;
	while($rcv = $dsql->GetObject()){
		$coinarr[$status] = array(
			'cointype'=>$rcv->cointype,
			'coinname'=>$rcv->coinname
		);
		$status ++;
	}
	$convertTypeNum = $status/6 + (($status % 6) == 0?  0 : 1);
	
	if(!isset($symbol) || empty($symbol)){
		$symbol = $coinarr[0]['cointype'];
	}else{
		$symbol = explode("_",$symbol);
		if(count($symbol) == 1){
			$symbol = preg_replace("#[^A-Za-z-]#", "", $symbol[0]);
		}else{
			$symbol = preg_replace("#[^A-Za-z-]#", "", $symbol[1]);
		}
	}
	
	foreach($coinarr as $key => $coinelement){
		if($coinarr[$key]['cointype'] == $symbol) $convertType .= "<li class='li1 cur'><a href='?type=".$type."&symbol=".$symbol."'>".$symbol."</a></li>";
		else $convertType .= "<li><a href='?type=".$type."&symbol=".$coinelement['cointype']."'>".$coinelement['cointype']."</a></li>";
	}
	
	$dsql->SetQuery("Select * From `#@__btcconvert` Where enabled=1 and moneytype = '" . $symbol . "'");
	$dsql->Execute();
	$status = 0;
	while($rcv = $dsql->GetObject())
	{
		$dtypearr[$status]=array(
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
			$cvid=$status;
		}
		$status++;
	}
	$convertNameNum = $status/6 + (($status % 6) == 0?  0 : 1);
	
	if($cvid=="") $cvid=0;
			$coinid=$dtypearr[$cvid]['coinid'];
			$cointype=$dtypearr[$cvid]['cointype'];
			$coinname=$dtypearr[$cvid]['coinname'];
			$moneyid=$dtypearr[$cvid]['moneyid'];
			$moneyname=$dtypearr[$cvid]['moneyname'];
			$moneytype=$dtypearr[$cvid]['moneytype'];
			$fee=$dtypearr[$cvid]['fee'];
			$digits=$dtypearr[$cvid]['digits'];
			
			if($type=="buy") $showtype="买入";
			else $showtype="卖出";
	foreach($dtypearr as $key => $typemune){
		/*if($key==$cvid) $convertName .= "<li><a class='show' href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a></li>";
		else $convertName .= "<li><a class='hide' href='".$cfg_cmsurl."/?".$typemune['cointype']."_".$typemune['moneytype']."'><span>".$typemune['cointype']."/".$typemune['moneytype']."</span></a></li>";*/

		$rateAllArr = FunNewRate($typemune['coinid'],$typemune['moneyid']);
		if($key==$cvid) $convertName .= "<li class='li1 cur'><a href='?type=".$type."&symbol=".$typemune['cointype']."_".$typemune['moneytype']."'>".$showtype.$typemune['cointype']."<span>(".$typemune['moneytype'].")</span></a></li>";
		else $convertName .= "<li><a href='?type=".$type."&symbol=".$typemune['cointype']."_".$typemune['moneytype']."'>".$showtype.$typemune['cointype']."<span>(".$typemune['moneytype'].")</span></a></li>";
		
	}
	//echo $coinid;
	$rty = $dsql->GetOne("Select about From `#@__btctype` Where id='".$coinid."'");
	$about=$rty['about'];
	
    $GLOBALS['_arclistEnv'] = 'index';
	
	
	$cfg_arrcoin=Getdeposit("",$cfg_ml->M_ID);

foreach ($cfg_arrcoin as $value){

	if($value['0']=="CNY"){
		$coinhtml.="<li>".$value['0']."：<span>".(floor($value['1']*100)/100)."</span><span class='but'><a href='buy_btc.php' >充值</a></span></li>";
		$freehtml.="<li>冻结：<span>".($value['2']/1)."</span></li>";
	}else{
		$coinhtml.="<li>".$value['0']."：<span>".(floor($value['1']*100)/100)."</span></li>";
		$freehtml.="<li>冻结：<span>".($value['2']/1)."</span></li>";
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
			$rod->E_time*1000,
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
			(time()-$i*300)*1000,
			0, 
			0, 
			0, 
			0, 
			0 
			);
		}
	}
	
	$time_line = str_replace("\"","",json_encode(array_reverse($tlinearr)));  
	
	
	//读取我的挂单
$dsql->SetQuery("SELECT oid,uprice,btccount,dealtype,coinid,moneyid,ordertime FROM #@__btcorder WHERE userid=".$cfg_ml->M_ID." AND coinid='".$coinid."' AND moneyid='".$moneyid."' ORDER BY ordertime DESC");
$dsql->Execute();
while($rord = $dsql->GetObject())
{
	//$dtype = $rord->dealtype == 1 ?  "<font color='#FF0000'>卖</font>" : "<font color='#009900'>买</font>";
	$dtype = $rord->dealtype == 1 ?  "<td width=\"134\" class='red'>卖出</td>" : "<td width=\"134\" class='lightgreen5'>买入</td>";
	//$ordershow .= "<ul><span style=\"width:27%;\">".date('Y-m-d H:i:s',$rord->ordertime)."</span><span style=\"width:13%;\">". $dtype ."</span><span style=\"width:20%;\">".($rord->uprice/1)."</span><span style=\"width:20%;\">".($rord->btccount/1)."</span><span style=\"width:12%;\"><a class='trash' href='#' title='撤单' onclick='_page.obj.cancel_order(\"".$dtypearr[$rcv->coinid]['cointype']."_".$dtypearr[$rcv->moneyid]['moneytype']."\",\"".$rord->oid."\");'>撤单</a></span></ul>";
	$ordershow .= "<tr><td width=\"204\">".date('Y-m-d H:i:s',$rord->ordertime)."</td>". $dtype ."<td width=\"144\">฿".($rord->btccount/1)."</td><td width=\"134\">￥".($rord->uprice/1)."</td><td width=\"154\" class=\"blue\"><a class='trash' href='#' title='撤单' onclick='_page.obj.cancel_order(\"".$dtypearr[$rcv->coinid]['cointype']."_".$dtypearr[$rcv->moneyid]['moneytype']."\",\"".$rord->oid."\");'>撤单</a></td></tr>";
}
	//读取我的成交记录
$dsql->SetQuery("SELECT uprice,tprice,btccount,coinid,moneyid,dealtime FROM #@__btcdeal WHERE (buserid=".$cfg_ml->M_ID." OR suserid=".$cfg_ml->M_ID.") AND coinid='".$coinid."' AND moneyid='".$moneyid."' ORDER BY dealtime DESC");
$dsql->Execute();
while($rord = $dsql->GetObject())
{
	
	$dtype = $rord->suserid==$cfg_ml->M_ID ?  "<font color='#FF0000'>卖</font>" : "<font color='#009900'>买</font>";
	$dealshow .= "<ul><span style=\"width:24%;\">".date('Y-m-d H:i:s',$rord->dealtime)."</span><span style=\"width:12%;\">". $dtype ."</span><span style=\"width:18%;\">".(round($rord->uprice*10000)/10000)."</span><span style=\"width:18%;\">".(round($rord->btccount*10000)/10000)."</span><span style=\"width:20%;\">".(round($rord->tprice*10000)/10000)."</span></ul>";
}

	
	
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
			'symbol_l' => rtrimandformat($v['vol']), 
			'rate' => rtrimandformat($v['rate']), 
			'symbol_r' => rtrimandformat($v['rate']*$v['vol']), 
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
			'symbol_l' => rtrimandformat($v['vol']), 
			'rate' => rtrimandformat($v['rate']), 
			'symbol_r' => rtrimandformat($v['rate']*$v['vol']), 
			'count' => $v['count']
		);
	}
	if($type == "buy"){
		foreach($listbuy as $listval){
			$contenthtml.= "<tr><td style='color:#068814'> "  . $listval['rate'] . "</td><td style='color:#068814'>" . $listval['symbol_l'] . "</td><td style='color:#068814'> " .$listval['symbol_r'] . "</td></tr>";
		}
	}else {
		foreach($listsell as $listval){
			$contenthtml.= "<tr><td style='color:#ff0000'> "  . $listval['rate'] . "</td><td style='color:#ff0000'>" . $listval['symbol_l'] . "</td><td style='color:#ff0000'> " .$listval['symbol_r'] . "</td></tr>";
		}
	}
	

/*
最新价格
*/
//$tikarr=newratefun($coinid,$moneyid,1);
$tikarr = FunNewRate($coinid,$moneyid);

//读取成交

	$dsql->SetQuery("SELECT id,btccount,uprice,dealtype,dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='1' ORDER BY dealtime DESC LIMIT 20");
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		if($last_rate=="") $last_rate=$rod->uprice/1;
		if($rod->dealtype==0) $orderT="<font color='#FF0000'>买入</font>";
		else  $orderT="<font color='#009900'>卖出</font>";
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
    $pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/default/tradetypes.htm");
    //$row['showmod'] = isset($row['showmod'])? $row['showmod'] : 0;
    
        $pv->Display();
        exit();


?>