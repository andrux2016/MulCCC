/*
 JavaScript Document 
 id="user-login-box-page" 会员登录
 */ 
 
$(document).ready(function(){	  
	  //功能：获得焦点外围变色
	  $('.user-login-box-page .l .login .row-un .user_name_bd .inp_user_name').focus(function(){
		  $('.user-login-box-page .u-box').removeClass('cur');
		  $(this).parent().addClass('cur');
	   });
	  $('.user-login-box-page .l .login .row-up .user_pwd_bd .inp_user_pwd').focus(function(){
		  $('.user-login-box-page .u-box').removeClass('cur');
		  $(this).parent().addClass('cur');
	   });
	   //功能：失去焦点
	    $('.user-login-box-page .l .login .row-un .user_name_bd .inp_user_name').blur(function(){
		  $('.user-login-box-page .u-box').removeClass('cur');		   
	   });
		 $('.user-login-box-page .l .login .row-up .user_pwd_bd .inp_user_pwd').blur(function(){
		  $('.user-login-box-page .u-box').removeClass('cur');		   
	   });
		
	  
});
 
 
 
 
 
 
 
 
 
 