<?php
/**
 * 

 */
 
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/datalistcp.class.php');
$showmune=1;

if($num!=""){			

	$addsql="AND id='$num'";
}
$rtil=$dsql->GetOne("SELECT * FROM `#@__archives` WHERE arcrank>=0 $addsql ORDER BY id DESC");
if(is_array($rtil)) $rbody=$dsql->GetOne("SELECT body FROM `#@__addonarticle` WHERE aid='".$rtil['id']."' ORDER BY aid DESC");



  $sql = "SELECT * FROM #@__archives  WHERE arcrank>=0 ORDER BY senddate DESC";
  $dl = new DataListCP();
  $dl->pageSize = 20;
  //这两句的顺序不能更换
  $dl->SetTemplate(dirname(__FILE__)."/templets/gonggao.htm");      //载入模板
  $dl->SetSource($sql);            //设定查询SQL
  $dl->Display();     
/*require_once(DEDEMEMBER."/templets/gonggao.htm"); */    