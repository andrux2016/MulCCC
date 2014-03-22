<%@page contentType="text/html;charset=UTF-8"%>
<%@include file="/globalpk.jsp" %>
<%@taglib uri="xweb" prefix="xweb" %>
<%@taglib prefix="oscache" uri="/oscache" %>
<%@page import="com.longtech.security.Encoder" %>
<%
String sina_user_id="testsunyuhai";
%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/p1.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户登录-<%=FragProxy.getInstance().getFragContent(10) %></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="description" content="800女性团购网站，最专业的化妆品团购和护肤品团购网站,800给您每天带来超值、低价的化妆品和护肤品，并且保证正品、假一赔三、三十天无条件退货。"/>
<meta name="keywords" content="800,团购,化妆,美妆,护肤,打折,抢购,秒杀,化妆品团购,护肤品团购,女性团购, 800团购网" />
<link rel="shortcut icon" href="http://img.800.cn/images/favicon.ico" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="/feed" rel="alternate" title="订阅更新" type="application/rss+xml" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/hand_foot.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/js/String.js"></script>
<script>
$(document).ready(function() {
	$('#subscribe_email_img').click(function(){
		if($('#subscribe_email').val().is('email')==null){
			$('#subscribe_email').val('email输入不正确!');
		}else{
			location.href='/800/subscribe';
		}
  	});
  	$('#userEmail').change(function(){
  		if($('#userEmail').val()==''){
  			$('#emailInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">请输入Email</span>');	
  		}else{
  			if(!($('#userEmail').val().is("email"))){
				$('#emailInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">用户email输入不正确!</span>');
				$('#userEmail').focus();
				return false;
			}else{
				$('#emailInint').html('<img src="/images/ico_d.gif" width="14" height="14" hspace="3" align="absmiddle" />');
				checkEmailExist($('#userEmail').val());
			}
  		}
  	});
  	
  	$('#password1').change(function(){
  		checkPasswd();
  	});
  	
  	$('#mobile').change(function(){
  		if($('#mobile').val().strLength()>0){
  			if(!$('#mobile').val().is('mobile')){
  				$('#regMobileInit').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">手机号输入不正确!</span>');
  			}else{
  				$('#regMobileInit').html('<img src="/images/ico_d.gif" width="14" height="14" hspace="3" align="absmiddle" />');
  			}
  		}
  	});
  	
  	$('#password2').change(function(){
  		checkPasswd();
  	});
  	
  	$('#regUserName').change(function(){
  		if($('#regUserName').val()==''){
  			$('#usernameInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">请输入用户名</span>');	
  		}else{
			$('#usernameInint').html('<img src="/images/ico_d.gif" width="14" height="14" hspace="3" align="absmiddle" />');
			checkUserExist($('#regUserName').val());
			
  		}
  	});
  	$('#headerLoginUserPwd').keypress(function(event){
  		if(event.keyCode==13){
  			checkHeaderLoginFrm();
  		}
  	});
  	$('#headerLoginButton').click(function(){
  		checkHeaderLoginFrm();
  	});
  	$('#regcommitok').click(function(){
  		var flag = checkRegFrm();
  	});
});
	function checkEmailExist(email){
		var randNum = Math.random(1000);
		$.post(
		    	"/member/checkUserEmail.jsp",
		    	{randNum:randNum,email: email},
		    	function(data){
			    	var html = "";
			    	var json = eval("("+data+")");
			    		if(json.para1==1){
			    			$('#emailInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" />Email已存在!');
			    		}else if(json.para1==2){
			    			$('#emailInint').html('<img src="/images/ico_d.gif" width="14" height="14" hspace="3" align="absmiddle" />');
			    		}else if(json.para1==3){
			    			$('#emailInint').html("无权限!");
			    		}else{
			    			$('#emailInint').html("无权限!");
			    		}
		    }
		   );
	}
	
	function checkUserExist(name){
		var randNum = Math.random(1000);
		$.post(
		    	"/member/checkUserName.jsp",
		    	{randNum:randNum,name: name},
		    	function(data){
			    	var html = "";
			    	var json = eval("("+data+")");
			    		if(json.para1==1){
			    			$('#usernameInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" />用户名已存在!');
			    		}else if(json.para1==2){
			    			$('#usernameInint').html('<img src="/images/ico_d.gif" width="14" height="14" hspace="3" align="absmiddle" />');
			    		}else if(json.para1==3){
			    			$('#usernameInint').html("无权限!");
			    		}else{
			    			$('#usernameInint').html("无权限!");
			    		}
		    }
		   );
	}
	
    function checkPasswd(){
    		if($('#password1').val()==''){
				$('#regUserPwdInit1').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">密码不能为空!</span>!');
				$('#password1').focus();
				return false;
			}else{
				if($('#password1').val().strLength()<3){
					$('#regUserPwdInit1').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">密码不能少于3个字符!</span>!');
					$('#password1').focus();
					return false;
				}
			}
			$('#regUserPwdInit1').html('<img src="/images/ico_d.gif" width="14" height="14" hspace="3" align="absmiddle" />');
			if($('#password1').val()!=$('#password2').val()){
				$('#regUserPwdInit2').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">两次密码不一致，请重新输入!</span>');
				$('#password2').focus();
				return false;
			}
			$('#regUserPwdInit2').html('<img src="/images/ico_d.gif" width="14" height="14" hspace="3" align="absmiddle" />');
    }
	function checkRegFrm(){
			if($('#userEmail').val()==''){
				$('#emailInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">用户email不能为空!</span>');
				$('#userEmail').focus();
				return false;
			}
			if(!($('#userEmail').val().is("email"))){
				$('#emailInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">用户email输入不正确!</span>');
				$('#userEmail').focus();
				return false;
			}
			if($('#regUserName').val()==''){
				$('#usernameInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">用户名不能为空!</span>');
				$('#regUserName').focus();
				return false;
			}
			if($('#password1').val()==''){
				$('#regUserPwdInit1').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">密码不能为空!</span>!');
				$('#password1').focus();
				return false;
			}else{
				if($('#password1').val().strLength()<3){
					$('#regUserPwdInit1').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">密码不能少于3个字符!</span>!');
					$('#password1').focus();
					return false;
				}
			}
			if($('#password1').val()!=$('#password2').val()){
				$('#regUserPwdInit2').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" /><span class="color06">两次密码不一致，请重新输入!</span>');
				$('#password2').focus();
				return false;
			}
			document.getElementById("regUser.userPasswd").value=$('#password1').val();
			document.getElementById("regUser.userName").value=$('#regUserName').val();
			document.getElementById("regUser.userEmail").value=$('#userEmail').val();
			document.getElementById("regUser.mobile").value=$('#mobile').val();
			var name = $('#regUserName').val();
			var email = $('#userEmail').val();
			var mobile = $('#mobile').val();
			var password1 = $('#password1').val();
			
			var addCode=$('#regCode').val();
			
			var randNum = Math.random(1000);
			$.post(
		    	"/member/addUserInfo.jsp",
		    	{randNum:randNum,name: name,email: email,passwd:password1,mobile:mobile,code:addCode},
		    	function(data){
			    	var html = "";
			    	var json = eval("("+data+")");
			    		if(json.para1==1){
			    			$('#emailInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" />Email已存在!');
			    		}else if(json.para1==2){
			    			$('#usernameInint').html('<img src="/images/ico_c.gif" width="14" height="14" hspace="3" align="absmiddle" />用户名已存在!');
			    		}else if(json.para1==3){
			    			document.regfrm.submit();
			    		}else{
			    			$('#usernameInint').html("code错误!");
			    		}
		    }
		   );
	}


