<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>" />
<title><?php echo $cfg_webname; ?> - 用户中心 - 会员充值 </title>
<link href="templets/style/base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/style/scport.css" rel="stylesheet" media="screen" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo $GLOBALS['cfg_templets_skin']; ?>/js/scport1.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['cfg_templets_skin']; ?>/js/jquery_1_7_2.js"></script>
<script type="text/javascript" src="templets/js/j.js"></script>
<script type="text/javascript" src="templets/js/load.js"></script>
<script type="text/javascript" src="templets/js/leftmenu.js"></script>

<script language='javascript'>
$(function(){


	$("#amount").change( function() {
		if($('#paytype').val()==""){
			/*apprise('选择充值方式！', {
				animate: false,
				textOk: "确定"
			});
			$("#amount").val("");
			return false;*/
		}else{
			$("#amountdiv").html((Number($("#amount").val())+Number($("#amount").val()*$('#payfee').val())).toFixed(2));
		}
	});
});

function getaddress(action){
	$.getJSON("ajax_address.php?cointype=<?php echo $cointype;?>&action="+action+"&tt="+new Date(), function(data){
			if(data){
				$("#address").val(data.address);
				$("#uaddress").html(data.address);
			}
	});
}

function CheckBTC(){
		if($('#txid').val()==""){
			$('#txid').focus();
			alert("请填写转账单号！");
			return false;
		}
		if($('#vdcode').val()==""){
			$('#vdcode').focus();
			alert("验证码不能为空！");
			return false;
		}
}

function selectPay(payid,num,fee,account,cod){
	$('#paytype').val(payid);
	$('#payfee').val(fee);
	$('#account').val(account);
	$('#cod').val(cod);
	$('#_address').html(account);
	for(var i=0;i<=num;i++){
		$('#pay'+i).css("border","5px solid #FFF");
	}
	$('#pay'+payid).css("border","5px solid green");
		if($('#amount').val()!=""){
			
			$("#amountdiv").html((Number($("#amount").val())+Number($("#amount").val()*$('#payfee').val())).toFixed(2));
		}
}


function selectType(showname,hidename,num){
	$('#'+hidename).hide();
	$('#'+showname).show();
	$('#'+hidename+"_bun").hide();
	$('#'+showname+"_bun").show();
	$('#'+hidename+"_mune").addClass("hidemune");
    $('#'+hidename+"_mune").removeClass("showmune");
	$('#'+showname+"_mune").addClass("showmune");
    $('#'+showname+"_mune").removeClass("hidemune ");
	$('#paytype').val("");
	$('#payfee').val("");
	$('#account').val("");
	$('#cod').val("");
	$('#_address').html("");
	for(var i=0;i<=num;i++){
		$('#pay'+i).css("border","5px solid #FFF");
	}
	
}

function CheckCNY(){
	if($('#paytype').val()=="" || $('#cod').val()==""){
		apprise('选择充值方式！', {
			animate: false,
			textOk: "确定"
		});
		return false;
	}
	if($('#amount').val()==""){
		apprise('充值金额不能为空！', {
			animate: false,
			textOk: "确定"
		});
		$('#amount').focus();
		return false;
	}
	if($('#vdcode1').val()==""){
		apprise('验证码不能为空！', {
			animate: false,
			textOk: "确定"
		});
		$('#vdcode1').focus();
		return false;
	}
	$("#amountdiv").html((Number($("#amount").val())+Number($("#amount").val()*$('#payfee').val())).toFixed(2));
	var recmsg='充值方式：'+ $('#payname'+$('#paytype').val()).val() +'<br>收款账户：'+ $('#account').val() +'<br>充值金额：'+ $("#amountdiv").html() +'(包含手续费)<br>转账备注：<?php echo $cfg_ml->M_LoginID;?>/'+$('#txid').val()+'<br><strong><font color=\"#FF0000\">提示：转账备注很重要，在您转账的时候务必填上备注，切记！</font></strong>'
		
		var json_req = {
					action: 'do',
					coinid: $('#coinid').val(),
					paytype: $('#paytype').val(),
					//vdcode1: $('#vdcode1').val(),
					address: $('#account').val(),
					amount: $("#amount").val(),
					fee: Number($("#amount").val())*Number($('#payfee').val()),
					txid: '<?php echo $cfg_ml->M_LoginID;?>/'+$('#txid').val()
				};
				$.ajax({
					type: "post",
					url: "buy_btc.php?op=" + new Date(),
					data: json_req,
					dataType: "json",
					success: function(data) {
						//alert(json_req.fee+json_req.coinid);
						if($("#cod").val()==1){
							apprise(recmsg, {
									animate: false,
									textOk: "确定"
									
							},
								function(r) {
									window.location.href="operation_btc.php";
								}
							);
						}else{
							//window.open("http://localhost/member/redirect.php?Billno=<?php echo $order_sn?>&Amount="+$('#amount').val()+"&Bankco="+$('#account').val()+"&DispAmount="+$('#amount').val()+"&Attach=<?php echo $cfg_ml->M_LoginID;?>/"+$('#txid').val());
							apprise("已经为您跳转到网银支付页面，网银转账充值是否成功？", {
									'verify': true,
									'textYes': "转账成功",
									'textNo': "不成功，重新支付"
							},
								function(r) {
									if(r) window.location.href="operation_btc.php";
									else location.reload(true);
								}
							);
						}
						
					}
				});
		
}

