<?php
/**
 * 系统核心函数存放文件
 * @version        $Id: common.func.php 4 16:39 2013年10月6日SZ $
 */
if(!defined('DEDEINC')) exit('dedecms');

/**
 *  载入小助手,系统默认载入小助手
 *  在/data/helper.inc.php中进行默认小助手初始化的设置
 *  使用示例:
 *      在开发中,首先需要创建一个小助手函数,目录在\include\helpers中
 *  例如,我们创建一个示例为test.helper.php,文件基本内容如下:
 *  <code>
 *  if ( ! function_exists('HelloDede'))
 *  {
 *      function HelloDede()
 *      {
 *          echo "Hello! Dede...";
 *      }
 *  }
 *  </code>
 *  则我们在开发中使用这个小助手的时候直接使用函数helper('test');初始化它
 *  然后在文件中就可以直接使用:HelloDede();来进行调用.
 *
 * @access    public
 * @param     mix   $helpers  小助手名称,可以是数组,可以是单个字符串
 * @return    void
 */
 

 
$_helpers = array();
function helper($helpers)
{
    //如果是数组,则进行递归操作
    if (is_array($helpers))
    {
        foreach($helpers as $dede)
        {
            helper($dede);
        }
        return;
    }

    if (isset($_helpers[$helpers]))
    {
        continue;
    }
    if (file_exists(DEDEINC.'/helpers/'.$helpers.'.helper.php'))
    { 
        include_once(DEDEINC.'/helpers/'.$helpers.'.helper.php');
        $_helpers[$helpers] = TRUE;
    }
    // 无法载入小助手
    if ( ! isset($_helpers[$helpers]))
    {
        exit('Unable to load the requested file: helpers/'.$helpers.'.helper.php');                
    }
}

/**
 *  控制器调用函数
 *
 * @access    public
 * @param     string  $ct    控制器
 * @param     string  $ac    操作事件
 * @param     string  $path  指定控制器所在目录
 * @return    string
 */
function RunApp($ct, $ac = '',$directory = '')
{
    
    $ct = preg_replace("/[^0-9a-z_]/i", '', $ct);
    $ac = preg_replace("/[^0-9a-z_]/i", '', $ac);
        
    $ac = empty ( $ac ) ? $ac = 'index' : $ac;
	if(!empty($directory)) $path = DEDECONTROL.'/'.$directory. '/' . $ct . '.php';
	else $path = DEDECONTROL . '/' . $ct . '.php';
        
	if (file_exists ( $path ))
	{
		require $path;
	} else {
		 if (DEBUG_LEVEL === TRUE)
        {
            trigger_error("Load Controller false!");
        }
        //生产环境中，找不到控制器的情况不需要记录日志
        else
        {
            header ( "location:/404.html" );
            die ();
        }
	}
	$action = 'ac_'.$ac;
    $loaderr = FALSE;
    $instance = new $ct ( );
    if (method_exists ( $instance, $action ) === TRUE)
    {
        $instance->$action();
        unset($instance);
    } else $loaderr = TRUE;
        
    if ($loaderr)
    {
        if (DEBUG_LEVEL === TRUE)
        {
            trigger_error("Load Method false!");
        }
        //生产环境中，找不到控制器的情况不需要记录日志
        else
        {
            header ( "location:/404.html" );
            die ();
        }
    }
}

/**
 *  载入小助手,这里用户可能载入用helps载入多个小助手
 *
 * @access    public
 * @param     string
 * @return    string
 */
function helpers($helpers)
{
    helper($helpers);
}

//兼容php4的file_put_contents
if(!function_exists('file_put_contents'))
{
    function file_put_contents($n, $d)
    {
        $f=@fopen($n, "w");
        if (!$f)
        {
            return FALSE;
        }
        else
        {
            fwrite($f, $d);
            fclose($f);
            return TRUE;
        }
    }
}

/**
 *  显示更新信息
 *
 * @return    void
 */
function UpdateStat()
{
    include_once(DEDEINC."/inc/inc_stat.php");
    return SpUpdateStat();
}

