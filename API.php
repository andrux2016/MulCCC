<?php
/*
 * 暂时不用此接口
@version        $Id: API_deal.php 1 8:38 2013年8月8日Z 
 */

/*		@session_id(preg_replace("#[^0-9a-zA-Z-]#", "0btc0",$_POST['userid']));
		@session_start();

		@session_id(preg_replace("#[^0-9a-zA-Z-]#", "0btc0",$_POST['userid']));*/
require_once(dirname(__FILE__).'/member/config.php');

$userip=$_SERVER["REMOTE_ADDR"];
$sessErr=$userid.'err';
$sessErrip=$userip.'err';
$ErrNums=GetPwErrNums($sessErr,"achieve");
$ErrNumsip=GetPwErrNums($sessErrip,"achieve");
//用户登录
    if($cfg_ml->M_ID=="" && $userid!="" && $password!="" )
    {
		$userid=safe_string($userid);
		$password=safe_string($password);
		
		
		$ruser = $dsql->GetOne("Select pwd,StrCode From #@__member where face = '".$userid."'");
		if(!is_array($ruser)){
			showJson("userid wrong!",'false');
			exit();
		}elseif($ruser['StrCode']==""){
			showJson("not open API!",'false');
			exit();
		}
		$userid = safe_string(mchStrCode($userid,'DECODE',$ruser['StrCode']));
		$pwd = safe_string(mchStrCode($password,'DECODE',$ruser['StrCode']));
        
        if(CheckUserID($userid,'',false)!='ok')
        {
            showJson('userid wrong！','false');
            exit();
        }
        if($pwd=='')
        {
            showJson('password wrong!','false');
            exit();
        }

		


		//if($_SESSION[$userip.'err']>5 || $_SESSION[$userid.'err']>5)
		if($ErrNums>5 || $ErrNumsip>5)
		{
			showJson("wrong more!","false");
			exit();
		}
		//if($_SESSION[$userid.'err']>7)
		if($ErrNums>7)
		{
			$rsup = $dsql->ExecuteNoneQuery("Update #@__member Set rank=0 where userid = '".$userid."' "); 
			$rsnew = $dsql->ExecuteNoneQuery("insert into #@__log(adminid,filename,method,query,cip,dtime) values('0','member/login.php','err-".$userid."-".$pwd."','login','$userip','".time()."')");
			showJson("wait 20 min!","false");
			exit();
		}
		
        //检查帐号
        $rs = $cfg_ml->CheckUser($userid,$pwd);  
        if($rs==0)
        {
            showJson('not useid','false');
            exit();
        }
        else if($rs==-1) {
            
			//$_SESSION[$userip.'err']++;
			//$_SESSION[$userid.'err']++;
			GetPwErrNums($sessErr,"add");
			$errtimes=6-GetPwErrNums($sessErrip,"add");
			//$errtimes=6-$_SESSION[$userip.'err'];

			showJson("pass worng,have ".$errtimes." change!","false");
            exit();
        }
        else if($rs==-2) {
            showJson('admin!','false');
            exit();
        }
		else if($rs==-3) {
            showJson('you had limit!','false');
            exit();
        }
        else
        {
            // 清除会员缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
			//unset($_SESSION[$userip.'err']);
			//unset($_SESSION[$userid.'err']);
            GetPwErrNums($sessErr,"unset");
			GetPwErrNums($sessErrip,"unset");
        }
		
    }





$market=preg_replace("#[^0-9-]#", "", $market)?preg_replace("#[^0-9-]#", "", $market):"1";//sz
//$symbol=preg_replace("#[^_A-Za-z-]#", "", $symbol)?preg_replace("#[^_A-Za-z-]#", "", $symbol):showJson("symbol wrong!",'false');//"BTC_CNY";
$type = preg_replace("#[^_A-Za-z-]#", "", $type)?preg_replace("#[^_A-Za-z-]#", "", $type):showJson("type wrong!",'false');//"";
/*
取消挂单
*/
if($type=="cancel"){
	if($cfg_ml->M_ID==""){
		showJson("not login!","false");
		exit();
	}
	
	$cancelrul = FunCancle($cfg_ml->M_ID,$tid,$market);
	
	
	$delArray=array(  
			'result' => $cancelrul, 
			'tid' => $tid
		);
		$json_string = json_encode($delArray);  
		echo $json_string;
}

