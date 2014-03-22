<?php
/**
 * @version        $Id: aboutus.php 1 8:38 2013年9月9日Z SZ $

 */
 require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");

$showmune=3;




    $sql = "SELECT * FROM `#@__arcrank` WHERE rank>0 ORDER BY id";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/help.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 




