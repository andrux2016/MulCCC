<?php
/**
 * @version        $Id: btc_buy.php 1 8:38 2013年7月18日 SZ 
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

$buycount = $btccount;//买入总量
$rmoy = $dsql->GetOne("Select * From #@__btcmoney where userid = ".$cfg_ml->M_ID." AND moneytype='CNY' ;");
if ($rmoy->m_count < $tprice){//判断余额是否足够
	exit("余额不足！");
}

$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btcbuy(btccount,uprice,tprice,userid,poundage,cointype,markname,ordertime) values(0,0,0,'".$cfg_ml->M_LoginID."','$poundage','$cointype','sz','".time()."')");
$buyid = $dsql->GetLastID();  //先挂个0单，获取id
if(!$rsnew) exit("数据异常交易失败");

$dsql->SetQuery("SELECT * FROM #@__btcsell WHERE cointype='$cointype' AND markname='$markname' AND uprice<=$uprice ORDER BY uprice");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    if($row->btccount >= $buycount){ //卖单数量 > 买入数量
		$dealcount = $buycount; //成交量
		$dealtprice = $dealcount * $row->uprice;//总价
		$bpoundage = $dealtprice * $poundage;//买手续费
		$spoundage = $dealtprice * $row->poundage;//卖手续费
		
		//记录成交订单情况
		$dsql->ExecuteNoneQuery("Insert Into #@__btcdeal(btccount,uprice,tprice,buyid,sellid,buserid,suserid,bpoundage,spoundage,cointype,markname,dealtime) Values($dealcount,".$row->uprice.",$dealtprice,'$buyid','".$row->id."','".$cfg_ml->M_LoginID."','".$row->userid."',$bpoundage,$spoundage,'$cointype','$markname','".time()."')");
		
		//在卖单中减去成交量
		$upsellcount = "Update #@__btcsell Set btccount=btccount-$dealcount Where id='".$row->id."'";
		$rs = $dsql->ExecuteNoneQuery($upsellcount); 
		
		//扣除买入人的费用,给买入人充btc
		$upbuymoney = "Update #@__member Set money=money-$dealtprice-$bpoundage,scores=scores+$dealcount Where mid='".$cfg_ml->M_LoginID."'";
		$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
		
		//给卖单人充值
		$upsellmoney = "Update #@__member Set money=money+$dealtprice-$spoundage Where mid='".$row->userid."'";
		$rs = $dsql->ExecuteNoneQuery($upsellmoney); 
		$buycount = 0; //剩余买入量
		break;//退出循环
	}else{			//买入数量 > 卖单数量
		$dealcount = $row->btccount; //成交量
		$dealtprice = $dealcount * $row->uprice;//总价
		$bpoundage = $dealtprice * $poundage;//买手续费
		$spoundage = $dealtprice * $row->poundage;//卖手续费
		
		//记录成交订单情况
		$dsql->ExecuteNoneQuery("Insert Into #@__btcdeal(btccount,uprice,tprice,buyid,sellid,buserid,suserid,bpoundage,spoundage,cointype,markname,dealtime) Values($dealcount,".$row->uprice.",$dealtprice,'$buyid','".$row->id."','".$cfg_ml->M_LoginID."','".$row->userid."',$bpoundage,$spoundage,'$cointype','$markname','".time()."')");
		
		//在卖单中减去成交量
		//$upsellcount = "Update #@__btcsell Set btccount=btccount-$dealcount Where id='".$row->id."'";
		//$rs = $dsql->ExecuteNoneQuery($upsellcount); 
		//删除卖单
		$dsql->ExecNoneQuery("Delete From #@__btcsell where id='".$row->id."';");  
		
		//扣除买入人的费用,给买入人充btc
		$upbuymoney = "Update #@__member Set money=money-$dealtprice-$bpoundage,scores=scores+$dealcount Where mid='".$cfg_ml->M_LoginID."'";
		$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
		//给卖单人充值
		$upsellmoney = "Update #@__member Set money=money+$dealtprice-$spoundage Where mid='".$row->userid."'";
		$rs = $dsql->ExecuteNoneQuery($upsellmoney); 
		$buycount = $buycount-$row->btccount; //剩余买入量
	}
}

if($buycount > 0){  //挂单
	$tprice = $uprice*$buycount;//总价
	$bpoundage = $tprice*$poundage;//手续费
	//写入订单
	$uporder = "Update #@__btcbuy Set btccount=$buycount,uprice=$uprice,tprice=$tprice,poundage=$poundage Where id=$buyid";
	$rs = $dsql->ExecuteNoneQuery($uporder); 
	//扣除费用
	$upbuymoney = "Update #@__member Set money=money-$tprice-$bpoundage Where mid='".$cfg_ml->M_LoginID."'";
	$rs = $dsql->ExecuteNoneQuery($upbuymoney); 
}else{
	//删除记录
	$dsql->ExecNoneQuery("Delete From #@__btcbuy where id='$buyid';");  
}



   $buyArray=array(  
    'buycount' => $buycount,  
	'uprice' => $uprice, 
    'tprice' => $tprice,  
	'bpoundage' => $bpoundage,  
    );
	$json_string = json_encode($buyArray);  
	echo $json_string;
