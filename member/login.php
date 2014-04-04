<?php
/**
 * @version        $Id: login.php 1 8:38 2010年7月9日Z SZ $

 */
require_once(dirname(__FILE__)."/config.php");
if($cfg_ml->IsLogin())
{
    ShowMsg('你已经登陆系统，无需重新注册！', '../trade.php');
    exit();
}
require_once(dirname(__FILE__)."/templets/login.htm");