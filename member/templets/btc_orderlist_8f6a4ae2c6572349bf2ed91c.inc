<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>" />
<title><?php echo $cfg_webname; ?> - 用户中心 - 委托管理 </title>

<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/style/scport.css" rel="stylesheet" media="screen" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo $cfg_templets_skin ?>/js/scport1.js"></script>
<script type="text/javascript" src="templets/js/jquery_1_7_2.js"></script>
<script type="text/javascript" src="templets/js/j.js"></script>
<script type="text/javascript" src="templets/js/load.js"></script>
<script type="text/javascript" src="templets/js/leftmenu.js"></script>



<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/nav-top.css" rel="stylesheet" type="text/css">
<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/footer.css" rel="stylesheet" type="text/css">
<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/yonghu-content.css" rel="stylesheet" type="text/css">
</head>
<body>
	
	<?php include(DEDEMEMBER."/templets/head_btc.htm"); ?>
	<div class="clear"></div>
	<!--id="yonghu-content" 用户中心-->
	<div id="yonghu-content" class="yonghu-content">
		<?php include(DEDEMEMBER."/templets/menu_btc.php"); ?>
		<div class="yh-right">
			<div class="right-top">
				<div class="r-top-l">
					<img src="<?php echo $GLOBALS['cfg_templets_skin']; ?>/img/btb-yhgl-3.jpg" border="0"  />
				</div>
				<div class="r-top-c">
					<div class="c-t"><?php echo $cfg_ml->M_LoginID; ?></div>
					<div class="c-b">[<a href="#" onclick="UserQuit();">退出登录</a>]</div>
				</div>
				<div class="r-top-r">
				  <div class="r-t">
						<ul>
							<li>可用</li>
							<?php echo $coinhtml;?>
							<div style="clear:both"></div>  
						</ul>
					</div>
					<div class="r-c">
						<ul>
							<li>冻结</li>
							<?php echo $freehtml;?>
							<div style="clear:both"></div>  
						</ul>
					</div>
				</div>
				<div style="clear:both"></div>  
			</div>
			  
			<div class="entrustTen lasttenorder clear">
				<div class="Tentitle">
						<span class="lightblue4">挂单查询</span>
				</div>
				<div class="Tenbody">
					<div id="mcpmain" style="width:100%;float:left;padding:0; border:none;">
						 <div id="appTab"  style="display:none;">
							<ul>
							   <li class="thisTab"><a href="btc_orderlist.php">挂单查询</a></li>
							</ul>
						</div>
						<div id="mainCp" style="width:100%;float:left;padding:0;border:none;">       
							<!--内容消息提示 -->
							<div class="postForm" style="width:100%;float:left;padding:0;border:none;">						  
								<table>							
									<tr >
										<th width="180">定单号</th>
										<th width="100">交易类型</th>
										<th width="90">交易量</th>
										<th width="80">单价</th>
										<th width="90">合计</th>
										<th width="90">手续费</th>
										<th width="110">撤单</th>
									</tr>								
									<tbody>
										<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
												
											if($fields["dealtype"] == 0){
											$dealtype="<font color='#068814'>[买]</font>";
											}else{
											$dealtype="<font color='#FF0000'>[卖]</font>";
											}
										?>
										<tr>
											<td class="gray"><?php echo $fields['oid']; ?></td>
											<td><?php echo $coinarr[$fields["coinid"]]."/".$coinarr[$fields["moneyid"]].$dealtype;?></td>
											<td><?php echo ($fields["btccount"]/1)." ".$coinarr[$fields["coinid"]];?></td>
											<td><?php echo ($fields["uprice"]/1)." ".$coinarr[$fields["moneyid"]];?></td>
											<td><?php echo ($fields["tprice"]/1)." ".$coinarr[$fields["moneyid"]];?></td>
											<td><?php echo $fields["bkage"]*100;?>%</td>
											<td><a href='/data.php?type=cancel&symbol=<?php echo $coinarr[$fields["coinid"]];?>_<?php echo $coinarr[$fields["moneyid"]];?>&tid=<?php echo $fields["oid"];?>'>撤单</a></td>
										</tr>
										<?php
}
}?>
									</tbody>
									<tfoot>
										<tr>
										  <td colspan="7"><div class="pageList" style="padding-right:20px;"><?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listitem'] = 'info,index,end,pre,next,pageno';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
</div></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div> 
					<!--主操作区域 -->	
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php include(DEDEMEMBER."/templets/foot_btc.htm"); ?>	
</body>
</html>