$arrs1 = array(0x63,0x66,0x67,0x5f,0x70,0x6f,0x77,0x65,0x72,0x62,0x79);
$arrs2 = array(0x20,0x3c,0x61,0x20,0x68,0x72,0x65,0x66,0x3d,0x68,0x74,0x74,0x70,0x3a,0x2f,0x2f,
0x77,0x77,0x77,0x2e,0x64,0x65,0x64,0x65,0x63,0x6d,0x73,0x2e,0x63,0x6f,0x6d,0x20,0x74,0x61,0x72,
0x67,0x65,0x74,0x3d,0x27,0x5f,0x62,0x6c,0x61,0x6e,0x6b,0x27,0x3e,0x50,0x6f,0x77,0x65,0x72,0x20,
0x62,0x79,0x20,0x44,0x65,0x64,0x65,0x43,0x6d,0x73,0x3c,0x2f,0x61,0x3e);

/**
 *  短消息函数,可以在某个动作处理后友好的提示信息
 *
 * @param     string  $msg      消息提示信息
 * @param     string  $gourl    跳转地址
 * @param     int     $onlymsg  仅显示信息
 * @param     int     $limittime  限制时间
 * @return    void
 */
function ShowMsg($msg, $gourl, $onlymsg=0, $limittime=0)
{
    if(empty($GLOBALS['cfg_plus_dir'])) $GLOBALS['cfg_plus_dir'] = '..';

    $htmlhead  = "<html>\r\n<head>\r\n<title>提示信息</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\" />\r\n";
    $htmlhead .= "<base target='_self'/>\r\n<style>div{line-height:160%;}</style></head>\r\n<body leftmargin='0' topmargin='0' bgcolor='#FFFFFF'>".(isset($GLOBALS['ucsynlogin']) ? $GLOBALS['ucsynlogin'] : '')."\r\n<center>\r\n<script>\r\n";
    $htmlfoot  = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";

    $litime = ($limittime==0 ? 1000 : $limittime);
    $func = '';

    if($gourl=='-1')
    {
        if($limittime==0) $litime = 5000;
        $gourl = "javascript:history.go(-1);";
    }

    if($gourl=='' || $onlymsg==1)
    {
        $msg = "<script>alert(\"".str_replace("\"","“",$msg)."\");</script>";
    }
    else
    {
        //当网址为:close::objname 时, 关闭父框架的id=objname元素
        if(preg_match('/close::/',$gourl))
        {
            $tgobj = trim(preg_replace('/close::/', '', $gourl));
            $gourl = 'javascript:;';
            $func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
        }
        
        $func .= "      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='$gourl'; pgo=1; }
      }\r\n";
        $rmsg = $func;
        $rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border:1px solid #DADADA;'>";
        $rmsg .= "<div style='padding:6px;font-size:12px;border-bottom:1px solid #DADADA;background:#DBEEBD url({$GLOBALS['cfg_plus_dir']}/img/wbg.gif)';'><b>提示信息！</b></div>\");\r\n";
        $rmsg .= "document.write(\"<div style='height:130px;font-size:10pt;background:#ffffff'><br />\");\r\n";
        $rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");\r\n";
        $rmsg .= "document.write(\"";
        
        if($onlymsg==0)
        {
            if( $gourl != 'javascript:;' && $gourl != '')
            {
                $rmsg .= "<br /><a href='{$gourl}'>如果你的浏览器没反应，请点击这里...</a>";
                $rmsg .= "<br/></div>\");\r\n";
                $rmsg .= "setTimeout('JumpUrl()',$litime);";
            }
            else
            {
                $rmsg .= "<br/></div>\");\r\n";
            }
        }
        else
        {
            $rmsg .= "<br/><br/></div>\");\r\n";
        }
        $msg  = $htmlhead.$rmsg.$htmlfoot;
    }
    echo $msg;
}




/**
 *  获取验证码的session值
 *
 * @return    string
 */
function GetCkVdValue()
{
	@session_id($_COOKIE['PHPSESSID']);
    @session_start();
    return isset($_SESSION['securimage_code_value']) ? $_SESSION['securimage_code_value'] : '';
}

/**
 *  PHP某些版本有Bug，不能在同一作用域中同时读session并改注销它，因此调用后需执行本函数
 *
 * @return    void
 */
function ResetVdValue()
{
    @session_start();
    $_SESSION['securimage_code_value'] = '';
}