$symbol=preg_replace("#[^_A-Za-z-]#", "", $symbol)?preg_replace("#[^_A-Za-z-]#", "", $symbol):"BTC_CNY";//;
$coinarr=explode('_',$symbol);
$cointype=preg_replace("#[^A-Za-z-]#", "", $coinarr[0]);
$moneytype=preg_replace("#[^A-Za-z-]#", "", $coinarr[1]);


$rcoin = $dsql->GetOne("Select * From #@__btctype where cointype = '".$coinarr[0]."' ");
if(is_array($rcoin)) $coinid=$rcoin['id'];
else exit();
$rmoney = $dsql->GetOne("Select * From #@__btctype where cointype = '".$coinarr[1]."' ");
if(is_array($rmoney)) $moneyid=$rmoney['id'];
else exit();


/*
最新价格
*/
if($type=="ticker"){
	$tikarr=FunNewRate($coinid,$moneyid,$market);
	if(is_array($tikarr)) $result="true";
	else $result="flase";
	$tickerArray=array(  
		'ticker' => $tikarr, 
	);
	$json_string = json_encode($tickerArray);  
	$json_string=str_replace("last_rate","last",$json_string);
	$json_string=str_replace("ask","sell",$json_string);
	$json_string=str_replace("bid","buy",$json_string);
	echo $json_string;
//echo "{\"result\":true,\"ticker\":{\"high\":554.9998,\"low\":542.69,\"vol\":980.953866,\"last_rate\":553,\"ask\":553.1,\"bid\":553}}";
}

/*
成交单据
*/
if($type=="ex_rec"){
	
	if($count>200) $count=200;
	$dealarr = FunExRec("",$coinid,$moneyid,$count,$market,$tid);
	/*if(is_array($dealarr)) $result="true";
	else $result="flase";
	$dealArray=array(  
		'result' => "$result", 
		'history' => $dealarr, 
	);*/
	foreach($dealarr as $value){
		if($value["order"]==0) $type="buy";
		else $type="sell";
		$dealArray[]=array(
			"date"=>$value["date"],
			"price"=>$value["rate"],
			"amount"=>$value["vol"],
			"tid"=>$value["ticket"],
			"type"=>$type
		);
	}
	
	$json_string = json_encode($dealArray);  
	echo $json_string;
}

/*
刷新时间标签

if($type=="time_mark"){

//$rline = $dsql->GetOne("SELECT E_time FROM #@__BTCtline WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND tspan='".$tspan."' ORDER BY E_time DESC");

$rord = $dsql->GetOne("SELECT ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' ORDER BY ordertime DESC");

//strtotime(date("Y-m-d H",time()).":".(floor(date("i",time())/5)*5+1).":00");

$timeArray=array(  
		'result' => "true", 
		'mark_ex' => strtotime(date("Y-m-d H",time()).":".(floor(date("i",time())/3)*3+1).":00"), 
		'mark_cc' => $rord['ordertime'], 
	);
	
	$json_string = json_encode($timeArray);  
	echo $json_string;
//echo "{\"result\":true,\"mark_ex\":1375168130,\"mark_cc\":1375168097}";
}
*/
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
	
	$json_string = json_encode($tlinearr);  
	echo $json_string;
	
}

/*
 * 读取挂单
 * 
*/
if($type=="rate_list"){
	$count=preg_replace("#[^0-9-]#", "", $count) ? preg_replace("#[^0-9-]#", "", $count) : 20;
	if($count>50) $count=50;
	$listArray = FunRateList($mid,$coinid,$moneyid,$cointype,$moneytype,$count,$market);
	
	foreach($listArray['rate_list']['bids'] as $value){
		$bidsarray[]=array($value['rate'],$value['vol']);
	}
	foreach($listArray['rate_list']['asks'] as $value){
		$asksarray[]=array($value['rate'],$value['vol']);
	}
	$showarray=array(
		'asks'=>$asksarray,
		'bids'=>$bidsarray
	);
	echo $json_string = json_encode($showarray);  

}

