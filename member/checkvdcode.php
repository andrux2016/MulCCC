<?php
require_once(dirname(__FILE__)."/config.php");



	function showJson($msg,$ruslt){
			/*$userArray=array(  
			'showMsg' => $msg, 
			'ruslt' => $ruslt,
			);
		
			$json_string = json_encode($userArray);  
			echo $json_string;*/
			echo $msg;
		}
	
        if(!isset($vdcode))
        {
            $vdcode = '';
        }
        $svali = GetCkVdValue();
        if(strtolower($vdcode)==$svali && $svali!='')
        {
            showJson('<img src="templets/images/correct.png" width="20" />', '-1');
			exit();
        }
		
	
?>