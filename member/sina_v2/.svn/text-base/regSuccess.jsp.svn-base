<%@page contentType="text/html;charset=UTF-8"%>
<%@include file="/globalpk.jsp" %>
<%@taglib uri="xweb" prefix="xweb" %>
<%@taglib prefix="oscache" uri="/oscache" %>
<%@include file="/refresh_1234800.jsp" %>
<%@include file="/wish/func.jsp" %>
<%
String globalMedia=GlobalMethod.getCookie(request,"GLOBALMEDIA");
String GLOBALMEDIAREFERER=GlobalMethod.getCookie(request,"GLOBALMEDIAREFERER");
String fromFlag=globalMedia;
String fromWhere=GLOBALMEDIAREFERER;
if(request.getAttribute("regSuccess")!=null&&((globalMedia!=null&&!globalMedia.trim().equals(""))||(GLOBALMEDIAREFERER!=null&&!GLOBALMEDIAREFERER.trim().equals("")))){
	UserInfo regUser = (UserInfo)request.getAttribute("userInfo");
	System.out.println("注册成功且来源不为空,写数据库...");
	AccessInfo access = new AccessInfo();
	access.setActType((int)1);//用户注册
	access.setCreateTime(System.currentTimeMillis());
	if((GLOBALMEDIAREFERER!=null&&!GLOBALMEDIAREFERER.trim().equals(""))){
		try{
			URL aURL = new URL(GLOBALMEDIAREFERER);
			if(aURL!=null){ 
				access.setFromMedia(aURL.getHost());
			}else{
				access.setFromMedia(globalMedia);
			}
		}catch(Exception e){
			e.printStackTrace();
			access.setFromMedia(globalMedia);
		}
	}else{
		access.setFromMedia(globalMedia);
	}
	access.setUserEmail(regUser.getUserEmail());
	access.setUserId(regUser.getUserId());
	access.setUserName(regUser.getUserName());
	access.setRemark(GLOBALMEDIAREFERER);
	AccessInfoProxy.getInstance().createAccessInfo(access);
	if(fromWhere!=null){
		if(fromFlag==null||fromFlag.equals("")){
			fromFlag=globalMedia;
		}
		if(fromWhere.length()>250)fromWhere = fromWhere.substring(0,248);
		if(fromFlag!=null&&fromFlag.length()>250)fromFlag = fromFlag.substring(0,248);
		System.out.println("更新用户来源!"+(regUser==null?"":regUser.getUserId()));
		if(regUser!=null){
			DBOper.exeSql("update user_info set from_flag='"+fromFlag+"',from_where='"+fromWhere+"' where user_id="+regUser.getUserId());
		}
		
	}
}
%>
<%

