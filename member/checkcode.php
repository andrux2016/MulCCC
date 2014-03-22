<?php

		//@session_start();

		/*@session_id(preg_replace("#[^0-9a-zA-Z-]#", "0btc0",$_POST['userid']));
		@session_start();

		@session_id(preg_replace("#[^0-9a-zA-Z-]#", "0btc0",$_POST['userid']));*/
require_once(dirname(__FILE__)."/config.php");
$userip=$_SERVER["REMOTE_ADDR"];
$sessErr=$userid.'err';
$sessErrip=$userip.'err';
//$sessName=$cfg_ml->M_LoginID.'vd';
//$sessCode=GetEmailCode($sessName,"achieve");
$ErrNums=GetPwErrNums($sessErr,"achieve");
$ErrNumsip=GetPwErrNums($sessErrip,"achieve");
	function showJson($msg,$ruslt){
			/*$userArray=array(  
			'showMsg' => $msg, 
			'ruslt' => $ruslt,
			);
		
			$json_string = json_encode($userArray);  
			echo $json_string;*/
			echo $msg;
		}
	
	//用户登录
    if($dopost=="login")
    {
       

        if(preg_match("/2/",$safe_gdopen)){
             if(!isset($vdcode))
        {
            $vdcode = '';
        }
        $svali = GetCkVdValue();
			if(strtolower($vdcode)!=$svali || $svali=='')
            {
                ResetVdValue();
				
                if($gourl=="json"){
					showJson('验证码错误！','f');
				}
				else {
					ShowMsg('验证码错误！', '-1');
					exit();
				}
            }
          }
		
		/*if(strtolower($vdcode)==GetCkVdValue()){ 
		} else{
		  ResetVdValue();
		  showJson("验证码错误",'f');
		  exit();
		}*/
		
		
        if(CheckUserID($userid,'',false)!='ok')
        {
            if($gourl=="json") showJson('你输入的用户名 {$userid} 不合法！','f');
			else ShowMsg("你输入的用户名 {$userid} 不合法！","-1");
            exit();
        }
        if($pwd=='')
        {
            if($gourl=="json") showJson('密码不能为空！','f');
			else ShowMsg("密码不能为空！","-1",0,2000);
            exit();
        }

		if($ErrNums>5 || $ErrNumsip>5)
		{
			showJson("错误次数过多，请".session_cache_expire()."分钟后再试！","-1");
			exit();
		}
		if($ErrNums>7)
		{
			$rsup = $dsql->ExecuteNoneQuery("Update #@__member Set rank=0 where userid = '".$userid."' "); 
			$rsnew = $dsql->ExecuteNoneQuery("insert into #@__log(adminid,filename,method,query,cip,dtime) values('0','member/login.php','err-".$userid."-".$pwd."','login','$userip','".time()."')");
			showJson("账号已经被锁定保护！","-1");
			exit();
		}
		

        //检查帐号
        $rs = $cfg_ml->CheckUser($userid,$pwd);  
        
        
        
        if($rs==0)
        {
            if($gourl=="json") showJson('用户名不存在！','f');
				else ShowMsg("用户名不存在！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-1) {
            
			//$_SESSION[$userip.'err']++;
			//$_SESSION[$userid.'err']++;
			GetPwErrNums($sessErr,"add");
			$errtimes=6-GetPwErrNums($sessErrip,"add");
			//$_SESSION[$userip.'err'];

			if($gourl=="json") showJson("密码错误！您还有".$errtimes."次机会","f");
				else ShowMsg("密码错误！您还有".$errtimes."次机会", "-1", 0, 2000);
            exit();
        }
        else if($rs==-2) {
            if($gourl=="json") showJson('管理员帐号不允许从前台登录！','f');
				else ShowMsg("管理员帐号不允许从前台登录！", "-1", 0, 2000);
            exit();
        }
		else if($rs==-3) {
            if($gourl=="json") showJson('您的账号已经被限制保护，请联系管理员！','f');
				else ShowMsg("您的账号已经被限制保护，请联系管理员！", "-1", 0, 2000);
            exit();
        }
        else
        {
            
			
			$row=$dsql->GetOne("SELECT google FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
			if($row['google'] != ""){
				if(empty($googlecode))
				{
					$cfg_ml->ExitCookie();//退出
					showJson("请输入google验证码！","f");
					exit();
				}
				require_once 'GoogleAuthenticator.php';
				
				$ga = new PHPGangsta_GoogleAuthenticator();
				$userCode = $googlecode;
				$secret = $row['google'];
				$checkResult = $ga->verifyCode($secret, $userCode, 2);    // 2 = 2*30sec clock tolerance
				if (!$checkResult) {
					$cfg_ml->ExitCookie();//退出
					showJson('google验证错误！请查看您的手机时间是否正确！',"-1");
					exit();
				}
			}
			// 清除会员缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
			//unset($_SESSION[$userip.'err']);
			//unset($_SESSION[$userid.'err']);
			GetPwErrNums($sessErr,"unset");
			GetPwErrNums($sessErrip,"unset");
            if(empty($gourl) || preg_match("#action|_do#i", $gourl))
            {
                if($gourl=="json") showJson('1','t');
				else ShowMsg($cfg_ml->M_ID."成功登录，5秒钟后转向系统主页...",$cfg_basehost,0,2000);
            }
            else
            {
                $gourl = str_replace('^','&',$gourl);
                //ShowMsg("成功登录，现在转向指定页面...",$gourl,0,2000);
				if($gourl=="json") showJson('1','t');
				else ShowMsg("成功登录，现在转向指定页面...",$cfg_basehost,0,2000);
				
            }
            exit();
        }
		
    }

    //退出登录
    else if($dopost=="exit")
    {
        $cfg_ml->ExitCookie();
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            $ucsynlogin = uc_user_synlogout();
        }
        #/aip}}
		showJson('成功退出登录！','t');
		
    }


?>