/**
 *  获取错误次数的session值
 *
 * @return    string
 */
function GetPwErrNums($sessName,$type="achieve")
{
	//preg_replace("#[^0-9a-zA-Z-]#", "bSz",$cfg_ml->M_LoginID);
	@session_id($_COOKIE['PHPSESSID']);
    @session_start();
	if($type=="add"){
    	return isset($_SESSION[$sessName]) ? $_SESSION[$sessName]++ : $_SESSION[$sessName]=1;
	}elseif($type=="unset"){
		unset($_SESSION[$sessName]);
	}
	return isset($_SESSION[$sessName]) ? $_SESSION[$sessName] : $_SESSION[$sessName]=1;
}

/**
 *  获取邮件验证码的session值
 *
 * @return    string
 */
function GetEmailCode($sessName,$type="achieve")
{
	//preg_replace("#[^0-9a-zA-Z-]#", "bSz",$cfg_ml->M_LoginID);
	@session_id($_COOKIE['PHPSESSID']);
    @session_start();
	if($type=="new"){
		$_SESSION[$sessName]=rand(10000,99999);
	}
    return isset($_SESSION[$sessName]) ? $_SESSION[$sessName] : '';
}


// 自定义函数接口
// 这里主要兼容早期的用户扩展,v5.7之后我们建议使用小助手helper进行扩展
if( file_exists(DEDEINC.'/extend.func.php') )
{
    require_once(DEDEINC.'/extend.func.php');
}



/**
 *  获取用户端的账户余额
 *
 * @return    array
 */
function Getdeposit($coinid,$mid,$moneyid=1,$market=1){ 
	global $dsql;
	$mid=preg_replace("#[^0-9-]#", "", $mid) ? preg_replace("#[^0-9-]#", "", $mid) : "";
	$market=preg_replace("#[^0-9-]#", "", $market) ? preg_replace("#[^0-9-]#", "", $market) : 1;
	$moneyid=preg_replace("#[^0-9-]#", "", $moneyid) ? preg_replace("#[^0-9-]#", "", $moneyid) : 1;
	$coinid=preg_replace("#[^0-9-]#", "", $coinid) ? preg_replace("#[^0-9-]#", "", $coinid) : "";
	if($mid=="") return ""; 
	if($coinid!="") $addsql = "AND coinid = ".$coinid."";
	$sql="Select coinid,cointype,c_deposit,c_freeze From #@__btccoin where userid='".$mid."' $addsql ";
	$dsql->SetQuery($sql);
	$dsql->Execute();
	while($rcoin = $dsql->GetObject())
	{
		$row = $dsql->GetOne("Select uprice From #@__btcdeal where coinid='".$rcoin->coinid."' AND moneyid='".$moneyid."' AND market='".$market."' ORDER BY id DESC");
		if(!is_array($row)){
			if($rcoin->coinid==$moneyid) $last_rate=1;
			else $last_rate=0;
		}else{
			if($rcoin->coinid==$moneyid) $last_rate=1;
			elseif($row['uprice']) $last_rate=$row['uprice'];
			else $last_rate=0;
		}
		$coinshow[] = array(
			$rcoin->cointype,
			$rcoin->c_deposit,
			$rcoin->c_freeze,
			$rcoin->coinid,
			$last_rate*($rcoin->c_deposit+$rcoin->c_freeze)
		);
		
	}
	return $coinshow; 
} 

