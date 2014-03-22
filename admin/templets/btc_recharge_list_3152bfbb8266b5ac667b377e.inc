<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>">
<title>充值审核</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<script language="javascript">
//获得选中项
function getCheckboxItem()
{
	var allSel="";
	if(document.form1.aids.value) return document.form1.aids.value;
	for(i=0;i<document.form1.aids.length;i++)
	{
		if(document.form1.aids[i].checked)
		{
			if(allSel=="")
			allSel=document.form1.aids[i].value;
			else
			allSel=allSel+"`"+document.form1.aids[i].value;
		}
	}
	return allSel;
}
function UpSel()
{
	var nid = getCheckboxItem();
	if(nid==""){
		alert("请选择要更改的订单记录!\r\n");
		return ;
	}
	location.href = "btc_recharge_list.php?dopost=up&nid="+nid;
}

function CancelSel()
{
	var nid = getCheckboxItem();
	if(nid==""){
		alert("请选择要更改的订单记录!\r\n");
		return ;
	}
	location.href = "btc_recharge_list.php?dopost=re&nid="+nid;
}
function ReSel()
{
	for(i=0;i<document.form1.aids.length;i++)
	{
		if(document.form1.aids[i].checked) document.form1.aids[i].checked = false;
		else document.form1.aids[i].checked = true;
	}
}
function DelSel()
{
	var nid = getCheckboxItem();
	if(nid==""){
		alert("请选择要删除的业务记录!\r\n");
		return ;
	}
	if (confirm("真的要删除吗？")){
				location.href = "btc_recharge_list.php?dopost=delete&nid="+nid;
				
		}else{
			return false;
	}
	
}

function OpenMyWinN(surl,w,h){
	window.open(surl, "popUpMyWinN", "scrollbars=yes,resizable=yes,statebar=no,width="+w+",height="+h+",left=200, top=100");
}
</script>
</head>
<body background='images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" cellpadding="1" cellspacing="1" align="center" class="tbtitle" style="background:#cbd8ac;margin-bottom:5px">
  <tr>
    <td height="30" align="center" bgcolor="#FAFCEF">
	<form name="form2" method="get" action="btc_recharge_list.php">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><select name="typeId" id="typeId">
              <option value="1">定单号</option>
              <option value="2">用户名</option>
            </select></td>
            <td>
              <input name="sid" type="text" id="sid"></td>

            <td width="100" align="center"> <input name="imageField" type="submit" class='np coolbg' value="搜索">
            </td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
<table width="98%" border="0" cellpadding="1" cellspacing="1" align="center" class="tbtitle" style="background:#cfcfcf;">
  <tr>
    <td height="28" colspan="13" bgcolor="#EDF9D5" background='images/tbg.gif'>
    	<table width="98%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="30%"  style="padding-left:10px;"><strong>充值单列表：</strong> </td>
          <td width="45%" align="right" style="padding-top:4px">
          	
          	<?php echo $CoinBN;?>
            <input type="button" name="ss16" value="全部" style="width:40px;margin-right:6px" onClick="location='btc_recharge_list.php';"  class='np coolbg'/>          </td>
          <td width="25%" align="right" style="padding-top:4px">
          	<input type="button" name="ss12" value="订单管理" style="width:90px;margin-right:6px" onClick="location='btc_recharge_list.php';"  class='np coolbg'/>          </td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="3%" align="center">选择</td>
	<td align="center">ID</td>
	<td align="center">类型</td>
    <td width="12%" align="center">提交日期</td>
    <td align="center">入账金额</td>
    <td align="center">手续费</td>
    <td align="center" width="240">单号</td>
    <td align="center">转入账号</td>
    <td align="center">用户</td>
    <td align="center">支付方式</td>
	<td align="center">接口</td>
	<td align="center">审核</td>
	<td align="center">入账</td>
  </tr>
  <form name="form1">
<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
?>
    <tr bgcolor="#FFFFFF" height="26" align="center" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
      <td align="center">
<input type='checkbox' name='aids' value='<?php echo $fields['id']?>' class='np'>
</td>
      <td align="center">
        <?php echo $fields['id'];?>      </td>
		<td align="center">
        <?php echo $coinarr[$fields['coinid']];?>      </td>
	  <td align="center">
        <?php echo GetDateTimeMk($fields['rcgtime'])?>      </td>
      <td align="center">
        <?php echo $fields['amount']/1?> <input name="money_<?php echo $fields['id']?>" value="<?php echo $fields['amount']?>" type="hidden" id="money_<?php echo $fields['id']?>" style="width:80%" class='pubinputs' />   </td>
		<td align="center">
        <?php echo $fields['fee']*10/10?>
        <!--<a href="javascript:OpenMyWinN('btc_recharge_list_cart.php?oid=<?php echo $fields['id']?>',680,450);" >[详情]</a>-->      </td>
        <td align="center"><?php echo $fields['txid'];?></td>
      <td align="center">
        <?php echo $fields['address']?>      </td>
      
      
      <td align="center"><?php echo GetMemberID($fields['userid'])?> </td>
      <td align="center"><?php echo GetsPayment($fields['paytype'])?> </td>
	  <td align="center"><?php echo GetsDealmark($fields['dealmark'])?> </td>
	  <td align="center"><?php echo GetsChecked($fields['checked'])?> </td>
	  <td align="center"><?php echo GetsAddUser($fields['adduser'])?></td>
    </tr>
<?php
}
}?>
  </form>
  <tr>
    <td height="28" colspan="13" bgcolor="#FCFDF7" style="padding-top:4px;">&nbsp;
      <input type="button" name="b7" value="反选" class='np coolbg' style="width:40" onClick="ReSel();">
      <input type="button" name="b5" value="设为审核通过" class='np coolbg' style="width:100" onClick="UpSel();">
	  <input type="button" name="b5" value="取消审核(已入账不能取消)" class='np coolbg' style="width:200" onClick="CancelSel();">
	  <input type="button" name="b" value="删除" style="width:40" onClick="DelSel();" class='np coolbg' />
      <!--
	  <input type="button" name="b9" value="设为已发货状态" class='np coolbg' style="width:100" onClick="PushSel();">
	  <input type="button" name="b9" value="设为已完成状态" class='np coolbg' style="width:100" onClick="OkSel();">-->    </td>
  </tr>
  <tr>
    <td height="36" colspan="13" bgcolor="#F9FCEF" align="center">
     <?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listitem'] = 'info,index,end,pre,next,pageno';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
    </td>
  </tr>
</table>
</body>
</html>