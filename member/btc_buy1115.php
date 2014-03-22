<?php
/**
 * @version        $Id: btc_buy.php 1 8:38 2013年7月18日Z 
 * @package        btcka.Member
 * @copyright      Copyright (c) 2007 - 2010, btcka, Inc.
 * @license        http://help.btcka.com/usersguide/license.html
 * @link           http://www.btcka.com
 */

require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'op';
$myurl = $cfg_basehost.$cfg_member_dir.'/index.php?uid='.$cfg_ml->M_LoginID;

$coinid=$coinid?$coinid:"1";//BTC
$moneyid=$moneyid?$moneyid:"1";//CNY
$market=$market?$market:"1";//sz
$bkage=$bkage?$bkage:"0.002";
$userid=$cfg_ml->M_ID?$cfg_ml->M_ID:"1";
$uprice=$uprice?$uprice:"600";
$btccount=$btccount?$btccount:"1";
$dealtype=$dealtype?$dealtype:"0";//0买，1卖


if($dealtype==0){//买入
	$rmoy = $dsql->GetOne("Select m_deposit,moneyid From #@__btcmoney where userid = ".$cfg_ml->M_ID." AND moneyid='$moneyid' ;");
	if($rmoy){
		if ($rmoy->m_deposit < $tprice){//判断余额是否足够
			exit("余额不足！");
		}
	}else{
		exit("余额不足！");
	}
}else{//卖出
	$rcoin = $dsql->GetOne("Select c_deposit,coinid From #@__btccoin where userid = ".$cfg_ml->M_ID." AND coinid='$coinid' ;");
	if($rcoin){
		if ($rcoin->c_deposit < $tprice){//判断余额是否足够
			exit("余额不足！");
		}
	}else{
		exit("余额不足！");
	}
}


//$dsql->SetQuery("lock tables #@__btcsell write;");
//$dsql->Execute(); 

@mysql_connect($cfg_dbhost, $cfg_dbuser,$cfg_dbpwd) //选择数据库之前需要先连接数据库服务器 
or die("数据库服务器连接失败"); 
@mysql_select_db("btcdata") //选择数据库mydb 
or die("数据库不存在或不可用"); 


function sqlflase($fid){
echo $fid;
}

$year_code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
$order_sn = $year_code[intval(date('Y'))-2013].strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('d',rand(0,99));

$tprice = $uprice*$btccount;//总价
$bbkage = $tprice*$bkage;//手续费
//提交申请单
$rsnew = $dsql->ExecuteNoneQuery("insert into #@__BTCapply(oid,btccount,uprice,tprice,userid,bkage,dealtype,coinid,moneyid,market,ordertime) values('$order_sn',$btccount,$uprice,$tprice,'".$cfg_ml->M_ID."',$bkage,$dealtype,$coinid,$moneyid,$market,'".time()."')");

if($dealtype==0){//买入
	//扣除买入人的费用
	$upmoney = "Update #@__btcmoney Set m_deposit=m_deposit-$tprice-$bbkage,m_freeze=m_freeze+$tprice+$bbkage Where userid='".$cfg_ml->M_ID."' And moneyid='$moneyid'";
}else{//卖出
	//扣除卖出量
	$upmoney = "Update #@__btccoin Set c_deposit=c_deposit-$btccount,c_freeze=c_freeze+$btccount Where userid='".$cfg_ml->M_ID."' And coinid='$coinid'";
}
$rs = $dsql->ExecuteNoneQuery($upmoney); 


//读取未处理买单
$dsql->SetQuery("SELECT * FROM #@__BTCapply WHERE cancel='0' AND solve='0' AND dealed='0' ORDER BY id DESC");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    $applyArr[]=array(  
    'oid' => $row->oid, 
	'userid' => $row->userid, 
	'btccount' => $row->btccount, 
    'uprice' => $row->uprice,  
	'bkage' => $row->bkage, 
	'dealtype' => $row->dealtype,
	'coinid' => $row->coinid, 
	'moneyid' => $row->moneyid,
	'market' => $row->market, 
    );
}

