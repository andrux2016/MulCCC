<?php
/**
 * 提成操作
 *
 * @version        $Id: btc_deduct_list.php 1 15:46 2013年10月3日 SZ $
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
            $query = "DELETE FROM `#@__btcdeduct` WHERE id='$n'";

            $dsql->ExecuteNoneQuery($query);

        }
        ShowMsg("成功删除指定的订单记录！",$ENV_GOBACK_URL);
        exit();
    }
    elseif ($dopost == 'adduser')
    {
        $nids = explode('`', $nid);
        foreach($nids as $n)
        {
			//给用户到账
			$rDed = $dsql->GetOne("Select userid,coinid,deduct From #@__btcdeduct Where id='$n' And adduser=0");
			if(is_array($rDed)){
				$query = "Update #@__btcdeduct Set adduser=1 WHERE id='$n' And adduser=0"; 
            	$dsql->ExecuteNoneQuery($query);
				$rCoin = $dsql->GetOne("Select coinid From #@__btccoin  Where userid='".$rDed['userid']."' And coinid='".$rDed['coinid']."' ");
				if(is_array($rCoin)){
					$upCoin = $dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."btccoin Set c_deposit=c_deposit+'".$rDed['deduct']."',edittime='".time()."' Where userid='".$rDed['userid']."' And coinid='".$rDed['coinid']."'"); 
				}else{
					$rnew = $dsql->ExecuteNoneQuery("Insert Into `".$cfg_dbprefix."btccoin`(userid,coinid,cointype,c_deposit,c_freeze,edittime) Values('".$rDed['userid']."','".$rDed['coinid']."','".GetsCoin($rDed['coinid'])."','".$rDed['deduct']."','0','".time()."')");
				}
			}
        }
        ShowMsg("成功结算的订单记录！",$ENV_GOBACK_URL);
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
  $addsql = "WHERE dealid='".$oid."'";
  break;  
case 2:
  $addsql = "WHERE userid='".GetUserID($oid)."'";
  break;
case 3:
  $addsql = "WHERE newuserid='".GetUserID($oid)."'";
  break;
default:
  $addsql = '';
}

if(isset($adduser))
{
    if(isset($addsql)) $addsql = "WHERE `adduser`='$adduser'";
	else $addsql = " AND `adduser`='$adduser'";
}



$sql = "SELECT * FROM #@__btcdeduct $addsql ORDER BY `id` DESC";

$dlist = new DataListCP();
if(isset($typeId)) $dlist->SetParameter("typeId",$typeId);
if(isset($oid)) $dlist->SetParameter("oid",$oid);
if(isset($adduser)) $dlist->SetParameter("adduser",$adduser);

$tplfile = DEDEADMIN."/templets/btc_deduct_list.htm";

//这两句的顺序不能更换
$dlist->SetTemplate($tplfile);      //载入模板
$dlist->SetSource($sql);            //设定查询SQLexit('dd');
$dlist->Display();

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

function GetsType($pid)
{
    if($pid==""){
		return "注册";
	}
	if($pid==0)
    {
        return "买";
    }
    elseif($pid==1)
    {
        return "卖";
    }elseif($pid==2)
    {
        return "提现";
    }
}
function GetsDeal($pid)
{
    if($pid==0)
    {
        return "未结算";
    }
    else
    {
        return "<font color='#00CC00'>已结算</font>";
    }
}


function GetMemberID($mid)
{
    global $dsql;
    if($mid==0) return '0';
    $row = $dsql->GetOne("SELECT joinip,userid FROM #@__member WHERE mid='$mid' ");
    if(is_array($row))
    {
        return "<a href='member_view.php?id={$mid}'>".$row['userid']."</a><br>".$row['joinip'];
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
