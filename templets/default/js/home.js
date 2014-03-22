/*
 JavaScript Document 
BTC最新成交    LTC最新成交    XPM最新成交
*/  
$(document).ready(function(){	  
		 
	  
});
 
function showList(id,obj){
	$('.hq-content .title li').removeClass('cur');
	$(obj).parent().addClass('cur');
	$('.hq-content .real table.transaction').hide();
	$('.hq-content .real table.transaction').eq(id).show();
}
 
 
 
 
 
 
 
 