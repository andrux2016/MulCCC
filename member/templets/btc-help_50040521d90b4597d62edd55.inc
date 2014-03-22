<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo $cfg_templets_skin ?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo $cfg_templets_skin ?>/css/nav-top.css" rel="stylesheet" type="text/css">
<link href="<?php echo $cfg_templets_skin ?>/css/footer.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['cfg_templets_skin']; ?>/js/jquery_1_7_2.js"></script>
<link href="<?php echo $cfg_templets_skin ?>/css/help-list.css" rel="stylesheet" type="text/css">
<title><?php echo $GLOBALS['cfg_webname']; ?>-帮助</title>
<script type="text/javascript">
$(function(){
	$.getJSON("/member/ajax_login.php?tt="+new Date(), function(data){
		if(data.userid){
			$('#usertop').html("<div class='login-yes'><span class=\"userlink\"><div class='out'><a href=\"#\" onclick=\"UserQuit()\">退出</a></div><div class='name'><a class='ugif name' href=\"/member/\"title="+data.username+"  >"+data.username+"</a></div></span> <div class='hi'>hi,</div></div>");
		}
	});
});
</script>
</head>
<body>
	<?php include(DEDEMEMBER."/templets/head_btc.htm"); ?>
	<!--id="help-list" 新闻-->
	<div id="help-list" class="help-list">
	
		<div class="btc8-content btc8-gonggao">
			<div class="gongaoh">
				<h1>帮助</h1>
			</div>
			<div style="min-height:300px" class="btc8-gonggaoleft">
				<ul>
					<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
?>
					<li>
						<span><?php echo date("Y-m-d",$fields["senddate"]);?></span><a href='?num=<?php echo $fields["id"];?>' ><?php echo $fields["title"];?></a>
					</li>
					<?php
}
}?>
				</ul>
				<div class="btc8-ggleft-fenye">
					<!--<span>共 1 页/11条记录</span>-->
					<a href="">首页</a>
					<a href="">上一页</a>
					<a href="" class="btc8-fy-avist">1</a>
					<!--<a href="">2</a>
					<a href="">3</a>-->
					<a href="">下一页</a>
					<a href="">尾页</a>
				</div>
			</div>
			<div style="min-height:300px" class="btc8-gonggaoright" id="arcdiv">
				<div class='btc8-ggr-h'>
					<h1 style='text-align:center;line-height:30px;'><?php echo $rtil["title"];?> </h1>
					<h4 style='text-align:center; line-height:20px;font-weight:normal;font-size:12px;color:#666;'>作者：<?php echo $rtil["write"]; echo date("Y-m-d",$rtil["senddate"]);?></h4>
				</div>
				<div class='btc8-ggr-b'><?php echo $rbody["body"];?></div>
				<!--<div class="btc8-ggr-f">
					<div class="btc8-ggr-f1">
						上一篇：<a href="">比特吧正式启动</a>
					</div>
					<div class="btc8-ggr-f2">
						下一篇：<a href="">比特吧正式启动</a>
					</div>
				</div>-->
			</div>
            <div style="clear:both;"></div>
		</div>
	</div>
	<div id="xxx"></div>
	<script type="text/javascript">
		function archives(title,arcdate,arcwrite,senddate,content){
			var arctext="<div class='btc8-ggr-h'><h1 style='text-align:center;line-height:30px;'>"+title+"</h1><h4 style='text-align:center; line-height:20px;font-weight:normal;font-size:12px;color:#666;'>作者："+arcwrite+"    "+senddate+"</h4></div><div class='btc8-ggr-b'>"+content+"</div>";
			$("#arcdiv").html(arctext);
		}
		<?php echo $oneshow;?>
	</script>
	<!--footer  -->
	<div class="clear"></div>
	 <?php include(DEDEMEMBER."/templets/foot_btc.htm"); ?>	
	<!--footer end  -->
</body>
</html>