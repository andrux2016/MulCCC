<!--
$(document).ready(function()
{
	//用户类型
	if($('.usermtype2').attr("checked")==true) $('#uwname').text('公司名称：'); 
	$('.usermtype').click(function()
	{
		$('#uwname').text('用户笔名：');
	});
	$('.usermtype2').click(function()
	{
		$('#uwname').text('公司名称：');
	});
	//checkSubmit
	$('#regUser').submit(function ()
	{
		if(!$('#agree').get(0).checked) {
			alert("你必须同意注册协议！");
			return false;
		}
		if($('#txtUsername').val()==""){
			$('#txtUsername').focus();
			alert("用户名不能为空！");
			return false;
		}
		var reg = /[\u4E00-\u9FA5]/g;
		if(reg.test($('#txtUsername').val())){
			$('#txtUsername').focus();
			alert("用户名不能含中文！");
			return false;
		}
		if($('#txtUsername').val().length < idmin){
			$('#txtUsername').focus();
			alert("用户名不能小于4个字符！");
			return false;
		}
		if($('#txtPassword').val()=="")
		{
			$('#txtPassword').focus();
			alert("登陆密码不能为空！");
			return false;
		}
		if($('#txtPassword').val().length < pwdmin)
		{
			$('#txtPassword').focus();
			alert("登陆密码不能少于6个字符！");
			return false;
		}
		if($('#userpwdok').val()!=$('#txtPassword').val())
		{
			$('#userpwdok').focus();
			alert("两次登陆密码不一致！");
			return false;
		}
		
		if($('#txPwd').val()=="")
		{
			$('#txPwd').focus();
			alert("提现密码不能为空！");
			return false;
		}
		if($('#txPwd').val().length < pwdmin)
		{
			$('#txPwd').focus();
			alert("提现密码不能少于6个字符！");
			return false;
		}
		if($('#txPwdok').val()!=$('#txPwd').val())
		{
			$('#txPwdok').focus();
			alert("两次提现密码不一致！");
			return false;
		}
		if($('#txtPassword').val()==$('#txPwd').val())
		{
			$('#txPwd').focus();
			alert("登陆密码和提现密码不能相同！");
			return false;
		}
		if($('#email').val()=="")
		{
			$('#email').focus();
			alert("Email不能为空！");
			return false;
		}
		if($('#safequestion').val()=="0")
		{
			$('#safequestion').focus();
			alert("请选择密码问题！");
			return false;
		}
		if($('#safeanswer').val()=="")
		{
			$('#safeanswer').focus();
			alert("问题答案不能为空！");
			return false;
		}
		if($('#vdcode').val()=="")
		{
			$('#vdcode').focus();
			alert("验证码不能为空！");
			return false;
		}
	})
	
	//AJAX changChickValue
	$("#txtUsername").change( function() {
		var reg = /[\u4E00-\u9FA5]/g;
		if(reg.test($('#txtUsername').val())){
			$('#_userid').html("<font color='red'><b>×用户名不能含中文</b></font>");
			$('#txtUsername').focus();
		}else if($('#txtUsername').val().length < idmin){
			$('#_userid').html("<font color='red'><b>×用户名太短</b></font>");
			$('#txtUsername').focus();
		}else{
			$.ajax({type: reMethod,url: "index_do.php",
			data: "dopost=checkuser&fmdo=user&cktype=1&uid="+$("#txtUsername").val(),
			dataType: 'html',
			success: function(result){$("#_userid").html(result);}}); 
		}
		
	});
	
	
	$("#email").change( function() {
		var sEmail = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
		if(!sEmail.exec($("#email").val()))
		{
			$('#_email').html("<font color='red'><b>×Email格式不正确</b></font>");
			$('#email').focus();
		}else{
			$.ajax({type: reMethod,url: "index_do.php",
			data: "dopost=checkmail&fmdo=user&email="+$("#email").val(),
			dataType: 'html',
			success: function(result){$("#_email").html(result);}}); 
		}
	});	
	
	$('#txtPassword').change( function(){
		if($('#txtPassword').val().length < pwdmin)
		{
			$('#_userpwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else if($('#userpwdok').val()!=$('txtPassword').val())
		{
			$('#_userpwdok').html("<font color='red'><b>×两次输入密码不一致</b></font>");
		}
		else if($('#userpwdok').val().length < pwdmin)
		{
			$('#_userpwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else
		{
			$('#_userpwdok').html("<font color='#4E7504'><b>√填写正确</b></font>");
		}
	});
	$('#userpwdok').change( function(){
		if($('#txtPassword').val().length < pwdmin)
		{
			$('#_userpwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else if($('#userpwdok').val()=='')
		{
			$('#_userpwdok').html("<b>请填写确认密码</b>");
		}
		else if($('#userpwdok').val()!=$('#txtPassword').val())
		{
			$('#_userpwdok').html("<font color='red'><b>×两次输入密码不一致</b></font>");
		}
		else
		{
			$('#_userpwdok').html("<font color='#4E7504'><b>√填写正确</b></font>");
		}
	});
	
	
	$('#txPwd').change( function(){
		if($('#txPwd').val().length < pwdmin)
		{
			$('#_txPwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else if($('#txPwdok').val()!=$('txPwd').val())
		{
			$('#_txPwdok').html("<font color='red'><b>×两次输入密码不一致</b></font>");
		}
		else if($('#txPwdok').val().length < pwdmin)
		{
			$('#_txPwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else
		{
			$('#_txPwdok').html("<font color='#4E7504'><b>√填写正确</b></font>");
		}
	});
	$('#txPwdok').change( function(){
		if($('#txPwd').val().length < pwdmin)
		{
			$('#_txPwdok').html("<font color='red'><b>×密码不能小于"+pwdmin+"位</b></font>");
		}
		else if($('#txPwdok').val()=='')
		{
			$('#_txPwdok').html("<b>请填写确认密码</b>");
		}
		else if($('#txPwdok').val()!=$('#txPwd').val())
		{
			$('#_txPwdok').html("<font color='red'><b>×两次输入密码不一致</b></font>");
		}
		else
		{
			$('#_txPwdok').html("<font color='#4E7504'><b>√填写正确</b></font>");
		}
	});
	
	
	$("a[href*='#vdcode'],#vdimgck").bind("click", function(){
		$("#vdimgck").attr("src","../include/vdimgck.php?tag="+Math.random());
		return false;
	});
});
-->