/*
 * 最新价格
 * 
 * @return    array
*/
function FunNewRate($coinid,$moneyid,$market=1){
	global $dsql;
	$market=preg_replace("#[^0-9-]#", "", $market) ? preg_replace("#[^0-9-]#", "", $market) : 1;
	$moneyid=preg_replace("#[^0-9-]#", "", $moneyid) ? preg_replace("#[^0-9-]#", "", $moneyid) : 1;
	$coinid=preg_replace("#[^0-9-]#", "", $coinid) ? preg_replace("#[^0-9-]#", "", $coinid) : 2;
	$tikarr=array(  
				'high' => 0, 
				'low' => 0, 
				'vol' => 0, 
				'last_rate' => 0, 
				'ask' => 0, 
				'bid' => 0
			);
	if($coinid==$moneyid){
		$tikarr=array(  
				'high' => 1, 
				'low' => 1, 
				'vol' => 0, 
				'last_rate' => 1, 
				'ask' => 1, 
				'bid' => 1
			);
		return $tikarr;
	}
	$rbuy = $dsql->GetOne("Select uprice From #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND dealtype='0' ORDER BY uprice DESC");
	$rsell = $dsql->GetOne("Select uprice From #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND dealtype='1' ORDER BY uprice");
	$dsql->SetQuery("SELECT id,btccount,uprice,dealtype,dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND dealtime>".strtotime("-1 day")." ORDER BY id DESC");
	$dsql->Execute();
	$one=1;
	while($rod = $dsql->GetObject())
	{
		if($one==1){
			$tikarr=array(  
				'high' => $rod->uprice/1, 
				'low' => $rod->uprice/1, 
				'vol' => floor($rod->btccount*100)/100, 
				'last_rate' => $rod->uprice/1, 
				'ask' => $rsell['uprice']/1, 
				'bid' => $rbuy['uprice']/1 
			);
			$one=2;
		}

		$tikarr=array(
			'high' => $rod->uprice > $tikarr['high'] ? $rod->uprice/1 : $tikarr['high'], 
			'low' => $rod->uprice < $tikarr['low'] ? $rod->uprice/1 : $tikarr['low'], 
			'vol' => $tikarr['vol']+$rod->btccount/1, 
			'last_rate' => $tikarr['last_rate'],
			'ask' => $tikarr['ask'],
			'bid' => $tikarr['bid']
		);
	}
	if($tikarr['vol']==0){
		$dsql->SetQuery("SELECT id,btccount,uprice,dealtype,dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND $market='1' ORDER BY id DESC limit 100");
		$dsql->Execute();
		$one=1;
		while($rod = $dsql->GetObject())
		{
			if($one==1){
				$tikarr=array(  
					'high' => $rod->uprice/1, 
					'low' => $rod->uprice/1, 
					'vol' => floor($rod->btccount*100)/100, 
					'last_rate' => $rod->uprice/1, 
					'ask' => $rsell['uprice']/1, 
					'bid' => $rbuy['uprice']/1 
				);
				$one=2;
			}
	
			$tikarr=array(
				'high' => $rod->uprice > $tikarr['high'] ? $rod->uprice/1 : $tikarr['high'], 
				'low' => $rod->uprice < $tikarr['low'] ? $rod->uprice/1 : $tikarr['low'], 
				'vol' => $tikarr['vol']+$rod->btccount/1, 
				'last_rate' => $tikarr['last_rate'],
				'ask' => $tikarr['ask'],
				'bid' => $tikarr['bid']
			);
		}
	}
	
	return $tikarr;
}
/*
 * 取消挂单
 * 
 * @return    true,flase
*/
function FunCancle($mid,$tid,$market=1){
	global $dsql;
	
	if($mid=="") return "flase";
	$tid=preg_replace("#[^0-9A-Za-z-]#", "", $tid)?preg_replace("#[^0-9A-Za-z-]#", "", $tid):"";
	if($tid=="") return "flase";
	$query = @mysql_query("lock tables btc_btcorder write,btc_btccoin write;") //锁
	or die("lock"); 

	$rord = $dsql->GetOne("Select btccount,uprice,bkage,dealtype,moneyid,coinid From #@__btcorder where oid = '$tid' And userid = '".$mid."' AND market=$market");
	if(is_array($rord)){
		if($rord['dealtype']==0){//买入
			if($rord['bkage']==0){
				$oprice=($rord['uprice']*$rord['btccount']);
			}else{
				$oprice=($rord['uprice']*$rord['btccount'])+($rord['uprice']*$rord['btccount'])/(1/$rord['bkage']+1);
			}	
		//$oprice=($rord['uprice']*$rord['btccount'])+feeFun(($rord['uprice']*$rord['btccount']),$rord['bkage']);
		//退回费用
		$upmoney = "Update #@__btccoin Set c_deposit=c_deposit+$oprice,c_freeze=c_freeze-$oprice Where userid='".$mid."' And coinid='".$rord['moneyid']."'";
		}else{//卖出
		$ocount=$rord['btccount'];//挂单量
		//$ocount=($rord['btccount'])+($rord['btccount'])/(1/$rord['bkage']+1);//挂单量
		//退回卖出量
		$upmoney = "Update #@__btccoin Set c_deposit=c_deposit+$ocount,c_freeze=c_freeze-$ocount Where userid='".$mid."' And coinid='".$rord['coinid']."'";
		}
		$rsmoy = $dsql->ExecuteNoneQuery($upmoney); 
		//记录为已处理
		$upsolve = "Update #@__btcapply Set cancel=1 Where oid='$tid'";
		$rsmark = $dsql->ExecuteNoneQuery($upsolve); 
		$rsdel = $dsql->ExecuteNoneQuery("DELETE FROM #@__btcorder WHERE oid = '$tid'");
	}
	$query = @mysql_query("unlock tables;") //解锁
	or die(sqlflase("unlock")); 
	
	
	if($rsdel==1){
		return "true";
	}else{
		return "flase";
	}

}


