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
if($pageno!=""){
    $conunt=$pageno*20;
    $conunt1=($pageno-1)*20;
    $addsql1="limit($conunt1,$conunt)";
}
$rtil=$dsql->GetOne("SELECT * FROM `#@__archives` WHERE arcrank>=0 $addsql ORDER BY id DESC");
if(is_array($rtil)) $rbody=$dsql->GetOne("SELECT body FROM `#@__addonarticle` WHERE aid='".$rtil['id']."' ORDER BY aid DESC");

$showmune = 3;

$typeid = 1;

$sql = "SELECT * FROM #@__archives  WHERE arcrank>=0 AND typeid=$typeid ORDER BY senddate DESC";
$dl = new DataListCP();
$dl->pageSize = 20;
//这两句的顺序不能更换
if(isset($typeid)) $dl->SetParameter("typeid",$typeid);
$dl->SetTemplate(dirname(__FILE__)."/templets/news.htm");      //载入模板
$dl->SetSource($sql);            //设定查询SQL
$dl->Display();
 ?>