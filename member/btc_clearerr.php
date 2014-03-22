<?php 
/*
@version        $Id: btc_deal.php 1 8:38 2013年8月8日Z 
*/
require_once(dirname(__FILE__).'/config.php');
?>
查询冻结出错金额<br>
<form action="" method="post">
币种id：<input name="coinid" type="text" value="<?php echo $coinid;?>" />如FEC为2<br>
用户名称：<input name="userid" type="text" value="<?php echo $userid;?>" /><br>
出错自动解冻CNY：<input name="tuihui" type="checkbox" value="1" <?php if($tuihui==1) echo "checked=\"checked\"" ?>/><br>
<input name="" type="submit" />
</form>


<?php



if($userid=="" || $coinid=="") exit("提交数据不全！");
$i=1;
echo $userid."挂单"."<br>";
$ruser = $dsql->GetOne("Select mid From #@__member where userid = '".$userid."'");
if(is_array($ruser)){
	$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."btcorder WHERE coinid='$coinid' AND dealtype='0' AND moneyid='1' AND userid='".$ruser['mid']."' ORDER BY ordertime");
	$dsql->Execute();
	while($row = $dsql->GetObject()){
		echo $i." 单价:".$row->uprice;
		echo " 量:".$row->btccount;
		echo " 总价:".$row->uprice*$row->btccount."<br>";
		$tprice=$tprice+$row->uprice*$row->btccount;
		$i++;
	}
	
	echo "合计:".$tprice."<br><br>";
	$rcoin = $dsql->GetOne("Select c_deposit,c_freeze From #@__btccoin where coinid='1' and userid = '".$ruser['mid']."'");
	if(is_array($rcoin)){
		echo "余额:".$rcoin['c_deposit']."<br>";
		echo "冻结:".$rcoin['c_freeze']."<br>";
		$jdtprice=$rcoin['c_freeze']-$tprice;
		echo "出错金额:".$jdtprice." CNY<br>";
		if($tuihui==1 && $rcoin['c_freeze']>=$tprice){
			echo "解冻金额:".$jdtprice." CNY<br>";
			//解冻费用
			$upmoney = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+$jdtprice,c_freeze=c_freeze-$jdtprice Where userid='".$ruser['mid']."' And coinid='1'"); 
		}
	}
}


?>