<?php
/**
 * @version        $Id: index.php 1 8:24 2010年7月9日Z SZ $
 */
require_once(dirname(__FILE__)."/config.php");

$uid=empty($uid)? "" : RemoveXSS($uid); 
if(empty($action)) $action = '';
if(empty($aid)) $aid = '';

$menutype = 'mydede';

//会员后台
if($uid=='')
{
    $iscontrol = 'yes';
    if(!$cfg_ml->IsLogin())
    {
        include_once(dirname(__FILE__)."/templets/login.htm");
    }
    else
    {
        if($spacesta=-10){
			$showmsg="您还未验证邮箱！请验证邮箱！";
		}else{
			CheckTxPdw();
		}
        
        $dpl = new DedeTemplate();
        $tpl = dirname(__FILE__)."/templets/yinhang.htm";
        $dpl->LoadTemplate($tpl);
        $dpl->display();
    }
}