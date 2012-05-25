<?php
	require(dirname(__FILE__)."/../include/config_base.php");
	require(dirname(__FILE__)."/../include/config_rglobals.php");
	require(dirname(__FILE__)."/include/checklogin.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="../style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../include/ajax.js"></script>
<title>仪器借用详细信息</title>
</head>
<body>
<?php
	if ($_GET['ac'] == "return")
	{
		$bsql = New Dedesql(false);
		$bsql->SetQuery("UPDATE  `#@__borrow` SET  `is_return` =  '1' WHERE  `id` = '$_GET[id]' LIMIT 1");
		$bsql->Execute();
		$brow = $bsql->GetArray();
		$bsql->close();
		
		$msg="归还成功！";
		showmsg($msg,'equip_bolist.php');
		exit();
	}
	else if ($_GET['ac'] == "show")
	{
		$bsql = New Dedesql(false);
		$bsql->SetQuery("SELECT * FROM  #@__borrow WHERE  `id` = '$_GET[id]' LIMIT 1");
		$bsql->Execute();
		$brow = $bsql->GetArray();
		$bsql->close();
		
		$asksql = New Dedesql(false);
		$asksql->SetQuery("SELECT * FROM  #@__borrow_ask WHERE  `id` = '$brow[askid]' LIMIT 1");
		$asksql->Execute();
		$askrow = $asksql->GetArray();
		$asksql->close();
		
		$basicsql = New Dedesql(false);
		$basicsql->SetQuery("select * from #@__basic where id = '$brow[basic_id]' LIMIT 1");
		$basicsql->Execute();
		$basicrow = $basicsql->GetArray();
		$basicsql->close();
		
		$bossql = New Dedesql(false);
		$bossql->SetQuery("select * from #@__boss where boss = '$brow[boss_id]' LIMIT 1");
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
      <td><strong>&nbsp;借用审批</strong>&nbsp;&nbsp;<a href="equip_bolist.php">借用历史</a> | <a href="equip_asklist.php">借用申请</a></td>
     </tr>
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
  	<td class="cellcolor">借用数量:</td>
    <td>&nbsp;<?=$askrow['amount']?></td>
  </tr>
  <tr>
    <td class="cellcolor">使用日期:</td>
    <td>&nbsp;<?=$brow['borrow_t']?>——<?=$brow['return_t']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">申请日期</td>
    <td>&nbsp;<?=$askrow['askdate']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">确认日期</td>
    <td>&nbsp;<?=$askrow['allowtime']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">状态</td>
    <td>&nbsp;<?php if ($brow['is_return'] == 0) echo "<font color=\"#FF0000\">未归还</font>"; else echo "已归还"; ?></td>
  </tr>
  <tr>
    <td class="cellcolor">备注:</td>
    <td>&nbsp;<p><?=$brow['comment']?></p></td>
  </tr>
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;
    	<?php 
			if ($brow['is_return'] == 0)
			{
		?>
    	<a href="?id=<?=$_GET['id']?>&ac=return">归还</a>
        <?php } ?>
    </td>
  </tr>
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