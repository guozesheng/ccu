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
<title>����������ϸ��Ϣ</title>
</head>
<body>
<?php
/*
	if ($_GET['ac'] == "dis")
	{
       $sql=New Dedesql(false);
	   $mysql = "UPDATE  #@__borrow_ask SET  `isallow` =  '2' WHERE  `id` = '$_GET[id]' LIMIT 1";
	   $sql->SetQuery($mysql);
	   $sql->Execute();
	   $sql->close();
	   $msg="�Ѿܾ���";
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
			$msg="�Ѿܾ���";
			showmsg($msg,'borrow_ask.php');
			exit();
		}
		
		if ($borrow_num > $borrow_reset || $borrow_num < 0)
		{
			Showmsg('��Ʒ�������㣬��˶�','equip.php?action=seek');
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
		$bowsql->SetQuery("INSERT INTO  #@__borrow (`id`, `askid` ,`basic_id` ,`boss_id` ,`amount` ,`borrow_t` ,`return_t` ,`is_return` ,`comment`)VALUES (NULL, '$_GET[id]',  '$askrow[basic_id]',  '$askrow[boss_id]',  '$borrow_num',  '$cp_edate',  '$re_date',  '0',  '$cp_bz')");
		$bowsql->Execute();
		$bowsql->close();
		
		$msg="����׼��";
		showmsg($msg,'borrow_ask.php');
		exit();
	}
	else if ($_GET['ac'] == "show")
	// */
	if ($_GET['ac'] == "show")
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
      <td><strong>&nbsp;��������</strong>&nbsp;&nbsp;<a href="borrow_ask.php">�������</a> | <a href="borrow_list.php">�ѽ��ѯ</a></td>
     </tr>
     <form action="?id=<?=$_GET['id']?>&ac=save" method="post" name="myform">
     <tr>
      <td bgcolor="#FFFFFF">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_border">
   <tr>
    <td class="cellcolor">��������:</td>
    <td>&nbsp;<input type="text" name="cp_number" value="<?php echo $basicrow['cp_number'] ?>" style="background:transparent;border:0px;color:red;font-weight:bold;font-family:Verdana, Arial, Helvetica, sans-serif;" readonly></td>
  </tr>
  <tr>
  	<td class="cellcolor">������:</td>
    <td>&nbsp;<?=$bossrow['name']?></td>
  </tr>
  <tr>
    <td class="cellcolor" width="30%">��������:</td>
    <td>&nbsp;<?=$basicrow['cp_name']?></td>
  </tr>
  <tr>
    <td class="cellcolor">�������:</td>
    <td>&nbsp;<?=$basicrow['cp_gg']?></td>
  </tr>
  <tr>
    <td class="cellcolor">��Ӧ��:</td>
    <td>&nbsp;<?=$basicrow['cp_gys']?></td>
  </tr>
  <tr>
    <td class="cellcolor">������������:</td>
    <td>&nbsp;
	<?php
    viewcategories($basicrow['cp_categories'],$basicrow['cp_categories_down']);
	?>
    </td>
  </tr>
  <tr>
    <td class="cellcolor">��������:</td>
    <td>&nbsp;<?=$basicrow['cp_sdate']?></td>
  </tr>
  <tr>
    <td class="cellcolor">����:</td>
    <td>&nbsp;��<?=$basicrow['cp_jj']?></td>
  </tr>
  <tr>
    <td class="cellcolor">��λ:</td>
    <td>&nbsp;<?php viewdw($basicrow['cp_dwname']) ?></td>
  </tr>
  <tr>
  	<td class="cellcolor">��������:</td>
    <td>
    <?php
	viewamout($askrow[basic_id], $basicrow['cp_sale']);
	?>
    </td>
  </tr>
  <tr>
  	<td class="cellcolor">��������:</td>
    <td>&nbsp;<?=$askrow['amount']?></td>
  </tr>
  <tr>
    <td class="cellcolor">ʹ������:</td>
    <td>&nbsp;<?=$brow['borrow_t']?>����<?=$brow['return_t']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">��������</td>
    <td>&nbsp;<?=$askrow['askdate']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">ȷ������</td>
    <td>&nbsp;<?=$askrow['allowtime']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">״̬</td>
    <td>&nbsp;<?php if ($brow['is_return'] == 0) echo "δ�黹"; else echo "�ѹ黹"; ?></td>
  </tr>
  <tr>
    <td class="cellcolor">��ע:</td>
    <td>&nbsp;<p><?=$brow['comment']?></p></td>
  </tr>
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;<input type="submit" value=" �ύ "></td>
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