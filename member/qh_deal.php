<?php
/*
@version        $Id: qh_deal.php 1 8:38 2013年8月8日Z 
 */

require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);


$qhmarket=preg_replace("#[^0-9-]#", "", $qhmarket)?preg_replace("#[^0-9-]#", "", $qhmarket):"1";//sz
$symbol=preg_replace("#[^_A-Za-z-]#", "", $symbol)?preg_replace("#[^_A-Za-z-]#", "", $symbol):showJson("类型有误！",'false');//"BTC_CNY";
$coinarr=explode('_',$symbol);
$cointype=preg_replace("#[^A-Za-z-]#", "", $coinarr[0]);
$moneytype=preg_replace("#[^A-Za-z-]#", "", $coinarr[1]);
$hyid=preg_replace("#[^0-9-]#", "", $hyid)?preg_replace("#[^0-9-]#", "", $hyid):"1";//
if($type=="ask") $dealtype=1;
elseif ($type=="bid") $dealtype=0;
else $dealtype=0;
$rcoin = $dsql->GetOne("Select * From #@__qhheyue where id = '".$hyid."'  ");
	if(is_array($rcoin)){
		$bkage=$cfg_ml->M_FeePer ? ($rcoin['fee']*$cfg_ml->M_FeePer) : $rcoin['fee'];
		$coinid=$rcoin['coinid'];
		$moneyid=$rcoin['moneyid'];
		$mdggper=$rcoin['mdggper'];
		$mkggper=$rcoin['mkggper'];
	}else{
		showJson("币种有误！",'false');
		exit();
	}

if(!isset($cfg_ml->M_ID)){
	showJson("用户有误！",'false');
	exit();
}else{
	$userid=$cfg_ml->M_ID;
}
$kpsign=preg_match("/^[0-9.]+$/",$kpsign)?preg_replace("#[^.0-9-]#", "", $kpsign):showJson("开平有误！",'false');//"0";
 //$mkggper=preg_match("/^[0-9.]+$/",$mkggper)?preg_replace("#[^.0-9-]#", "", $mkggper):showJson("比例有误！",'false');//"1.25";
 //$mdggper=preg_match("/^[0-9.]+$/",$mdggper)?preg_replace("#[^.0-9-]#", "", $mdggper):showJson("比例有误！",'false');//"1.25";
$uprice=preg_match("/^[0-9.]+$/",$rate)?preg_replace("#[^.0-9-]#", "", $rate):showJson("价格有误！",'false');//"1500";
$btccount=preg_match("/^[0-9.]+$/",$vol)?preg_replace("#[^.0-9-]#", "", $vol):showJson("购买量有误！",'false');//"1";
if(substr_count($uprice,".")>1) showJson("价格有误！",'false');
if(substr_count($btccount,".")>1) showJson("购买量有误！",'false');

//产生订单号
$year_code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
$order_sn = $year_code[intval(date('Y'))-2013].strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('d',rand(0,99));


$applist=array(  
    'oid' => $order_sn, 
	'hyid' => $hyid, 
	'userid' => $cfg_ml->M_ID, 
	'btccount' => $btccount,
    'uprice' => $uprice,
	'bkage' => $bkage,
	'dealtype' => $dealtype,
	'coinid' => $coinid,
	'cointype' => $cointype,
	'moneyid' => $moneyid,
	'moneytype' => $moneytype,
	'kpsign' => $kpsign,
	'mdggper' => $mdggper,
	'mkggper' => $mkggper,
	'qhmarket' => $qhmarket,
    );


$query = @mysql_query("lock tables ".$cfg_dbprefix."qhorder write,".$cfg_dbprefix."qhpossess write,".$cfg_dbprefix."qhdeal write,".$cfg_dbprefix."btccoin write,".$cfg_dbprefix."qhapply write;") //锁
or die(sqlflase("lock")); 

