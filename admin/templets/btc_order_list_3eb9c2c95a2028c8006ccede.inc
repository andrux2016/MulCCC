<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>">
<title>订单管理</title>
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
	location.href = "btc_order_list.php?dopost=delete&nid="+nid;
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
	<form name="form2" method="get" action="btc_order_list.php">
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
    <td height="28" colspan="11" bgcolor="#EDF9D5" background='images/tbg.gif'>
    	<table width="98%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="30%"  style="padding-left:10px;"><strong>挂单列表：</strong> </td>
          <td width="45%" align="right" style="padding-top:4px">
          	<input type="button" name="ss13" value="买" style="width:50px;margin-right:6px" onClick="location='btc_order_list.php?dealtype=0';"  class='np coolbg'/>
            <input type="button" name="ss14" value="卖" style="width:50px;margin-right:6px" onClick="location='btc_order_list.php?dealtype=1';"  class='np coolbg'/>
          	<?php echo GetCoinBN();?>
            <input type="button" name="ss16" value="全部" style="width:40px;margin-right:6px" onClick="location='btc_order_list.php';"  class='np coolbg'/>
          </td>
          <td width="25%" align="right" style="padding-top:4px">
          	<input type="button" name="ss12" value="订单管理" style="width:90px;margin-right:6px" onClick="location='btc_order_list.php';"  class='np coolbg'/>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="5%" align="center">选择</td>
	<td width="10%" align="center">订单号</td>
    <td width="12%" align="center">生成日期</td>
    <td align="center">数量</td>
    <td align="center">单价</td>
    <td align="center">合计</td>
    <td align="center">手续费</td>
	<td align="center">类型</td>
    <td align="center">买卖</td>
    <td align="center">用户</td>
	<td align="center">市场</td>

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
<input type='checkbox' name='aids' value='<?php echo $fields['oid']?>' class='np'></td>
      <td align="center">
        <?php echo $fields['oid'];?>
      </td>
	  <td align="center">
        <?php echo GetDateTimeMk($fields['ordertime'])?>
        <!--(<font color="#FF0000"><?php echo floorTime(time()-$fields['dealtime'],$fields['dealtime']); ?></font>)-->
      </td>
      <td align="center">
        <?php echo $fields['btccount']?>
        <!--<a href="javascript:OpenMyWinN('btc_order_list_cart.php?oid=<?php echo $fields['id']?>',680,450);" >[详情]</a>-->
      </td>
      
      <td align="center">
        <?php echo $fields['uprice']?>
      </td>
      <td align="center">
        <?php echo $fields['tprice']?>
      </td>
	  <td align="center">
        <?php echo $fields['bkage']?>
      </td>
      <td align="center">
        <?php echo GetsCoin($fields['moneyid'])?>-<?php echo GetsCoin($fields['coinid'])?>
      </td>
      <td align="center">
        <?php echo GetsType($fields['dealtype'])?>
      </td>
      <td align="center">
		<?php echo GetMemberID($fields['userid'])?>
      </td>
	  <td align="center">
        <?php echo $fields['market']?>
      </td>

    </tr>
<?php
}
}?>
  </form>
  <tr>
    <td height="28" colspan="11" bgcolor="#FCFDF7" style="padding-top:4px;">&nbsp;
      <input type="button" name="b7" value="反选" class='np coolbg' style="width:40" onClick="ReSel();">
      <input type="button" name="b" value="删除" style="width:40" onClick="DelSel();" class='np coolbg' />
      <!--<input type="button" name="b5" value="设为已付款状态" class='np coolbg' style="width:100" onClick="UpSel();">
      <input type="button" name="b9" value="设为已发货状态" class='np coolbg' style="width:100" onClick="PushSel();">
	  <input type="button" name="b9" value="设为已完成状态" class='np coolbg' style="width:100" onClick="OkSel();">-->
    </td>
  </tr>
  <tr>
    <td height="36" colspan="11" bgcolor="#F9FCEF" align="center">
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