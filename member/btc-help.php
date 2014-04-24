<?php
/**
 * 

 */
 
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/datalistcp.class.php');
$typeid = 3;

if($num!=""){			

	$addsql="AND id='$num'";
}
$rtil=$dsql->GetOne("SELECT * FROM `#@__archives` WHERE arcrank>=0 $addsql and typeid = $typeid ORDER BY id DESC");
if(is_array($rtil)) $rbody=$dsql->GetOne("SELECT body FROM `#@__addonarticle` WHERE aid='".$rtil['id']."' ORDER BY aid DESC");

$showmune = 5;

  $sql = "SELECT * FROM #@__archives  WHERE arcrank>=0 and typeid = ".$typeid." ORDER BY senddate DESC";
  $dl = new DataListCP();
  $dl->pageSize = 20;
  //�������˳���ܸ�
  $dl->SetTemplate(dirname(__FILE__)."/templets/btc-help.htm");      //����ģ��
  $dl->SetSource($sql);            //�趨��ѯSQL
  $dl->Display();     
   