<?php
/**
 * 交易类型
 *
 * @version        $Id: btc_cv_manage.php 1 14:14 2013年8月20日Z SZ $
 */

require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
if(empty($dopost)) $dopost = "";

$arcranks = array();
$dsql->SetQuery("SELECT id,cointype,coinname,coinsign FROM #@__btctype ");
$dsql->Execute();
while($row=$dsql->GetArray())
{
	if($row['coinsign']=="0") $coinsign="(币种未启用)";
	else $coinsign="";
	$arcranks[$row['id']] = array(
	cointype=>$row['cointype'],
	coinname=>$row['coinname'].$coinsign
	);
}

//保存更改
if($dopost=="save")
{
    $startID = 1;
    $endID = $idend;
    for( ;$startID <= $endID; $startID++)
    {
        $query = '';
        $aid = ${'ID_'.$startID};
        $coinid = ${'coin_'.$startID};
		$cointype = $arcranks[${'coin_'.$startID}]['cointype'];
		$moneyid =   ${'money_'.$startID};
		$moneytype =   $arcranks[${'money_'.$startID}]['cointype'];
        $fee =    ${'fee_'.$startID};
		$explain =    ${'explain_'.$startID};
		$mdggper =    ${'mdggper_'.$startID};
		$mkggper =    ${'mkggper_'.$startID};
		$explain =    ${'explain_'.$startID};
		if($coinid==$moneyid){
			//exit("相同币种不能交易！");
		}
		if(isset(${'enabled_'.$startID})){
        	$enabled = 1;
		}else{
			$enabled = 0;
		}
		
        if(isset(${'check_'.$startID}))
        {
            //$query = "UPDATE #@__qhheyue SET coinid='$coinid',cointype='$cointype',moneyid='$moneyid',moneytype='$moneytype',mdggper='$mdggper',mkggper='$mkggper',fee='$fee',enabled='$enabled' WHERE id='$aid'";
			$query = "UPDATE #@__qhheyue SET mdggper='$mdggper',mkggper='$mkggper',`explain`='$explain',fee='$fee',enabled='$enabled' WHERE id='$aid'";
			//exit();
        }
        else
        {
            
			$query = "DELETE FROM #@__qhheyue WHERE id='$aid' AND ( etime > (".strtotime(+10 ).") OR stime < ".time().") ";
        }
        if($query!='')
        {
            $dsql->ExecuteNoneQuery($query);
        }
    }

    //增加新记录
    if(isset($check_new) && $coin_new!="" && $money_new!="")
    {
		$stime = strtotime($stime_new);
		$etime = strtotime($etime_new);
		$query = "INSERT INTO #@__qhheyue(stime,etime,coinid,cointype,moneyid,moneytype,mdggper,mkggper,fee,`explain`,qhmarket,enabled) VALUES('{$stime}','{$etime}','{$coin_new}','".$arcranks[$coin_new]['cointype']."','{$money_new}','".$arcranks[$money_new]['cointype']."',{$mdggper_new},{$mkggper_new},{$fee_new},'{$explain_new}','1','1');";
        $dsql->ExecuteNoneQuery($query);
    }
    header("Content-Type: text/html; charset={$cfg_soft_lang}");
    echo "<script> alert('成功更新合约！'); </script>";
}


require_once(DEDEADMIN."/templets/qh_heyue.htm");