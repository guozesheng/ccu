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
<title>����������������</title>
</head>
<body>
<?php
	if ($_GET['ac'] == "delete")
	{
		$asksql = New Dedesql(false);
		$asksql->SetQuery("SELECT * FROM  #@__borrow_ask WHERE  `id` = '$_GET[id]' LIMIT 1");
		$asksql->Execute();
		$askrow = $asksql->GetArray();
		
		if ($askrow['isallow'] == 1)
		{
			$asksql->close();
			showmsg("�������ѱ�����Աͬ�⣡", 'equip_asklist.php');
			exit();
		}
		
		
		$asksql->SetQuery("DELETE FROM `#@__borrow_ask` WHERE `id` = '$_GET[id]' LIMIT 1");
		$asksql->Execute();
		$asksql->close();
		showmsg("��������ȡ����", 'equip_asklist.php');
		exit();
	}
	else if ($_GET['ac'] == "save")
	{
		exit();
	}
	else if ($_GET['ac'] == "show")
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
      <td><strong>&nbsp;��������</strong>&nbsp;&nbsp;<a href="equip_asklist.php">��������</a> | <a href="equip_bolist.php?ac=noreturn">�ѽ��ѯ</a></td>
     </tr>
     <form action="?id=<?=$_GET['id']?>&ac=delete" method="post" name="myform">
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
  	<td class="cellcolor">����ʣ��</td>
    <td>&nbsp;
    <?php
		getrestmount($askrow[basic_id], $basicrow['cp_sale']);
	?>
    </td>
  </tr>
  <tr>
  	<td class="cellcolor">��������:</td>
    <td>&nbsp;<?=$askrow['amount']?></td>
  </tr>
  <tr>
    <td class="cellcolor">ʹ������:</td>
    <td>&nbsp;<?=$askrow['asktime']?>����<?=$askrow['retime']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">������</td>
    <td>&nbsp;
    <?php
		if ($askrow['isallow'] == 0)
		{
			echo "<font color=\"#0000FF\">δ��׼</font>";
		}
		else if ($askrow['isallow'] == 2)
		{
			echo "<font color=\"#FF0000\">�Ѿܾ�</font>";
		}
		else 
		{
			echo $askrow['allowtime'];
		}
	?>
    </td>
  </tr>
  <tr>
    <td class="cellcolor">��ע:</td>
    <td>&nbsp;<?=$comment?></td>
  </tr>
  <?php
  	if ($askrow['isallow'] != 1)
	{
  ?>
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;<input type="submit" value=" ȡ������ "></td>
  </tr>
  <?php } ?>
  </form>
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