<?php
/**
 * @version        $Id: edit_fullinfo_btc.php 1 8:38 2013年8月9日Z SZ $
 */
require_once(dirname(__FILE__).'/config.php');
require_once DEDEINC.'/membermodel.cls.php';
require_once(DEDEINC."/userlogin.class.php");
CheckRank(0,0);
require_once(DEDEINC.'/enums.func.php');
$menutype = 'config';
if(!isset($dopost)) $dopost = '';

foreach ($cfg_arrcoin as $value){

	$coinhtml.="<p><label class='fleft'>".$value['0']."余额：</label><span class='coininfo'>".($value['1']/1)."</span><span>冻结：".($value['2']/1)."</span></p>";
	$coinvol+=$value['4'];
}


$qq=preg_replace("#[^0-9-]#", "",$qq);
$msn=safe_string($msn);
$tel=preg_replace("#[^+0-9-]#", "",$tel);
$mobile=preg_replace("#[^0-9-]#", "",$mobile);

if($dopost=='')
{
    $dede_fields = empty($dede_fields) ? '' : trim($dede_fields);
    if(!empty($dede_fields))
    {
        if($dede_fieldshash != md5($dede_fields.$cfg_cookie_encode))
        {
            showJson('数据校验不对，程序返回', '-1');
            exit();
        }
    }
    $ruser=$dsql->GetOne("SELECT * FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
    $dede_fieldshash = empty($dede_fieldshash) ? '' : trim($dede_fieldshash);
    $membermodel = new membermodel($cfg_ml->M_MbType);
    $modelform = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$membermodel->modid' ");
    if(!is_array($modelform))
    {
        showJson('模型表单不存在', '-1');
        exit();
    }
    $row = $dsql->GetOne("SELECT * FROM ".$modelform['table']." WHERE mid=$cfg_ml->M_ID");
    if(!is_array($row))
    {
        showJson("你访问的记录不存在或未经审核", '-1');
        exit();
    }
    $postform = $membermodel->getForm('edit', $row, 'membermodel');
    include(DEDEMEMBER."/templets/edit_fullinfo_btc.htm");
    exit();
}
/*------------------------
function __Save()
------------------------*/
if($dopost=='save'){
    
        $membermodel = new membermodel($cfg_ml->M_MbType);
        $postform = $membermodel->getForm(true);

      //这里完成详细内容填写
        $dede_fields = empty($dede_fields) ? '' : trim($dede_fields);
        $dede_fieldshash = empty($dede_fieldshash) ? '' : trim($dede_fieldshash);
        $modid = empty($modid)? 0 : intval(preg_replace("/[^\d]/",'', $modid));
        
        if(!empty($dede_fields))
        {
            if($dede_fieldshash != md5($dede_fields.$cfg_cookie_encode))
            {
                showJson('数据校验不对，程序返回', '-1');
                exit();
            }
        }
        $modelform = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$modid' ");
        if(!is_array($modelform))
        {
            showJson('模型表单不存在', '-1');
            exit();
        }
        
        $inadd_f = '';
        if(!empty($dede_fields))
        {
            $fieldarr = explode(';', $dede_fields);
            if(is_array($fieldarr))
            {
                foreach($fieldarr as $field)
                {
                    if($field == '') continue;
                    $fieldinfo = explode(',', $field);
                    if($fieldinfo[1] == 'textdata')
                    {
                        ${$fieldinfo[0]} = FilterSearch(stripslashes(${$fieldinfo[0]}));
                        ${$fieldinfo[0]} = addslashes(${$fieldinfo[0]});
                    } else if ($fieldinfo[1] == 'img')
                    {
                        ${$fieldinfo[0]} = addslashes(${$fieldinfo[0]});
                    }
                    else
                    {
                        if(empty(${$fieldinfo[0]})) ${$fieldinfo[0]} = '';
                        ${$fieldinfo[0]} = GetFieldValue(${$fieldinfo[0]}, $fieldinfo[1],0,'add','','diy', $fieldinfo[0]);
                    }
                    if($fieldinfo[0]=="birthday") ${$fieldinfo[0]}=GetDateMk(${$fieldinfo[0]});
                    $inadd_f .= ','.$fieldinfo[0]." ='".${$fieldinfo[0]}."'";
                }
            }

        }
        $inadd_f=preg_replace('/,/','',$inadd_f,1);
        $query = "UPDATE `{$membermodel->table}` set {$inadd_f} WHERE mid='{$cfg_ml->M_ID}'";
        // 清除缓存
        $cfg_ml->DelCache($cfg_ml->M_ID);
        
        if(!$dsql->ExecuteNoneQuery($query))
        {
            showJson("更新附加表 `{$membermodel->table}`  时出错，请联系管理员！","-1");
            exit();
        }else{
            showJson('成功更新你的详细资料！',1);
            exit();
        }
}

/**
 *  提示信息
 */
function showJson($msg,$ruslt){
			$msgArray=array(  
			'showMsg' => $msg, 
			'ruslt' => $ruslt,
			);
			$json_string = json_encode($msgArray);  
			echo $json_string;
		}