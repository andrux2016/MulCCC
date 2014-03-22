<?php
/**
 * 订单操作
 *
 * @version        $Id: btc_order_list.php 1 15:46 2013年8月20日 SZ $
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('shops_Operations');
require_once(DEDEINC.'/datalistcp.class.php');

if(isset($dopost))
{
    CheckPurview('shops_Operations_cpanel');
    if ($dopost == 'delete')
    {
        $nids = explode('`', $nid);
        foreach($nids as $n)
        {
            $query = "DELETE FROM `#@__btcorder` WHERE oid='$n'";

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
  $addsql = "WHERE oid='".$sid."'";
  break;  
case 2:
  $addsql = "WHERE userid='".GetUserID($sid)."'";
  break;
default:
  $addsql = '';
}

if(isset($dealtype))
{
    if(isset($addsql)) $addsql = "WHERE `dealtype`='$dealtype'";
	else $addsql = " AND `dealtype`='$dealtype'";
}
if(isset($coinid))
{
    if(isset($addsql)) $addsql = "WHERE (`coinid`='$coinid' OR `moneyid`='$coinid')";
	else $addsql = " AND (`coinid`='$coinid' OR `moneyid`='$coinid')";
}


$sql = "SELECT * FROM #@__btcorder $addsql ORDER BY `ordertime` DESC";

$dlist = new DataListCP();
if(isset($typeId)) $dlist->SetParameter("typeId",$typeId);
if(isset($sid)) $dlist->SetParameter("sid",$sid);
if(isset($dealtype)) $dlist->SetParameter("dealtype",$dealtype);
if(isset($coinid)) $dlist->SetParameter("coinid",$coinid);
$tplfile = DEDEADMIN."/templets/btc_order_list.htm";

//这两句的顺序不能更换
$dlist->SetTemplate($tplfile);      //载入模板
$dlist->SetSource($sql);            //设定查询SQLexit('dd');
$dlist->Display();



function GetsType($pid)
{
    if($pid==0)
    {
        return "买";
    }
    else
    {
        return "卖";
    }
}
function GetsCoin($pid)
{
    global $dsql;
    $pid = intval($pid);
    $row = $dsql->GetOne("SELECT cointype FROM #@__btctype WHERE id='$pid'");
    if(is_array($row))
    {
        return $row['cointype'];
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
function GetCoinBN()
{
    global $dsql;
    $dsql->SetQuery("SELECT * FROM #@__btctype WHERE coinsign=1 ");
	$dsql->Execute();
    while($row = $dsql->GetObject())
	{
		 $CoinBN .= "<input type=\"button\" name=\"ss".$row->id."\" value=\"".$row->cointype."\" style=\"width:50px;margin-right:6px\" onClick=\"location='btc_order_list.php?coinid=".$row->id."';\"  class='np coolbg'/>";
	}
	return $CoinBN;
}