if($applist['kpsign']==0){ //开仓判断余额并扣除费用。

	/*if($type=="ask"){//卖出
		$dealtype=1;
	}elseif ($type=="bid"){
		$dealtype=0;
	}*/
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
	/*}elseif ($type=="bid"){//买入
		$dealtype=0;
		//$btccount = $btccount/(1-$bkage);//实际提交的买入量，因为前台已经减去手续费
		//$tprice = $uprice*$btccount;//总价
		
		$rmoy = $dsql->GetOne("Select c_deposit,coinid From ".$cfg_dbprefix."btccoin where userid = ".$cfg_ml->M_ID." AND coinid='$coinid' ");
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
			$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$tprice-$feecount,c_freeze=c_freeze+$tprice+$feecount Where userid='".$cfg_ml->M_ID."' And coinid='".$applist['coinid']."' And c_deposit >= $pricecount"); 
			if(!$upmoney){
				showJson("余额不足！",'false');
				exit();
			}
			
			
		}
	}
	else{
		showJson("买卖不对！",'false');
		exit();
	}*/
}

	//提交申请单
	$rsnew = $dsql->ExecuteNoneQuery("insert into #@__qhapply(oid,btccount,uprice,tprice,userid,bkage,dealtype,coinid,moneyid,ggper,kpsign,qhmarket,ordertime) values('$order_sn',$btccount,$uprice,$tprice,'".$cfg_ml->M_ID."',$bkage,$dealtype,$coinid,$moneyid,$ggper,$kpsign,$qhmarket,'".time()."')");
	
