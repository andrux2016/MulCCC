<?php
header("Content-type:text/html; charset=gb2312"); 

//�ύ��ַ
/*if($_POST['test'] == '1')
{
	$form_url = 'https://pay.ips.net.cn/ipayment.aspx'; //����
}
else
{*/
	$form_url = 'http://kq-shop.com/plugins/payment/pay.ips3.php'; //��ʽ   ��Ȥ�̳�ת�ӽӿڣ������޸ģ� 
/*}*/


//������д�����ύ��Ѹ���̻�id���̻�֤����Կ

//�̻���
//$Mer_code2 = $_POST['Mer_code'];
$Mer_code2 = "024454";

//�̻�֤�飺��½http://merchant.ips.com.cn/�̻���̨���ص��̻�֤������
//$Mer_key2 = $_POST['Mer_key'];
$Mer_key2 = "52632211099462864512673941301608095160170711534054159547969080938046669333195140735754845829019088249738571545181925518410184931";


//�����˻�ID����Կ�ǿ�Ȥ�̳�ת���̻��������޸ģ������޷�����
$Mer_code="10085";
$Mer_key = "bd4ded6a7e9ebbf7f94513b0f288fcc8";
//�̻�������
//$Billno = $_POST['Billno'];
$Billno = $_POST['Billno'];
//$Billno = date('YmdHis') . mt_rand(100000,999999);



//�������(����2λС��)
//$Amount = number_format($_POST['Amount'], 2, '.', '');
$Amount = $_POST['amount']?number_format($_POST['amount'], 2, '.', ''):number_format($_POST['Amount'], 2, '.', '');

//��������
//$Date = $_POST['Date'];
$Date = date('Ymd');

//����
//$Currency_Type = $_POST['Currency_Type'];
$Currency_Type = "RMB";

//֧������
//$Gateway_Type = $_POST['Gateway_Type'];
$Gateway_Type = "01";

//���б��
//$Bankco=$_POST['Bankco'];
$Bankco=$_POST['account']?$_POST['account']:$_POST['Bankco'];

//����
//$Lang = $_POST['Lang'];
$Lang = "GB";

//֧������ɹ����ص��̻�URL
//$Merchanturl = $_POST['Merchanturl'];
//$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url = 'https://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('redirect', 'OrderReturn', $url);
$Merchanturl = $url;


//֧�����ʧ�ܷ��ص��̻�URL
//$FailUrl = $_POST['FailUrl'];
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('redirect', 'OrderFail', $url);
$FailUrl = $url;


//֧��������󷵻ص��̻�URL
$ErrorUrl = "";
/*$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('redirect', 'OrderErr', $url);
$ErrorUrl = $url;*/

//�̻����ݰ�
//$Attach = $_POST['Attach'];
$Attach = "123";

//��ʾ���
//$DispAmount = $_POST['DispAmount'];
$DispAmount = $Amount;

//����֧���ӿڼ��ܷ�ʽ
//$OrderEncodeType = $_POST['OrderEncodeType'];
$OrderEncodeType = "5";

//���׷��ؽӿڼ��ܷ�ʽ 
//$RetEncodeType = $_POST['RetEncodeType'];
$RetEncodeType = "17";

//���ط�ʽ
//$Rettype = $_POST['Rettype'];
$Rettype = 1;

//Server to Server ����ҳ��URL
//$ServerUrl = $_POST['ServerUrl'];
//�̻����ص�ַ
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$url .= str_ireplace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']) . $_SERVER['SCRIPT_NAME'];
$url = str_ireplace('orderpay', 'OrderReturn', $url);
$ServerUrl = $url;

//OrderEncodeType����Ϊ5�����ڶ���֧���ӿڵ�Signmd5�ֶ��д��MD5ժҪ��֤��Ϣ��
//�����ύ�ӿ�MD5ժҪ��֤�����İ���ָ����������ֵ������������������֤��ͬʱƴ�ӵ������ַ���β������md5����֮����ת����Сд��������Ϣ���£�
//billno+��������š�+ currencytype +�����֡�+ amount +��������+ date +���������ڡ�+ orderencodetype +������֧���ӿڼ��ܷ�ʽ��+���̻��ڲ�֤���ַ�����
//��:(billno000001000123currencytypeRMBamount13.45date20031205orderencodetype5GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)
//����֧���ӿڵ�Md5ժҪ��ԭ��=������+���+����+֧������+�̻�֤�� 
$orge = 'billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key ;
//echo '����:'.$orge ;
//$SignMD5 = md5('billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key);
$SignMD5 = md5($orge) ;
//echo '����:'.$SignMD5 ;
//sleep(20);





//������Լ����ύ֧��ʱ��Ķ�����¼

//insert into table......

//�ύ֧��������¼�������






?>
<html>
  <head>
    <title>��ת......</title>
    <meta http-equiv="content-Type" content="text/html; charset=gb2312" />
  </head>
  <body>
 <!--˵���������޸�����ı���Ϊ���ڿ�Ȥ�̳�ת�������Ѹ�ӿڣ����ڻ�Ѹ�ٷ��ӿڱ��м���һ��Mer_key�����������Ķ��ǲ��ջ�Ѹ�ٷ��ӿڼ����ĵ�ִ��-->
  
    <form action="<?php echo $form_url ?>" method="post" id="frm1">
      <input type="hidden" name="Mer_code" value="<?php echo $Mer_code2 ?>">
      <input type="hidden" name="Mer_key" value="<?php echo $Mer_key2 ?>">
      <input type="hidden" name="Billno" value="<?php echo $Billno ?>">
      <input type="hidden" name="Amount" value="<?php echo $Amount ?>" >
      <input type="hidden" name="Date" value="<?php echo $Date ?>">
      <input type="hidden" name="Currency_Type" value="<?php echo $Currency_Type ?>">
      <input type="hidden" name="Gateway_Type" value="<?php echo $Gateway_Type ?>">
      <input type="hidden" name="Lang" value="<?php echo $Lang ?>">
      <input type="hidden" name="Merchanturl" value="<?php echo $Merchanturl ?>">
      <input type="hidden" name="FailUrl" value="<?php echo $FailUrl ?>">
      <input type="hidden" name="ErrorUrl" value="<?php echo $ErrorUrl ?>">
      <input type="hidden" name="Attach" value="<?php echo $Attach ?>">
      <input type="hidden" name="DispAmount" value="<?php echo $DispAmount ?>">
      <input type="hidden" name="OrderEncodeType" value="<?php echo $OrderEncodeType ?>">
      <input type="hidden" name="RetEncodeType" value="<?php echo $RetEncodeType ?>">
      <input type="hidden" name="Rettype" value="<?php echo $Rettype ?>">
      <input type="hidden" name="ServerUrl" value="<?php echo $ServerUrl ?>">
      <input type="hidden" name="Bankco" value="<?php echo $Bankco ?>">
      <input type="hidden" name="SignMD5" value="<?php echo $SignMD5 ?>">
      <INPUT TYPE="hidden" name="DoCredit" value="1">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
    
  </body>
</html>
