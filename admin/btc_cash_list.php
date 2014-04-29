<?php
/**
 * 订单操作
 *
 * @version        $Id: btc_cash_list.php 1 15:46 2013年8月20日 SZ $
 */
 
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Data,a_Check,a_AccCheck');
require_once(DEDEINC.'/datalistcp.class.php');


$dsql->SetQuery("SELECT * FROM #@__btctype");
	$dsql->Execute();
    while($row = $dsql->GetObject())
	{
		 if($row->coinsign==1) $CoinBN .= "<input type=\"button\" name=\"ss".$row->id."\" value=\"".$row->cointype."\" style=\"width:50px;margin-right:6px\" onClick=\"location='btc_cash_list.php?coinid=".$row->id."';\"  class='np coolbg'/>";
		 $coinarr[$row->id]=$row->cointype;
		 $coinhost[$row->id]=$row->coinhost;
		 $coinpayid[$row->id]=$row->payid;
	}


if(isset($dopost))
{
    CheckPurview('sys_Data,a_Check,a_AccCheck');
    if($dopost == 'up')
    {
        $nids = explode('`',$nid);
        $wh = '';
        foreach($nids as $n)
        {
			$row = $dsql->GetOne("Select coinid,amount,fee,checked,userid,address,adduser From #@__btccash where id='$n' AND checked=0");
			if(is_array($row)){
				$rchk=$dsql->ExecuteNoneQuery("UPDATE #@__btccash SET `checked`='1',`checktime`='".time()."' WHERE id='$n'");
				if($coinarr[$row['coinid']]=="CNY"){
					$rjs = $dsql->GetOne("SELECT `jsuserid` FROM `#@__member` WHERE `mid`='".$row['userid']."'");
					if(is_array($rjs) && $rjs['jsuserid']>0){
						$rdt = $dsql->GetOne("SELECT `dealid` FROM `#@__btcdeduct` WHERE `dealid`='".$n."' AND `dealtype`='2'");
						if(!is_array($rdt)){
							$rsnew = $dsql->ExecuteNoneQuery("INSERT INTO  #@__btcdeduct(`dealid`,`newuserid`,`userid`,`fee`,`deduct`,`dealtype`,`dealtime`,`adduser`,`coinid`)VALUES('$n','".$row['userid']."','".$rjs['jsuserid']."','".$row['fee']."','".(floor($cfg_deduct*$row['fee']*10000000)/10000000)."','2','".time()."','0','".$row['coinid']."')");	
						}
						
					}
				}
				//if(!is_array($row)){echo "失败";exit();} 
				//调用转账接口
				if($row['adduser']!=1 && $coinpayid[$row['coinid']]=='2'){
					require_once DEDEINC.'/rpcQuery.php';
					$method="sendtoaddress";
					
					//转账参数
					$params=array($row['address'],floatval($row['amount']),GetMemberIDwithoutA($row['userid']),$cfg_webname);
					$balance=coinQuery ($coinarr[$row['coinid']],$method,$params);
					if(isset($balance['r'])){
						$rsup = $dsql->ExecuteNoneQuery("Update #@__btccash Set dealmark=1,adduser=1,txid='".$balance['r']."' where id = '".$n."'"); 
					}
				}
			}
			
        }
        
		
    }
	elseif($dopost == 'out')
    {
        $nids = explode('`',$nid);
        $wh = '';
        foreach($nids as $n)
        {
			$row = $dsql->GetOne("Select coinid,amount,fee,checked,userid,address,adduser From #@__btccash where id='$n' AND checked=0");
			if(is_array($row)){
				$rchk=$dsql->ExecuteNoneQuery("UPDATE #@__btccash SET `checked`='1',`checktime`='".time()."' WHERE id='$n'");
				if($coinarr[$row['coinid']]=="CNY"){
					$rjs = $dsql->GetOne("SELECT `jsuserid` FROM `#@__member` WHERE `mid`='".$row['userid']."'");
					if(is_array($rjs) && $rjs['jsuserid']>0){
						$rdt = $dsql->GetOne("SELECT `dealid` FROM `#@__btcdeduct` WHERE `dealid`='".$n."' AND `dealtype`='2'");
						if(!is_array($rdt)){
							$rsnew = $dsql->ExecuteNoneQuery("INSERT INTO  #@__btcdeduct(`dealid`,`newuserid`,`userid`,`fee`,`deduct`,`dealtype`,`dealtime`,`adduser`,`coinid`)VALUES('$n','".$row['userid']."','".$rjs['jsuserid']."','".$row['fee']."','".(floor($cfg_deduct*$row['fee']*10000000)/10000000)."','2','".time()."','0','".$row['coinid']."')");	
						}
						
					}
				}
				
			}
			
        }
        $sql="UPDATE #@__btccash SET `checked`='1',`adduser`='1' $wh ";
        $dsql->ExecuteNoneQuery($sql);
    }
	elseif($dopost == 're')
    {
        $nids = explode('`',$nid);
        $wh = '';
        foreach($nids as $n)
        {
			$row = $dsql->GetOne("Select dealid From #@__btcdeduct WHERE dealid='$n' AND dealtype='2'");
			if(is_array($row)){
				if($row['adduser']==0){
					$query = "DELETE FROM `#@__btcdeduct` WHERE dealid='$n' AND dealtype='2' AND adduser='0'";
            		$dsql->ExecuteNoneQuery($query);
					if($wh=='') $wh = " id='$n' ";
            		else $wh .= " OR id='$n' ";
				}
			}else{
				if($wh=='') $wh = " id='$n' ";
            	else $wh .= " OR id='$n' ";
			}
        }
        $sql="UPDATE #@__btccash SET `checked`='0' WHERE ($wh) AND `adduser`=0";
        $dsql->ExecuteNoneQuery($sql);
    }
	elseif ($dopost == 'delete')
    {
        $nids = explode('`', $nid);
        foreach($nids as $n)
        {
            $query = "DELETE FROM `#@__btccash` WHERE id='$n'";

            $dsql->ExecuteNoneQuery($query);

        }
        ShowMsg("成功删除指定的订单记录！",$ENV_GOBACK_URL);
        exit();
    }
    else
    {
        ShowMsg("不充许的操作范围！",$ENV_GOBACK_URL);
        exit();
    }
    ShowMsg("成功更改指定的订单记录！",$ENV_GOBACK_URL);
    exit();
}

