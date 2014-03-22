<?php
/*
@version        $Id: qh_possess.php 1 8:38 2013年8月8日Z 
 */

require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);

$dsql->SetQuery("SELECT id,cointype FROM #@__btctype");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$coinarr[$row->id] = $row->cointype;
}
require_once('mtgoxavg.php');

$result=catch_url("https://data.mtgox.com/api/2/BTCUSD/money/ticker");
$mtgoxavg=mtgoxavg($result);
  $sql = "SELECT * FROM #@__qhpossess where userid = '".$cfg_ml->M_ID."'";
  $dl = new DataListCP();
  $dl->pageSize = 20;
  //这两句的顺序不能更换
  $dl->SetTemplate(dirname(__FILE__)."/templets/qh_possess.htm");      //载入模板
  $dl->SetSource($sql);            //设定查询SQL
  $dl->Display();      