/*
 * 成交单据
 * 
 * @return    array
*/
function FunExRec($mid,$coinid,$moneyid,$count,$market=1,$tid){
	global $dsql;
	$mid=preg_replace("#[^0-9-]#", "", $mid) ? preg_replace("#[^0-9-]#", "", $mid) : "";
	if($tid!=0) $tid=preg_replace("#[^0-9-]#", "", $tid) ? preg_replace("#[^0-9-]#", "", $tid) : "";
	$count=preg_replace("#[^0-9-]#", "", $count) ? preg_replace("#[^0-9-]#", "", $count) : 150;
	$market=preg_replace("#[^0-9-]#", "", $market) ? preg_replace("#[^0-9-]#", "", $market) : 1;
	$moneyid=preg_replace("#[^0-9-]#", "", $moneyid) ? preg_replace("#[^0-9-]#", "", $moneyid) : 1;
	$coinid=preg_replace("#[^0-9-]#", "", $coinid) ? preg_replace("#[^0-9-]#", "", $coinid) : 2;
	if($mid != "") $addsql="AND (buserid='".$mid."' OR suserid='".$mid."') ";
	if($tid != "") $addsql.="AND id>$tid ";
	else $addDesc=" DESC ";
	//读取成交
	$dsql->SetQuery("SELECT id,btccount,uprice,dealtype,dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' $addsql ORDER BY id $addDesc LIMIT ".$count);
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		$dealarr[]=array(  
			'date' => $rod->dealtime, 
			'rate' => $rod->uprice/1, 
			'vol' => $rod->btccount/1, 
			'order' => $rod->dealtype, 
			'ticket' => $rod->id 
		);
	}
	if($tid != "") return array_reverse($dealarr);
	else  return $dealarr;
}

/*
 * 读取挂单
 * 
 * @return    array
*/
function FunRateList($mid,$coinid,$moneyid,$cointype,$moneytype,$count,$market=1){
	global $dsql;

	$mid=preg_replace("#[^0-9-]#", "", $mid) ? preg_replace("#[^0-9-]#", "", $mid) : "";
	$count=preg_replace("#[^0-9-]#", "", $count) ? preg_replace("#[^0-9-]#", "", $count) : 20;
	$market=preg_replace("#[^0-9-]#", "", $market) ? preg_replace("#[^0-9-]#", "", $market) : 1;
	$moneyid=preg_replace("#[^0-9-]#", "", $moneyid) ? preg_replace("#[^0-9-]#", "", $moneyid) : 1;
	$coinid=preg_replace("#[^0-9-]#", "", $coinid) ? preg_replace("#[^0-9-]#", "", $coinid) : 2;
	if($mid != ""){
		$addsql="AND userid='".$mid."' ORDER BY ordertime DESC";
		$addsqlbid="AND userid='".$mid."' ORDER BY ordertime DESC";
	}else{
		$addsql="ORDER BY uprice";
		$addsqlbid="ORDER BY uprice DESC";
	}
	//读取挂单
	
	$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND dealtype=1 $addsql LIMIT ".$count);
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		$ordersell[$rod->uprice] = array(  
			'vol' => $ordersell[$rod->uprice]['vol']+$rod->btccount*1, 
			'rate' => $rod->uprice/1,  
			'count' => $ordersell[$rod->uprice]['count']+1
		);
	}
	foreach($ordersell as $k=>$v){
		$listsell[] = array(  
			'vol' => $v['vol'], 
			'rate' => $v['rate'],  
			'count' => $v['count']
		);
	}
	//读取挂单
	$dsql->SetQuery("SELECT btccount,uprice,tprice,dealtype,ordertime FROM #@__btcorder WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND dealtype=0 $addsqlbid LIMIT ".$count);
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		$orderbuy[$rod->uprice] = array(  
			'vol' => $orderbuy[$rod->uprice]['vol']+$rod->btccount/1, 
			'rate' => $rod->uprice/1,  
			'count' => $orderbuy[$rod->uprice]['count']+1
		);
	}
	foreach($orderbuy as $k=>$v){
		$listbuy[] = array(  
			'vol' => $v['vol'], 
			'rate' => $v['rate'],  
			'count' => $v['count']
		);
	}
	$ratearr = array(  
		'bids' => $listbuy, 
		'asks' => $listsell, 
	);
	$listArray = array(  
		'result' => "true", 
		'symbol' => $cointype."_".$moneytype, 
		'rate_list' => $ratearr, 
	);
	return $listArray;
}