//

$query = @mysql_query("lock tables btc_BTCorder write,btc_btcdeal write,btc_btcmoney write,btc_btccoin write;") //锁
or die(sqlflase("lock")); 



foreach($applyArr as $key=>$applist){
	if($applist['dealtype']==0) $othertype=1;
	else $othertype=0;
	$result = @mysql_query("SELECT * FROM ".$cfg_dbprefix."BTCorder WHERE dealtype=$othertype AND coinid='".$applist['coinid']."' AND moneyid='".$applist['moneyid']."' AND market='".$applist['market']."' AND uprice<=".$applist['uprice']." ORDER BY uprice;") //执行SQL语句
	or die(sqlflase("SELECT BTCorder")); 
	if(mysql_num_rows($result)==0){
		//挂单
		$dealTprice = $applist['btccount']*$applist['uprice'];
		$result = @mysql_query("Insert Into ".$cfg_dbprefix."BTCorder(oid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,market,dealtype,ordertime) Values('".$applist['oid']."',".$applist['btccount'].",".$applist['uprice'].",$dealTprice,'".$applist['userid']."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",".$applist['market'].",".$applist['dealtype'].",'".time()."')");
		
	}else{
		$appcount = $applist['btccount'];//申请量
		while($row = mysql_fetch_array($result))
		{
			if($row['btccount'] > $appcount){ //挂单量 > 申请量
				$dealcount = $appcount; //交易量=申请量
				$dealTprice = $dealcount * $row['uprice'];//总价
				$deloid = $applist['oid'];
			}else{		//申请量 <= 挂单量
				
				$dealcount = $row['btccount']; //成交量
				$dealTprice = $dealcount * $row['uprice'];//总价
				$deloid = $row['oid'];
			}
			if($applist['dealtype']==0) {  //申请是买单
				$bbkage = $dealTprice * $applist['bkage'];//买单手续费
				$bPAdif = $dealcount*$applist['uprice'] - $bbkage;//买单手续费差价
				$sPAdif = 0;//卖单手续费差价
				$sbkage = $dealTprice * $row['bkage'];//卖单手续费
				$buyoid = $applist['oid'];
				$selloid = $row['oid'];
				$Buserid = $applist['userid'];
				$Suserid = $row['userid'];
			}else{  //申请是卖单
				$bbkage = $dealTprice * $row['bkage'];//买单手续费
				$sbkage = $dealTprice * $applist['bkage'];//卖单手续费
				$bPAdif = 0;//买单手续费差价
				$sPAdif = $sbkage - $dealcount*$applist['uprice'];//卖单手续费差价
				$buyoid = $row['oid'];
				$selloid = $applist['oid'];
				$Buserid = $row['userid'];
				$Suserid = $applist['userid'];
			}
			
			//记录成交订单情况
			$dsql->ExecuteNoneQuery("Insert Into ".$cfg_dbprefix."BTCdeal(btccount,uprice,tprice,buyoid,selloid,buserid,suserid,bbkage,sbkage,coinid,moneyid,market,dealtime) Values($dealcount,".$row['uprice'].",$dealTprice,'".$buyoid."','".$selloid."','$Buserid','".$Suserid."',$bbkage,$sbkage,'".$applist['coinid']."','".$applist['moneyid']."','".$applist['market']."','".time()."')");
			
			//在卖单中减去成交量
			$result = @mysql_query("Update ".$cfg_dbprefix."BTCorder Set btccount=btccount-$dealcount,tprice=tprice-$dealTprice-$sbkage Where oid='".$selloid."'"); 
			//给卖单人扣除btc
			$upSfreeze = "Update #@__btccoin Set c_freeze=c_freeze-$dealcount Where userid='".$Suserid."' And coinid='".$applist['coinid']."'";
			$rs = $dsql->ExecuteNoneQuery($upSfreeze); 
			//给卖单人充money
			$upSmoney = "Update #@__btcmoney Set m_deposit=m_deposit+$dealTprice-$sbkage+$sPAdif Where userid='".$Suserid."' And moneyid='".$applist['moneyid']."'";
			$rs = $dsql->ExecuteNoneQuery($upSmoney); 
			//扣除买入人的费用,补回手续费差价
			$upBmoney = "Update #@__btcmoney Set m_freeze=m_freeze-$dealTprice-$bbkage-$bPAdif,m_deposit=m_deposit+$bPAdif Where userid='".$Buserid."' And moneyid='".$applist['moneyid']."'";
			$rs = $dsql->ExecuteNoneQuery($upBmoney); 
			//给买入人充btc
			$upBfreeze = "Update #@__btccoin Set c_deposit=c_deposit+$dealcount Where userid='".$Buserid."' And coinid='".$applist['coinid']."'";
			$rs = $dsql->ExecuteNoneQuery($upBfreeze); 
			//记录为已成交
			//$updealed = "Update #@__btcapply Set dealed=1 Where oid='".$deloid."'";
			//$rs = $dsql->ExecuteNoneQuery($updealed); 
			$deloids[]=$deloid;
			//删除
			$result = @mysql_query("Delete From ".$cfg_dbprefix."btcorder where oid='".$deloid."'"); 
			
			$appcount = $appcount - $row['btccount']; //剩余申请量
			if($appcount<=0) {
				$deloids[]=$applist['oid'];
				break;//退出循环
			}
		}
		if($appcount > 0){  
			$tprice = $applist['uprice']*$appcount;//总价
			//挂申请单
			$result = @mysql_query("Insert Into ".$cfg_dbprefix."BTCorder(oid,btccount,uprice,tprice,userid,bkage,coinid,moneyid,market,dealtype,ordertime) Values('".$applist['oid']."',$appcount,".$applist['uprice'].",$tprice,'".$applist['userid']."',".$applist['bkage'].",".$applist['coinid'].",".$applist['moneyid'].",".$applist['market'].",".$applist['dealtype'].",'".time()."')");
		}
	}
}

