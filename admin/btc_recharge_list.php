<?php
/**
 * 订单操作
 *
 * @version        $Id: btc_recharge_list.php 1 15:46 2013年8月20日 SZ $
 */
 
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_Check,a_AccCheck');
require_once(DEDEINC.'/datalistcp.class.php');


$dsql->SetQuery("SELECT * FROM #@__btctype");
	$dsql->Execute();
    while($row = $dsql->GetObject())
	{
		 if($row->coinsign==1) $CoinBN .= "<input type=\"button\" name=\"ss".$row->id."\" value=\"".$row->cointype."\" style=\"width:50px;margin-right:6px\" onClick=\"location='btc_recharge_list.php?coinid=".$row->id."';\"  class='np coolbg'/>";
		 $coinarr[$row->id]=$row->cointype;
	}


if(isset($dopost))
{
    CheckPurview('sys_Data,a_Check,a_AccCheck');
    if($dopost == 'up')
    {
        $nids = explode('`',$nid);
        $wh = '';
        foreach($nids as $n)
        {
            if($wh=='') $wh = " WHERE id='$n' ";
            else $wh .= " OR id='$n' ";
        }
        $sql="UPDATE #@__btcrecharge SET `checked`='1' $wh ";
        $dsql->ExecuteNoneQuery($sql);
    }
	elseif($dopost == 're')
    {
        $nids = explode('`',$nid);
        $wh = '';
        foreach($nids as $n)
        {
            if($wh=='') $wh = " id='$n' ";
            else $wh .= " OR id='$n' ";
        }
        $sql="UPDATE #@__btcrecharge SET `checked`='0' WHERE ($wh) AND `adduser`=0";
        $dsql->ExecuteNoneQuery($sql);
    }
	elseif ($dopost == 'delete')
    {
        $nids = explode('`', $nid);
        foreach($nids as $n)
        {
            $query = "DELETE FROM `#@__btcrecharge` WHERE id='$n'";

            $dsql->ExecuteNoneQuery($query);

        }
        ShowMsg("成功删除指定的订单记录！",$ENV_GOBACK_URL);
        exit();
    }
    else
    {
        ShowMsg("不充许的操作范围！",$ENV_GOBACK_URL);
        exit();
    }
    ShowMsg("成功更改指定的订单记录！",$ENV_GOBACK_URL);
    exit();
}

$addsql = '';
//if(empty($dealid)) $dealid = 0;
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(isset($typeId))
{
    $typeId  = preg_replace("#[^-0-9A-Z]#", "", $typeId);
}
switch ($typeId)
{
case 1:
  $addsql = "WHERE txid='".$sid."'";
  break;  
case 2:
  $addsql = "WHERE userid='".GetUserID($sid)."'";
  break;
default:
  $addsql = '';
}


if(isset($coinid))
{
    if(isset($addsql)) $addsql = "WHERE `coinid`='$coinid' ";
	else $addsql = " AND `coinid`='$coinid'";
}


$sql = "SELECT * FROM #@__btcrecharge $addsql ORDER BY `rcgtime` DESC";

$dlist = new DataListCP();
if(isset($typeId)) $dlist->SetParameter("typeId",$typeId);
if(isset($txid)) $dlist->SetParameter("txid",$txid);
if(isset($dealtype)) $dlist->SetParameter("dealtype",$dealtype);
if(isset($coinid)) $dlist->SetParameter("coinid",$coinid);
$tplfile = DEDEADMIN."/templets/btc_recharge_list.htm";

//这两句的顺序不能更换
$dlist->SetTemplate($tplfile);      //载入模板
$dlist->SetSource($sql);            //设定查询SQLexit('dd');
$dlist->Display();


function GetsPayment($pid)
{
    global $dsql;
    $pid = intval($pid);
    $row = $dsql->GetOne("SELECT name FROM #@__payment WHERE id='$pid'");
    if(is_array($row))
    {
        return $row['name'];
    }
    else
    {
        return "未知";
    }
}



function GetMemberID($mid)
{
    global $dsql;
    if($mid==0) return '0';
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
function GetUserID($name)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT mid FROM #@__member WHERE userid='$name' ");
    if(is_array($row))
    {
        return $row['mid'];
    }
    else
    {
        return '0';
    }
}

function GetsChecked($pid)
{
    if($pid==1)
    {
        return "<font color=#00FF00>通过</font>";
    }
    else
    {
        return "未审";
    }
}
function GetsAddUser($pid)
{
    if($pid==1)
    {
        return "<font color=#00FF00>已入</font>";
    }
    else
    {
        return "未入";
    }
}

function GetsDealmark($pid)
{
    if($pid==1)
    {
        return "<font color=#00FF00>已到</font>";
    }elseif($pid==-1){
        return "<font color=#FFFFFF>错号</font>";
    }else{
        return "未入";
    }
}
