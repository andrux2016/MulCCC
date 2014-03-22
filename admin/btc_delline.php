<?php
/**
 * 会员类型
 *
 * @version        $Id: member_type.php 1 14:14 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
if(empty($dopost)) $dopost = "";


//保存更改
if($dopost=="del")
{
    $mtime=strtotime('-'.$exptime_new.'day');
	$query = "DELETE FROM #@__btctline WHERE E_time<".$mtime." ";
	$dsql->ExecuteNoneQuery($query);
	header("Content-Type: text/html; charset={$cfg_soft_lang}");
    echo "<script> alert('成功删除走势数据！'); </script>";

}



$times[1098] = '三年';
$times[366] = '一年';
$times[183] = '半年';
$times[90] = '三个月';
$times[30] = '一个月';
$times[7] = '一周';
$times[1] = '一天';




require_once(DEDEADMIN."/templets/btc_delline.htm");
