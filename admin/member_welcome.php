<?php
/**
 * 会员类型
 *
 * @version        $Id: member_techarge_cards.php 1 14:14 2013年8月20日 SZ $
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
require_once(DEDEINC.'/datalistcp.class.php');
if(empty($dopost)) $dopost = "";

$dsql->SetQuery("SELECT id,cointype,coinsign FROM #@__btctype");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$coinTypeArr[$row->id] = $row->cointype;
	if($row->coinsign==1){
		$coinselect .= "<option value='".$row->id ."'>".$row->cointype ." </option>";
	}
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
        $pname =   ${'pname_'.$startID};
        $rank =    ${'rank_'.$startID};
        $money =   ${'money_'.$startID};
        $exptime = ${'exptime_'.$startID};
        if(isset(${'check_'.$startID}))
        {
            /*if($pname!='')
            {
                $query = "UPDATE #@__member_type SET pname='$pname',money='$money',rank='$rank',exptime='$exptime' WHERE aid='$aid'";
            }*/
			$query = "DELETE FROM #@__moneycard_welcome WHERE aid='$aid' ";
			header("Content-Type: text/html; charset={$cfg_soft_lang}");
    		echo "<script> alert('成功删除充值卡！'); </script>";
        }
        else
        {
            //$query = "DELETE FROM #@__member_type WHERE aid='$aid' ";
        }
        if($query!='')
        {
            $dsql->ExecuteNoneQuery($query);
        }
		
    }
    //增加新记录
    if(isset($check_new) && $numbers!='')
    {
		if($exptime_new>7000) $mtime=3530742291;
		else $mtime=strtotime('+'.$exptime_new.'day');
		//exit($mtime);
		$rechargeCodes = rechargeCode($numbers);
		foreach ($rechargeCodes as $key => $value){ 
			/*$ctid=$value['no'];
			$ctidlen=16-strlen($ctid);
			for($i=0;$i<$ctidlen;$i++){
				$ctid=$ctid."0";
			}
			$cardid=$value['pwd'];*/
			$ctid=$value['no'];
			$cardid=$value['pwd'];
			$query = "INSERT INTO #@__moneycard_welcome(ctid,coinid,mid,cardid,isexp,money,scores,stime,mtime,cardnote) VALUES('{$ctid}','{$coinid}','".$cuserLogin->getUserId()."','{$cardid}','1','{$money_new}','{$scores_new}',".time().",'{$mtime}','{$cardnote}');";
			$dsql->ExecuteNoneQuery($query);
		} 
		header("Content-Type: text/html; charset={$cfg_soft_lang}");
    	echo "<script> alert('成功生成".$numbers."个邀请码！'); </script>";
    }
}



$times[7320] = '终生';
$times[1098] = '三年';
$times[366] = '一年';
$times[183] = '半年';
$times[90] = '三个月';
$times[30] = '一个月';
$times[7] = '一周';



$sql = "Select * From #@__moneycard_welcome ORDER BY `stime` DESC";

$dlist = new DataListCP();

if(isset($aid)) $dlist->SetParameter("aid",$aid);

$tplfile = DEDEADMIN."/templets/member_welcome.htm";

//这两句的顺序不能更换
$dlist->SetTemplate($tplfile);      //载入模板
$dlist->SetSource($sql);            //设定查询SQLexit('dd');
$dlist->Display();



//require_once(DEDEADMIN."/templets/member_recharge_cards.htm");

/**
 * 生成充值卡密码
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作
 * @return    string
 */
function rechargeCode($nums)
{
    $numLen=10;
	$pwdLen=10;
	$c=$nums;//生成1组卡号密码
	$sTempArr=range(0,9);
	$sNumArr=array_merge($sTempArr,range('A','Z'));
	$sPwdArr=array_merge($sTempArr,range('A','Z'));
	
	$cards=array();
	for($x=0;$x< $c;$x++){
	  $tempNumStr=array();
	  for($i=0;$i< $numLen;$i++){
		$tempNumStr[]=array_rand($sNumArr);
	  }
	  $tempPwdStr=array();
	  for($i=0;$i< $pwdLen;$i++){
		$tempPwdStr[]=$sPwdArr[array_rand($sPwdArr)];  
		$tempPwdStr2[]=$sPwdArr[array_rand($sPwdArr)];   
	  }
	  $cards[$x]['no']=implode('',$tempPwdStr2);
	  $cards[$x]['pwd']=implode('',$tempPwdStr);
	}
	array_unique($cards);
	//print_r($cards);
    return $cards;
}

/**
 *  加密函数
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作
 * @return    string
 */
function mchStrCode($string,$action='ENCODE')
{
    //$key = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
	$key = "e87drga49ae10f3c87";
    $string    = $action == 'ENCODE' ? $string : base64_decode($string);
    $len    = strlen($key);
    $code    = '';
    for($i=0; $i<strlen($string); $i++)
    {
        $k        = $i % $len;
        $code  .= $string[$i] ^ $key[$k];
    }
    $code = $action == 'DECODE' ? $code : base64_encode($code);
    return $code;
}


function GetMemberID($mid)
{
    global $dsql;
    if($mid==0) return '';
    $row = $dsql->GetOne("SELECT userid FROM #@__member WHERE mid='$mid' ");
    if(is_array($row))
    {
        return "<a href='member_view.php?id={$mid}'>".$row['userid']."</a>";
    }
    else
    {
        return '0';
    }
}