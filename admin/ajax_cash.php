<?php
/**
 * 提款订单传递
 *
 * @version        $Id: ajax_cash.php 1 15:46 2013年9月20日 SZ $
 */
require_once(dirname(__FILE__).'/../include/common.inc.php');

//if($_SERVER["REMOTE_ADDR"]!=GetPayIP() && $_SERVER['HTTP_HOST']!="101.1.27.245") exit();

$checktime=preg_replace("#[^.0-9-]#", "", $checktime)?preg_replace("#[^.0-9-]#", "", $checktime):"0";

$dsql->SetQuery("SELECT * FROM #@__btctype WHERE coinhost=1");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	 $coinarr[$row->id]=$row->cointype;
	 $coinhost[] = "coinid=".$row->id;
}
if(!is_array($coinhost)) exit();
$addsql= implode(" or ",$coinhost);

$dsql->SetQuery("SELECT * FROM #@__btccash WHERE checktime>$checktime AND ($addsql) ORDER BY id limit 100");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$temparr=(array)$row;
	$temparr['username']=GetMemberID($temparr['userid']);
	$temparr['cointype']=$coinarr[$temparr['coinid']];
	$listarr[]=$temparr;
}
if($listarr!=""){
	//print_r($listarr);
	print_r(mchStrCode(json_encode($listarr),'ENCODE'));
}

/**
 *  获取用户名
 *
 */
function GetMemberID($mid)
{
    global $dsql;
    if($mid==0) return '0';
    $row = $dsql->GetOne("SELECT userid FROM #@__member WHERE mid='$mid' ");
    if(is_array($row))
    {
        return $row['userid'];
    }
    else
    {
        return '未知';
    }
}
/**
 *  钱包ip
 *
 */
function GetPayIP()
{
	global $dsql;
	$row = $dsql->GetOne("SELECT description FROM #@__payment WHERE enabled=1 AND cod=2");
	if(!is_array($row)){
		return 0;
	}else{
		return $row['description'];
	}
}

/**
 *  加密函数
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作EN加密
 * @return    string
 */
function mchStrCode($string,$action='DECODE')
{
    //$key    = substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['cfg_cookie_encode']),8,18);
    $key    = "xs2dw96e23rfv3245dfw27cw45";
	$string    = $action == 'ENCODE' ? $string : base64_decode($string);
    $len    = strlen($key);
    $code    = '';
    for($i=0; $i < strlen($string); $i++)
    {
        $k  = $i % $len;
        $code  .= $string[$i] ^ $key[$k];
    }
    $code = $action == 'DECODE' ? $code : base64_encode($code);
    return $code;
}