String isActive=GlobalMethod.getDictText("other_condition","email_is_active");
String regSuccess=null;
String tempDomain = com.syh.common.ConfigManager.getInstance().get("config","domain.name");
String inviteUserId = com.syh.common.Cookies.getCookie(request,"inviteUserId" + "@" + tempDomain);
if(request.getAttribute("regSuccess")!=null){
	UserInfo regUser = (UserInfo)request.getAttribute("userInfo");
	int curUserId = regUser.getUserId();
	if(inviteUserId!=null&&!inviteUserId.trim().equals("")){
		Invite obj = new Invite();
		obj.setUserId(Integer.valueOf(inviteUserId));
		obj.setOtherUserId(curUserId);
		List list = InviteProxy.getInstance().queryInfo(obj,1);
		if(list.size()<=0){
			UserInfo inviteUser = UserInfoProxy.getInstance().getObj(Integer.valueOf(inviteUserId));
			obj.setUserName(inviteUser.getUserName());
			obj.setUserEmail(inviteUser.getUserEmail());
			obj.setAdminId(0);
			obj.setCreateTime(System.currentTimeMillis());
			obj.setOtherUserIp(GlobalMethod.getRemoteIpAddr(request));
			obj.setOtherUserEmail(regUser.getUserEmail());
			obj.setOtherUserName(regUser.getUserName());
			obj.setPay("N");
			obj.setInviteStatus(1);
			InviteProxy.getInstance().createInvite(obj);
			Cookies.setCookie(response, "inviteUserId" + "@" + tempDomain,"", 0, tempDomain);
			request.removeAttribute("regSuccess");
			System.out.println("保存被邀请人记录成功!");
		}
	}
	if(isActive.equals("1")){
		MailServiceImpl mail = new MailServiceImpl();
		String subject="800用户邮箱账户激活";
		String content = GlobalMethod.getUrlContent("http://"+request.getServerName()+"/member/mailcontent1.jsp?userId="+curUserId);
		String toMail = regUser.getUserEmail();
		String from = BaseConfiguration.getString("smtp.stmpEmail", "webmaster@"+GLOBAL_DOMAIN_NAME+"");
		mail.sendMailContent(subject,content,from,toMail);
	}
}
 %>
 
  <%
                String msgContent="";
                if(isActive.equals("1")){
                	if(request.getAttribute("userInfo")!=null){
                		UserInfo regUser = (UserInfo)request.getAttribute("userInfo");
                		if(regUser!=null){
                			String regUserEmail = regUser.getUserEmail();
                			if(regUserEmail!=null&&regUserEmail.indexOf("@")!=-1){
                				String regUserEmailArr[]=regUserEmail.split("@");
                				if(regUserEmailArr.length==2){
                					String showEmailHttp="mail."+regUserEmailArr[1]+"";
                					if(regUserEmailArr.equals("gmail.com"))showEmailHttp="gmail.com";
                					msgContent="注册还未完成，请到<a href='http://"+showEmailHttp+"/'>http://"+showEmailHttp+"/</a>激活您的账户，来完成注册。";
                				}else{
                					System.out.println("regUserEmail="+regUserEmail+"格式不正确");
                					msgContent=" 注册未完成,请查收您的邮箱并激活您的账户，来完成注册!";
                				}
                			}else{
                				System.out.println("regUserEmail="+regUserEmail+"为空");
                				msgContent=" 注册未完成,请查收您的邮箱并激活您的账户，来完成注册!";
                			}
                		}else{
                			msgContent=" 注册未完成,请查收您的邮箱并激活您的账户，来完成注册!";
                		}
                	}else{
                		msgContent=" 注册未完成,请查收您的邮箱并激活您的账户，来完成注册!";
                	}
                }else{
	                //out.println(" 注册成功,<a href='/'>返回首页</a>!");
	                String friendWishId = Cookies.getCookie(request, "friend_wish_id" + "@" + tempDomain);
	                if(friendWishId==null||friendWishId.trim().equals(""))friendWishId=session.getAttribute("friend_wish_id")==null?"":session.getAttribute("friend_wish_id").toString();
	                if(friendWishId==null||friendWishId.trim().equals("")){
	                	String logintourl = session.getAttribute("logintourl")==null?"/":session.getAttribute("logintourl").toString();
	                	msgContent=" 注册成功,<a href='"+logintourl+"'>返回</a>!";
	                }else{
	                	//跳到购物车
	                	try{
	                		MyWish wish = MyWishProxy.getInstance().getMyWish(Integer.valueOf(friendWishId));
	                		String proIdStr=wish.getWishProId();
	                		String proIdStrArr[]=proIdStr.split(",");
	                		for(int i=0,n=proIdStrArr.length;i<n;i++){
	                			setCookie(response,request,"order",Integer.parseInt(proIdStrArr[i]));
	                		}
	                		out.println("<script>location.href='/800/charge/showcart';</script>");
	                		return;
	                	}catch(Exception e){
	                		e.printStackTrace();
	                		out.println("<script>location.href='/';</script>");
	                		return;
	                	}
	                }
	               
                }
                String logintourl = session.getAttribute("logintourl")==null?"/":session.getAttribute("logintourl").toString();
             	double regMoney = BaseConfiguration.getDouble("reg.money",10);
                 %>
                 
                 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/p2.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>注册成功-<%=FragProxy.getInstance().getFragContent(10) %></title>
<!-- InstanceEndEditable -->
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/hand_foot.css" rel="stylesheet" type="text/css" />

</head>
<body>
<%@include file="/share/header.jsp" %>
<div class="b_6"></div>
<div align="center"></div>

<div class="d_main_960 border_solid1">
 <div class="b_16" >
  <table style="line-height:30px;"><tr><td width="100"></td><td>
  <span style='font-size:16px;'><b><img src="/templates/800/images/mall/bg-pay-return-success.gif"/>恭喜您，注册成功!
  <%
  if(logintourl.equals("/800/cart/confirmation")){
 		%>
 			<a href='/800/cart/confirmation' style="color:red;font-size:16pt;">返回购物车,进行订单信息确认</a>
 		<%
 	
 	}else{
   %>
  现在就去<a href='<%=logintourl %>' style="color:red;font-size:16pt;">抢购</a>吧</b> </span>
    <%} %>
  </td></tr>
 </table>
  </div>
</div>
<%@include file="/share/footer_ad.jsp" %>
<%@include file="/share/footer.jsp" %>
</body>
<!-- InstanceEnd --></html>


