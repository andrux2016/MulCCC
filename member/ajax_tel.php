
<?php
/**
 * @version        $Id: ajax_trans.php 1 8:38 2013年8月29日Z
 */
require_once(dirname(__FILE__)."/config.php");

$telNumber=preg_replace("#[^0-9-]#", "", $telNumber) ? preg_replace("#[^0-9-]#", "", $telNumber) : "";
$sessName=$telNumber.'tel';
if($cfg_ml->M_ID==""){
$out=array('code'=>0,'msg'=>"登陆过期，重新登陆！");
showJson($out);
}else{
	if($action=="code"){
		
		$userhash = GetEmailCode($sessName,"new");
		if($telNumber=="" || strlen($telNumber) != 11){
			$out=array('code'=>0,'msg'=>"号码错误！");
			showJson($out);
			exit();
		}
		$row = $dsql->GetOne("SELECT mid,tel FROM `#@__member_person` WHERE tel='$telNumber' ");
    	if(is_array($row))
    	{
			if($row['mid']!=$cfg_ml->M_ID){
				$out=array('code'=>0,'msg'=>"手机号已绑定其他账号！");
				showJson($out);
				exit();
			}
		}
		
		try {
		
			$client = new SoapClient ( "http://106.ihuyi.com/webservice/sms.php?WSDL", array ('trace' => 1, 'uri' => 'http://106.ihuyi.com/' ) );	

			$data['account'] = "cf_bts";
			$data['password'] ="R7DRK6";
			//$data['content'] = "您的验证码是：".$userhash."。请不要把验证码泄露给其他人。[".$cfg_webname."]";	
			$data['content'] = '您的验证码是：'.$userhash.'。请不要把验证码泄露给其他人。';	
			$data['mobile'] = $telNumber;
			
			$out = $client->Submit($data);
			/*echo ('<pre>');
			print_r($out);
			echo ('</pre>');*/
			$out=(array)$out;
			//$out=array('code'=>2,'msg'=>$_SESSION[$sessName]);
			showJson($out['SubmitResult']);
			
		} catch (SoapFault $fault){
			//echo "Error: ",$fault->faultcode,", string: ",$fault->faultstring;
			$out=array('code'=>0,'msg'=>$fault->faultcode."-".$fault->faultstring);
			showJson($out);
		}
	}elseif($action=="check"){
		if(empty($telCode))
		{
			$out=array('code'=>0,'msg'=>"请输入短信验证码！");
			showJson($out);
			exit();
		}
		$emailcode=GetEmailCode($sessName);
		if(!isset($emailcode)){
			$out=array('code'=>0,'msg'=>'短信验证码过期！');
			showJson($out); 
			exit();
		}
		if($telCode!=$emailcode){
			$userip=$telNumber;
			GetPwErrNums($telNumber.'err',"add");
			//$_SESSION[$userip.'err']=$_SESSION[$userip.'err']+1;
			$errtimes=5-GetPwErrNums($telNumber.'err');
			$out=array('code'=>0,'msg'=>$telCode."=".$emailcode."短信验证码错误！您还有".$errtimes."次机会","-1");
			showJson($out); 
			exit();
		}
		GetPwErrNums($sessName,"unset");
		GetPwErrNums($telNumber.'err',"unset");
		//unset($_SESSION[$sessName]);
		//unset($_SESSION[$telNumber.'err']);
		$dsql->ExecuteNoneQuery("Update ".$cfg_dbprefix."member_person Set tel='".$telNumber."' Where mid='".$cfg_ml->M_ID."'"); 
		$out=array('code'=>2,'msg'=>"验证成功！");
			showJson($out);
	}elseif($cfg_ml->M_Tel==""){
		$showhtml="<div id='googleDiv' style='float:left; margin-bottom:30px; margin-top:20px'><ul style='float:left;'><li style='border-bottom:1px solid #000000;'><strong>为了您的账号安全，请绑定手机</strong></li>";
		$showhtml.="<li style='float:left; margin-left:10px;width:360px'>";
		$showhtml.="手机号码：<br><input name='telNumber' id='telNumber' /><br>";
		$showhtml.="验证码：<br><input id='telCode' type='text' /><span id='showtel'><input type='button' onclick='telCode();' value=' 发送验证码 ' /></span><div id='buntel' style='display:none'><input type='button' onclick='telCode();' value=' 发送验证码 ' /></div><div id='waitdiv' style='display:none'>60秒后可以重发</div><br><br><input type='button' onclick='telSub();' value=' 提  交 ' />";
		$showhtml.="<span id='msgDiv'></span><br><br></li></ul></div>";
	}else{
		$showhtml = "您已经绑定手机！";
	}
}

echo $showhtml;

function showJson($out){
	//print_r($out);
	$json_string = json_encode($out);  
	echo $json_string;
}
?>
