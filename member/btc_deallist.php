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
	

 $sql = "SELECT * FROM #@__btcdeal WHERE buserid = '".$cfg_ml->M_ID."' OR suserid = '".$cfg_ml->M_ID."' ORDER BY dealtime DESC";

  $dl = new DataListCP();
  $dl->pageSize = 20;
  //这两句的顺序不能更换
  $dl->SetTemplate(DEDEMEMBER."/templets/btc_deallist.htm");      //载入模板
  $dl->SetSource($sql);            //设定查询SQL
  $dl->Display();                  //显示
