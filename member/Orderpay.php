<?php
header("Content-type:text/html; charset=gb2312");
/*require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);*/
//�̻�������
$BillNo = date('YmdHis') . mt_rand(100000,999999);

//�̻���������
$BillDate = date('Ymd');

//�̻����ص�ַ
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('orderpay', 'OrderReturn', $url);
?>
<html>
  <head>
    <meta http-equiv="content-Type" content="text/html; charset=gb2312">
    <title>��׼�̻�����֧���ӿ�(�½ӿ�)</title>
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
            <font color="#FFFF00"><b>��׼�̻�����֧���ӿ�(�½ӿ�)</b></font>
          </td>
        </tr>
        <tr>
          <td width="37%">�ύ��ַ</td>
          <td width="63%">
            <select name="test">
             
              <option value="0"selected="selected">��ʽ����</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>�̻���</td>
          <td>
            <input type="text" name="Mer_code" size="18" /><!--�����̻���-->
          </td>
        </tr>
        <tr>
          <td>�̻�֤��</td>
          <td>
            <input type="text" name="Mer_key" size="40" /><!--�����̻���-->
          </td>
        </tr>
        <tr>
          <td>������</td>
          <td>
            <input type="text" name="Billno" size="24" value="<?php echo $BillNo; ?>" />
          </td>
        </tr>
        <tr>
          <td>��&nbsp;&nbsp;��</td>
          <td>
            <input type="text" name="Amount" size="18" value="0.02" /><!--������λС��-->
          </td>
        </tr>
        <tr>
          <td>��ʾ���</td>
          <td>
            <input type="text" name="DispAmount" size="18" value="0.10" />
          </td>
        </tr>
        <tr>
          <td>��&nbsp;&nbsp;��</td>
          <td>
            <input type="text" name="Date" size="18" value="<?php echo $BillDate; ?>" />
          </td>
        </tr>
        <tr>
          <td>֧������</td>
          <td>
            <select name="Currency_Type">
              <option value="RMB" selected="selected">�����</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>֧����ʽ</td>
          <td>
            <select name="Gateway_Type">
              <option value="01" selected="selected">��ǿ�</option>
              
            </select>
          </td>
        </tr>
        <tr>
          <td>ѡ������</td>
          <td><select name="Bankco">
           
            <option value="00004" selected="selected">��������</option>
            <option value="00017">ũҵ����</option>
            <option value="00003">��������</option>
            <option value="00083">�й�����</option>
            <option value="00005">��ͨ����</option>
            <option value="00051">������������</option>
            <option value="00057">�������</option>
            <option value="00052">�㶫��չ����</option>
            <option value="00054">��������</option>
            <option value="00021">��������</option>
            <option value="00013">��������</option>
            <option value="00016">��ҵ����</option>
            <option value="00087">�й�ƽ������</option>
            <option value="00023">���ڷ�չ����</option>
            <option value="00050">������ҵ����</option>
            <option value="00055">�Ͼ�����</option>
            <option value="00056">����ũ������</option>
            <option value="00095">��������</option>
            <option value="00032">�ַ�����</option>
            <option value="00084">�Ϻ�����</option>
            <option value="00081">��������</option>
            <option value="00085">��������</option>
            <option value="00086">��������</option>
            <option value="00096">��������(�й�)</option>
            <option value="00041">��������</option>
            
            
          </select>
          </td>
        </tr>
        <tr>
          <td>����</td>
          <td>
            <select name="Lang">
              <option value="GB">GB����</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>֧���ɹ�����URL</td>
          <td>
            <input type="text" name="Merchanturl" size="40" value="<?php echo $url; ?>" />
          </td>
        </tr>
        <tr>
          <td>֧��ʧ�ܷ���URL</td>
          <td>
            <input type="text" name="FailUrl" size="40" value="" />
          </td>
        </tr>
        <tr>
          <td>�̻��������ݰ�</td>
          <td>
            <input type="text" name="Attach" size="40" value="" />
          </td>
        </tr>
        <tr>
          <td>����֧�����ܷ�ʽ</td>
          <td>
            <select name="OrderEncodeType">
              <option value="5" selected="selected">md5ժҪ</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>���׷��ؼ��ܷ�ʽ</td>
          <td>
            <select name="RetEncodeType">
              <option value="16">md5withRsa</option>
              <option value="17" selected="selected">md5ժҪ</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>�Ƿ��ṩServer���ط�ʽ</td>
          <td>
            <select name="Rettype">
              <option value="0">��Server to Server</option>
              <option value="1" selected="selected">��Server to Server</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Server to Server����ҳ��</td>
          <td>
            <input type="text" name="ServerUrl" size="40" value="<?php echo $url; ?>" />
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="submit" value="�ύ" />
            <input type="reset" value="��д" />
          </td>
        </tr>
      </table>
    </form> 
  </body> 
</html>