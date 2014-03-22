<?php
/**
 * @version        $Id: index.php 1 8:24 2010年7月9日Z SZ $
 */
?>
<script>
location.href='../trade.php';
</script>
<?php 
die();
?>
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
        $tpl = dirname(__FILE__)."/templets/index.htm";
        $dpl->LoadTemplate($tpl);
        $dpl->display();
    }
}
/*-----------------------------
//会员空间主页
function space_index(){  }
------------------------------*/
else
{
	
	require_once(DEDEMEMBER.'/inc/config_space.php');
    if($action == '')
    {
        include_once(DEDEINC."/channelunit.func.php");
        $dpl = new DedeTemplate();
        $tplfile = DEDEMEMBER."/space/{$_vars['spacestyle']}/index.htm";

        //更新最近访客记录及站点统计记录
        $vtime = time();
        $last_vtime = GetCookie('last_vtime');
        $last_vid = GetCookie('last_vid');
        if(empty($last_vtime))
        {
            $last_vtime = 0;
        }
        if($vtime - $last_vtime > 3600 || !preg_match('#,'.$uid.',#i', ','.$last_vid.',') )
        {
            if($last_vid!='')
            {
                $last_vids = explode(',',$last_vid);
                $i = 0;
                $last_vid = $uid;
                foreach($last_vids as $lsid)
                {
                    if($i>10)
                    {
                        break;
                    }
                    else if($lsid != $uid)
                    {
                        $i++;
                        $last_vid .= ','.$last_vid;
                    }
                }
            }
            else
            {
                $last_vid = $uid;
            }
            PutCookie('last_vtime', $vtime, 3600*24, '/');
            PutCookie('last_vid', $last_vid, 3600*24, '/');
            if($cfg_ml->IsLogin() && $cfg_ml->M_LoginID != $uid)
            {
                $vip = GetIP();
                $arr = $dsql->GetOne("SELECT * FROM `#@__member_vhistory` WHERE mid='{$_vars['mid']}' AND vid='{$cfg_ml->M_ID}' ");
                if(is_array($arr))
                {
                    $dsql->ExecuteNoneQuery("UPDATE `#@__member_vhistory` SET vip='$vip',vtime='$vtime',count=count+1 WHERE mid='{$_vars['mid']}' AND vid='{$cfg_ml->M_ID}' ");
                }
                else
                {
                    $query = "INSERT INTO `#@__member_vhistory`(mid,loginid,vid,vloginid,count,vip,vtime)
                             VALUES('{$_vars['mid']}','{$_vars['userid']}','{$cfg_ml->M_ID}','{$cfg_ml->M_LoginID}','1','$vip','$vtime'); ";
                    $dsql->ExecuteNoneQuery($query);
                }
            }
            $dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET homecount=homecount+1 WHERE mid='{$_vars['mid']}' ");
        }
        $dpl->LoadTemplate($tplfile);
        $dpl->display();
        exit();
    }
    else
    {
        require_once(DEDEMEMBER.'/inc/space_action.php');
        exit();
    }
}