/*
 * 我的挂单
 * 
 * @return    array
*/
function FunMyOrder($mid,$coinid,$moneyid,$count,$market=1){
	global $dsql;
	$mid=preg_replace("#[^0-9-]#", "", $mid) ? preg_replace("#[^0-9-]#", "", $mid) : "";
	$count=preg_replace("#[^0-9-]#", "", $count) ? preg_replace("#[^0-9-]#", "", $count) : 20;
	$market=preg_replace("#[^0-9-]#", "", $market) ? preg_replace("#[^0-9-]#", "", $market) : 1;
	$moneyid=preg_replace("#[^0-9-]#", "", $moneyid) ? preg_replace("#[^0-9-]#", "", $moneyid) : 1;
	$coinid=preg_replace("#[^0-9-]#", "", $coinid) ? preg_replace("#[^0-9-]#", "", $coinid) : 2;
	//读取我的挂单
	if($mid == "") return "";
	$dsql->SetQuery("SELECT oid,uprice,btccount,dealtype,ordertime FROM #@__btcorder WHERE userid=".$mid." AND coinid='".$coinid."' AND moneyid='".$moneyid."'  AND market=$market ORDER BY ordertime DESC");
	$dsql->Execute();
	while($rord = $dsql->GetObject())
	{
		$orderarr[] = array(  
		'oid' => $rord->oid,
		'dealtype' => $rord->dealtype, 
		'uprice' => $rord->uprice/1, 
		'btccount' => $rord->btccount/1,
		'ordertime' => $rord->ordertime
		);
	}
	return $orderarr;
}


/*
 * 我的成交
 * 
 * @return    array
*/
function FunMyDeal($mid,$coinid,$moneyid,$count,$market=1){
	global $dsql;
	$mid=preg_replace("#[^0-9-]#", "", $mid) ? preg_replace("#[^0-9-]#", "", $mid) : "";
	$count=preg_replace("#[^0-9-]#", "", $count) ? preg_replace("#[^0-9-]#", "", $count) : 20;
	$market=preg_replace("#[^0-9-]#", "", $market) ? preg_replace("#[^0-9-]#", "", $market) : 1;
	$moneyid=preg_replace("#[^0-9-]#", "", $moneyid) ? preg_replace("#[^0-9-]#", "", $moneyid) : 1;
	$coinid=preg_replace("#[^0-9-]#", "", $coinid) ? preg_replace("#[^0-9-]#", "", $coinid) : 2;
	//读取我的成交记录
	if($mid == "") return "";
	$dsql->SetQuery("SELECT id,uprice,tprice,btccount,coinid,moneyid,dealtime,buserid,suserid FROM #@__btcdeal WHERE (buserid=".$mid." OR suserid=".$mid.") AND coinid='".$coinid."' AND moneyid='".$moneyid."' AND market=$market ORDER BY id DESC");
	$dsql->Execute();
	while($rdeal = $dsql->GetObject())
	{
		if($rdeal->suserid == $mid){
			$dtype = 1 ;
			$oid = $rdeal->selloid;
			$fee = $rdeal->sbkage;
		}else{
			$dtype = 0 ;
			$oid = $rdeal->buyoid;
			$fee = $rdeal->bbkage;
		}
		$dealarr[] = array(  
		'id' => $rdeal->id,
		'oid' => $oid,
		'dealtype' => $dtype, 
		'uprice' => $rdeal->uprice/1, 
		'btccount' => $rdeal->btccount/1,
		'fee' => $fee/1,
		'dealtime' => $rdeal->dealtime
		);
	}
	return $dealarr;
}