$query = @mysql_query("unlock tables;") //解锁
or die(sqlflase("unlock")); 
foreach($applyArr as $key=>$applist){
//记录为已处理
	$upsolve = "Update #@__btcapply Set solve=1 Where oid='".$applist['oid']."'";
	$rs = $dsql->ExecuteNoneQuery($upsolve); 
}
foreach($deloids as $key=>$dellist){
//记录为已成交
	$updealed = "Update #@__btcapply Set dealed=1 Where oid='".$dellist."'";
	$rs = $dsql->ExecuteNoneQuery($updealed); 
}
$dsql->SetQuery("SELECT * FROM #@__btcapply WHERE oid='$order_sn'");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	echo "申请：".$row->oid;
}
$dsql->SetQuery("SELECT * FROM #@__btcorder WHERE oid='$order_sn'");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	echo "挂单：".$row->oid;
}
$dsql->SetQuery("SELECT * FROM #@__btcdeal WHERE buyoid='$order_sn' or selloid='$order_sn'");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	echo "成交：".$row->id;
}

exit("停止");



$dsql->SetQuery("SELECT * FROM #@__btcsell WHERE coinid='$coinid' AND market='$market' AND uprice<=$uprice ORDER BY uprice");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    if($row->btccount > $appcount){ //卖单数量 > 买入数量
		$dealcount = $appcount; //成交量
		$dealTprice = $dealcount * $row->uprice;//总价
		$bbkage = $dealTprice * $bkage;//买手续费
		$sbkage = $dealTprice * $row->bkage;//卖手续费
		
		//记录成交订单情况
		$dsql->ExecuteNoneQuery("Insert Into #@__BTCdeal(btccount,uprice,tprice,buyid,sellid,buserid,suserid,bbkage,sbkage,coinid,market,dealtime) Values($dealcount,".$row->uprice.",$dealTprice,'$buyid','".$row->id."','".$cfg_ml->M_ID."','".$Suserid."',$bbkage,$sbkage,'$coinid','$market','".time()."')");
		
		//在卖单中减去成交量
		$upsellcount = "Update #@__btcsell Set btccount=btccount-$dealcount Where id='".$row->id."'";
		$rs = $dsql->ExecuteNoneQuery($upsellcount); 
		
		//扣除买入人的费用,给买入人充btc
		$upbuymoney = "Update #@__member Set money=money-$dealTprice-$bbkage,scores=scores+$dealcount Where mid='".$cfg_ml->M_ID."'";
		$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
		
		//给卖单人充值
		$upsellmoney = "Update #@__member Set money=money+$dealTprice-$sbkage Where mid='".$Suserid."'";
		$rs = $dsql->ExecuteNoneQuery($upsellmoney); 
		$appcount = 0; //剩余买入量
		break;//退出循环
	}else{			//买入数量 > 卖单数量
		$dealcount = $row->btccount; //成交量
		$dealTprice = $dealcount * $row->uprice;//总价
		$bbkage = $dealTprice * $bkage;//买手续费
		$sbkage = $dealTprice * $row->bkage;//卖手续费
		
		//记录成交订单情况
		$dsql->ExecuteNoneQuery("Insert Into #@__BTCdeal(btccount,uprice,tprice,buyid,sellid,buserid,suserid,bbkage,sbkage,coinid,market,dealtime) Values($dealcount,".$row->uprice.",$dealTprice,'$buyid','".$row->id."','".$cfg_ml->M_ID."','".$Suserid."',$bbkage,$sbkage,'$coinid','$market','".time()."')");
		
		//在卖单中减去成交量
		//$upsellcount = "Update #@__btcsell Set btccount=btccount-$dealcount Where id='".$row->id."'";
		//$rs = $dsql->ExecuteNoneQuery($upsellcount); 
		//删除卖单
		$dsql->ExecNoneQuery("Delete From #@__btcsell where id='".$row->id."';");  
		
		//扣除买入人的费用,给买入人充btc
		$upbuymoney = "Update #@__member Set money=money-$dealTprice-$bbkage,scores=scores+$dealcount Where mid='".$cfg_ml->M_ID."'";
		$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
		//给卖单人充值
		$upsellmoney = "Update #@__member Set money=money+$dealTprice-$sbkage Where mid='".$Suserid."'";
		$rs = $dsql->ExecuteNoneQuery($upsellmoney); 
		$appcount = $appcount-$row->btccount; //剩余买入量
		if($appcount==0) break;//退出循环
	}
}

if($appcount > 0){  //挂单
	$tprice = $uprice*$appcount;//总价
	$bbkage = $tprice*$bkage;//手续费
	//写入订单
	$uporder = "Update #@__btcbuy Set btccount=$appcount,uprice=$uprice,tprice=$tprice,bkage=$bkage Where id=$buyid";
	$rs = $dsql->ExecuteNoneQuery($uporder); 
	//扣除费用
	$upbuymoney = "Update #@__member Set money=money-$tprice-$bbkage Where mid='".$cfg_ml->M_ID."'";
	$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
}else{
	//删除记录
	$dsql->ExecNoneQuery("Delete From #@__btcbuy where id='$buyid';");  
}

$dsql->SetQuery("unlock tables;");
$dsql->Execute(); 

   $dealArr=array(  
    'buycount' => $appcount,  
	'uprice' => $uprice, 
    'tprice' => $tprice,  
	'bbkage' => $bbkage,  
    );
	$json_string = json_encode($dealArr);  
	echo $json_string;
