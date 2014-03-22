<?php
/**
 * @version        $Id: ajax_trans.php 1 8:38 2013年8月29日Z
 */
 

require_once(dirname(__FILE__)."/config.php");


if($dopost=="send"){
	if($cfg_ml->M_ID){
		$usermid=$cfg_ml->M_ID;
	}else{
		showJson("请重新登录",'flase');
		exit();
	}
	if($cfg_ml->M_Spacesta<="-2"){
		showJson("您未验证邮箱或被禁止发言",'flase');
		exit();
	}
	$rom = $dsql->GetOne("Select * From ".$cfg_dbprefix."member_snsmsg Where mid='{$usermid}' ORDER BY id DESC");
	if(is_array($rom)){
		if((time()-$rom['sendtime'])<5) {
			showJson("提交太频繁！请稍后！",'flase');
			exit();
		}
	} 
	
	$msg=filterhtml($msg);
	$rsnew = $dsql->ExecuteNoneQuery("insert into ".$cfg_dbprefix."member_snsmsg(mid,userid,sendtime,msg,ip) values('".$usermid."','".$cfg_ml->M_LoginID."','".time()."','".$msg."','".GetIP()."')");
	$rsnewid = $dsql->GetLastID();  
	$userarr=explode("@",$cfg_ml->M_LoginID);
	$userid=substr($userarr['0'],0,3)."*@".substr($userarr['1'],0,3)."*";
	$msgArray[]=array(
				'id' => $rsnewid,
				'mid' => $usermid,
				'userid' => $userid,
				'sendtime' => time(),
				'msg' => $msg
			);
	showJson($msgArray,'ture');
}elseif($dopost=="read"){
	$id=preg_replace("#[^0-9-]#", "", $id)?preg_replace("#[^0-9-]#", "", $id):"";
	if($id!="") $addsql="WHERE id > $id";
	$dsql->SetQuery("SELECT * FROM ".$cfg_dbprefix."member_snsmsg $addsql ORDER BY id DESC Limit 50");
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
		'showMsg' => array_reverse($msgArray), 
		'ruslt' => $ruslt
		);
	$json_string = json_encode($msgshow);  
	echo $json_string;
}
function showJson($msgArray,$ruslt){
	$msgshow=array(  
		'showMsg' => $msgArray, 
		'ruslt' => $ruslt
		);
	$json_string = json_encode($msgshow);  
	echo $json_string;

}




function filterhtml($str)
{
 $str=stripslashes($str);
 
 $str=preg_replace("/\s+/", ' ', $str); //过滤多余回车 
 $str=preg_replace("/<[ ]+/si",'<',$str); //过滤<__("<"号后面带空格) 
 
 $str=preg_replace("/<\!--.*?-->/si",'',$str); //注释 
 $str=preg_replace("/<(\!.*?)>/si",'',$str); //过滤DOCTYPE 
 $str=preg_replace("/<(\/?html.*?)>/si",'',$str); //过滤html标签 
 $str=preg_replace("/<(\/?head.*?)>/si",'',$str); //过滤head标签 
 $str=preg_replace("/<(\/?meta.*?)>/si",'',$str); //过滤meta标签 
 $str=preg_replace("/<(\/?body.*?)>/si",'',$str); //过滤body标签 
 $str=preg_replace("/<(\/?link.*?)>/si",'',$str); //过滤link标签 
 $str=preg_replace("/<(\/?form.*?)>/si",'',$str); //过滤form标签 
 $str=preg_replace("/cookie/si","COOKIE",$str); //过滤COOKIE标签 
 
 $str=preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si",'',$str); //过滤applet标签 
 $str=preg_replace("/<(\/?applet.*?)>/si",'',$str); //过滤applet标签 
 
 $str=preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si",'',$str); //过滤style标签 
 $str=preg_replace("/<(\/?style.*?)>/si",'',$str); //过滤style标签 
 
 $str=preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si",'',$str); //过滤title标签 
 $str=preg_replace("/<(\/?title.*?)>/si",'',$str); //过滤title标签 
 
 $str=preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si",'',$str); //过滤object标签 
 $str=preg_replace("/<(\/?objec.*?)>/si",'',$str); //过滤object标签 
 
 $str=preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si",'',$str); //过滤noframes标签 
 $str=preg_replace("/<(\/?noframes.*?)>/si",'',$str); //过滤noframes标签 
 
 $str=preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si",'',$str); //过滤frame标签 
 $str=preg_replace("/<(\/?i?frame.*?)>/si",'',$str); //过滤frame标签 
 
 $str=preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si",'',$str); //过滤script标签 
 $str=preg_replace("/<(\/?script.*?)>/si",'',$str); //过滤script标签 
 $str=preg_replace("/javascript/si","JAVASCRIPT",$str); //过滤script标签 
 $str=preg_replace("/vbscript/si","VBSCRIPT",$str); //过滤script标签 
 $str=preg_replace("/on([a-z]+)\s*=/si","ON\\1=",$str); //过滤script标签 
 $str=preg_replace("/&#/si","&＃",$str); //过滤script标签，如javAsCript:alert('aabb) 
 
 $str=addslashes($str);
 return($str);
}
?>

