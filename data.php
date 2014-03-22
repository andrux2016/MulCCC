<?php 
require_once(dirname(__FILE__)."/member/config.php");
AjaxHead();
$market=preg_replace("#[^0-9-]#", "", $market)?preg_replace("#[^0-9-]#", "", $market):"1";//sz
$type=preg_replace("#[^_A-Za-z-]#", "", $type)?preg_replace("#[^_A-Za-z-]#", "", $type):exit();

/*
取消挂单
*/
if($type=="cancel"){
	
	$tid=preg_replace("#[^_0-9A-Za-z-]#", "", $tid)?preg_replace("#[^_0-9A-Za-z-]#", "", $tid):exit();
	$cancelrul = FunCancle($cfg_ml->M_ID,$tid,$market);
	
	if(empty($op) && $cancelrul == "true"){
		ShowMsg("成功撤单！","member/btc_orderlist.php");
		exit();
	}elseif(empty($op) &&  $cancelrul != "true"){
		ShowMsg("撤单不成功！","-1");
		exit();
	}
	
	$delArray=array(  
			'result' => $cancelrul, 
		);
		$json_string = json_encode($delArray);  
		echo $json_string;
}

$symbol=preg_replace("#[^_A-Za-z-]#", "", $symbol)?preg_replace("#[^_A-Za-z-]#", "", $symbol):exit();//"BTC_CNY";showJson("类型有误！",'false');

$coinarr=explode('_',$symbol);
	$rcoin = $dsql->GetOne("Select * From #@__btctype where cointype = '".$coinarr[0]."' ");
	if(is_array($rcoin)) $coinid=$rcoin['id'];
	else exit();
	$rmoney = $dsql->GetOne("Select * From #@__btctype where cointype = '".$coinarr[1]."' ");
	if(is_array($rmoney)) $moneyid=$rmoney['id'];
	else exit();

//echo $symbol;
//exit();
/*$coinid=$coinid?$coinid:"2";//BTC
$moneyid=$moneyid?$moneyid:"1";//CNY*/



/*
最新价格
*/
if($type=="ticker"){
	$tikarr=FunNewRate($coinid,$moneyid,$market);
	if(is_array($tikarr)) $result="true";
	else $result="flase";
	$tickerArray=array(  
		'result' => $result, 
		'ticker' => $tikarr, 
	);
	$json_string = json_encode($tickerArray);  
	echo $json_string;
//echo "{\"result\":true,\"ticker\":{\"high\":554.9998,\"low\":542.69,\"vol\":980.953866,\"last_rate\":553,\"ask\":553.1,\"bid\":553}}";
}

/*
成交单据
*/
if($type=="ex_rec"){
	$count=preg_replace("#[^0-9-]#", "", $count)?preg_replace("#[^0-9-]#", "", $count):20;
	if($count>200) $count=200;
	$dealarr = FunExRec("",$coinid,$moneyid,$num,$market);
	if(is_array($dealarr)) $result="true";
	else $result="flase";
	$dealArray=array(  
		'result' => "$result", 
		'history' => $dealarr, 
	);
	
	$json_string = json_encode($dealArray);  
	echo $json_string;
}

/*
刷新时间标签
*/
if($type=="time_mark"){

//$rline = $dsql->GetOne("SELECT E_time FROM #@__BTCtline WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND tspan='".$tspan."' ORDER BY E_time DESC");

$rord = $dsql->GetOne("SELECT ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' ORDER BY ordertime DESC");
$rdeal = $dsql->GetOne("SELECT dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' ORDER BY id DESC");
$cctime=$rdeal['dealtime']>$rord['ordertime']?$rord['dealtime']:$rord['ordertime'];

//strtotime(date("Y-m-d H",time()).":".(floor(date("i",time())/5)*5+1).":00");

$timeArray=array(  
		'result' => "true", 
		'mark_ex' => strtotime(date("Y-m-d H",time()).":".(floor(date("i",time())/3)*3+1).":00"), 
		'mark_cc' =>$cctime, 
	);
	
	$json_string = json_encode($timeArray);  
	echo $json_string;
//echo "{\"result\":true,\"mark_ex\":1375168130,\"mark_cc\":1375168097}";
}

/*
走势图数据
*/
if($type=="tline"){

	$tspan = $tspan?$tspan:"300";
	$count = $count?$count:100;
	if($count>300) $count=300;

	$tlinearr = FunTline($coinid,$moneyid,$tspan,$count,$market);
	if(is_array($tlinearr)) $result="true";
	else $result="flase";
	
	$tlineArray=array(  
		'result' => $result, 
		'tline' => $tlinearr, 
		'tspan' => $tspan,
		'count' => $count
	);
	
	$json_string = json_encode($tlineArray);  
	echo $json_string;
	
}

/*
 * 我的挂单
 * 
*/
if($type=="my_order"){
	$orderarr = FunMyOrder($cfg_ml->M_ID,$coinid,$moneyid,$count,$market);
	if(is_array($orderarr)) $result="true";
	else $result="flase";
	$listarr=array(
		'result'=>$result,
		'order'=>$orderarr,
	);
	echo $listjson = json_encode($listarr);  
}


/*
 * 我的成交
 * 
*/
if($type=="my_deal"){
	$dealarr = FunExRec($cfg_ml->M_ID,$coinid,$moneyid,$count,$market);
	if(is_array($dealarr)) $result="true";
	else $result="flase";
	$listarr=array(
		'result'=>$result,
		'order'=>$dealarr,
	);
	echo $listjson = json_encode($listarr);  
}

/*
 * 读取挂单
 * 
*/
if($type=="rate_list"){
	$count=preg_replace("#[^0-9-]#", "", $count) ? preg_replace("#[^0-9-]#", "", $count) : 20;
	if($count>200) $count=200;
	$listArray = FunRateList("",$coinid,$moneyid,$cointype,$moneytype,$count,$market);
	/*$ratearr = FunExRec($cfg_ml->M_ID,$coinid,$moneyid,$cointype,$moneytype,$count,$market);
	$listArray=array(  
		'result' => "true", 
		'symbol' => $symbol, 
		'rate_list' => $ratearr, 
	);*/
	$json_string = json_encode($listArray);  
	echo $json_string;
	
}
?>

