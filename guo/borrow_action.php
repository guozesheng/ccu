<?php
	require(dirname(__FILE__)."/include/config_base.php");
	require(dirname(__FILE__)."/include/config_rglobals.php");
	require(dirname(__FILE__)."/include/checklogin.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="include/ajax.js"></script>
<title>仪器借用申请审批</title>
</head>
<body>
<?php
	if ($_GET['ac'] == "dis")
	{
       $sql=New Dedesql(false);
	   $mysql = "UPDATE  #@__borrow_ask SET  `isallow` =  '2' WHERE  `id` = '$_GET[id]' LIMIT 1";
	   $sql->SetQuery($mysql);
	   $sql->Execute();
	   $sql->close();
	   $msg="已拒绝！";
	   showmsg($msg,'borrow_ask.php');
	   exit();
	}
	else if ($_GET['ac'] == "save")
	{
		if ($baction == 0)
		{
			echo $showtime=date("Y-m-d");
			$sql=New Dedesql(false);
			$mysql = "UPDATE  #@__borrow_ask SET  `isallow` =  '2' WHERE  `id` = '$_GET[id]' LIMIT 1";
			$sql->SetQuery($mysql);
			$sql->Execute();
			$sql->close();
			$msg="已拒绝！";
			showmsg($msg,'borrow_ask.php');
			exit();
		}
		
		if ($borrow_num > $borrow_reset || $borrow_num < 0)
		{
			Showmsg('产品数量不足，请核对','equip.php?action=seek');
			exit();
		}
		
		$today=date("Y-m-d");
		$asksql = New Dedesql(false);
		$asksql->SetQuery("UPDATE  #@__borrow_ask SET `isallow` =  '1', `allowtime` = '$today' WHERE `id` = '$_GET[id]' LIMIT 1");
		$asksql->Execute();
		$asksql->SetQuery("SELECT * FROM  #@__borrow_ask WHERE  `id` = '$_GET[id]' LIMIT 1");
		$asksql->Execute();
		$askrow = $asksql->GetArray();
		$asksql->close();
		
		$bowsql = New Dedesql(false);
		$bowsql->SetQuery("INSERT INTO  #@__borrow (`id` ,`basic_id` ,`boss_id` ,`amount` ,`borrow_t` ,`return_t` ,`is_return` ,`comment`)VALUES (NULL ,  '$askrow[basic_id]',  '$askrow[boss_id]',  '$borrow_num',  '$cp_edate',  '$re_date',  '0',  '$cp_bz')");
		$bowsql->Execute();
		$bowsql->close();
		
		$msg="已批准！";
		showmsg($msg,'borrow_ask.php');
		exit();
	}
	else if ($_GET['ac'] == "allow")
	{
		$asksql = New Dedesql(false);
		$asksql->SetQuery("SELECT * FROM  #@__borrow_ask WHERE  `id` = '$_GET[id]' LIMIT 1");
		$asksql->Execute();
		$askrow = $asksql->GetArray();
		$asksql->close();
		
		$basicsql = New Dedesql(false);
		$basicsql->SetQuery("select * from #@__basic where id = '$askrow[basic_id]' LIMIT 1");
		$basicsql->Execute();
		$basicrow = $basicsql->GetArray();
		$basicsql->close();
		
		$bossql = New Dedesql(false);
		$bossql->SetQuery("select * from #@__boss where boss = '$askrow[boss_id]' LIMIT 1");
		$bossql->Execute();
		$bossrow = $bossql->GetArray();
		$bossql->close();
?>
<table width="100%" border="0" id="table_style_all" cellpadding="0" cellspacing="0">
  <tr>
    <td id="table_style" class="l_t">&nbsp;</td>
    <td>&nbsp;</td>
    <td id="table_style" class="r_t">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="2">
     <tr>
      <td><strong>&nbsp;借用审批</strong>&nbsp;&nbsp;<a href="borrow_ask.php">申请待批</a> | <a href="borrow_list.php">已借查询</a></td>
     </tr>
     <form action="?id=<?=$_GET['id']?>&ac=save" method="post" name="myform">
     <tr>
      <td bgcolor="#FFFFFF">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_border">
   <tr>
    <td class="cellcolor">仪器货号:</td>
    <td>&nbsp;<input type="text" name="cp_number" value="<?php echo $basicrow['cp_number'] ?>" style="background:transparent;border:0px;color:red;font-weight:bold;font-family:Verdana, Arial, Helvetica, sans-serif;" readonly></td>
  </tr>
  <tr>
  	<td class="cellcolor">申请人:</td>
    <td>&nbsp;<?=$bossrow['name']?></td>
  </tr>
  <tr>
    <td class="cellcolor" width="30%">仪器名称:</td>
    <td>&nbsp;<?=$basicrow['cp_name']?></td>
  </tr>
  <tr>
    <td class="cellcolor">仪器规格:</td>
    <td>&nbsp;<?=$basicrow['cp_gg']?></td>
  </tr>
  <tr>
    <td class="cellcolor">供应商:</td>
    <td>&nbsp;<?=$basicrow['cp_gys']?></td>
  </tr>
  <tr>
    <td class="cellcolor">仪器所属分类:</td>
    <td>&nbsp;
	<?php
    viewcategories($basicrow['cp_categories'],$basicrow['cp_categories_down']);
	?>
    </td>
  </tr>
  <tr>
    <td class="cellcolor">购买日期:</td>
    <td>&nbsp;<?=$basicrow['cp_sdate']?></td>
  </tr>
  <tr>
    <td class="cellcolor">进价:</td>
    <td>&nbsp;￥<?=$basicrow['cp_jj']?></td>
  </tr>
  <tr>
    <td class="cellcolor">单位:</td>
    <td>&nbsp;<?php viewdw($basicrow['cp_dwname']) ?></td>
  </tr>
  <tr>
  	<td class="cellcolor">仪器数量:</td>
    <td>
    <?php
	viewamout($askrow[basic_id], $basicrow['cp_sale']);
	?>
    </td>
  </tr>
  <tr>
  	<td class="cellcolor">仪器剩余</td>
    <td>&nbsp;
    <?php
		getrestmount($askrow[basic_id], $basicrow['cp_sale']);
	?>
    </td>
  </tr>
  <tr>
  	<td class="cellcolor">借用数量:</td>
    <td>&nbsp;<input type="text" name="borrow_num" value="<?=$askrow['amount']?>" /><input type="hidden" name="borrow_reset" value="<?php getrestmount($askrow[basic_id], $basicrow['cp_sale']); ?>" />
    </td>
  </tr>
  <tr>
    <td class="cellcolor">使用日期:</td>
    <td>&nbsp;<input type="text" name="cp_edate" value="<?=$askrow['asktime']?>" readonly="readonly" />——<input type="text" name="re_date" value="<?=$askrow['retime']?>" readonly="readonly" /></td>
  </tr>
  <tr>
  	<td class="cellcolor">操作</td>
    <td>批准<input type="radio" name="baction" checked="checked" value="1" />&nbsp;拒绝<input type="radio" name="baction" value="0" /></td>
  </tr>
  <tr>
    <td class="cellcolor">备注:</td>
    <td>&nbsp;
      <textarea rows="5" cols="80" name="cp_bz"></textarea></td>
  </tr>
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;<input type="submit" value=" 提交 "></td>
  </tr></form>
</table>
    </td>
     </tr>
    </table>
	</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td id="table_style" class="l_b">&nbsp;</td>
    <td>&nbsp;</td>
    <td id="table_style" class="r_b">&nbsp;</td>
  </tr>
</table>
<?php 
	}
?>
</body>