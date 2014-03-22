<?php
header("Content-type:text/html; charset=gb2312");
/*require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);*/
//商户订单号
$BillNo = date('YmdHis') . mt_rand(100000,999999);

//商户交易日期
$BillDate = date('Ymd');

//商户返回地址
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('orderpay', 'OrderReturn', $url);
?>
<html>
  <head>
    <meta http-equiv="content-Type" content="text/html; charset=gb2312">
    <title>标准商户订单支付接口(新接口)</title>
    <style type="text/css">
      <!--
      TD {FONT-SIZE: 9pt}
      SELECT {FONT-SIZE: 9pt}
      OPTION {COLOR: #5040aa; FONT-SIZE: 9pt}
      INPUT {FONT-SIZE: 9pt}
      -->
    </style>
  </head>

  <body bgcolor="#FFFFFF">
    <form action="redirect.php" METHOD="POST"  target="_blank" name="frm1">	
      <table width="450px" border="1" cellspacing="0" cellpadding="3" bordercolordark="#FFFFFF" bordercolorlight="#333333" bgcolor="#F0F0FF" align="center">
        <tr bgcolor="#8070FF"> 
          <td colspan="2" align="center">
            <font color="#FFFF00"><b>标准商户订单支付接口(新接口)</b></font>
          </td>
        </tr>
        <tr>
          <td width="37%">提交地址</td>
          <td width="63%">
            <select name="test">
             
              <option value="0"selected="selected">正式环境</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>商户号</td>
          <td>
            <input type="text" name="Mer_code" size="18" /><!--测试商户号-->
          </td>
        </tr>
        <tr>
          <td>商户证书</td>
          <td>
            <input type="text" name="Mer_key" size="40" /><!--测试商户号-->
          </td>
        </tr>
        <tr>
          <td>订单号</td>
          <td>
            <input type="text" name="Billno" size="24" value="<?php echo $BillNo; ?>" />
          </td>
        </tr>
        <tr>
          <td>金&nbsp;&nbsp;额</td>
          <td>
            <input type="text" name="Amount" size="18" value="0.02" /><!--保留两位小数-->
          </td>
        </tr>
        <tr>
          <td>显示金额</td>
          <td>
            <input type="text" name="DispAmount" size="18" value="0.10" />
          </td>
        </tr>
        <tr>
          <td>日&nbsp;&nbsp;期</td>
          <td>
            <input type="text" name="Date" size="18" value="<?php echo $BillDate; ?>" />
          </td>
        </tr>
        <tr>
          <td>支付币种</td>
          <td>
            <select name="Currency_Type">
              <option value="RMB" selected="selected">人民币</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>支付方式</td>
          <td>
            <select name="Gateway_Type">
              <option value="01" selected="selected">借记卡</option>
              
            </select>
          </td>
        </tr>
        <tr>
          <td>选择银行</td>
          <td><select name="Bankco">
           
            <option value="00004" selected="selected">工商银行</option>
            <option value="00017">农业银行</option>
            <option value="00003">建设银行</option>
            <option value="00083">中国银行</option>
            <option value="00005">交通银行</option>
            <option value="00051">邮政储蓄银行</option>
            <option value="00057">光大银行</option>
            <option value="00052">广东发展银行</option>
            <option value="00054">中信银行</option>
            <option value="00021">招商银行</option>
            <option value="00013">民生银行</option>
            <option value="00016">兴业银行</option>
            <option value="00087">中国平安银行</option>
            <option value="00023">深圳发展银行</option>
            <option value="00050">北京商业银行</option>
            <option value="00055">南京银行</option>
            <option value="00056">北京农商银行</option>
            <option value="00095">渤海银行</option>
            <option value="00032">浦发银行</option>
            <option value="00084">上海银行</option>
            <option value="00081">杭州银行</option>
            <option value="00085">宁波银行</option>
            <option value="00086">浙商银行</option>
            <option value="00096">东亚银行(中国)</option>
            <option value="00041">华夏银行</option>
            
            
          </select>
          </td>
        </tr>
        <tr>
          <td>语言</td>
          <td>
            <select name="Lang">
              <option value="GB">GB中文</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>支付成功返回URL</td>
          <td>
            <input type="text" name="Merchanturl" size="40" value="<?php echo $url; ?>" />
          </td>
        </tr>
        <tr>
          <td>支付失败返回URL</td>
          <td>
            <input type="text" name="FailUrl" size="40" value="" />
          </td>
        </tr>
        <tr>
          <td>商户附加数据包</td>
          <td>
            <input type="text" name="Attach" size="40" value="" />
          </td>
        </tr>
        <tr>
          <td>订单支付加密方式</td>
          <td>
            <select name="OrderEncodeType">
              <option value="5" selected="selected">md5摘要</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>交易返回加密方式</td>
          <td>
            <select name="RetEncodeType">
              <option value="16">md5withRsa</option>
              <option value="17" selected="selected">md5摘要</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>是否提供Server返回方式</td>
          <td>
            <select name="Rettype">
              <option value="0">无Server to Server</option>
              <option value="1" selected="selected">有Server to Server</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Server to Server返回页面</td>
          <td>
            <input type="text" name="ServerUrl" size="40" value="<?php echo $url; ?>" />
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="submit" value="提交" />
            <input type="reset" value="重写" />
          </td>
        </tr>
      </table>
    </form> 
  </body> 
</html>