if($dealtype==0){//买入**************************************************
		$bbkageall=$tprice*$bkage;//手续费
		$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."qhorder WHERE dealtype=1 AND coinid='".$applist['coinid']."' AND moneyid='".$applist['moneyid']."' AND qhmarket='".$applist['qhmarket']."' AND uprice <= ".$applist['uprice']." ORDER BY uprice,ordertime");//执行SQL语句
		
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
			$kcaccout=$dealcount/$mdggper;//保证金
			$bbkage = feeFun($dealcount,$applist['bkage']);
			//$sbkage = $dealcount/(1/$row->bkage+1) ;//卖单手续费
			$sbkage = feeFun($dealcount,$row->bkage);
			$buyoid = $applist['oid'];
			$selloid = $row->oid;
			$Buserid = $applist['userid'];
			$Suserid = $row->userid;
			
			
			
			//记录成交订单情况
			$dealsql = "Insert Into ".$cfg_dbprefix."qhdeal(btccount,uprice,tprice,buyoid,selloid,buserid,suserid,bbkage,sbkage,coinid,moneyid,kpsign,hyid,qhmarket,dealtype,dealtime) Values($dealcount,".$row->uprice.",$dealTprice,'".$buyoid."','".$selloid."','$Buserid','".$Suserid."',$bbkage,$sbkage,'".$applist['coinid']."','".$applist['moneyid']."','".$applist['kpsign']."','".$applist['hyid']."','".$applist['qhmarket']."','".$applist['dealtype']."','".time()."')";
			$dsql->ExecuteNoneQuery($dealsql);

			//在卖单中减去成交量
			$dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."qhorder Set btccount=btccount-$dealcount,tprice=tprice-$dealTprice Where oid='".$selloid."'"); 
			
			if($applist['kpsign']==0){   //开仓
				//扣除买入人的保证金
				
				$upBmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_freeze=c_freeze-$kcaccout Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'"); 
				//添加持仓量
				$rqh = $dsql->GetOne("Select * From #@__qhpossess Where userid='".$Buserid."' And hyid='".$applist['hyid']."' And dealtype='0'");
				if(is_array($rqh)){
					$possprice=($applist['uprice']*$dealcount+$rqh['uprice']*$rqh['btccount'])/($rqh['btccount']+$dealcount);
					$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount+$dealcount,uprice=$possprice,posstime='".time()."' Where userid='".$Buserid."' And hyid='".$applist['hyid']."' And dealtype='0'"); 
				}else{
					$rkq = $dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."qhpossess(oid,hyid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,ggper,qhmarket,dealtype,posstime) Values('".$applist['oid']."','".$applist['hyid']."',$dealcount,".$applist['uprice'].",'".($dealcount*$applist['uprice'])."','".$Buserid."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",'".$applist['mkggper']."',".$applist['qhmarket'].",0,'".time()."')");	
					$rkq = $dsql->ExecuteNoneQuery($kdsql);	
				}
				
			}elseif($applist['kpsign']==1){  //平仓
				//给买入人平仓充值,同时扣出手续费
				$rpc = $dsql->GetOne("Select * From #@__btccoin  Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'");
				if(is_array($rpc)){
					//保证金计算返回金额
					$pcBaccout=$rpc['uprice']/$applist['uprice']*$dealcount;
					$upBfreeze = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$pcBaccout-$bbkage,edittime='".time()."' Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'"); 
				}else{
					$rnew = $dsql->ExecuteNoneQuery("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Buserid."','".$applist['moneyid']."','".$applist['moneytype']."','".($dealcount-$bbkage)."','0','".time()."')");
				}
				//扣除持仓量
				$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount-$dealcount Where userid='".$Buserid."' And hyid='".$applist['hyid']."' And dealtype='0'"); 
			}
			if($row->kpsign==0){   //开仓
				//给卖单人扣除费用
				$upSfreeze = "Update #@__btccoin Set c_freeze=c_freeze-$kcaccout,edittime='".time()."' Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'";
				//添加持仓量
				$rqh = $dsql->GetOne("Select * From #@__qhpossess Where userid='".$Suserid."' And hyid='".$applist['hyid']."' And dealtype='1'" );
				if(is_array($rqh)){
					$possprice=($applist['uprice']*$dealcount+$rqh['uprice']*$rqh['btccount'])/($rqh['btccount']+$dealcount);
					$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount+$dealcount,uprice=$possprice,posstime='".time()."' Where userid='".$Suserid."' And hyid='".$applist['hyid']."' And dealtype='1'"); 
				}else{
					$rkq = $dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."qhpossess(oid,hyid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,ggper,qhmarket,dealtype,posstime) Values('".$applist['oid']."','".$applist['hyid']."',$dealcount,".$applist['uprice'].",'".($dealcount*$applist['uprice'])."','".$Suserid."',".$row->bkage.",".$applist['coinid'].",".$applist['moneyid'].",'".$applist['mkggper']."',".$applist['qhmarket'].",1,'".time()."')");	
				}
			}elseif($row->kpsign==1){  //平仓
				//平仓充值 同时扣出手续费
				//给卖单人充money
				$rSpc = $dsql->GetOne("Select * From #@__btccoin Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'");
				if(is_array($rSpc)){
					//保证金计算返回金额
					$pcSaccout=$rSpc['uprice']/$applist['uprice']*$dealcount;
					$upSmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$pcSaccout-$sbkage,edittime='".time()."' Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'"); 
				}else{
					$rnew = $dsql->ExecuteNoneQuery("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Suserid."','".$applist['moneyid']."','".$applist['moneytype']."','".($dealcount-$sbkage)."','0','".time()."')");
				}
				//扣除持仓量
				$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount-$dealcount Where userid='".$Suserid."' And hyid='".$applist['hyid']."' And dealtype='1'"); 
			}
			
			//记录为已成交
			$updealed = "Update #@__qhapply Set dealed=1 Where oid='".$deloid."'";
			$rs = $dsql->ExecuteNoneQuery($updealed); 
			
			if($row->btccount <= $appcount){ //挂单量 <= 申请量
				//删除
				$result = @mysql_query("Delete From ".$cfg_dbprefix."qhorder where oid='".$row->oid."'"); 
			}
			$appcount = $appcount - $dealcount; //剩余申请量
			if($appcount<=0) {
				break;//退出循环
			}
		}
		if($appcount > 0){  
			$gdtprice=$appcount*$applist['uprice'];
			$gdbkage=$gdtprice/(1/$applist['bkage']+1);//买单手续费x=200/(1/0.002+1)
			//挂单
			$gd=$dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."qhorder(oid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,ggper,kpsign,hyid,qhmarket,dealtype,ordertime) Values('".$applist['oid']."',$appcount,".$applist['uprice'].",'".($gdtprice)."','".$applist['userid']."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",'".$applist['mdggper']."','".$applist['kpsign']."',".$applist['hyid'].",".$applist['qhmarket'].",".$applist['dealtype'].",'".time()."')");	

			//冻结买入人的费用 同时冻结手续费
			//$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$gdtprice-$gdbkage,c_freeze=c_freeze+$gdtprice+$gdbkage Where userid='".$cfg_ml->M_ID."' And coinid='".$applist['moneyid']."'"); 
			//冻结买入人的费用
			//$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$appcount,c_freeze=c_freeze+$appcount Where userid='".$cfg_ml->M_ID."' And coinid='".$applist['moneyid']."'"); 

			if($gd==0) showJson("挂单错误，联系管理员",-1);
			//记录为已处理
			$upsolve = $dsql->ExecuteNoneQuery("Update #@__qhapply Set solve=1 Where oid='".$applist['oid']."'"); 
		}

}elseif($dealtype==1){//卖出*******************************************************************************************
	
	//读取符合要求的挂单
	$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."qhorder WHERE dealtype=0 AND coinid='".$applist['coinid']."' AND moneyid='".$applist['moneyid']."' AND qhmarket='".$applist['qhmarket']."' AND uprice >= ".$applist['uprice']." ORDER BY uprice DESC,ordertime");//执行SQL语句
		
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
			$bbkage = feeFun($dealcount,$row->bkage);
			
			//$sbkage = $$dealcount/(1/$applist['bkage']+1) ;//卖单手续费 x=200/(1/0.002+1)
			//$sbkage = $dealTprice/(1/$applist['bkage']+1) ;//卖单手续费 x=200/(1/0.002+1)
			$sbkage  = feeFun($dealcount,$applist['bkage']);
			
			$kcaccout=$dealcount/$mdggper;//保证金
			$buyoid = $row->oid;
			$selloid = $applist['oid'];
			$Buserid = $row->userid;
			$Suserid = $applist['userid'];
			//在买单中减去成交量
			$dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."qhorder Set btccount=btccount-$dealcount,tprice=tprice-$dealTprice Where oid='".$buyoid."'"); 
			
			//记录成交订单情况
			$dealsql="Insert Into ".$cfg_dbprefix."qhdeal(btccount,uprice,tprice,buyoid,selloid,buserid,suserid,bbkage,sbkage,coinid,moneyid,kpsign,qhmarket,dealtype,dealtime) Values($dealcount,".$row->uprice.",$dealTprice,'".$buyoid."','".$selloid."','$Buserid','".$Suserid."',$bbkage,$sbkage,'".$applist['coinid']."','".$applist['moneyid']."',$kpsign,'".$applist['qhmarket']."','".$applist['dealtype']."','".time()."')";
			$dsql->ExecuteNoneQuery($dealsql);
			
			
			if($applist['kpsign']==0){   //开仓
				//扣除买入人的费用
				$upBmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_freeze=c_freeze-$kcaccout Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'"); 
				//添加持仓量
				$rqh = $dsql->GetOne("Select * From #@__qhpossess Where userid='".$Buserid."' And hyid='".$applist['hyid']."' And dealtype='0'");
				if(is_array($rSmoney)){
					$possprice=($applist['uprice']*$dealcount+$rqh['uprice']*$rqh['btccount'])/($rqh['btccount']+$dealcount);
					$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount+$dealcount,uprice=$possprice,posstime='".time()."' Where userid='".$Buserid."' And hyid='".$applist['hyid']."' And dealtype='0'"); 
				}else{
					$rkq = $dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."qhpossess(oid,hyid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,ggper,qhmarket,dealtype,posstime) Values('".$applist['oid']."','".$applist['hyid']."',$dealcount,".$applist['uprice'].",'".($dealcount*$applist['uprice'])."','".$Buserid."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",'".$applist['mkggper']."',".$applist['qhmarket'].",0,'".time()."')");		
				}
			}elseif($applist['kpsign']==1){  //平仓
				//给买入人平仓充值,同时扣出手续费
				$rpc = $dsql->GetOne("Select * From #@__btccoin  Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'");
				if(is_array($rpc)){
					//保证金计算返回金额
					$pcBaccout=($rpc['uprice']/$row->uprice*$dealcount);
					$upBfreeze = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$pcBaccout-$bbkage,edittime='".time()."' Where userid='".$Buserid."' And coinid='".$applist['moneyid']."'"); 
				}else{
					$rnew = $dsql->ExecuteNoneQuery("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Buserid."','".$applist['moneyid']."','".$applist['moneytype']."','".($dealcount-$bbkage)."','0','".time()."')");
				}
				//扣除持仓量
				$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount-$dealcount Where userid='".$Buserid."' And hyid='".$applist['hyid']."' And dealtype='0'"); 
			}
			if($row->kpsign==0){   //开仓
				//给卖单人扣除费用
				$upSfreeze = "Update #@__btccoin Set c_freeze=c_freeze-$kcaccout,edittime='".time()."' Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'";
				//添加持仓量
				$rqh = $dsql->GetOne("Select * From #@__qhpossess Where userid='".$Suserid."' And hyid='".$applist['hyid']."' And dealtype='1'");
				if(is_array($rSmoney)){
					$possprice=($applist['uprice']*$dealcount+$rqh['uprice']*$rqh['btccount'])/($rqh['btccount']+$dealcount);
					$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount+$dealcount,uprice=$possprice,posstime='".time()."' Where userid='".$Suserid."' And hyid='".$applist['hyid']."' And dealtype='1'"); 
				}else{
					$rkq = $dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."qhpossess(oid,hyid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,ggper,qhmarket,dealtype,posstime) Values('".$applist['oid']."','".$applist['hyid']."',$dealcount,".$applist['uprice'].",'".($dealcount*$applist['uprice'])."','".$Suserid."',".$row->bkage.",".$applist['coinid'].",".$applist['moneyid'].",'".$applist['mkggper']."',".$applist['qhmarket'].",1,'".time()."')");	
				}
			}elseif($row->kpsign==1){  //平仓
				//平仓充值 同时扣出手续费
				//给卖单人充money
				$rSpc = $dsql->GetOne("Select * From #@__btccoin Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'");
				if(is_array($rSpc)){
					//保证金计算返回金额
					$pcSaccout=($rSpc['uprice']/$row->uprice*$dealcount);
					$upSmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$pcSaccout-$sbkage,edittime='".time()."' Where userid='".$Suserid."' And coinid='".$applist['moneyid']."'"); 
				}else{
					$rnew = $dsql->ExecuteNoneQuery("Insert Into `#@__btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$Suserid."','".$applist['moneyid']."','".$applist['moneytype']."','".($dealcount-$sbkage)."','0','".time()."')");
				}
				//扣除持仓量
				$upqh = $dsql->ExecuteNoneQuery("Update #@__qhpossess Set btccount=btccount-$dealcount Where userid='".$Suserid."' And hyid='".$applist['hyid']."' And dealtype='1'"); 
			}
			
			//添加积分
			
			
			//记录为已成交
			$updealed = $dsql->ExecuteNoneQuery("Update #@__qhapply Set dealed=1 Where oid='".$deloid."'"); 
			
			if($row->btccount <= $appcount){ //挂单量 <= 申请量
				//删除
				$result = @mysql_query("Delete From ".$cfg_dbprefix."qhorder where oid='".$row->oid."'"); 
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
			$dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."qhorder(oid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,ggper,kpsign,qhmarket,dealtype,ordertime) Values('".$applist['oid']."',$appcount,".$applist['uprice'].",'".($applist['uprice']*$appcount)."','".$applist['userid']."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",'".$applist['mkggper']."','".$applist['kpsign']."',".$applist['qhmarket'].",".$applist['dealtype'].",'".time()."')");
			
			//冻结挂单量
			$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit-$appcount,c_freeze=c_freeze+$appcount Where userid='".$cfg_ml->M_ID."' And coinid='$coinid'"); 
			//记录为已处理
			$upsolve = "Update #@__qhapply Set solve=1 Where oid='".$applist['oid']."'";
			$rs = $dsql->ExecuteNoneQuery($upsolve); 
		}
}


