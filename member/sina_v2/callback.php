<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$ms  = $c->home_timeline(); // done
	$uid_get = $c->get_uid();
	$uid = $uid_get['uid'];
	$SECRET_KEY = '@4!@#$%@';
	$code=md5($me['id'].$SECRET_KEY);
    //$setcookie_url = "http://".$_SERVER['SERVER_NAME']."/sina_v2/setcookie_last_key.jsp?last_key=".$_SESSION['token']['access_token']; 
	$setcookie_url = "http://www.800.cn/sina_v2/setcookie_last_key.jsp?last_key=".$_SESSION['token']['access_token'];
    $contents = file_get_contents($setcookie_url); 
    /*
?>
<script>location.href="/sina_v2/bind.jsp?sina_user_id=<?=$uid?>&code=<?=$code?>";</script>
<?php
*/
   echo "可以进行业务处理了";
} else {
?>
授权失败。<a href="/">直接访问首页</a>
<?php
}
?>
