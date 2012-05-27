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
<title>仪器维修申请</title>
</head>

<?php
if ($action=='save')
{
	$today=date("Y-m-d");
	$sql = "INSERT INTO `#@__repair_ask` (`id`, `basic_id`, `boss_id`, `askdate`, `isallow`, `allowdate`, `comment`) VALUES (NULL, '$id', '$_SESSION[boss]', '$today', '', '', '$cp_bz');";
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($sql);
 $asql->close();
 $msg="申请已提交！";
 showmsg($msg,'equip.php?action=seek');
 exit();
}

$seekrs=New Dedesql(falsh);
$squery="select * from #@__basic where id='$id'";
$seekrs->SetQuery($squery);
$seekrs->Execute();
$rowcount=$seekrs->gettotalrow();
if($rowcount==0){
Showmsg('非法的参数','-1');
exit();
}
$row=$seekrs->GetOne();
$seekrs->close();
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
      <td><strong>&nbsp;维修申请详单</strong>&nbsp;&nbsp;- <a href="equip_repairlist.php?action=seek">申请列表</a> | <a href="equip_repairlist.php">申请历史</a></td>
     </tr><form action="equip_repair.php?action=save" method="post" name="form1">
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
  	<td class="cellcolor">仪器剩余</td>
    <td>&nbsp;
    <?php
		getrestmount($id, $row['cp_sale']);
	?>
    </td>
  </tr>
  <tr>
    <td class="cellcolor">维修原因:</td>
    <td>&nbsp;
      <textarea rows="5" cols="100" name="cp_bz"></textarea></td>
  </tr>
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;<input type="submit" value=" 确认申请 "></td>
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
copyright();
?>
</body>
</html>
