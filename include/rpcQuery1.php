<?php
require_once(dirname(__FILE__)."/../member/config.php");
//require_once("../../data/rpcQuery.php");
function coinQuery ($cointype,$method,$params)
{
	$coinhost=$cointype?GetCoinHost($cointype):exit();
	if($coinhost==1){
		$params=mchStrCode(json_encode($params),'ENCODE');//提交的参数，用户名	
		$coinip=GetPayIP();
		$handle = fopen("http://".$coinip."/payAddress.php?params=".$params."&method=".$method."&cointype=".$cointype, "rb"); 
		$contents = stream_get_contents($handle); 
		fclose($handle);
		$obj = json_decode(mchStrCode($contents,'DECODE'));
		$trans=(array)$obj;
		//return "http://".$coinip."/payAddress.php?params=".$params."&method=".$method."&cointype=".$cointype;
		//return $trans;
		return $contents."--".$obj;
	}elseif($coinhost==0){
		return rpcQuery($cointype,$method,$params);
	}
}
/**
 *  充值方式
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
 *  获取币种信息
 *
 */
function GetCoinHost($cointype)
{
    global $dsql;
    if($cointype=="") return '0';
    $row = $dsql->GetOne("SELECT coinhost FROM #@__btctype WHERE cointype='$cointype' ");
    if(is_array($row))
    {
        return $row['coinhost'];
    }
    else
    {
        return '0';
    }
}

		
/**
 *  加密函数
 *
 * @access    public
 * @param     string  $string  字符串
 * @param     string  $action  操作 EN加密
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
        $k        = $i % $len;
        $code  .= $string[$i] ^ $key[$k];
    }
    $code = $action == 'DECODE' ? $code : base64_encode($code);
    return $code;
}
?>