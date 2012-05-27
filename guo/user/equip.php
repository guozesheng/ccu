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
<!--<script language="javascript" src="include/calendar.js"></script>-->
<script language="javascript" src="include/py.js"></script>
<script language="javascript" type="text/javascript" src="include/WdatePicker.js"></script>
<title>仪器基本信息录入</title>
<script language = "JavaScript">
var onecount; 
onecount = 0; 
subcat = new Array();
<?php
$count=0;
$rsql=New Dedesql(false);
$rsql->SetQuery("select * from #@__categories where reid!=0");
$rsql->Execute();
while($rs=$rsql->GetArray()){
?>
subcat[<?php echo $count;?>] = new Array("<?php echo $rs['categories'];?>","<?php echo $rs['reid'];?>","<?php echo $rs['id'];?>");
<?php 
    $count++;
}
$rsql->close();
?>
onecount=<?php echo $count?>; 
</script>
<script type="text/vbscript"> 
function vbChr(c) 
vbChr = chr(c) 
end function 

function vbAsc(n) 
vbAsc = asc(n) 
end function 
</script> 
</head>
<?php
if($action=='seek'){ //列表
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
      <td><strong>&nbsp;仪器基本信息管理</strong>&nbsp;&nbsp;- <a href="equip_bolist.php">已借仪器查询</a> - <a href="equip.php?action=seek">仪器基本信息查询</a></td>
     <tr>
      <td bgcolor="#FFFFFF">
<?php
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
$query="select * from #@__basic where cp_isdrop != 1";
$csql=New Dedesql(false);
$dlist = new DataList();
$dlist->pageSize = $cfg_record;
//$dlist->SetParameter("form",$form);
//$dlist->SetParameter("field",$field);//设置GET参数表
$dlist->SetSource($query);
	   echo "<tr class='row_color_head'><td>ID</td><td>名称</td><td>规格</td><td>分类</td><td>单位</td><td>进价</td><td>供应商</td><td>助记词</td><td>操作</td></tr>";
	   $mylist = $dlist->GetDataList();
       while($row = $mylist->GetArray('dm')){
	   echo "<tr class='row_content'><td>".$row['id']."</td><td>&nbsp;".$row['cp_name']."</td><td>&nbsp;".$row['cp_gg']."</td><td>&nbsp;".get_name($row['cp_categories'],'categories').">".get_name($row['cp_categories_down'],'categories')."</td><td>&nbsp;".get_name($row['cp_dwname'],'dw')."</td><td>&nbsp;￥".$row['cp_jj']."</td><td>".$row['cp_gys']."</td><td>".$row['cp_helpword']."</td><td><a href=equip_borrow.php?id=".$row['id'].">借用</a> | <a href=equip_drop.php?id=".$row['id'].">申请报废</a> | <a href=equip_repair.php?id=".$row['id'].">申请维修</a></td></tr>";
	   }
	   echo "<tr><td colspan='8'>&nbsp;".$dlist->GetPageList($cfg_record)."</td></tr></table>";
	  
	   $csql->close();
   echo " </td></tr></table>
 </td></tr><tr>
    <td id=\"table_style\" class=\"l_b\">&nbsp;</td>
    <td>&nbsp;</td>
    <td id=\"table_style\" class=\"r_b\">&nbsp;</td>
  </tr>
</table>";
 }
?>
</table>
<?php
copyright();
?>
</body>
</html>
