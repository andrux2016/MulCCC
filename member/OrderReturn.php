<?php



header("Content-type:text/html; charset=gb2312"); 







//----------------------------------------------------

//  接收数据

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

//  接收数据

//  Receive the data

//----------------------------------------------------



/*echo $sql = "insert into #@__btcautobill(billno,amount,mydate,succ,msg,attach,ipsbillno,retencodetype,Currency_type,signature) values('$billno','$amount','$date','$succ','$msg','$attach','$ipsbillno','$retencodetype','$Currency_type','$signature')";

$rsnew = $dsql->ExecuteNoneQuery($sql);

$sql = "Update #@__btcrecharge Set dealmark=1 Where id=$Attach";

$rsnew = $dsql->ExecuteNoneQuery($sql);*/

//'----------------------------------------------------

//'   Md5摘要认证

//'   verify  md5

//'----------------------------------------------------



//RetEncodeType设置为17（MD5摘要数字签名方式）

//交易返回接口MD5摘要认证的明文信息如下：

//billno+【订单编号】+currencytype+【币种】+amount+【订单金额】+date+【订单日期】+succ+【成功标志】+ipsbillno+【IPS订单编号】+retencodetype +【交易返回签名方式】+【商户内部证书】

//例:(billno000001000123currencytypeRMBamount13.45date20031205succYipsbillnoNT2012082781196443retencodetype17GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)



//返回参数的次序为：

//billno + mercode + amount + date + succ + msg + ipsbillno + Currecny_type + retencodetype + attach + signature + bankbillno

//注2：当RetEncodeType=17时，摘要内容已全转成小写字符，请在验证的时将您生成的Md5摘要先转成小写后再做比较



 $content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;



//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书



 $cert = 'bd4ded6a7e9ebbf7f94513b0f288fcc8';



 $signature_1ocal = md5($content . $cert);


 $signature_1ocal = strtolower($signature_1ocal);



	//----------------------------------------------------

	//  判断交易是否成功

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

		*比较返回的订单号和金额与您数据库中的金额是否相符1245

		*compare the billno and amount from ips with the data recorded in your datebase

		*----------------------------------------------------

		

		if(不等)

			echo "从IPS返回的数据和本地记录的不符合，失败！"

			exit

		else

			'----------------------------------------------------

			'交易成功，处理您的数据库

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

		ShowMsg(iconv("gb2312","UTF-8","交易成功！"),"operation_btc.php",0,2000);

		exit();

	}

	else

	{

		echo iconv("gb2312","UTF-8","交易失败！");

		exit;

	}



}

else

{

	echo iconv("gb2312","UTF-8","签名不正确！");

	//echo "签名不正确！";

	exit;

}



//$cert = '52632211099462864512673941301608095160170711534054159547969080938046669333195140735754845829019088249738571545181925518410184931';



?>