function checkHeaderLoginFrm(){
    var name = $('#headerLoginUserName').val();
	var passwd = $('#headerLoginUserPwd').val();
	var addCode=$('#loginCode').val();
	var sinaUserId = $('#sinaUserId').val();
	if(name==''){
		$('#headerLoginUserNameErr').html("用户名/Email不能为空!");
		return;
	}
	if(passwd==''){
		$('#headerLoginUserPwdErr').html("密码不能为空!");
		return;
	}
	var randNum = Math.random(1000);
	$.post(
    	"/wish/checkUserPwd.jsp",
    	{name: name,passwd: passwd,randNum:randNum,code:addCode},
    	function(data){
	    	var json = eval("("+data+")");
	    		if(json.para1==1){
	    			location.href='/sina/login_success.jsp?sinaId='+sinaUserId+'&'+randNum;//登录成功
	    		}else if(json.para1==2){
	    			$('#headerLoginUserNameErr').html("<font color='red'>用户名或Email不存在</font>!");
	    		}else if(json.para1==3){
	    			$('#headerLoginUserPwdErr').html("<font color='red'>密码输入有误!</font>");
	    		}else{
	    			$('#headerLoginUserNameErr').html("<font color='red'>code错误!</font>");
	    		}
    }
   );
}
</script>
</head>
<body>
<%
session.setAttribute("loginjspcode",com.longtech.security.Encoder.md5Encode("login"+com.syh.common.GlobalMethod.getDictText("other_condition", "secret_key")));
 %>