/*
 * 我的挂单
 * 
*/
if($type=="my_order"){
	if($cfg_ml->M_ID==""){
		showJson("not login!","false");
		exit();
	}
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
	if($cfg_ml->M_ID==""){
		showJson("not login!","false");
		exit();
	}
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
 * 交易
 * 
*/

if($type=="bid" || $type=="ask"){

	$rcoin = $dsql->GetOne("Select * From #@__btcconvert where cointype = '".$cointype."' And moneytype = '".$moneytype."' ");
	if(is_array($rcoin)){
		$bkage=$rcoin['fee']*$cfg_ml->M_FeePer;
		$coinid=$rcoin['coinid'];
		$moneyid=$rcoin['moneyid'];
	}else{
		showJson("coin wrong!",'false');
		exit();
	}

if(!isset($cfg_ml->M_ID)){
	showJson("not login!",'false');
	exit();
}else{
	$userid=$cfg_ml->M_ID;
}
 
$uprice=preg_match("/^[0-9.]+$/",$rate)?preg_replace("#[^.0-9-]#", "", $rate):showJson("price wrong!",'false');//"578.1";
$btccount=preg_match("/^[0-9.]+$/",$vol)?preg_replace("#[^.0-9-]#", "", $vol):showJson("vol wrong!",'false');//"1.4";
if(substr_count($uprice,".")>1) showJson("价格有误！",'false');
if(substr_count($btccount,".")>1) showJson("购买量有误！",'false');
if($type=="bid") $dealtype=0;
elseif ($type=="ask") $dealtype=1;
else $dealtype=0;
//产生订单号
$year_code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
$order_sn = $year_code[intval(date('Y'))-2013].strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('d',rand(0,99));






$applist=array(  
    'oid' => $order_sn, 
	'userid' => $cfg_ml->M_ID, 
	'btccount' => $btccount,
    'uprice' => $uprice,
	'bkage' => $bkage,
	'dealtype' => $dealtype,
	'coinid' => $coinid,
	'cointype' => $cointype,
	'moneyid' => $moneyid,
	'moneytype' => $moneytype,
	'market' => $market,
    );


$query = @mysql_query("lock tables ".$cfg_dbprefix."btcorder write,".$cfg_dbprefix."btcdeal write,".$cfg_dbprefix."btccoin write,".$cfg_dbprefix."btcapply write;") //锁
or die(sqlflase("lock")); 

if($type=="ask"){//卖出

	$tprice = $uprice*$btccount;//总价
	
	
	$rcoin = $dsql->GetOne("Select c_deposit,coinid From #@__btccoin where userid = ".$cfg_ml->M_ID." AND coinid='$coinid'");
	if(!is_array($rcoin)){
		showJson("没有余额！",'false');
		exit();
	}else{
		if ($rcoin['c_deposit'] < $btccount){//判断余额是否足够
			showJson("余额不足！",'false');
			exit();
		}
		//冻结挂单量
		$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$btccount,c_freeze=c_freeze+$btccount Where userid='".$cfg_ml->M_ID."' And coinid='$coinid' And c_deposit >= $btccount"); 
		if(!$upmoney){
			showJson("余额不足！",'false');
			exit();
		}
	}
}elseif ($type=="bid"){//买入

	//$btccount = $btccount/(1-$bkage);//实际提交的买入量，因为前台已经减去手续费
	$tprice = $uprice*$btccount;//总价
	
	$rmoy = $dsql->GetOne("Select c_deposit,coinid From ".$cfg_dbprefix."btccoin where userid = ".$cfg_ml->M_ID." AND coinid='$moneyid' ");
	if(!is_array($rmoy)){
		showJson("没有余额！",'false');
		exit();
	}else{
		if ($rmoy['c_deposit'] < $tprice){//判断余额是否足够
			showJson("余额不足！",'false');
			exit();
		}
		$feecount = feeFun($tprice,$applist['bkage']);
		$pricecount = $tprice+$feecount;
		//冻结买入人的费用 同时冻结手续费
		$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$tprice-$feecount,c_freeze=c_freeze+$tprice+$feecount Where userid='".$cfg_ml->M_ID."' And coinid='".$applist['moneyid']."' And c_deposit >= $pricecount"); 
		if(!$upmoney){
			showJson("余额不足！",'false');
			exit();
		}
	}
}
else{
	showJson("买卖不对！",'false');
	exit();
}


	//提交申请单
	$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btcapply(oid,btccount,uprice,tprice,userid,bkage,dealtype,coinid,moneyid,market,ordertime) values('$order_sn',$btccount,$uprice,$tprice,'".$cfg_ml->M_ID."',$bkage,$dealtype,$coinid,$moneyid,$market,'".time()."')");
	
if($dealtype==0){//买入**************************************************
		$bbkageall=$tprice*$bkage;//手续费
		$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."btcorder WHERE dealtype=1 AND coinid='".$applist['coinid']."' AND moneyid='".$applist['moneyid']."' AND market='".$applist['market']."' AND uprice <= ".$applist['uprice']." ORDER BY uprice,ordertime");//执行SQL语句
		
		//$appcount = $applist['btccount']*(1-$bkage);//申请量
		//$appcount =  $applist['btccount']-$applist['btccount']*(1/$applist['bkage']+1);//申请量
		$appcount = $applist['btccount'];//申请量
		$dsql->Execute();
		while($row = $dsql->GetObject()){

			if($row->btccount > $appcount){ //挂单量 > 申请量
				$dealcount = $appcount; //成交量=申请量
				$dealTprice = $dealcount * $row->uprice;//总价
			}else{		//申请量 <= 挂单量
				$dealcount = $row->btccount; //成交量
				$dealTprice = $dealcount * $row->uprice;//总价
			}
			//$bbkage = $dealTprice/(1/$applist['bkage']+1) ;//买单手续费x=200/(1/0.002+1)
			$bbkage = feeFun($dealTprice,$applist['bkage']);
			//$sbkage = $dealcount/(1/$row->bkage+1) ;//卖单手续费
			$sbkage = feeFun($dealTprice,$row->bkage);
			$buyoid = $applist['oid'];
			$selloid = $row->oid;
			$Buserid = $applist['userid'];
			$Suserid = $row->userid;
			//在卖单中减去成交量
			$dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btcorder Set btccount=btccount-$dealcount,tprice=tprice-$dealTprice Where oid='".$selloid."'"); 
			
			
			//记录成交订单情况
			$dealsql="Insert Into ".$cfg_dbprefix."btcdeal(btccount,uprice,tprice,buyoid,selloid,buserid,suserid,bbkage,sbkage,coinid,moneyid,market,dealtype,dealtime) Values($dealcount,".$row->uprice.",$dealTprice,'".$buyoid."','".$selloid."','$Buserid','".$Suserid."',$bbkage,$sbkage,'".$applist['coinid']."','".$applist['moneyid']."','".$applist['market']."','".$applist['dealtype']."','".time()."')";
			$dsql->ExecuteNoneQuery($dealsql);
			
			//给卖单人扣除btc 同时扣出手续费
			$upSfreeze = "Update #@__btccoin Set c_freeze=c_freeze-$dealcount,edittime='".time()."' Where userid='".$Suserid."' And coinid='".$applist['coinid']."'";
			$rs = $dsql->ExecuteNoneQuery($upSfreeze);

			//给卖单人充money
			$rSmoney = $dsql->GetOne("Select * From #@__btccoin Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'");
			if(is_array($rSmoney)){
				$upSmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$dealTprice-$sbkage,edittime='".time()."' Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'"); 
			}else{
				$rnew = $dsql->ExecuteNoneQuery("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Suserid."','".$applist['moneyid']."','".$applist['moneytype']."','".($dealTprice-$sbkage)."','0','".time()."')");
			}
			
			//扣除买入人的费用,同时扣出手续费
			//$upBmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$dealTprice-$bbkage Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'"); 
			$upBmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_freeze=c_freeze-$dealTprice-$bbkage Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'"); 
			//给买入人充btc
			$rBfreeze = $dsql->GetOne("Select * From #@__btccoin  Where userid='".$Buserid."' And coinid='".$applist['coinid']."'");
			if(is_array($rBfreeze)){
				$upBfreeze = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$dealcount,edittime='".time()."' Where userid='".$Buserid."' And coinid='".$applist['coinid']."'"); 
			}else{
				$rnew = $dsql->ExecuteNoneQuery("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Buserid."','".$applist['coinid']."','".$applist['cointype']."','".$dealcount."','0','".time()."')");
			}
			
			//记录为已成交
			$updealed = "Update #@__btcapply Set dealed=1 Where oid='".$deloid."'";
			$rs = $dsql->ExecuteNoneQuery($updealed); 
			
			if($row->btccount <= $appcount){ //挂单量 <= 申请量
				//删除
				$result = @mysql_query("Delete From ".$cfg_dbprefix."btcorder where oid='".$row->oid."'"); 
			}
			$appcount = $appcount - $dealcount; //剩余申请量
			if($appcount<=0) {
				break;//退出循环
			}
		}
		if($appcount > 0){  
			$gdtprice=$appcount*$applist['uprice'];
			$gdbkage=feeFun($gdtprice,$applist['bkage']);
			//$gdtprice/(1/$applist['bkage']+1);//买单手续费x=200/(1/0.002+1)
			//挂单
			$gd=$dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."btcorder(oid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,market,dealtype,ordertime) Values('".$applist['oid']."',$appcount,".$applist['uprice'].",'".($gdtprice)."','".$applist['userid']."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",".$applist['market'].",".$applist['dealtype'].",'".time()."')");	

			//冻结买入人的费用 同时冻结手续费
			//$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$gdtprice-$gdbkage,c_freeze=c_freeze+$gdtprice+$gdbkage Where userid='".$cfg_ml->M_ID."' And coinid='".$applist['moneyid']."'"); 

			if($gd==0) showJson("挂单错误，联系管理员",-1);
			//记录为已处理
			$upsolve = $dsql->ExecuteNoneQuery("Update #@__btcapply Set solve=1 Where oid='".$applist['oid']."'"); 
		}

}elseif($dealtype==1){//卖出*******************************************************************************************
	
	//读取符合要求的挂单
	$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."btcorder WHERE dealtype=0 AND coinid='".$applist['coinid']."' AND moneyid='".$applist['moneyid']."' AND market='".$applist['market']."' AND uprice >= ".$applist['uprice']." ORDER BY uprice DESC,ordertime");//执行SQL语句
		
		//$appcount = $applist['btccount']*(1-$bkage);//申请量
		$appcount = $applist['btccount'];//申请量
		
		$dsql->Execute();
		while($row = $dsql->GetObject()){
			
			if($row->btccount > $appcount){ //挂单量 > 申请量
				$dealcount = $appcount; //交易量=申请量
				$dealTprice = $dealcount * $row->uprice;//总价
			}else{		//申请量 <= 挂单量
				$dealcount = $row->btccount; //成交量
				$dealTprice = $dealcount * $row->uprice;//总价
			}
			
			//$bbkage = $dealTprice/(1/$row->bkage+1);//买单手续费
			$bbkage = feeFun($dealTprice,$row->bkage);
			//$sbkage = $$dealcount/(1/$applist['bkage']+1) ;//卖单手续费 x=200/(1/0.002+1)
			//$sbkage = $dealTprice/(1/$applist['bkage']+1) ;//卖单手续费 x=200/(1/0.002+1)
			$sbkage  = feeFun($dealTprice,$applist['bkage']);
			$buyoid = $row->oid;
			$selloid = $applist['oid'];
			$Buserid = $row->userid;
			$Suserid = $applist['userid'];
			//在买单中减去成交量
			$dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btcorder Set btccount=btccount-$dealcount,tprice=tprice-$dealTprice Where oid='".$buyoid."'"); 
			
			//记录成交订单情况
			$dealsql="Insert Into ".$cfg_dbprefix."btcdeal(btccount,uprice,tprice,buyoid,selloid,buserid,suserid,bbkage,sbkage,coinid,moneyid,market,dealtype,dealtime) Values($dealcount,".$row->uprice.",$dealTprice,'".$buyoid."','".$selloid."','$Buserid','".$Suserid."',$bbkage,$sbkage,'".$applist['coinid']."','".$applist['moneyid']."','".$applist['market']."','".$applist['dealtype']."','".time()."')";
			$dsql->ExecuteNoneQuery($dealsql);
			
			//给卖单人扣除btc
			//$upSfreeze = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$dealcount Where userid='".$Suserid."' And coinid='".$applist['coinid']."'"); 
			$upSfreeze = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_freeze=c_freeze-$dealcount Where userid='".$Suserid."' And coinid='".$applist['coinid']."'"); 
			
			//给卖单人充money 同时扣除手续费
			$rSmoney = $dsql->GetOne("Select * From #@__btccoin  Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'");
			if(is_array($rSmoney)){
				$upSmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$dealTprice-$sbkage,edittime='".time()."' Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'"); 
			}else{
				$rnew = $dsql->ExecuteNoneQuery("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Suserid."','".$applist['moneyid']."','".$applist['moneytype']."','".($dealTprice-$sbkage)."','0','".time()."')");
			}
			
			//扣除买入人的费 用同时扣出手续费
			$upBmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_freeze=c_freeze-$dealTprice-$bbkage Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'"); 
			//给买入人充btc
			$rBfreeze = $dsql->GetOne("Select * From #@__btccoin  Where userid='".$Buserid."' And coinid='".$applist['coinid']."'");
			if(is_array($rBfreeze)){
				$upBfreeze = $dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btccoin Set c_deposit=c_deposit+$dealcount,edittime='".time()."' Where userid='".$Buserid."' And coinid='".$applist['coinid']."'"); 
			}else{
				$rnew = $dsql->ExecuteNoneQuery("Insert Into `".$cfg_dbprefix."btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Buserid."','".$applist['coinid']."','".$applist['cointype']."','".$dealcount."','0','".time()."')");
			}
			//添加积分
			/*if($moneytype=="CNY"){
				$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealTprice Where mid='".$Buserid."'"); 
				$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealTprice Where mid='".$Suserid."'"); 
			}else{
				$rcny = $dsql->GetOne("SELECT id,uprice FROM #@__btcdeal WHERE coinid='$coinid' AND moneyid='1' ORDER BY id DESC");
				if(is_array($rcny)){
					$dealScores=$dealcount*$rcny['uprice'];
					$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$Buserid."'"); 
					$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$Suserid."'"); 
				}else{
					$rbtc = $dsql->GetOne("SELECT id,uprice FROM #@__btcdeal WHERE coinid='$coinid' AND moneyid='2' ORDER BY id DESC");
					$rcnybtc = $dsql->GetOne("SELECT id,uprice FROM #@__btcdeal WHERE coinid='2' AND moneyid='1' ORDER BY id DESC");
					if(is_array($rjs)){
						$dealScores=$dealcount*$rbtc['uprice']*$rcnybtc['uprice'];

						$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$Buserid."'"); 
						$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$Suserid."'"); 
					}
				}
			}*/
			
			//记录为已成交
			$updealed = $dsql->ExecuteNoneQuery("Update #@__btcapply Set dealed=1 Where oid='".$deloid."'"); 
			
			if($row->btccount <= $appcount){ //挂单量 <= 申请量
				//删除
				$result = @mysql_query("Delete From ".$cfg_dbprefix."btcorder where oid='".$row->oid."'"); 
			}
			
			$appcount = $appcount - $dealcount; //剩余申请量
			if($appcount<=0) {
				break;//退出循环
			}
		}
		if($appcount > 0){  
			//$gdsbkage=$appcount/(1/$applist['bkage']+1);//挂单手续费同时冻结
			/*echo $appcount+$gdsbkage;
			exit();*/
			//挂单
			$dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."btcorder(oid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,market,dealtype,ordertime) Values('".$applist['oid']."',$appcount,".$applist['uprice'].",'".($applist['uprice']*$appcount)."','".$applist['userid']."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",".$applist['market'].",".$applist['dealtype'].",'".time()."')");
			
			//冻结挂单量
			//$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$appcount,c_freeze=c_freeze+$appcount Where userid='".$cfg_ml->M_ID."' And coinid='$coinid'"); 
			//记录为已处理
			$upsolve = "Update #@__btcapply Set solve=1 Where oid='".$applist['oid']."'";
			$rs = $dsql->ExecuteNoneQuery($upsolve); 
		}
}


$query = @mysql_query("unlock tables;") //解锁
or die(sqlflase("unlock")); 
	
	$dsql->SetQuery("SELECT oid,btccount,uprice,bkage,dealtype FROM #@__btcapply WHERE oid='$order_sn'");
	$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		//echo "申请：".$row->oid;
		$type = $row->dealtype == 0 ? "bid": "ask";
		$apArr[]=array(  
		'id' => $row->oid,  
		'vol' => $row->btccount, 
		'rate' => $row->uprice,  
		'fee' => $row->bkage,  
		'type' => $type,  
		);
	}
	$dsql->SetQuery("SELECT oid,btccount,uprice,bkage,dealtype FROM #@__btcorder WHERE oid='$order_sn'");
	$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		//echo "挂单：".$row->oid;
		$type = $row->dealtype == 0 ? "bid": "ask";
		$otderArr[]=array(  
		'id' => $row->oid,  
		'vol' => $row->btccount/1, 
		'rate' => $row->uprice/1,  
		'fee' => $row->bkage/1,  
		'type' => $type,  
		);
	}
	$dsql->SetQuery("SELECT id,btccount,uprice,bbkage,sbkage,dealtype FROM #@__btcdeal WHERE buyoid='$order_sn' or selloid='$order_sn' ORDER BY id");
	$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		//echo "成交：".$row->id;
		$type = $row->dealtype == 0 ? "bid": "ask";
		$fee = $row->dealtype == 0 ? $row->bbkage: $row->sbkage;
		$dealArr[]=array(  
		'id' => $row->id,  
		'vol' => $row->btccount/1, 
		'rate' => $row->uprice/1,  
		'fee' => $fee,  
		'type' => $type,  
		);
	}


   $dealArr=array(  
	'result' => 'true', 
	/*'showMsg' => '购买成功！', */
    'pending' => $otderArr,  
	'records' => $dealArr,  
    );
	$json_string = json_encode($dealArr);  
	echo $json_string;
}


