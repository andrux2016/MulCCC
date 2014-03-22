<?php
/**
 * @version        $Id: btc_dealist.php 1 8:38 2010年8月9日Z SZ $
 */
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/datalistcp.class.php');
CheckRank(0,0);
CheckTxPdw();
$menutype = 'qihuo';
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



 $sql = "SELECT * FROM #@__qhdeal WHERE buserid = '".$cfg_ml->M_ID."' OR suserid = '".$cfg_ml->M_ID."' ORDER BY dealtime DESC";

  $dl = new DataListCP();
  $dl->pageSize = 20;
  //这两句的顺序不能更换
  $dl->SetTemplate(DEDEMEMBER."/templets/qh_deallist.htm");      //载入模板
  $dl->SetSource($sql);            //设定查询SQL
  $dl->Display();                  //显示
