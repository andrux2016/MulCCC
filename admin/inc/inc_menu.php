<?php
/**
 * 后台管理菜单项
 *
 * @version        $Id: inc_menu.php 1 10:32 2010年7月21日Z SZ $
 */
require_once(dirname(__FILE__)."/../config.php");

//载入可发布频道
$addset = '';

//检测可用的内容模型
if($cfg_admin_channel = 'array' && count($admin_catalogs) > 0)
{
    $admin_catalog = join(',', $admin_catalogs);
    $dsql->SetQuery(" SELECT channeltype FROM `#@__arctype` WHERE id IN({$admin_catalog}) GROUP BY channeltype ");
}
else
{
    $dsql->SetQuery(" SELECT channeltype FROM `#@__arctype` GROUP BY channeltype ");
}
$dsql->Execute();
$candoChannel = '';
while($row = $dsql->GetObject())
{
    $candoChannel .= ($candoChannel=='' ? $row->channeltype : ','.$row->channeltype);
}
if(empty($candoChannel)) $candoChannel = 1;
$dsql->SetQuery("SELECT id,typename,addcon,mancon FROM `#@__channeltype` WHERE id IN({$candoChannel}) AND id<>-1 AND isshow=1 ORDER BY id ASC");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    $addset .= "  <m:item name='{$row->typename}' ischannel='1' link='{$row->mancon}?channelid={$row->id}' linkadd='{$row->addcon}?channelid={$row->id}' channelid='{$row->id}' rank='' target='main' />\r\n";
}
//////////////////////////

$adminMenu1 = $adminMenu2 = '';
if($cuserLogin->getUserType() >= 10)
{
   /* $adminMenu1 = "<m:top item='1_' name='频道模型' display='block' rank='t_List,t_AccList,c_List,temp_One'>
  <m:item name='内容模型管理' link='mychannel_main.php' rank='c_List' target='main' />
  <m:item name='单页文档管理' link='templets_one.php' rank='temp_One' target='main'/>
  <m:item name='联动类别管理' link='stepselect_main.php' rank='c_Stepseclect' target='main' />
  <m:item name='自由列表管理' link='freelist_main.php' rank='c_List' target='main' />
  <m:item name='自定义表单' link='diy_main.php' rank='c_List' target='main' />
</m:top>
<m:item name='会员短信管理' link='member_pm.php' rank='member_Type' target='main' />
<m:item name='积分头衔设置' link='member_scores.php' rank='member_Type' target='main' />
  <m:item name='会员模型管理' link='member_model_main.php' rank='member_Type' target='main' />
";<m:item name='系统日志管理' link='log_list.php' rank='sys_Log' target='main' />*/
$adminMenu2 = "<m:top item='7_' name='模板管理' display='none' rank='temp_One,temp_Other,temp_MyTag,temp_test,temp_All'>
  <m:item name='默认模板管理' link='templets_main.php' rank='temp_All' target='main'/>
  <m:item name='标签源码管理' link='templets_tagsource.php' rank='temp_All' target='main'/>
  <m:item name='自定义宏标记' link='mytag_main.php' rank='temp_MyTag' target='main'/>
  <m:item name='智能标记向导' link='mytag_tag_guide.php' rank='temp_Other' target='main'/>
  <m:item name='全局标记测试' link='tag_test.php' rank='temp_Test' target='main'/>
</m:top>
<m:top item='10_' name='系统设置' display='none' rank='sys_User,sys_Group,sys_Edit,sys_Log,sys_Data'>
  <m:item name='系统基本参数' link='sys_info.php' rank='sys_Edit' target='main' />
  <m:item name='系统用户管理' link='sys_admin_user.php' rank='sys_User' target='main' />
  <m:item name='用户组设定' link='sys_group.php' rank='sys_Group' target='main' />
 <m:item name='图片水印设置' link='sys_info_mark.php' rank='sys_Edit' target='main' />
  <m:item name='验证安全设置' link='sys_safe.php' rank='sys_verify' target='main' />
  <m:item name='清除走势数据' link='btc_delline.php' rank='sys_Data' target='main' />
  <m:item name='数据库备份/还原' link='sys_data.php' rank='sys_Data' target='main' />
</m:top>


    ";
}
$remoteMenu = ($cfg_remote_site=='Y')? "<m:item name='远程服务器同步' link='makeremote_all.php' rank='sys_ArcBatch' target='main' />" : "";
$menusMain = "
-----------------------------------------------

<m:top item='1_' name='常用操作' display='block'>
  <m:item name='成交单列表' link='btc_deal_list.php' rank='sys_Data' target='main' />
  <m:item name='挂单列表' link='btc_order_list.php' rank='sys_Data' target='main' />

  <m:item name='充值审核' link='btc_recharge_list.php' rank='sys_Dat,a_Check,a_AccCheck' target='main' />
  <m:item name='提现审核' link='btc_cash_list.php' rank='sys_Data,a_Check,a_AccCheck' target='main' />
  <m:item name='币种管理' link='btc_coin_manage.php' rank='sys_Data' target='main' />
  <m:item name='交易类型管理' link='btc_cv_manage.php' rank='sys_Data' target='main' />
  <m:item name='充值卡管理' link='member_recharge_cards.php' rank='sys_Data' target='main' />
  <m:item name='内测邀请码' link='member_welcome.php' rank='sys_Data' target='main' />
  <m:item name='支付接口设置' link='sys_payment.php' rank='sys_Data' target='main' />
  <m:item name='网站栏目管理' link='catalog_main.php' ischannel='1' addalt='创建栏目' linkadd='catalog_add.php?listtype=all' rank='t_List,t_AccList' target='main' />
  <m:item name='默认模板管理' link='templets_main.php' rank='temp_All' target='main'/>
  <m:item name='更新栏目HTML' link='makehtml_list.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新文档HTML' link='makehtml_archives.php' rank='sys_MakeHtml' target='main' />
  <m:item name='更新系统缓存' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
  <m:item name='智能标记向导' link='mytag_tag_guide.php' rank='temp_Other' target='main'/>
  
</m:top>  




$adminMenu1





<m:top item='5_' name='期货' notshowall='1'  display='block' rank='sys_MakeHtml'>
  <m:item name='合约设置' link='qh_heyue.php' rank='sys_MakeHtml' target='main' />
  <m:item name='挂单查询' link='qh_order.php' rank='sys_MakeHtml' target='main' />
  <m:item name='成交查询' link='qh_deal.php' rank='sys_MakeHtml' target='main' />
  <m:item name='持仓查询' link='qh_possess.php' rank='sys_MakeHtml' target='main' />
</m:top>

<m:top item='5_' name='HTML更新' notshowall='1' display='none' rank='sys_MakeHtml'>


</m:top>

<m:top item='6_' name='会员管理' display='none' rank='member_List,member_Type'>
  <m:item name='注册会员列表' link='member_main.php' rank='member_List' target='main' />
  <m:item name='会员级别设置' link='member_rank.php' rank='member_Type' target='main' />
  <m:item name='会员留言管理' link='member_snsmsg.php' rank='member_Type' target='main' />
  <m:item name='推荐奖励提成' link='btc_deduct_list.php' rank='member_Type' target='main' />
</m:top>

$adminMenu2

<m:top item='1_10_7_' name='系统帮助' display='none'>
  <m:item name='参考文档' link='' rank='' target='_blank' />

</m:top>

-----------------------------------------------
";