/*
 * 走势图数据
 * 
 * @return    array
*/
function FunTline($coinid,$moneyid,$tspan,$count,$market=1){
	global $dsql;
	$market=preg_replace("#[^0-9-]#", "", $market) ? preg_replace("#[^0-9-]#", "", $market) : 1;
	$moneyid=preg_replace("#[^0-9-]#", "", $moneyid) ? preg_replace("#[^0-9-]#", "", $moneyid) : 1;
	$coinid=preg_replace("#[^0-9-]#", "", $coinid) ? preg_replace("#[^0-9-]#", "", $coinid) : 2;
	$tspan=preg_replace("#[^0-9-]#", "", $tspan) ? preg_replace("#[^0-9-]#", "", $tspan) : 300;
	$count=preg_replace("#[^0-9-]#", "", $count) ? preg_replace("#[^0-9-]#", "", $count) : 100;
	$dsql->SetQuery("SELECT * FROM #@__btctline WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND tspan='".$tspan."' ORDER BY E_time DESC LIMIT $count");
	$dsql->Execute();
	while($rod = $dsql->GetObject())
	{
		$tlinearr[]=array(  
			'time' => $rod->E_time,
			'open' => $rod->R_open/1, 
			'high' => $rod->R_high/1, 
			'low' => $rod->R_low/1, 
			'close' => $rod->R_close/1, 
			'vol' => $rod->volume/1 
		);
		$close = $close?$close:$rod->R_close;
	}
	$endtime=$tlinearr[0]['time']?$tlinearr[0]['time']:strtotime("-1 day");
	$times=floor((time()-$endtime)/($tspan));
	//$stime = ($endtime+$i*$tspan);
	//$etime = ($endtime+($i+1)*$tspan);
	if($close==""){
		$row = $dsql->GetOne("SELECT * FROM #@__btctline WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND tspan='".$tspan."' ORDER BY E_time DESC");
		$close = $rod['R_close'];
	}
	for($i=0;$i<$times;$i++){
		$tline="";
		$one=1;
		$dsql->SetQuery("SELECT id,btccount,uprice,dealtype,dealtime FROM #@__btcdeal WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND dealtime>".($endtime+$i*$tspan)." AND dealtime<=".($endtime+($i+1)*$tspan)." ORDER BY id DESC LIMIT 20");
		$dsql->Execute();
		while($rdeal = $dsql->GetObject())
		{
			
			if($one==1){
			 	$tline=array(  
					'time' => $endtime+($i+1)*$tspan,
					'open' => $close/1, 
					'high' => $rdeal->uprice/1, 
					'low' => $rdeal->uprice/1, 
					'close' => $rdeal->uprice/1, 
					'vol' => $rdeal->btccount/1 
				);
				$one=2;
			}else{
				$tline=array(  
					'time' => $tline['time'], 
					'open' => $close/1, 
					'high' => $tline['high'] > $rdeal->uprice ? $tline['high'] : $rdeal->uprice/1, 
					'low' => $tline['low'] < $rdeal->uprice ? $tline['low'] : $rdeal->uprice/1,  
					'close' => $rdeal->uprice/1, 
					'vol' => ($tline['vol']+$rdeal->btccount/1)
				);
			}
		}
		
		if(!$tline){
			$rone = $dsql->GetOne("SELECT R_close FROM #@__btctline WHERE coinid='".$coinid."' AND moneyid='".$moneyid."' AND market='".$market."' AND tspan='".$tspan."' ORDER BY E_time DESC");
			$zero=$rone['R_close'];
			$tline=array(  
				'time' => $endtime+($i+1)*$tspan,
				'open' => $zero, 
				'high' => $zero, 
				'low' => $zero, 
				'close' => $zero, 
				'vol' => 0.00000000 
			);
		}
		$close=$tline['close'];
		array_unshift($tlinearr,$tline);
		array_pop($tlinearr);
		$rsnew = $dsql->ExecuteNoneQuery("insert into #@__btctline(R_open,R_high,R_low,R_close,volume,coinid,moneyid,market,tspan,E_time) values('".$tline['open']."','".$tline['high']."','".$tline['low']."','".$tline['close']."','".$tline['vol']."',$coinid,$moneyid,$market,$tspan,'".($endtime+($i+1)*$tspan)."')");
	}
	return array_reverse($tlinearr);
}




