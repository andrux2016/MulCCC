<?php
require_once('/member/config.php');
require_once('/include/datalistcp.class.php');
$mid = $cfg_ml->M_ID;   
if ($mid <= 0) {
    header("location:/member");
}
?>
<!DOCTYPE html>
<html>  
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=10" />
        <meta name="description" content="多币交易平台,比特币,狗狗币,莱特币," />
        <title>多币交易平台</title>
        <!-- BEGIN SCRIPT INCLUDE -->
        <script type="text/javascript" src="/templets/default//js/jquery.js"></script>
        <script type="text/javascript" src="/templets/default//js/lightBox.js"></script>
        <script type="text/javascript" src="/templets/default//js/fancyCaption.js"></script>
        <script type="text/javascript" src="/templets/default//js/jqueryui.js"></script>
        <script type="text/javascript" src="/templets/default//js/general.js"></script>
        <script type="text/javascript" src="/templets/default//js/twitterFeed.js"></script>
        <!-- END SCRIPT INCLUDE -->
        <!-- BEGIN STYLE INCLUDE -->
        <link rel="stylesheet" type="text/css" href="/templets/default//css/style.css" />
        <link rel="stylesheet" type="text/css" href="/templets/default//css/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="/templets/default//css/jquery.lightbox-0.5.css" />
        <link rel="stylesheet" type="text/css" href="/templets/default//css/fancy-caption.css" />
        <link rel="stylesheet" type="text/css" href="/templets/default//css/jquery.tweet.html" />
        <!-- END STYLE INCLUDE -->
        <!-- Google web font include -->
        <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow|Oleo+Script|Droid+Sans' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <!-- BEGIN HEADER -->
        <header id="scrolltop">
            <div id="header" class="header-main">
                <div id="header-content">
                    <div id="header-telephone">
                        <?php
                        echo $cfg_ml->M_LoginID . ' <a href="/member/index_do.php?fmdo=login&dopost=exit">退出登录</a>';
                        ?>
                    </div>
                    <div id="social-button">
                        <a href="#"><img src="/templets/default//images/facebook.png" alt="Facebook icon" /></a>
                        <a href="#"><img src="/templets/default//images/twitter.png" alt="Twitter icon" /></a>
                        <a href="#"><img src="/templets/default//images/rss.png" alt="RSS icon" /></a>
                        <a href="#"><img src="/templets/default//images/linkedin.png" alt="Linkedin icon" /></a>
                        <a href="#"><img src="/templets/default//images/google.png" alt="Google icon" /></a>
                    </div>
                    <div id="menu">
                        <ul id="menu-item">
                            <li id="item-home"><a href="/"><span class="menu-item-text1">首 页</span>
                            <span class="menu-item-text3">Home</span></a></li>
                            <li id="item-globex"><a href="/oldindex.php"><span class="menu-item-text1">交易平台</span>
                            <span class="menu-item-text3">Globex</span></a></li>
                            <li id="item-user"><a href="/trade.php"><span class="menu-item-text1">用户中心</span>
                            <span class="menu-item-text3">User</span></a>
                                <!-- <div id="drop-down-blog">
                                    <img src="images/triangle.png" class="drop-triangle" alt="Triangle icon" />
                                    <ul class="drop-down">
                                        <li><a href="blog.html">用户登录</a></li>
                                        <li><a href="blogpost.html">用户注册</a></li>
                                    </ul>
                                </div> -->
                            </li>
                            <li id="item-news"><a href="/member/news.php?typeid=1"><span class="menu-item-text1">新 闻</span>
                            <span class="menu-item-text3">News</span></a></li>
                            <li id="item-vote">
                                <a href="/vote.php"><span class="menu-item-text1">投票上币</span>
                                <span class="menu-item-text3">Vote</span></a></li>
                            <li id="item-help"><a href="/member/btc-help.php"><span class="menu-item-text1">帮 助</span>
                            <span class="menu-item-text3">Help</span></a></li>  
                        </ul>
                    </div>                  
                </div>
            </div>  
        </header>
        <!-- END HEADER -->
        <!-- BEGIN PAGE TITLE -->
        <div id="page-title-wrap">
            <div id="page-title">
                <div id="page-title-content">我的推荐</div>
                <img id="left-ornament" src="/templets/default//images/left_ornament.png" alt="Left ornament"/>
                <img id="right-ornament" src="/templets/default//images/right_ornament.png" alt="Right ornament"/>
            </div>
            <div id="page-title-descr">(Reward)</div>
        </div>
        <!-- END PAGE TITLE -->
        <!-- BEGIN ABOUT US CONTENT -->
        <div id="main-content" class="story-pages">
            <div id="content-left" class="full-content">
                <div class="story-header-wrap">
                    <ul class='rewardList'>
                    <p>　　公测期间根据平台提供的链接通过发送邀请分享到QQ群、人人、开心网、新浪微博等，不限平台邀请好友或推广新用户注册将获得狗狗币或其他等值虚拟币奖励。(<a href="/reward" target="_blank">详细奖励政策</a>)</p>
                    <?php
					echo '您的网站推荐链接为：<span class="c930">http://www.mulcoin.com/?u=' . $mid . '</span><br>';
                    echo '您的注册推荐链接为：<span class="c930">http://www.mulcoin.com/reward/?u=' . $mid . '</span><br>';
                    $dsql->SetQuery("Select * From `#@__reward` Where reward_id = '" . $mid . "'");
                    $dsql->Execute();
                    $rnum = $db->GetTotalRow($rsid="me");
                    echo "您当前推荐的注册会员共 <span class='c930'>$rnum</span> 人<br>";
                    while($arr = $dsql->GetArray())
                    {
                        echo "<li>" . $arr['userid'] . "</li>";
                    }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END ABOUT US CONTENT -->
        <div class="clear"></div>
        <!-- BEGIN PREFOOTER -->
        <div id="prefooter-main-wrap">
            <div id="prefooter-content">
                <a href="#scrolltop" class="scroll"><img src="/templets/default//images/top.png" id="scrolltop-img" alt="Scroll to top" /></a>
                <div id="findus">
                    <h1>风险提示</h1>
                    <div id="adress-wrap">
                        虚拟币币的交易都存在风险，作为全球的虚拟数字货币，它们都是全天24小时交易，没有涨跌限制，价格容易因为庄家、全球政府的政策影响而大幅波动，我们强烈建议您在自身能承受的风险范围内，参与虚拟货币交易。
                    </div>
                </div>
                <div id="hours">
                    <h1>联系我们</h1>
                    <div class="meal-wrap">
                        电话：<br>
                        地址：<br>
                        网站：www.mulcoin.com<br>
                        企业邮箱：admin@mulcoin.com
                    </div>
                </div>
                <div id="events">
                    <h1>在线客服</h1>
                    <div class="event-wrap">
                        客服QQ：1561149734<br>
                        多币交易网官方群1：239183521<br>
                        多币交易网官方群2：339169803<br>
                        多币交易网官方群3：346167736
                    </div>
                </div>
            </div>
        </div>
        <!-- END PREFOOTER -->
        <!-- BEGIN FOOTER -->
        <div id="footer">
            <div id="footer-content-main">
                <div id="footer-copyright">&copy; Copyright &copy; 2014 mulcoin.com  
                版权所有 &copy;环球汇付科技集团股份有限公司</div>
                <div id="footer-misc"></div>
            </div> 
        </div>
        <!-- END FOOTER -->
<script type="text/javascript">
    function UserQuit(){

        $.post("/member/checkcode.php?act="+new Date(),{code:$('#vdcode').val(),fmdo:'login',dopost:'exit'},function(msg){ 
                    apprise(msg, {
                    animate: false,
                    textOk: "确定"
                    },function(r) {
                        location.reload(true);
                    });
        });     
    }

</script>
</body>
</html>