<?php
/**
 * 会员类型
 *
 * @version        $Id: btc_coin_manage.php 1 14:14 2010年7月20日Z SZ $
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
if(empty($dopost)) $dopost = "";

//保存更改
if($dopost=="save")
{
    $startID = 1;
    $endID = $idend;
    for( ;$startID <= $endID; $startID++)
    {
        $query = '';
        $aid = ${'ID_'.$startID};
        $cointype =    ${'cointype_'.$startID};
		$coinname =    ${'coinname_'.$startID};
        $coinfee =   ${'coinfee_'.$startID};
        $recfee = ${'recfee_'.$startID};
		$feetype = ${'feetype_'.$startID};
		$about = ${'about_'.$startID};
		$buynote = ${'buynote_'.$startID};
		$cashnote = ${'cashnote_'.$startID};
		$payid = ${'pay_'.$startID};
		if(isset(${'coinsign_'.$startID})) $coinsign = 1;
		else $coinsign = 0;
		if(isset(${'reccheck_'.$startID})) $reccheck = 0;
		else $reccheck = 1;
		if(isset(${'cashcheck_'.$startID})) $cashcheck = 0;
		else $cashcheck = 1;
		if(isset(${'coinhost_'.$startID})) $coinhost = 1;
		else $coinhost = 0;
        if(isset(${'check_'.$startID}))
        {
           $query = "UPDATE #@__btctype SET cointype='$cointype',coinname='$coinname',coinfee='$coinfee',recfee='$recfee',feetype='$feetype',about='$about',buynote='$buynote',cashnote='$cashnote',coinsign='$coinsign',reccheck='$reccheck',cashcheck='$cashcheck',coinhost='$coinhost',payid='$payid' WHERE id='$aid'";
		   
        }
        else
        {
            $query = "DELETE FROM #@__btctype WHERE id='$aid' ";
        }
        if($query!='')
        {
            $dsql->ExecuteNoneQuery($query);
			//exit();
        }
    }

    //增加新记录
    if(isset($check_new) && $cointype_new!='' && $coinname_new!='')
    {
        $query = "INSERT INTO #@__btctype(cointype,coinname,coinfee,recfee,feetype) VALUES('{$cointype_new}','{$coinname_new}','{$coinfee_new}','{$recfee_new}','{$feetype_new}');";
        $dsql->ExecuteNoneQuery($query);
    }
    header("Content-Type: text/html; charset={$cfg_soft_lang}");
    echo "<script> alert('成功更新币种！'); </script>";
}


$feetypearr = array();
$feetypearr[1] = '比例';
$feetypearr[2] = '实币';


require_once(DEDEADMIN."/templets/btc_coin_manage.htm");