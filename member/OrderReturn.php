<?php



header("Content-type:text/html; charset=gb2312"); 







//----------------------------------------------------

//  ��������

//  Receive the data

//----------------------------------------------------

$billno = $_GET['billno'];

$amount = $_GET['amount'];

$mydate = $_GET['date'];

$succ = $_GET['succ'];

$msg = $_GET['msg'];

$attach = $_GET['attach'];

$ipsbillno = $_GET['ipsbillno'];

$retEncodeType = $_GET['retencodetype'];

$currency_type = $_GET['Currency_type'];

$signature = $_GET['signature'];





//----------------------------------------------------

//  ��������

//  Receive the data

//----------------------------------------------------



/*echo $sql = "insert into #@__btcautobill(billno,amount,mydate,succ,msg,attach,ipsbillno,retencodetype,Currency_type,signature) values('$billno','$amount','$date','$succ','$msg','$attach','$ipsbillno','$retencodetype','$Currency_type','$signature')";

$rsnew = $dsql->ExecuteNoneQuery($sql);

$sql = "Update #@__btcrecharge Set dealmark=1 Where id=$Attach";

$rsnew = $dsql->ExecuteNoneQuery($sql);*/

//'----------------------------------------------------

//'   Md5ժҪ��֤

//'   verify  md5

//'----------------------------------------------------



//RetEncodeType����Ϊ17��MD5ժҪ����ǩ����ʽ��

//���׷��ؽӿ�MD5ժҪ��֤��������Ϣ���£�

//billno+��������š�+currencytype+�����֡�+amount+��������+date+���������ڡ�+succ+���ɹ���־��+ipsbillno+��IPS������š�+retencodetype +�����׷���ǩ����ʽ��+���̻��ڲ�֤�顿

//��:(billno000001000123currencytypeRMBamount13.45date20031205succYipsbillnoNT2012082781196443retencodetype17GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)



//���ز����Ĵ���Ϊ��

//billno + mercode + amount + date + succ + msg + ipsbillno + Currecny_type + retencodetype + attach + signature + bankbillno

//ע2����RetEncodeType=17ʱ��ժҪ������ȫת��Сд�ַ���������֤��ʱ�������ɵ�Md5ժҪ��ת��Сд�������Ƚ�



 $content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;



//���ڸ��ֶ��з����̻���½merchant.ips.com.cn���ص�֤��



 $cert = 'bd4ded6a7e9ebbf7f94513b0f288fcc8';



 $signature_1ocal = md5($content . $cert);


 $signature_1ocal = strtolower($signature_1ocal);



	//----------------------------------------------------

	//  �жϽ����Ƿ�ɹ�

	//  See the successful flag of this transaction

	//----------------------------------------------------


if ($signature_1ocal == $signature)

{

	require_once(dirname(__FILE__).'/config.php');

	$billno = iconv('GB2312', 'UTF-8', $billno);

	$amount = iconv('GB2312', 'UTF-8', $amount);

	$mydate = iconv('GB2312', 'UTF-8', $date);

	$succ = iconv('GB2312', 'UTF-8', $succ);

	$msg = iconv('GB2312', 'UTF-8', $msg);

	$attach = iconv('GB2312', 'UTF-8', $attach);

	$ipsbillno = iconv('GB2312', 'UTF-8', $ipsbillno);

	$retEncodeType = iconv('GB2312', 'UTF-8', $retencodetype);

	$currency_type = iconv('GB2312', 'UTF-8', $Currency_type);

	$signature = iconv('GB2312', 'UTF-8', $signature);

	if ($succ == 'Y')

	{



//echo $sql = "insert into btc_btcautobill(billno) values('11');";

$sql = "insert into #@__btcautobill(billno,amount,mydate,succ,msg,attach,ipsbillno,retEncodeType,currency_type,signature) values('$billno','$amount','$mydate','$succ','$msg','$attach','$ipsbillno','$retEncodeType','$currency_type','$signature',(".time()."))";

		$rsnew = $dsql->ExecuteNoneQuery($sql);

		/**----------------------------------------------------

		*�ȽϷ��صĶ����źͽ���������ݿ��еĽ���Ƿ����1245

		*compare the billno and amount from ips with the data recorded in your datebase

		*----------------------------------------------------

		

		if(����)

			echo "��IPS���ص����ݺͱ��ؼ�¼�Ĳ����ϣ�ʧ�ܣ�"

			exit

		else

			'----------------------------------------------------

			'���׳ɹ��������������ݿ�

			'The transaction is successful. update your database.

			'----------------------------------------------------

		end if

		**/

		$bill = $dsql->GetOne("Select billno From #@__btcautobill where billno = '$billno' and amount='$amount'");

		if(!is_array($bill)){

			$sql = "insert into #@__btcautobill(billno,amount,mydate,succ,msg,attach,ipsbillno,retencodetype,Currency_type,signature) values('$billno','$amount','$date','$succ','$msg','$attach','$ipsbillno','$retencodetype','$Currency_type','$signature')";

			$rsnew = $dsql->ExecuteNoneQuery($sql);

			$sql = "Update #@__btcrecharge Set dealmark=1 Where billno = '$billno'";

			$rup = $dsql->ExecuteNoneQuery($sql);

		}

		ShowMsg(iconv("gb2312","UTF-8","���׳ɹ���"),"operation_btc.php",0,2000);

		exit();

	}

	else

	{

		echo iconv("gb2312","UTF-8","����ʧ�ܣ�");

		exit;

	}



}

else

{

	echo iconv("gb2312","UTF-8","ǩ������ȷ��");

	//echo "ǩ������ȷ��";

	exit;

}



//$cert = '52632211099462864512673941301608095160170711534054159547969080938046669333195140735754845829019088249738571545181925518410184931';



?>