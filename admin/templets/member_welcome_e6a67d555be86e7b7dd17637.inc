<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>">
<title>邀请码管理</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
</head>
<body background='images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" cellpadding="1" cellspacing="1" align="center" class="tbtitle" style="background:#cfcfcf;">
  <form name="form1" action="member_welcome.php" method="post">
    <input type="hidden" name="dopost" value="save" />
    <tr>
      <td height="24" colspan="12" bgcolor="#EDF9D5" background='images/tbg.gif'>
      	<table width="98%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="30%"  style="padding-left:10px;"><strong>邀请码管理：</strong></td>
            <td align="right" style="padding-top:4px;">            	            </td>
          </tr>
        </table>      </td>
    </tr>
    <tr bgcolor="#FBFCE2">
      <td width="24%" height="24" align="center" valign="top">邀请码</td>
	  <td width="5%" align="center">币种</td>
      <td width="6%" align="center">预存金额</td>
	  <td width="7%" align="center">预存积分</td>
	  <td width="10%" align="center">创建时间</td>
	  <td width="6%" align="center">创建人</td>
      <td width="10%" align="center">到期时间</td>
	  <td width="8%" align="center">备注</td>
	  <td width="10%" align="center">使用时间</td>
	  <td width="8%" align="center">使用人</td>
      <td width="8%" align="center">状态</td>
      <td width="5%" align="center">&nbsp;</td>
    </tr>
   <?php $k=0;
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
?>
    <tr bgcolor="#FFFFFF" height="26" align="center" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
     
       
		<td align="center">
        <?php $k++;?> <input type="hidden" name="ID_<?php echo $k?>" value="<?php echo $fields['aid']?>" />    <?php echo $CardStr=$cfg_web_mark."-".$coinTypeArr[$fields['coinid']]."-".substr($fields['ctid'],0,5)."-".substr($fields['cardid'],5,10)."-".substr($fields['cardid'],0,5)."-".substr($fields['ctid'],5,10);?></td>
		<td align="center">
		 <?php echo $coinTypeArr[$fields['coinid']]; ?>
          </td>
	  <td align="center">
        <?php echo $fields['money']/1; ?>  </td>
		<td align="center">
        <?php echo $fields['scores']; ?>  </td>
		<td align="center">
         <?php echo date('y-m-d',$fields['stime']); ?>    </td>
		 <td align="center">
         <?php echo GetMemberID($fields['mid']); ?>    </td>
      <td align="center">
         <?php echo date('y-m-d',$fields['mtime']); ?>    </td>
		<td align="center"><?php echo $fields['cardnote']; ?></td>
		<td align="center">
        <?php if($fields['utime']) echo date('y-m-d',$fields['utime'])?>    </td>
		<td align="center">
         <?php echo GetMemberID($fields['uid']); ?>    </td>
      <td align="center">
        <?php  if($fields['isexp'] ==-1){?>
       已使用
       <?php }elseif($fields['mtime']<time()){ echo "<font color=#FF0000>过期</font>";}else{echo "<font color=#00CC00>有效</font>";}?> </td>
      
      
      <td align="center"><input name="check_<?php echo $k?>" type="checkbox" id="check_<?php echo $k?>"  value="1"  class='np' />
        删   </td>
    </tr>
<?php
}
}?>

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
    <input type="hidden" name="idend" value="<?php echo $k?>">
    <tr bgcolor="#F9FCEF">
      <td height="24" colspan="12" valign="top">&nbsp;<strong>新增一批邀请码：</strong></td>
    </tr>
    <tr height="24" align="center" bgcolor="#FFFFFF">
      <td>数量：
      <input name="numbers" type="text" id="numbers" style="width:50px"  class='pubinputs' /></td>
      <td><select name="coinid"><?php echo $coinselect ?></select></td>
      <td><input name="money_new" type="text" id="money_new" style='width:40px' value="100"  class='pubinputs' /></td>
	  <td><input name="scores_new" type="text" id="scores_new" style='width:40px' value="10000"  class='pubinputs' /></td>
      <td>有效期：</td>
	  <td>
        <select name='exptime_new' id='exptime_new' style='width:100px'>
          <?php
          foreach($times as $kkk=>$vvv){
          	echo "    <option value='{$kkk}'>{$vvv}</option>\r\n";
          }
	   	?>
        </select></td>
		
		<td>备注：</td>
	  <td valign="top"><textarea name="cardnote" style='width:50px'></textarea></td>
	  <td align="center" bgcolor="#FFFFFF"><input name="check_new" type="checkbox"  id="check_new" value="1" checked='1' class='np' />
新增 </td>
      
	  <td valign="top">&nbsp;</td>
	  <td valign="top">&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
      
    </tr>
    <tr>
      <td height="24" colspan="12" bgcolor="#ffffff">&nbsp;</td>
    </tr>
    <tr>
      <td height="34" colspan="12" align="center" bgcolor="#F9FCEF">
      	<input name="imageField"  type="submit" class="np coolbg" value="确定" class='np coolbg /' />      </td>
    </tr>
  </form>
</table>
</body>
</html>