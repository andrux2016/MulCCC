<?php
/**
 * 会员留言管理
 *
 * @version        $Id: member_snsmsg.php 1 14:08 2013年7月19日 SZ $
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_memberguestbook');
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC."/common.func.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$dopost = empty($dopost)? "" : $dopost;
$uname = empty($uname)? "" : $uname;
$ways = empty($ways)? "" : $ways;

$sql = $where = "";
$mid = empty($mid) ? 0 : intval($mid);
if($mid>0) $where .= "AND mid='$mid' ";
if(!$ways=='' && !$body=='')
{
    $body = preg_replace ("#^(　| )+#i", '', $body);
    $body = preg_replace ("#(　| )+$#i", '', $body);
    switch ($ways) {
        
    case "userid": 
        $row=$dsql->GetOne("SELECT mid FROM #@__member WHERE userid='$body' LIMIT 1");
        $mid=$row['mid'];
        $where .="AND mid='$mid'";
    break;
    case "msg": 
        $where .="AND msg LIKE '%$body%'";
    break;
  }
}

//删除留言
if($dopost=="del")
{
    $bkurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "member_snsmsg.php";
    $ids = explode('`',$ids);
    $dquery = "";
    foreach($ids as $id)
    {
        if($dquery=="")
        {
            $dquery .= " id='$id' ";
        }
        else
        {
            $dquery .= " OR id='$id' ";
        }
    }
    if($dquery!="") $dquery = " WHERE ".$dquery;
    $dsql->ExecuteNoneQuery("DELETE FROM #@__member_snsmsg $dquery");
    ShowMsg("成功删除指定的记录！",$bkurl);
    exit();
}

//删除相同留言者的所有留言
else if( $dopost=="deluname" )
{
        $ids = preg_replace("#[^0-9,]#i", ',', $ids);
        $dsql->SetQuery("SELECT userid FROM `#@__member_snsmsg` WHERE id IN ($ids) ");
        $dsql->Execute();
        $unames = '';
        while($row = $dsql->GetArray())
        {
            $unames .= ($unames=='' ? " userid = '{$row['userid']}' " : " OR userid = '{$row['userid']}' ");
        }
        if($unames!='')
        {
            $query = "DELETE FROM `#@__member_snsmsg` WHERE $unames ";
            $dsql->ExecuteNoneQuery($query);
        }
        ShowMsg("成功删除指定相同留言者的所有留言!",$_COOKIE['ENV_GOBACK_URL'],0,500);
        exit();
}

//删除相同IP的所有留言
else if( $dopost=="delall" )
{
        $ids = preg_replace("#[^0-9,]#i", ',', $ids);
        $dsql->SetQuery("SELECT ip FROM `#@__member_snsmsg` WHERE id IN ($ids) ");
        $dsql->Execute();
        $ips = '';
        while($row = $dsql->GetArray())
        {
            $ips .= ($ips=='' ? " ip = '{$row['ip']}' " : " OR ip = '{$row['ip']}' ");
        }
        if($ips!='')
        {
            $query = "DELETE FROM `#@__member_snsmsg` WHERE $ips ";
            $dsql->ExecuteNoneQuery($query);
        }
        ShowMsg("成功删除指定相同IP的所有留言!",$_COOKIE['ENV_GOBACK_URL'],0,500);
        exit();
}
$sql = "SELECT * FROM #@__member_snsmsg WHERE 1=1 $where ORDER BY id DESC";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetTemplate(DEDEADMIN."/templets/member_snsmsg.htm");
$dlist->SetSource($sql);
$dlist->Display();