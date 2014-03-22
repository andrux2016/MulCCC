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
$dsql->SetQuery("SELECT * FROM #@__btctype ");
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
        $coinid =   ${'coin_'.$startID};
		$cointype =   $arcranks[${'coin_'.$startID}]['cointype'];
		$coinname =   $arcranks[${'coin_'.$startID}]['coinname'];
        $fee =    ${'fee_'.$startID};
        $moneyid =   ${'money_'.$startID};
		$moneytype =   $arcranks[${'money_'.$startID}]['cointype'];
		$moneyname =   $arcranks[${'money_'.$startID}]['coinname'];
		$digits = ${'digits_'.$startID};
		if($coinid==$moneyid){
			exit("相同币种不能交易！");
		}
		if(isset(${'enabled_'.$startID})){
        	$enabled = 1;
		}else{
			$enabled = 0;
		}
		
        if(isset(${'check_'.$startID}))
        {
            $query = "UPDATE #@__btcconvert SET coinid='$coinid',cointype='$cointype',coinname='$coinname',moneyid='$moneyid',moneytype='$moneytype',moneyname='$moneyname',fee='$fee',digits='$digits ',enabled='$enabled' WHERE id='$aid'";
        }
        else
        {
            $query = "DELETE FROM #@__btcconvert WHERE id='$aid' ";
        }
        if($query!='')
        {
            $dsql->ExecuteNoneQuery($query);
        }
    }

    //增加新记录
    if(isset($check_new) && $coin_new!="" && $money_new!="")
    {
        if($coinid==$moneyid){
			exit("相同币种不能交易！");
		}
		$query = "INSERT INTO #@__btcconvert(coinid,cointype,coinname,moneyid,moneytype,moneyname,fee) VALUES('{$coin_new}','".$arcranks[$coin_new]['cointype']."','".$arcranks[$coin_new]['coinname']."','{$money_new}','".$arcranks[$money_new]['cointype']."','".$arcranks[$money_new]['coinname']."','{$fee_new}');";
        $dsql->ExecuteNoneQuery($query);
    }
    header("Content-Type: text/html; charset={$cfg_soft_lang}");
    echo "<script> alert('成功更新币种交易！'); </script>";
}




require_once(DEDEADMIN."/templets/btc_cv_manage.htm");