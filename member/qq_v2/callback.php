<?php
require_once('./comm/config.php');
require_once('./comm/utils.php');

function qq_callback()
{
    //debug
    //print_r($_REQUEST);
   // print_r($_SESSION);
	//print_r($_REQUEST["code"]);
	//die();
    if($_REQUEST['state'] == $_SESSION['state']) //csrf
    {
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
            . "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
            . "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];
		//echo $token_url;
        $response = get_url_contents($token_url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }

        $params = array();
        parse_str($response, $params);

        //debug
        //print_r($params);

        //set access token to session
        $_SESSION["access_token"] = $params["access_token"];

    }
    else 
    {
        echo("The state does not match. You may be a victim of CSRF.");
    }
}

function get_openid()
{
    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $_SESSION['access_token'];

    $str  = get_url_contents($graph_url);
    if (strpos($str, "callback") !== false)
    {
        $lpos = strpos($str, "(");
        $rpos = strrpos($str, ")");
        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
    }

    $user = json_decode($str);
    if (isset($user->error))
    {
        echo "<h3>error:</h3>" . $user->error;
        echo "<h3>msg  :</h3>" . $user->error_description;
        exit;
    }

    //debug
    //echo("Hello " . $user->openid);

    //set openid to session
    $_SESSION["openid"] = $user->openid;
}

function get_user_info()
{
    $get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = get_url_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}

//QQ登录成功后的回调地址,主要保存access token
qq_callback();

//获取用户标示id
get_openid();

//echo '<pre>';
//print_r($_SESSION);
//获取用户基本资料
$arr = get_user_info();
if($arr['nickname']){
	$nickName =  $arr['nickname'];
	$avatar = $arr['figureurl_2'];
	$avatar = urlencode($avatar);
	$openid= $_SESSION['openid'];
	$code=md5('654232'.$openid);
	echo "在这里处理联合登录成功业务逻辑(创建用户信息并自动登录)";
	/*
	$bindUrl = "/qq/bind?code=".$code."&avatar=".$avatar."&nickName=".$nickName."&openid=".$openid."";
    header("Location:$bindUrl");
    */
}else{
	echo "在这里处理联合登录失败逻辑";
	//RedirectPage(0,'http://www.800.cn/?qq_login_failed');
}
//echo "<script>window.close();</script>";
?>