<?php
/**
 * @version        $Id: btc_dealist.php 1 8:38 2010年8月9日Z SZ $
 */
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/datalistcp.class.php');
CheckRank(0,0);
CheckTxPdw();
$menutype = 'mydede';
$menutype_son = 'op';
$myurl = $cfg_basehost.$cfg_member_dir.'/index.php?uid='.$cfg_ml->M_LoginID;
$memberorders = '';
$memberdeals = '';

	$cvid = $cvid?$cvid:0;
	//$dtype=explode("_",$_SERVER['QUERY_STRING']);
	$dtype=$symbol?explode("_",$symbol): "";
	$dtype1=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[0]);
	$dtype2=$password=preg_replace("#[^A-Za-z-]#", "", $dtype[1]);
	//$rcvid = $dsql->GetOne("Select id From `#@__btcconvert` Where coinid=$dtype1 AND moneyid=$dtype2");
	
	$dsql->SetQuery("select * from `#@__btctype` where coinsign = 1");
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
		if($coinarr[$key]['cointype'] == $symbol) $convertType .= "<li class='li1 cur'><a href='?symbol=".$symbol."'>".$symbol."</a></li>";
		else $convertType .= "<li><a href='?symbol=".$coinelement['cointype']."'>".$coinelement['cointype']."</a></li>";
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
			
	foreach($dtypearr as $key => $typemune){
		if($key==$cvid) {$convertName .= "<li class='li1 cur'><a href='?symbol=".$typemune['cointype']."_".$typemune['moneytype']."'>".$showtype.$typemune['cointype']."<span>(".$typemune['moneytype'].")</span></a></li>";
			$cccoinid = $typemune['coinid'];
			$mmmoneyid = $typemune['moneyid'];
		}
		else $convertName .= "<li><a href='?symbol=".$typemune['cointype']."_".$typemune['moneytype']."'>".$showtype.$typemune['cointype']."<span>(".$typemune['moneytype'].")</span></a></li>";
	}

$dsql->SetQuery("SELECT id,cointype FROM #@__btctype");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$coinarr[$row->id] = $row->cointype;
}


$cfg_arrcoin=Getdeposit("",$cfg_ml->M_ID);

foreach ($cfg_arrcoin as $value){

	$coinhtml.="<li>".$value['0']."：<span>".rtrimandformat(floor($value['1']*100)/100)."</span></li>";
	$freehtml.="<li>冻结：<span>".rtrimandformat($value['2']/1)."</span></li>";
	$coinvol+=$value['4'];
}
	

 $sql = "SELECT * FROM #@__btcdeal WHERE (buserid = '".$cfg_ml->M_ID."' OR suserid = '".$cfg_ml->M_ID."') and coinid = '".$cccoinid."' and moneyid = '".$mmmoneyid."' ORDER BY dealtime DESC";

  $dl = new DataListCP();
  $dl->pageSize = 20;
  //这两句的顺序不能更换
  $dl->SetTemplate(DEDEMEMBER."/templets/btc_deallist.htm");      //载入模板
  $dl->SetSource($sql);            //设定查询SQL
  $dl->Display();                  //显示