$query = @mysql_query("unlock tables;") //解锁
or die(sqlflase("unlock")); 

$dsql->SetQuery("SELECT oid,btccount,uprice,bkage,dealtype FROM #@__qhapply WHERE oid='$order_sn'");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	//echo "申请：".$row->oid;
	$type = $row->dealtype == 1 ? "bid": "ask";
	$apArr[]=array(  
    'id' => $row->oid,  
	'vol' => $row->btccount, 
    'rate' => $row->uprice,  
	'fee' => $row->btccount/(1/$row->bkage+1),  
	'type' => $type,  
    );
}
$dsql->SetQuery("SELECT oid,btccount,uprice,bkage,dealtype FROM #@__qhorder WHERE oid='$order_sn'");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	//echo "挂单：".$row->oid;
	$type = $row->dealtype == 1 ? "bid": "ask";
	$otderArr[]=array(  
    'id' => $row->oid,  
	'vol' => $row->btccount/1, 
    'rate' => $row->uprice/1,  
	'fee' => $row->bkage/1,  
	'type' => $type,  
    );
}
$dsql->SetQuery("SELECT id,btccount,uprice,bbkage,sbkage,dealtype,buserid,suserid,moneyid FROM #@__qhdeal WHERE buyoid='$order_sn' or selloid='$order_sn' ORDER BY id");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	//echo "成交：".$row->id;
	deductFun($row->id,$row->buserid,$row->bbkage,0,$row->moneyid);
	deductFun($row->id,$row->suserid,$row->sbkage,1,$row->moneyid);
	$type = $row->dealtype == 0 ? "ask": "bid";
	$fee = $row->dealtype == 0 ? $row->bbkage: $row->sbkage;
	$dealArr[]=array(  
    'id' => $row->id,
	'vol' => $row->btccount/1,
    'rate' => $row->uprice/1,
	'fee' => $fee,
	'type' => $type,
    );
	//添加积分
			if($moneytype=="CNY"){
				$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+".$row->tprice." Where mid='".$row->buserid."'"); 
				$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+".$row->tprice." Where mid='".$row->suserid."'"); 
			}else{
				$rcny = $dsql->GetOne("SELECT id,uprice FROM #@__btcdeal WHERE coinid='$coinid' AND moneyid='1' ORDER BY id DESC");
				if(is_array($rcny)){
					$dealScores=$row->btccount*$rcny['uprice'];
					$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$row->buserid."'"); 
					$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$row->suserid."'"); 
				}else{
					$rbtc = $dsql->GetOne("SELECT id,uprice FROM #@__btcdeal WHERE coinid='$coinid' AND moneyid='2' ORDER BY id DESC");
					$rcnybtc = $dsql->GetOne("SELECT id,uprice FROM #@__btcdeal WHERE coinid='2' AND moneyid='1' ORDER BY id DESC");
					if(is_array($rjs)){
						$dealScores=$row->btccount*$rbtc['uprice']*$rcnybtc['uprice'];
						$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$row->buserid."'"); 
						$upscores = $dsql->ExecuteNoneQuery("Update #@__member Set scores=scores+$dealScores Where mid='".$row->suserid."'"); 
					}
				}
			}
}


   $dealArr=array(  
	'result' => 'true', 
	/*'showMsg' => '购买成功！', */
    'pending' => $otderArr,  
	'records' => $dealArr,  
    );
	$json_string = json_encode($dealArr);  
	echo $json_string;

/**
 *  提示信息
 */
function showJson($msg,$ruslt){
		$msgArray=array(  
		'showMsg' => $msg, 
		'ruslt' => $ruslt,
		'pending' => "",  
		'records' => ""  
		);
		echo $msg;
		$json_string = json_encode($msgArray);  
		echo $json_string;
		exit();
}

/**
 *  记录提成
 */
function deductFun($dealid,$userid,$fee,$dealtype,$coinid){
	global $dsql,$cfg_deduct,$cfg_dbprefix;
	//挂单
	if($cfg_deduct>0){
		$rjs = $dsql->GetOne("Select jsuserid From #@__member Where mid='".$userid."'");
			if(is_array($rjs)){
				if($rjs['jsuserid']!="") $dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."btcdeduct(dealid,newuserid,userid,fee,deduct,dealtype,dealtime,coinid) Values('$dealid','".$userid."','".$rjs['jsuserid']."','$fee','".($fee*$cfg_deduct)."',".$dealtype.",'".time()."','$coinid')");
			}
	}
}