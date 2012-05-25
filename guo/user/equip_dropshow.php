<?php
require(dirname(__FILE__)."/../include/config_rglobals.php");
require(dirname(__FILE__)."/../include/config_base.php");
require(dirname(__FILE__)."/../include/page.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="../style/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../include/py.js"></script>
<script language="javascript" type="text/javascript" src="../include/WdatePicker.js"></script>
<title>仪器报废申请</title>
</head>

<?php
if ($ac=='delete')
{
	$asksql = New Dedesql(false);
	$asksql->SetQuery("SELECT * FROM  #@__drop_ask WHERE  `id` = '$_GET[id]' LIMIT 1");
	$asksql->Execute();
	$askrow = $asksql->GetArray();
	
	if ($askrow['isallow'] == 1)
	{
		$asksql->close();
		showmsg("该申请已被管理员同意！", 'equip_asklist.php');
		exit();
	}
	
	
	$asksql->SetQuery("DELETE FROM `#@__drop_ask` WHERE `id` = '$_GET[id]' LIMIT 1");
	$asksql->Execute();
	$asksql->close();
	showmsg("该申请已取消！", 'equip_droplist.php');
	exit();
}
else if ($ac == "show")
{
	$dropsql=New Dedesql(false);
	$squery="select * from #@__drop_ask where id='$_GET[id]'";
	$dropsql->SetQuery($squery);
	$dropsql->Execute();
	$droprow=$dropsql->GetOne();
	
	$seekrs=New Dedesql(false);
	$squery="select * from #@__basic where id='$droprow[basic_id]'";
	$seekrs->SetQuery($squery);
	$seekrs->Execute();
	$rowcount=$seekrs->gettotalrow();
	if($rowcount==0)
	{
		Showmsg('非法的参数','-1');
		exit();
	}
	$row=$seekrs->GetOne();
	
	$bossql = New Dedesql(false);
	$squery="select * from #@__boss where boss='$droprow[boss_id]'";
	$bossql->SetQuery($squery);
	$bossql->Execute();
	$bossrow=$bossql->GetOne();
	
	$seekrs->close();
	$dropsql->close();
	$bossql->close();
?>
<body onload="form1.cp_tm.focus()">
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
      <td><strong>&nbsp;仪器报废申请</strong>&nbsp;&nbsp;- <a href="equip_dropshow.php?action=seek">申请列表</a> - <a href="equip_dropshow.php">申请历史</a></td>
     </tr><form action="equip_dropshow.php?id=<?=$droprow['id']?>&ac=delete" method="post" name="form1">
     <tr>
      <td bgcolor="#FFFFFF">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_border">
   <tr>
    <td class="cellcolor">仪器货号:</td>
    <td>&nbsp;<input type="hidden" value="<?php echo $id?>" name="id"><input type="text" name="cp_number" value="<?php echo $row['cp_number'] ?>" style="background:transparent;border:0px;color:red;font-weight:bold;font-family:Verdana, Arial, Helvetica, sans-serif;" readonly></td>
  </tr>
  <tr>
    <td class="cellcolor" width="30%">仪器名称:</td>
    <td>&nbsp;<?=$row['cp_name']?></td>
  </tr>
  <tr>
    <td class="cellcolor">仪器规格:</td>
    <td>&nbsp;<?=$row['cp_gg']?></td>
  </tr>
  <tr>
    <td class="cellcolor">供应商:</td>
    <td>&nbsp;<?=$row['cp_gys']?></td>
  </tr>
  <tr>
    <td class="cellcolor">仪器所属分类:</td>
    <td>&nbsp;
	<?php
    viewcategories($row['cp_categories'],$row['cp_categories_down']);
	?>
    </td>
  </tr>
  <tr>
    <td class="cellcolor">购买日期:</td>
    <td>&nbsp;<?=$row['cp_sdate']?></td>
  </tr>
  <tr>
    <td class="cellcolor">进价:</td>
    <td>&nbsp;￥<?=$row['cp_jj']?></td>
  </tr>
  <tr>
    <td class="cellcolor">单位:</td>
    <td>&nbsp;<?php viewdw($row['cp_dwname']) ?></td>
  </tr>
  <tr>
  	<td class="cellcolor">仪器数量:</td>
    <td>
    <?php
	viewamout($id, $row['cp_sale']);
	?>
    </td>
  </tr>
  <tr>
  	<td class="cellcolor">报废申请者</td>
    <td>&nbsp;<?=$bossrow['name']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">申请时间</td>
    <td>&nbsp;<?=$droprow['askdate']?></td>
  </tr>
  <tr>
  	<td class="cellcolor">申请结果</td>
    <td>&nbsp;<?php if ($droprow['isallow'] == 0) echo "未批准"; else if ($droprow['isallow'] == 1) echo "<font color=\"#0000FF\">已报废</font>"; else if ($droprow['isallow'] == 2) echo "<font color=\"#FF0000\">已拒绝</font>"; ?></td>
  </tr>
  <tr>
  	<td class="cellcolor">确认时间</td>
    <td>&nbsp;
		<?php
            if ($row['allowdate'] != 0)
            {
                echo $allowdate;
            }
            else 
            {
                echo "-";
            }
        ?>
    </td>
  </tr>
  <tr>
    <td class="cellcolor">报废原因:</td>
    <td>&nbsp;<?=$droprow['comment']?></td>
  </tr>
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;
    <?php
		if ($droprow['isallow'] != 1)
		{ 
	?>
    <input type="submit" value=" 取消申请 "></td>
    <?php } ?>
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
copyright();
?>
</body>
</html>
