<?php
require_once(dirname(__FILE__)."/../member/config.php");
function coinQuery ($cointype,$method,$params)
{
	$coinhost=$cointype?GetCoinHost($cointype):exit();
	if($coinhost==1){
		$params=mchStrCode(json_encode($params),'ENCODE');//encode
		$coinip=GetPayIP();
		$handle = fopen("http://".$coinip."/payAddress.php?params=".$params."&method=".$method."&cointype=".$cointype, "rb");
		$contents = stream_get_contents($handle);
		fclose($handle);
		$obj = json_decode(mchStrCode($contents,'DECODE'));
		$trans=(array)$obj;
		return $trans;
	}elseif($coinhost==0){
		return rpcQuery($cointype,$method,$params);
	}
}
/**
 *  海퍊 *
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
 *  衱Җ֐ŏ꠪
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
 *  㜺퍊 *
 * @access    public
 * @param     string  $string  ז䮍
 * @param     string  $action  締EN㜍
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