/**
 *  提示信息
 */
function showJson($msg,$ruslt){
		$msgArray=array(  
		'showMsg' => $msg, 
		'ruslt' => $ruslt,
		);
		//echo $msg;
		$json_string = urldecode(json_encode($msgArray));  
		echo $json_string;
		exit();
}



/**
 * 生成加密码
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作
 * @return    string
 */
function rechargeCode($nums)
{
    $numLen=10;
	$pwdLen=10;
	$c=$nums;//生成1组卡号密码
	$sTempArr=range(0,9);
	$sNumArr=array_merge($sTempArr,range('A','Z'));
	$sPwdArr=array_merge($sTempArr,range('A','Z'));
	
	$cards=array();
	for($x=0;$x< $c;$x++){
	  $tempNumStr=array();
	  for($i=0;$i< $numLen;$i++){
		$tempNumStr[]=array_rand($sNumArr);
	  }
	  $tempPwdStr=array();
	  for($i=0;$i< $pwdLen;$i++){
		$tempPwdStr[]=$sPwdArr[array_rand($sPwdArr)];  
		$tempPwdStr2[]=$sPwdArr[array_rand($sPwdArr)];   
	  }
	  $cards[$x]['no']=implode('',$tempPwdStr2);
	  $cards[$x]['pwd']=implode('',$tempPwdStr);
	}
	array_unique($cards);
	//print_r($cards);
    return $cards;
}

/**
 *  加密函数
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作
 * @return    string
 */
function mchStrCode($string,$action='ENCODE',$key)
{
    
	//$key    = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
	//$key    = "e87drga49ae10f3c87";
    $string    = $action == 'ENCODE' ? $string : base64_decode($string);
    $len    = strlen($key);
    $code    = '';
    for($i=0; $i<strlen($string); $i++)
    {
        $k        = $i % $len;
        $code  .= $string[$i] ^ $key[$k];
    }
    $code = $action == 'DECODE' ? $code : base64_encode($code);
    return $code;
}