<%@include file="/share/header.jsp" %>
<div class="b_6"></div>
<div align="center"><!-- InstanceBeginEditable name="ad" --><!-- InstanceEndEditable --></div>
<div class="d_main_960 border_solid1">
  <div class="b_16">
  <!-- InstanceBeginEditable name="m" -->
  <div align="center"></div>
  <h3 style="padding-left:30px;">欢迎来自新浪的用户，第一次来到八零零800.cn<br/>
		请将新浪帐号与八零零账号绑定，您以后可以直接用新浪帐号登录。 </h3>
  <hr class="hr1" />
  <div class="b_6"></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="30%" valign="top"><div class="bg03 b_6"><span class="b_10"></span>
      <span class="f12b"> 已经在八零零注册过？请输入八零零账户进行绑定</span><br/>
     <span class="f12b" style="color:#bababa;padding-left:50px;">您以后可以通过合作账号或八零零账号登录</span>
      <span class="f12b"></span>
      </div>
        <div class="b_3"></div>
        <div class="">
          <div class="b_16">
            <table border="0" align="center" cellpadding="4" cellspacing="0" width="400">
              <tr>
                <td align="right" class="bindText">帐号：</td>
                <td><input id="headerLoginUserName" name="headerLoginUserName" type="text" value="Email或用户名" class="border_solid1 bindTxt" onMouseOver="javascript:if(this.value=='Email或用户名')this.value='';"  onblur="rClass(this,'bindTxtRedBod');aClass(this,'border_solid1');"
onfocus="aClass(this,'bindTxtRedBod');rClass(this,'border_solid1');"/><span id="headerLoginUserNameErr"></span></td>
              </tr>
              <tr>
                <td align="right" class="bindText">密码：</td>
                <td><input id="headerLoginUserPwd" name="headerLoginUserPwd" type="password" class="border_solid1 bindTxt"  onblur="rClass(this,'bindTxtRedBod');aClass(this,'border_solid1');"
onfocus="aClass(this,'bindTxtRedBod');rClass(this,'border_solid1');"/><span id="headerLoginUserPwdErr"></span></td>
              </tr>
              <tr>
                <td><input type="hidden" id="loginCode" name="loginCode" value="<%=com.longtech.security.Encoder.md5Encode("login"+com.syh.common.GlobalMethod.getDictText("other_condition", "secret_key")) %>"/></td>
                <td><p>
                  <input name="B1" type="button" class="d_button_login" id="headerLoginButton" style="cursor:pointer;" value="登录" />
                  <a href="/member/resetreq.jsp" class="a_blue">忘记密码</a></p>
                  <input type="hidden" id="sinaUserId" name="sinaUserId" value="<%=sina_user_id+"@sina" %>"/>
                 </td>
                 
              </tr>
             
              
                
            </table>
          </div>
        </div>        </td>
      <td width="2%" valign="top" class="border_dashed_r1">&nbsp;</td>
      <td width="2%" valign="top">&nbsp;</td>
      <td width="66%" valign="top"><div class="bg03 b_6"><span class="b_10"></span>
     <span class="f12b"> 第一次来八零零？请补充资料，完成八零零注册</span><br/>
      <span class="f12b" style="color:#bababa;padding-left:50px;"> 您以后可以通过合作账号或八零零账号登录</span>
      </div>
        <div class="b_6"></div>
         <%
        String userEmail=RequestUtil.getStringParam("userEmail",request,"").trim();
        String regUserName=RequestUtil.getStringParam("regUserName",request,"").trim();
        String password1=RequestUtil.getStringParam("password1",request,"").trim();
        String password2=RequestUtil.getStringParam("password2",request,"").trim();
        String mobile=RequestUtil.getStringParam("mobile",request,"").trim();
        %>
        <xweb:form name="regfrm" id="regfrm" type="com.bll.act.UserInfoReg">
        <input type="hidden" id="regCode" name="regCode" value="<%=Encoder.md5Encode("reg"+GlobalMethod.getDictText("other_condition", "secret_key")) %>"/>
        <input type="hidden" id="regUser.userName" name="regUser.userName" value=""/>
        <input type="hidden" id="regUser.userPasswd" name="regUser.userPasswd" value=""/>
        
        <table border="0" cellspacing="0" cellpadding="4">
          <tr>
            <td class="bindText">Email：</td>
            <td><input name="userEmail" id="userEmail" type="text" class="border_solid1 bindTxt" value="<%=userEmail %>"  onblur="rClass(this,'bindTxtRedBod');aClass(this,'border_solid1');"
