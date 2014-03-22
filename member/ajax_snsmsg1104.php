<?php
/**
 * @version        $Id: ajax_trans.php 1 8:38 2013年8月29日Z
 */
require_once(dirname(__FILE__)."/config.php");


if($dopost=="send"){

	$usermid=$cfg_ml->M_ID?$cfg_ml->M_ID:exit("请重新登录");
	$rom = $dsql->GetOne("Select * From ".$cfg_dbprefix."member_snsmsg Where userid={$usermid}");
	if(is_array($rom)){
		if(($rom['sendtime']-time())<5) exit("提交太频繁！请稍后！");
	} 
	$msg=safe_string($msg);
	$rsnew = $dsql->ExecuteNoneQuery("insert into ".$cfg_dbprefix."member_snsmsg(mid,userid,sendtime,msg,ip) values('".$usermid."','".$cfg_ml->M_LoginID."','".time()."','".$msg."','".GetIP()."')");
	echo "提交成功！";
}elseif($dopost=="read"){
	$id=preg_replace("#[^0-9-]#", "", $id)?preg_replace("#[^0-9-]#", "", $id):"";
	if($id!="") $addsql="WHERE id>$id";
	$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."member_snsmsg $addsql ORDER BY id DESC Limit 100");
$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		if($row->id != 1){
			$userarr=explode("@",$row->userid);
			//$usercom=substr(strrchr($row->userid, "@"), 0);
			$userid=substr($userarr['0'],0,3)."*@".substr($userarr['1'],0,3)."*";
			$msgArray[]=array(
				'id' => $row->id,
				'mid' => $row->mid,
				'userid' => $userid,
				'sendtime' => $row->sendtime,
				'msg' => $row->msg
			);
		}
	}
	$ruslt='ture';
	$msgshow=array(  
		'showMsg' => $msgArray, 
		'ruslt' => $ruslt
		);
	$json_string = json_encode($msgshow);  
	echo $json_string;
}

?>

