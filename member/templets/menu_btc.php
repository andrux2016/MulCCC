    <?php
        $add_channel_menu = array();
        //如果为游客访问，不启用左侧菜单
        if(!empty($cfg_ml->M_ID))
        {
            $dsql->SetQuery("SELECT * FROM #@__btctype WHERE coinsign=1");
			$dsql->Execute();
			while($row = $dsql->GetObject())
			{
				$coincashs .= "<li class=\"icon flink\"><a href='cash_btc.php?coinid=".$row->id ."'><b></b>".$row->cointype ."提现</a></li>";
				$coinbuys .= "<li class=\"icon flink\"><a href='buy_btc.php?coinid=".$row->id ."'><b></b>".$row->cointype ."充值</a></li>";
				$cardsmune .= "<li class=\"icon flink\"><a href='cards_btc.php?coinid=".$row->id ."'><b></b>".$row->cointype ."充值码</a></li>";
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
			<div class="yh-left">
				<ul>
					<li class="nav tit">
						<a>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-1.jpg" border="0" />
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-1-a.jpg" border="0" style="display:none;" />
							交易
						</a>
					</li>
					<li>
						<a href="/trade.php" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-18.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-18-a.jpg" border="0" class="c" />                    
							我的账户
						</a>
					</li>
					<li>
						<a href="btc_orderlist.php" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-7.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-7-a.jpg" border="0" class="c"/>
							委托管理
						</a>
					</li>
					<li>
						<a href="btc_deallist.php" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-8.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-8-a.jpg" border="0" class="c"/>
							成交记录
						</a>
					</li>
					<li class="nav2 tit">
						<a>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-9.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-9-a.jpg" border="0" style="display:none;"/>
							账户管理
						</a>
					</li>
<!--					<li>-->
<!--						<a href="buy_btc.php?coinid=1" >-->
<!--							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-10.jpg" border="0" class="c-no"/>-->
<!--							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-10-a.jpg" border="0" class="c"/>-->
<!--							人民币充值-->
<!--						</a>-->
<!--					</li>-->
<!--					<li>-->
<!--						<a href="cash_btc.php?coinid=1" >-->
<!--							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-11.jpg" border="0" class="c-no"/>-->
<!--							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-11-a.jpg" border="0" class="c"/>-->
<!--							人民币提现-->
<!--						</a>-->
<!--					</li>-->
					<li>
						<a href="buy_btc.php?coinid=2" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-12.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-12-a.jpg" border="0" class="c"/>
							币种充值
						</a>
					</li>
					<li>
						<a href="cash_btc.php?coinid=2" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-13.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-13-a.jpg" border="0" class="c"/>
							币种提现
						</a>
					</li>
					<li>
						<a href="operation_btc.php" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-14.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-14-a.jpg" border="0" class="c"/>
							充值记录
						</a>
					</li>
					<li>
						<a href="operation_cash.php" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-15.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-15-a.jpg" border="0" class="c"/>
							提现记录
						</a>
					</li>
					<li class="nav2 tit">
						<a>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-16.jpg" border="0"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-16-a.jpg" border="0" style="display:none;" />
							基本信息
						</a>
					</li>
					<li>
						<a href="edit_baseinfo_btc.php" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-17.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-17-a.jpg" border="0" class="c"/>
							安全中心
						</a>
					</li>
					<li class="cur">
						<a href="edit_fullinfo_btc.php" >
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-18.jpg" border="0" class="c-no"/>
							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-18-a.jpg" border="0" class="c"/>
							账户信息
						</a>
					</li>
<!--					<li class="nav2 tit">-->
<!--						<a>-->
<!--							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-19.jpg" border="0" />-->
<!--							<img src="<?php echo $cfg_templets_skin; ?>/img/btb-yhgl-19-a.jpg" border="0" style="display:none;" />-->
<!--							交易API-->
<!--						</a>-->
<!--					</li>-->
<!--					<li style="text-indent:24px;">-->
<!--						<a href="APICode_btc.php" >-->
<!--							交易API-->
<!--						</a>-->
<!--					</li>-->
				</ul>
			</div>
			
			
			
			<div style="display:none;">
			
			
			
			
			
			
			
			
			
			
			
			
			
			<div class="xbt-left-con">
				<div class="xbt-left-info" style="display:none">
					<ul>
						<li>您当前登录的账号是</li>
						<li><a class="xbt-ugif" href="">loosangles</a>   <a class="xbt-vip" href="" >[VIP1]</a></li>
						<li>详细信息：</li>
						<li>CNY：0.00 <a href=""><img src="images/chong_c2.png" alt="" /></a> </li>
						<li>USD：0.00 <a href=""><img src="images/chong_c2.png" alt="" /></a> </li>
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
								<li><a class="xbt-gllmul-avist"  href="/trade_inout.php?type=buy">人民币买入BTC</a></li>
								<li><a href="/trade_inout.php?type=sell">人民币卖出BTC</a></li>
								<li><a href="/trade_inout.php?type=buy">美元买入BTC</a></li>
								<li><a href="/trade_inout.php?type=sell">美元卖出BTC</a></li>
								
							</ul>
						</div>
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuSec')" id="menuSec_t"><b><i class="xbt-gllmenu-i2">期货交易</i></b></a></h2>
							<ul id="menuSec" class="xbt-gllmul">
								<li><a href="/qhtrade.php">买多</a></li>
								<li><a href="/qhtrade.php">卖空</a></li>							
							</ul>
						</div>
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuThr')" id="menuThr_t"><b><i class="xbt-gllmenu-i3">融资融券</i></b></a></h2>
							<ul id="menuThr" class="xbt-gllmul">
								<li><a href="securities.php">借款</a></li>
								<li><a href="securities.php">借币</a></li>							
							</ul>
						</div>
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuFour')" id="menuFour_t"><b><i class="xbt-gllmenu-i4">BTC银行</i></b></a></h2>
							<ul id="menuFour" class="xbt-gllmul">
								<li><a href="yinhang.php">存款</a></li>
								<li><a href="yinhang.php">取款</a></li>							
							</ul>
						</div>
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuFive')" id="menuFive_t"><b><i class="xbt-gllmenu-i5">账户管理</i></b></a></h2>
							<ul id="menuFive" class="xbt-gllmul">
								<?php echo $coinbuys; ?>
								<?php echo $coincashs; ?>								
							</ul>
						</div>
						<div>
							<h2 class="menuTitle" onclick="menuShow('menuSix')" id="menuSix_t"><b><i class="xbt-gllmenu-i6">安全中心</i></b></a></h2>
							<ul id="menuSix" class="xbt-gllmul">
								<li><a <?php if($nowname=="/member/edit_baseinfo_btc.php") echo "class='btc8-gllmul-avist'";?> href="edit_baseinfo_btc.php">账号安全</a></li>
								<li><a href="personfin.php">个人财务中心</a></li>
								<li><a <?php if($nowname=="/member/edit_fullinfo_btc.php") echo "class='btc8-gllmul-avist'";?> href="edit_fullinfo_btc.php">个人资料</a></li>
								<li><a <?php if($nowname=="/member/index.php") echo "class='btc8-gllmul-avist'";?> href="index.php">邀请任务</a></li>
								<li><a <?php if($nowname=="/member/help.php") echo "class='btc8-gllmul-avist'";?> href="help.php">帮助</a></li>
								<li><a href="APICode_btc.php">交易API</a></li>
							</ul>
						</div>
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