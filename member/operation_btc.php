<?php 
/**
 * 操作
 * 
 * @version        $Id: operation_btc.php 1 8:38 2010年7月9日Z tianya $
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckRank(0,0);
CheckTxPdw();
$menutype = 'mydede';
$menutype_son = 'op';
setcookie("ENV_GOBACK_URL",GetCurUrl(),time()+3600,"/");
if(!isset($dopost)) $dopost = '';

/**
 *  获取状态
 *
 * @param     string  $sta  状态ID
 * @return    string
 */
 
 $dsql->SetQuery("SELECT * FROM #@__btctype ");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$coinarr[$row->id]=$row->cointype;
}
 
function GetSta($sta){
    if($sta==0) return '处理中';
    else if($sta==1) return '成功';
    else if($sta==-1) return '已取消';
}

function lenCat($str){
    if(strlen($str)>15) return '<span title=\''.$str.'\'>'.substr($str,0,15).'..<span>';
    else return $str;
}

if($dopost=='')
{
    $sql = "SELECT * FROM `#@__btcrecharge` WHERE userid='".$cfg_ml->M_ID."'  ORDER BY id DESC";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/operation_btc.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
}
else if($dopost=='del')
{
    //$ids = preg_replace("#[^0-9,]#", "", $ids);
    //$query = "DELETE FROM `#@__btcrecharge` WHERE checked='0' AND id ='{$deleteid}' AND userid='{$cfg_ml->M_ID}'";
    //$dsql->ExecuteNoneQuery($query);
    //ShowMsg("成功删除指定的交易记录!","operation_btc.php");
    exit();
}