$addsql = '';
//if(empty($dealid)) $dealid = 0;
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(isset($typeId))
{
    $typeId  = preg_replace("#[^-0-9A-Z]#", "", $typeId);
}
switch ($typeId)
{
case 1:
  $addsql = "WHERE id='".$sid."'";
  break;  
case 2:
  $addsql = "WHERE userid='".GetUserID($sid)."'";
  break;
default:
  $addsql = '';
}


if(isset($coinid))
{
    if(isset($addsql)) $addsql = "WHERE `coinid`='$coinid' ";
	else $addsql = " AND `coinid`='$coinid'";
}


$sql = "SELECT * FROM #@__btccash $addsql ORDER BY `cashtime` DESC";

$dlist = new DataListCP();
if(isset($typeId)) $dlist->SetParameter("typeId",$typeId);
if(isset($sid)) $dlist->SetParameter("sid",$sid);
if(isset($dealtype)) $dlist->SetParameter("dealtype",$dealtype);
if(isset($coinid)) $dlist->SetParameter("coinid",$coinid);
$tplfile = DEDEADMIN."/templets/btc_cash_list.htm";

//这两句的顺序不能更换
$dlist->SetTemplate($tplfile);      //载入模板
$dlist->SetSource($sql);            //设定查询SQLexit('dd');
$dlist->Display();


function GetsPayment($pid)
{
    global $dsql;
    $pid = intval($pid);
    $row = $dsql->GetOne("SELECT name FROM #@__payment WHERE id='$pid'");
    if(is_array($row))
    {
        return $row['name'];
    }
    else
    {
        return "未知";
    }
}



function GetMemberID($mid)
{
    global $dsql;
    if($mid==0) return '0';
    $row = $dsql->GetOne("SELECT userid FROM #@__member WHERE mid='$mid' ");
    if(is_array($row))
    {
        return "<a href='member_view.php?id={$mid}'>".$row['userid']."</a>";
    }
    else
    {
        return '0';
    }
}

function GetMemberIDwithoutA($mid)
{
    global $dsql;
    if($mid==0) return '0';
    $row = $dsql->GetOne("SELECT userid FROM #@__member WHERE mid='$mid' ");
    if(is_array($row))
    {
        return $row['userid'];
    }
    else
    {
        return '0';
    }
}

function GetUserID($name)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT mid FROM #@__member WHERE userid='$name' ");
    if(is_array($row))
    {
        return $row['mid'];
    }
    else
    {
        return '0';
    }
}

function GetsChecked($pid)
{
    if($pid==1)
    {
        return "<font color=#00FF00>通过</font>";
    }elseif($pid==-1){
        return "<font color=#FF0000>取消</font>";
    }elseif($pid==0){
        return "未审";
    }
}
function GetsAddUser($pid)
{
    if($pid==1)
    {
        return "<font color=#00FF00>已出</font>";
    }
    else
    {
        return "未出";
    }
}
function lenCat($str){
    if(strlen($str)>15) return '<span title=\''.$str.'\'>'.substr($str,0,15).'..<span>';
    else return $str;
}