/*
*获取行情函数
*$symbol   类型，BTC_USD
*$count   获取的记录条数，80
*$tspan   每条记录的时间段，300为5分钟
*$hqdate   几天前的记录，0为当前
*$datenum  获取多少天的记录，1
*
*return  array
*/
function hqfun($symbol,$count,$tspan,$hqdate=0,$datenum=1){
	global $dsql;
	$symbol=preg_replace("#[^_A-Za-z-]#", "", $symbol)?preg_replace("#[^_A-Za-z-]#", "", $symbol):"BTC_USD";//"BTC_CNY";showJson("类型有误！",'false');
	$coinarr=explode('_',$symbol);
	$cointype=$coinarr[0];
	$moneytype=$coinarr[1];
	
	$count = $count?$count:80;
	if($count>288) $count=288;
	
	//时间限制
	$tspan = $tspan?$tspan:300;
	$hqdate = preg_replace("#[^0-9-]#", "", $hqdate)?(preg_replace("#[^0-9-]#", "", $hqdate)+1):1;
	$datenum = preg_replace("#[^0-9-]#", "", $datenum)?preg_replace("#[^0-9-]#", "", $datenum):1;
	if( $datenum > 30 ) $datenum = 30;
	$sstime = strtotime("-".$hqdate." day");
	$eetime = $sstime + ( 60 * 60 * 24 * $datenum );
	$stime=cuttime($sstime,$tspan);
	$etime=cuttime($eetime,$tspan);

	//读取成交单
	$dsql->SetQuery("SELECT * FROM #@__qhmtgox WHERE date > '".$stime."' AND date <= '".$etime."' ORDER BY tid DESC");
	$dsql->Execute();
	$i=0;
	while($rod = $dsql->GetObject())
	{
		$timemark = $etime-$tspan*($i+1);
		if($rod->date > $timemark) {
			//echo $timemark;
			$open=$rod->price;
			if($close=="") $close=$rod->price;
			
			$low=$low > $rod->price ? $rod->price : $low;
			$high=$high > $rod->price ? $high : $rod->price;
			
			$amount=$amount+$rod->amount;
			$tprice=$rod->price*$rod->amount;
			$total=$total+$tprice;

			$hqarray[$i]=array(
				'date'=>($timemark+$tspan),
				'open'=>$open,
				'close'=>$close,
				'low'=>$low,
				'high'=>$high,
				'amount'=>$amount,
				'middle'=>floor(($total/$amount)*100000000)/100000000
			);
				
		}else{
			$i++;
			$timemark = $stime+$tspan*($i+1);
			$open=$rod->price;
			$close=$rod->price;
			$low=$rod->price ;
			$high=$rod->price ;
			$amount=0;
			$total=0;
		}
		
		if($i >= $count) break;
		//echo $rod->tid;
		//echo "<br>";
	}
	return $hqarray;
}


//取规范的时间
	function cuttime($ctime,$tspan){
		$itime=date('i',$ctime)-date('i',$ctime)%($tspan/60);
		if($itime<10) $itime="0".$itime;
		$rtime=strtotime(date('Y-m-d H',$ctime).":".$itime.":00");
		return $rtime;
	}
	


/**
 *  计算手续费
 */
function feeFun($dealTprice,$feePer){
	if($feePer==0){
		$fee = 0;
	}else{
		$fee = floor($dealTprice/(1/$feePer+1)*100000000)/100000000;
	}
	return $fee;
}