onfocus="aClass(this,'bindTxtRedBod');rClass(this,'border_solid1');"/><span class="color06" id="emailInint" name="emailInint"></span></td>
          </tr>
          <tr>
            <td class="bindText">用户名：</td>
            <td><input name="regUserName" id="regUserName" type="text" class="border_solid1 bindTxt" value="<%=regUserName %>"  onblur="rClass(this,'bindTxtRedBod');aClass(this,'border_solid1');"
onfocus="aClass(this,'bindTxtRedBod');rClass(this,'border_solid1');"/><span class="color06" id="usernameInint" name="usernameInint"></span></td>
          </tr>
          <tr>
            <td class="bindText">密码：</td>
            <td><input type="password" id="password1" name="password1" class="border_solid1 bindTxt" value="<%=password1 %>"  onblur="rClass(this,'bindTxtRedBod');aClass(this,'border_solid1');"
onfocus="aClass(this,'bindTxtRedBod');rClass(this,'border_solid1');"/><span class="color01" id="regUserPwdInit1"></span></td>
          </tr>
          <tr>
            <td class="bindText">重复密码：</td>
            <td><input type="password" id="password2" name="password2" class="border_solid1 bindTxt" value="<%=password2%>"  onblur="rClass(this,'bindTxtRedBod');aClass(this,'border_solid1');"
onfocus="aClass(this,'bindTxtRedBod');rClass(this,'border_solid1');"/><span class="color01" id="regUserPwdInit2"></span></td>
          </tr>
          <tr>
            <td class="bindText">手机号：</td>
            <td><input name="mobile" id="mobile" type="text" class="border_solid1 bindTxt" value="<%=mobile %>"  onblur="rClass(this,'bindTxtRedBod');aClass(this,'border_solid1');"
onfocus="aClass(this,'bindTxtRedBod');rClass(this,'border_solid1');"/><span class="color01" id="regMobileInit"></span></td>
          </tr>
          <tr>
            <td align="right" class="bindText">验证码</td>
            <td>
            <xweb:token inputClass="textfile" inputStyle="font_bluered" index="3" size="10"/><span class="color01" id="checkCodeInit"><xweb:error name="userCheckCodeErr"/></span>
			</td>
          </tr>
          <tr>
            <td><input type="hidden" id="regUser.userEmail" name="regUser.userEmail" value=""/>
             <input type="hidden" id="regUser.mobile" name="regUser.mobile" value=""/>
             <input type="hidden" name="actionMode" value="new"/>
             <input type="hidden" id="regUser.publishInfo" name="regUser.publishInfo" value="<%=sina_user_id+"@sina" %>"/>
             <input type="hidden" name="destURL" value="/sina/regSuccess.jsp"/>
            </td>
            <td>
           
            <input type="button" class="d_button_login" name="regcommitok" id="regcommitok" onclick="checkRegFrm()"  style="cursor:pointer;" value="注册" /></td>
          </tr>
        </table>
        </xweb:form>		
        </td>
    </tr>
  </table>
  <p>&nbsp;</p>
    <!-- InstanceEndEditable -->
  </div>
</div>
<%@include file="/share/footer_ad.jsp" %>
<%@include file="/share/footer.jsp" %>
</body>
<!-- InstanceEnd --></html>

