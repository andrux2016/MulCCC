$(document).ready(function(){ 
	  //表格奇偶行不同样式	
	  $(".list tbody tr:even").addClass("row0");//偶行
	  $(".list tbody tr:odd").addClass("row1");//奇行
  
	  $(".submit tbody tr:even").addClass("row0");//偶行
	  $(".submit tbody tr:odd").addClass("row1");//奇行
	  
	   //修正IE6下hover Bug
	  if ( $.browser.msie ){
	  	if($.browser.version == '6.0'){
	  		$("#menuBody li").hover(
	  			function(){
	  				//进行判断,是否存在
	  				//先设置所有.act为隐藏
	  				$(".act").each(function(){this.style.visibility='hidden'});
	  				if($(this).find(".act").length != 0)
	  				{
	  					$(this).children(".act").css("visibility","visible");
	  				} else {
	  					$(".act").css("visibility","hidden");
	  				}
	  			}
	  		)
	  	}
	  }	   
	  
	
	  CheckLogin();
})

function showtext() {  
    if($("#pwd").val()=="") {  
    $("#pwd_warpper").html("<input type=\"text\" value=\"请输入密码\" id=\"_pwd\" onfocus=\"showpassword();\" class=\"ipt-txt\"/>");  
    }  
}  
  
function showpassword() {  
    $("#pwd_warpper").html("<input type=\"password\" value=\"\" id=\"pwd\" onblur=\"showtext();\" class=\"ipt-txt\"/>");  
    setTimeout(function(){  
    $("#pwd").focus();  
    },20);  
}  

	
	 /******************************************************************************************
	 * 检测验证码
	 ******************************************************************************************/
	function checkvdcode() {
			$.ajax({type: reMethod,url: "checkvdcode.php",
			data: "dopost=checkvdcode&vdcode="+$("#vdcode").val(),
			dataType: 'html',
			success: function(result){$("#_vdcode").html(result);}}); 
	}
	
	function UserLogin(){
	  	
		if($('#userid').val()=="Email" || $('#userid').val()==""){
			$('#showdiv').html("请填写Email！");
			$('#userid').focus();
			return false;
		}
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
		if(!reg.test($('#userid').val())){
			$('#showdiv').html("Email格式不正确！");
			$('#userid').focus();
			return false;
		}
		if($('#pwd').val()=="密码" || $('#pwd').val()==""){
			$('#showdiv').html("请填写密码！");
			$('#pwd').focus();
			return false;
		}
		$.post("checkcode.php?act="+new Date(),{vdcode:$('#vdcode').val(),fmdo:'login',dopost:'login',keeptime:'604800',gourl:'json',userid:$('#userid').val(),pwd:$('#pwd').val()},function(msg){ 
			if(msg=="1"){
				apprise("登陆成功！", {
					animate: false,
					textOk: "确定"
					},function(r) {
						location.reload(true);
						//location.href="../trade.php";
					});
			}else{
				apprise(msg, {
					animate: false,
					textOk: "确定"
					},function(r) {
						vdimgck.src=vdimgck.src+"?";
					});
			}
					

        }); 
	}
	
	function UserQuit(){
		$.post("checkcode.php?act="+new Date(),{code:$('#vdcode').val(),fmdo:'login',dopost:'exit'},function(msg){ 
			apprise(msg, {
			animate: false,
			textOk: "确定"
			},function(r) {
				location.reload(true);
			});
        }); 
	}
	trade_global = {};
	if(trade_global.moneyid="undefinde") trade_global.money = 'CNY';
	if(trade_global.coinid="undefinde") trade_global.coin = 'BTC';
	
	function CheckLogin(){
		$.getJSON("ajax_login.php?tt="+new Date(), function(data){	
			$('#_loaduser').hide();
			if(data.username){
				$('#userinfo').show();
				$('#headrate').hide();
				$('#userName').html(data.username);
				//$('#usertop').html("<li class=\"userlink\"><a class='ugif' href=\"\" >"+data.username+"</a></li><li>[<a href=\"help.php?mark=vip/\" >"+data.mvip+"</a>][<a href=\"\" >会员中心</a>][<a href=\"#\" onclick=\"UserQuit()\">退出</a>]</li><li></li>");
				//$('#usertop').html("<li><span class=\"userlink\"><a class='ugif' href=\"\" >"+data.username+"</a></span> <span>[<a href=\"help.php?mark=vip/\" >"+data.mvip+"</a>][<a href=\"\" >会员中心</a>][<a href=\"#\" onclick=\"UserQuit()\">退出</a>]</span></li>");					$('#usertop').html("<div class='login-yes'><span class=\"userlink\"><div class='out'><a href=\"#\" onclick=\"UserQuit()\">退出</a></div><div class='name'><a class='ugif name' href=\"{dede:global.cfg_cmspath/}/member/\"title="+data.username+"  >"+data.username+"</a></div></span> <div class='hi'>hi,</div></div>");			
				uid=data.userid;
				/*$.each(data.coin, function(key, val) {   
					if(val[1] && val[1]!=0) var valshow=val[1];
					else var valshow=0.00;
					$("#userMoney").append("<li style='margin-right:15px'>"+ val[0] +":<span>"+valshow.toFixed(4)+"</span></li>");
				}); */
				var coinval=0;
				$.each(data.coin, function(key, val) {   
					if(val[1] && val[1]!=0) var valshow=Number(val[1]).toFixed(4);
					else var valshow=0.00;
					if(val[2] && val[2]!=0) var freshow=Number(val[2]).toFixed(4);
					else var freshow=0.00;
					coinval=Number(coinval)+Number(val[4]);
					if(trade_global.money==val[0]){
						$("#balance_ask_able").html(valshow);
						$("#userMoney").append("<dd><div style='width:160px;float:left'>"+ val[0] +":<font color='#FF0000'>"+valshow+"</font></div>&nbsp;&nbsp;冻结："+freshow+"</dd>");
					}
					if(trade_global.coin==val[0]){
						$("#balance_bid_able").html(valshow);
						$("#userMoney").append("<dd><div style='width:160px;float:left'>"+ val[0] +":<font color='#FF0000'>"+valshow+"</font></div>&nbsp;&nbsp;冻结："+freshow+"</dd>");
					}
				}); 
				$('#zzc').html("<a href='edit_fullinfo_btc.php'>"+Number(coinval).toFixed(4)+"</a>")
				
			}else{
				$('#_userlogin').show();
				$('#headrate').show();
			}
			
		});
		
	}
	