</script>



<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/nav-top.css" rel="stylesheet" type="text/css">
<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/footer.css" rel="stylesheet" type="text/css">
<link href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/yonghu-content.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="<?php echo $GLOBALS['cfg_templets_skin']; ?>/css/c.css"  /> 



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
							<?php
echo $coinhtml;
?>
							<div style="clear:both"></div>  
						</ul>
					</div>
					<div class="r-c">
						<ul>
							<li>冻结</li>
							<?php
echo $freehtml;
?>
							<div style="clear:both"></div>  
						</ul>
					</div>
				</div>
				<div style="clear:both"></div>  
			</div>
			  
			
				<div style="width:100%;" class="btc8-guanliright" id="content_right">
					<div id="mcpmain">
						<div id="appTab">
							<ul>
							  <?php
							  echo $coincards;
							  ?>           
							</ul>
						</div>
						<div id="mainCp">
							<!--<div class="appMsg">
							  <ul class="tips" id="userMoney"></ul>
							</div>-->
							<!--内容消息提示 --> 
							<div class="postForm">
							<h3 class="meTitle"><?php echo $cointype;?>余额：<font color="#FF0000"><?php 
							$arrcoin = Getdeposit($coinid,$cfg_ml->M_ID);
								echo $arrcoin['0']['1'];
							?></font></h3>		
							<?php    //?type
								if($cointype != "CNY"){	
								
							?>
							<form name="formrank" action="buy_btc.php" onsubmit="return CheckBTC();" method="post">
							<input type="hidden" name="coinid" value="<?php echo $coinid;?>" />
							<input type="hidden" name="action" value="do" />
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="list">
									<tbody>
										
									<?php if($buynote!=""){ ?><tr>
										<td width="18%" height="30" align="right"><strong>说明：</strong>
									  </td>
										<td height="20" colspan="2"><strong><font color="#FF0000" style="font-size:12px"><?php echo $buynote; ?></font></strong>
										
										</td>
										
									</tr><?php }?>
										<tr style='height:28px;background:#F9FEE9;'>
											<td width="15%" valign="top" colspan="2"><strong>请将 <?php echo $cointype;?> 转入以下地址，系统将自动确认您的转账并为您充值！</strong></td>
										</tr>
										<tr>
											<td width="15%" align="right" valign="top">充值地址：</td>
											<td>
											<script language='javascript'>  getaddress(0);</script>
											<span id="uaddress" style="margin-left:10px;margin-right:20px"><?php echo $uadress;?></span><input type="hidden" name="address" id="address" value="<?php echo $uadress;?>" /><button id="getadr" style=" width:80px; height:30px;" onclick="getaddress(1);" type="button">获取新地址</button></td>
										</tr>
										<?php if($recfee>0) { ?>
										<tr>
											<td width="15%" align="right" valign="top">手续费：<br>
											</td>
											<td><?php echo $recfee." ".$cointype;?>
											</td>
										</tr>
										<?php } ?>
										
									</tbody>
									
								</table>
								
								
								<table border="0" cellspacing="0" cellpadding="0" width="100%" class="list">
								 <tbody>
								 <tr >
									<td colspan="4"><strong>未确认的充值单</strong>[<a onclick="javascript:location.reload(true);" href="#">刷新</a>]</td>
								
								   </tr>
								  <tr style='height:28px;background:#F9FEE9;'>
									<td></td>
									<td>转入地址</td>
									<td>金额</td>
									<td>确认数</td>
									<td>时间</td>
									</tr>
									<?php echo $transhtml;?>
									<tr>
										<td colspan="5" align="right"><a href='operation_btc.php'>查看充值记录</a></td>
									</tr>
									</tbody>
								</table>

						  </form>
						  <?php }//?type
						  ?>
						</div>
						  
							
					</div>
					<!--主操作区域 -->
					<!--主操作区域 -->
					
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php include(DEDEMEMBER."/templets/foot_btc.htm"); ?>	

</body>
</html>