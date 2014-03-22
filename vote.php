<?php

/**
 * @version        $Id: vote.php 1 9:23 2013-08-11
 */
$nowtime = time();
//自动生成HTML版

if(isset($_GET['upcache']) || !file_exists('vote.html'))
{
	require_once (dirname(__FILE__) . "/include/common.inc.php");
	require_once DEDEINC."/arc.partview.class.php";
	if(isset($_POST['action']) && isset($_POST['checklist'])){
		$action = $_POST['action'];
		if($action == 'vote'){
			$checklist = $_POST['checklist'];
			foreach ($checklist as $check){
				$rsup = $dsql->ExecuteNoneQuery("Update #@__btctype Set vote = vote +1 where cointype = '".$check."'"); 
			}
		}
		$msg = "投票成功";
	}
	//交易类型$cointypelist
	$dsql->SetQuery("Select cointype, coinname From `#@__btctype` Where coinsign = 1 and coinhost = 0");
	$dsql->Execute();
	$count = 0;
	while ($rcv = $dsql->GetObject()){
			$cointypelist[$count] = array(
				'id' => $rcv->id,
				'cointype' => $rcv->cointype,
				'coinname' => $rcv->coinname
			);
			$count++;
	}
	
    $row = $dsql->GetOne("Select * From `#@__homepageset`");
    $row['templet'] = MfTemplet($row['templet']);
    $pv = new PartView();
    $pv->SetTemplet($cfg_basedir . $cfg_templets_dir . "/default/vote.htm");
    
        $pv->Display();
        exit();
}
else
{
	header('HTTP/1.1 301 Moved Permanently');
    header('Location:vote.html');
}





?>