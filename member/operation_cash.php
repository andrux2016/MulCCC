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
    else if($sta==1) return '已通过';
    else if($sta==-1) return '已取消';
}
function lenCat($str){
    if(strlen($str)>30) return '<span title=\''.$str.'\'>'.substr($str,0,30).'..<span>';
    else return $str;
}

if($dopost=='')
{
    $sql = "SELECT * FROM `#@__btccash` WHERE userid='".$cfg_ml->M_ID."'  ORDER BY id DESC";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/operation_cash.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
}
else if($dopost=='del')
{
	$deleteid=preg_replace("#[^0-9-]#", "", $deleteid);
	//扣除金额
	$sql="Select id,amount,fee,coinid From `#@__btccash` WHERE checked = '0' AND id ='{$deleteid}' AND userid='{$cfg_ml->M_ID}' ;";
	$rcash = $dsql->GetOne($sql);
	if(is_array($rcash)){
		//echo $rcash['amount'];
		//$rdel = $dsql->ExecuteNoneQuery("DELETE FROM `#@__btccash` WHERE checked = '0' AND id ='{$deleteid}' AND userid='{$cfg_ml->M_ID}'");
		$rdel = $dsql->ExecuteNoneQuery("Update #@__btccash Set checked = '-1' where checked = '0' AND id ='{$deleteid}' AND userid='{$cfg_ml->M_ID}'"); 
		
		
		if($rdel) $rsup = $dsql->ExecuteNoneQuery("Update #@__btccoin Set c_deposit=c_deposit+".$rcash['amount']."+".$rcash['fee']." where coinid = ".$rcash['coinid']." AND userid='".$cfg_ml->M_ID."'"); 
		if($rsup){
			showJson("成功撤销提现请求!","1");
			exit();
		}else{
			$rsin = $dsql->ExecuteNoneQuery("insert into #@__erradd(aid,mid,title,type,errtxt,oktxt,sendtime) values('$deleteid','{$cfg_ml->M_ID}','撤销提现错误','Update','返回提现款失败','应当返还".($rcash['amount']+$rcash['fee'])."','".time()."')"); 
			showJson("发还提现款失败，请联系管理员!","-1");
			exit();
		}
		
	}else{
		showJson("撤销失败!","-1");
		exit();
	}
	
}
/**
 *  提示信息
 */
function showJson($msg,$ruslt){
			$msgArray=array(  
			'showMsg' => $msg, 
			'ruslt' => $ruslt,
			);
			$json_string = json_encode($msgArray);  
			echo $json_string;
		}