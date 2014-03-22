<?php
/*
@version        $Id: qh_deal.php 1 8:38 2013年8月8日Z 
 */
 
 $key="AGHNRI0S30KY8I8231";
$userid= MdString($key,"test@sina.com");
$password= MdString($key,"123456");
function MdString($key,$string){
   $len  = strlen($key);
   for($i=0; $i<strlen($string); $i++)
    {
        $k = $i % $len;
        $code  .= $string[$i] ^ $key[$k];
    }
   return base64_encode($code);
}
echo 'userid:'.$userid.'<br>';
echo 'password:'.$password.'<br>';


$abc=json_decode("{\"showMsg\":\"\u6302\u5355\u9519\u8bef\uff0c\u8054\u7cfb\u7ba1\u7406\u5458\",\"ruslt\":-1}");  
print_r($abc);


?>

<form action="qh_deal.php" method="post">

qhmarket<input name="qhmarket" type="text" value="1" />
<br>
symbol<input name="symbol" type="text" value="BTC_CNY" />
<br>
kpsign<input name="kpsign" type="text" value="0" />
<br>
mdggper<input name="mdggper" type="text" value="1.25" /><br>
mkggper<input name="mkggper" type="text" value="1.5" />
<br>
rate<input name="rate" type="text" value="1300" />
<br>
vol<input name="vol" type="text" value="1" />
<br>
type<input name="type" type="text" value="bid" /><br>
hyid<input name="hyid" type="text" value="1" /><br>
<input name="" type="submit" />
</form>