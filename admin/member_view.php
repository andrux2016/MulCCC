<?php
/**
 * 会员查看
 *
 * @version        $Id: member_view.php 1 14:15 2010年8月20日Z SZ $
 */
require(dirname(__FILE__)."/config.php");
CheckPurview('member_Edit');
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? "member_main.php" : '';
$id = preg_replace("#[^0-9]#", "", $id);
$row = $dsql->GetOne("select  * from #@__member where mid='$id'");
 $rtel = $dsql->GetOne("select tel from #@__member_person where mid='$id'");
$staArr = array(
    -10=>'等待验证邮件',
    -2=>'限制用户(禁言)',
    -1=>'未通过审核',
     0=>'审核通过，提示填写完整信息',
     1=>'没填写详细资料',
     2=>'正常使用状态'
);

//如果这个用户是管理员帐号，必须有足够权限的用户才能操作
if($row['matt']==10) CheckPurview('sys_User');

if($row['uptime']>0 && $row['exptime']>0)
{
    $mhasDay = $row['exptime'] - ceil((time() - $row['uptime'])/3600/24)+1;
} else {
    $mhasDay = 0;
}

    $dsql->SetQuery("SELECT * FROM #@__btctype WHERE coinsign='1' ");
	$dsql->Execute();
	while($rtype = $dsql->GetObject())
	{
		$vol = 0;
		$free = 0;
		$rcoin = $dsql->GetOne("SELECT * FROM #@__btccoin WHERE userid='$id' AND coinid='".$rtype->id."' ");
		if(is_array($rcoin))
		{
			 $vol = $rcoin['c_deposit']*10/10;
			$free = $rcoin['c_freeze']*10/10;
		}
		$CoinINP .= $rtype->cointype."：<input name=\"coin_".$rtype->cointype."\" type=\"text\" id=\"coin_".$rtype->cointype."\" value=\"$vol\" style=\"width:160px;\" /> ";
		$CoinINP .= "冻结：<input name=\"free_".$rtype->cointype."\" type=\"text\" id=\"free_".$rtype->cointype."\" value=\"$free\" style=\"width:160px;\" /><br>";
	}


include DedeInclude('templets/member_view.htm');


    