<%@page contentType="text/html;charset=utf-8"%>
<%@include file="/globalpk.jsp" %>
<%
String last_key = RequestUtil.getStringParam("last_key",request,"");
System.out.println("last_key="+last_key);
String tempDomain = ConfigManager.getInstance().get("config","domain.name");
Cookies.setCookie(response,"last_key@"+tempDomain,last_key,60 * 60 * 5,tempDomain);
session.setAttribute("last_key",last_key);
out.println("");
%>