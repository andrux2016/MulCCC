<?php
/**
 * @version        $Id: btc_sell.php 1 8:38 2013年7月18日Z 
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

$cointype=$cointype?$cointype:"btc";
$markname=$markname?$markname:"sz";
$poundage=$poundage?$poundage:"0.002";
$userid=$userid?$userid:"1";
$uprice=$uprice?$uprice:"600";
$btccount=$btccount?$btccount:"1";

$sellcount = $btccount;//卖出总量

if ($cfg_ml->M_Scores < $btccount){//判断余额是否足够
	exit("余额不足！");
}

//$dsql->SetQuery("lock tables #@__btcsell write;");
//$dsql->Execute(); 

$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btcsell(btccount,uprice,tprice,userid,poundage,cointype,markname,ordertime) values(0,0,0,'".$cfg_ml->M_ID."','$poundage','$cointype','sz','".time()."')");
$sellid = $dsql->GetLastID();  //先挂个0单，获取id
if(!$rsnew) exit("数据异常交易失败");

$dsql->SetQuery("SELECT * FROM #@__btcbuy WHERE cointype='$cointype' AND markname='$markname' AND uprice>=$uprice ORDER BY uprice DESC");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    if($row->btccount > $sellcount){ //买单数量 > 卖出数量
		$dealcount = $sellcount; //成交量
		$dealtprice = $dealcount * $row->uprice;//总价
		$bpoundage = $dealtprice * $row->poundage;//买手续费
		$spoundage = $dealtprice * $poundage;//卖手续费
		
		//记录成交订单情况
		$dsql->ExecuteNoneQuery("Insert Into #@__btcdeal(btccount,uprice,tprice,buyid,sellid,buserid,suserid,bpoundage,spoundage,cointype,markname,dealtime) Values($dealcount,".$row->uprice.",$dealtprice,'".$row->id."','$sellid','".$cfg_ml->M_ID."','".$row->userid."',$bpoundage,$spoundage,'$cointype','$markname','".time()."')");
		
		//在买单中减去成交量
		$upsellcount = "Update #@__btcbuy Set btccount=btccount-$dealcount Where id='".$row->id."'";
		$rs = $dsql->ExecuteNoneQuery($upsellcount); 
		
		//扣除卖出人的btc,给人卖出人充值
		$upsellmoney = "Update #@__member Set scores=scores-$dealcount,money=money+$dealtprice-$spoundage Where mid='".$cfg_ml->M_ID."'";
		$rs = $dsql->ExecuteNoneQuery($upsellmoney); 
		//给人买单人充btc
		$upbuymoney = "Update #@__member Set scores=scores+$dealcount Where mid='".$row->userid."'";
		$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
		$sellcount = 0; //剩余卖出量
		break;//退出循环
	}else{			//卖出数量 > 买单数量
		$dealcount = $row->btccount; //成交量
		$dealtprice = $dealcount * $row->uprice;//总价
		$bpoundage = $dealtprice * $row->poundage;//买手续费
		$spoundage = $dealtprice * $poundage;//卖手续费
		
		//记录成交订单情况
		$dsql->ExecuteNoneQuery("Insert Into #@__btcdeal(btccount,uprice,tprice,buyid,sellid,buserid,suserid,bpoundage,spoundage,cointype,markname,dealtime) Values($dealcount,".$row->uprice.",$dealtprice,'".$row->id."','$sellid','".$cfg_ml->M_ID."','".$row->userid."',$bpoundage,$spoundage,'$cointype','$markname','".time()."')");
		
		//在买单中减去成交量
		//$upsellcount = "Update #@__btcsell Set btccount=btccount-$dealcount Where id='".$row->id."'";
		//$rs = $dsql->ExecuteNoneQuery($upsellcount); 
		//删除买单
		$dsql->ExecNoneQuery("Delete From #@__btcbuy where id='".$row->id."';");  
		
		//扣除卖出人的btc,给人卖出人充值
		$upsellmoney = "Update #@__member Set scores=scores-$dealcount,money=money+$dealtprice-$spoundage Where mid='".$cfg_ml->M_ID."'";
		$rs = $dsql->ExecuteNoneQuery($upsellmoney); 
		//给人买单人充btc
		$upbuymoney = "Update #@__member Set scores=scores+$dealcount Where mid='".$row->userid."'";
		$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
		$sellcount = $sellcount-$row->btccount; //剩余卖出量
		if($buycount==0) break;//退出循环
	}
}

if($sellcount > 0){  //挂单
	$tprice = $uprice*$sellcount;//总价
	$bpoundage = $tprice*$poundage;//手续费
	//写入订单
	$uporder = "Update #@__btcsell Set btccount=$sellcount,uprice=$uprice,tprice=$tprice,poundage=$poundage Where id=$sellid";
	$rs = $dsql->ExecuteNoneQuery($uporder); 
	//扣除btc
	$upbuymoney = "Update #@__member Set scores=scores-$sellcount Where mid='".$cfg_ml->M_ID."'";
	$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
}else{
	//删除记录
	$dsql->ExecNoneQuery("Delete From #@__btcsell where id='$sellid';");  
}
//$dsql->SetQuery("unlock tables;");
//$dsql->Execute(); 


   $buyArray=array(  
    'sellcount' => $sellcount,  
	'uprice' => $uprice, 
    'tprice' => $tprice,  
	'bpoundage' => $bpoundage,  
    );
	$json_string = json_encode($buyArray);  
	echo $json_string;
