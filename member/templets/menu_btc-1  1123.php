    <?php
        $add_channel_menu = array();
        //如果为游客访问，不启用左侧菜单
        if(!empty($cfg_ml->M_ID))
        {
            $dsql->SetQuery("SELECT * FROM #@__btctype WHERE coinsign=1");
			$dsql->Execute();
			while($row = $dsql->GetObject())
			{
				$coincashs .= "<li class=\"icon flink\"><a href='/member/cash_btc.php?coinid=".$row->id ."'><b></b>".$row->cointype ."提现</a></li>";
				$coinbuys .= "<li class=\"icon flink\"><a href='/member/buy_btc.php?coinid=".$row->id ."'><b></b>".$row->cointype ."充值</a></li>";
				$cardsmune .= "<li class=\"icon flink\"><a href='/member/cards_btc.php?coinid=".$row->id ."'><b></b>".$row->cointype ."充值码</a></li>";
			}
            unset($menurow);
			
			$nowname = $_SERVER['PHP_SELF'];
			switch ($nowname)
			{
			case "/member/edit_fullinfo_btc.php":
			  $jsshow = "menuFirst";
			  break;  
			case "/member/edit_baseinfo_btc.php":
			  $jsshow = "menuFirst";
			  break;
			case "/member/index.php":
			  $jsshow = "menuFirst";
			  break;  
			case "/member/help.php":
			  $jsshow = "menuFirst";
			  break;
			case "/member/buy_btc.php":
			  $jsshow = "menuSec";
			  break;
			case "/member/cash_btc.php":
			  $jsshow = "menuThr";
			  break;
			case "/member/btc_deallist.php":
			  $jsshow = "menuFour";
			  break;
			case "/member/btc_orderlist.php":
			  $jsshow = "menuFour";
			  break;
			case "/member/operation_btc.php":
			  $jsshow = "menuFour";
			  break;
			case "/member/operation_cash.php":
			  $jsshow = "menuFour";
			  break;
			case "/member/deduct_btc.php":
			  $jsshow = "menuFour";
			  break;
			case "/member/cards_list_btc.php":
			  $jsshow = "menuFour";
			  break;
			case "/member/APICode_btc.php":
			  $jsshow = "menuFive";
			  break;
			default:
			  $jsshow = "menuFirst";
			}
		?>
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
    <div id="mcpsub" class="hideMenu">
		<div id="menuBody10-15">
			<!-- 系统设置菜单-->
			<div class="xbt-left-con">
				<div class="xbt-left-info">
					<ul>
						<li>您当前登录的账号是</li>
						<li><a class="xbt-ugif" href=""><?php echo $cfg_ml->M_LoginID;?></a>   <a class="xbt-vip" href="" >[VIP1]</a></li>
						<li>详细信息：</li>
						<li>CNY：0.00 <a href=""><img src="<?php echo $cfg_templets_skin; ?>/images/chong_c2.png" alt="" /></a> </li>
						<li>USD：0.00 <a href=""><img src="<?php echo $cfg_templets_skin; ?>/images/chong_c2.png" alt="" /></a> </li>
						<li>BTC：0.00000000 </li>
						<li>折合人民币总资产：</li>
						<li>CNY：0.00</li>
					</ul>
				</div>
				<div class="xbt-left-leftnav">
					<div class="xbt-gllmenu">
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuFirst')" id="menuFirst_t"><b><i class="xbt-gllmenu-i1">交易管理</i></b></a></h2>
							<ul id="menuFirst" class="xbt-gllmul">
								<li><a  href="/trade_inout.php?type=buy">人民币买入BTC</a></li>
								<li><a  href="/trade_inout.php?type=sell">人民币卖出BTC</a></li>
								<li><a href="">人民币计划委托</a></li>
								<li><a href="">美元买入BTC</a></li>
								<li><a href="">美元卖出BTC</a></li>
								<li><a href="">美元计划委托</a></li>
								<li><a href="">委托管理</a></li>
							</ul>
						</div>
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuSec')" id="menuSec_t"><b><i class="xbt-gllmenu-i2">账户管理</i></b></a></h2>
							<ul id="menuSec" class="xbt-gllmul">
								<?php echo $coinbuys; ?>
								<?php echo $coincashs; ?>
								<li><a class="xbt-gllmul-avist" href="hqjy.htm">人民币充值</a></li>
								<li><a href="">人民币提现</a></li>
								<li><a href="">美元充值</a></li>
								<li><a href="">美元提现</a></li>
								<li><a href="">比特币充值</a></li>
								<li><a href="">比特币提现</a></li>
								<li><a href="">账单明细</a></li>
							</ul>
						</div>
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuThr')" id="menuThr_t"><b><i class="xbt-gllmenu-i3">安全中心</i></b></a></h2>
							<ul id="menuThr" class="xbt-gllmul">
								<li><a <?php if($nowname=="/member/edit_baseinfo_btc.php") echo "class='btc8-gllmul-avist'";?> href="/member/edit_baseinfo_btc.php">账号安全</a></li>
								<li><a <?php if($nowname=="/member/edit_fullinfo_btc.php") echo "class='btc8-gllmul-avist'";?> href="/member/edit_fullinfo_btc.php">个人资料</a></li>
								<li><a <?php if($nowname=="/member/index.php") echo "class='btc8-gllmul-avist'";?> href="/member/index.php">邀请任务</a></li>
								<li><a <?php if($nowname=="/member/help.php") echo "class='btc8-gllmul-avist'";?> href="/member/help.php">帮助</a></li>
								<li><a href="/member/APICode_btc.php">交易API</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>				
		</div>
	</div>
    <?php
    }
	/*if($jsshow != "menuFirst") echo "<script type=\"text/javascript\">menuShow('menuFirst');</script>";*/
	echo "<script type=\"text/javascript\">menuShow('".$jsshow."');</script>";

    ?>