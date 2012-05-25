<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="include/ajax.js"></script>
<title>仪器报废列表</title>
</head>
<body>
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
      <td><strong>&nbsp;仪器报废列表</strong>&nbsp;&nbsp;<a href="equip_asklist.php">刷新</a> | <a href="equip_dropask.php">报废申请</a></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF">
	   <?php
       echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
		$csql->SetQuery("select * from #@__basic where  cp_isdrop = 1 ");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;没有任何报废记录。</td></tr>";
	   else{	?>
       <tr class="row_color_head">
       	<td>ID</td>
        <td>仪器名称</td>
        <td>仪器货号</td>
        <td>报废时间</td>
        <td>操作</td>
       </tr>
       <?php
	   	while ($row=$csql->GetArray())
		{
	   ?>
       	<tr class='row_content'>
        	<td>&nbsp;<?=$row['id']?></td>
        	<td>&nbsp;<?=$row['cp_name']?></td>
        	<td>&nbsp;<?=$row['cp_number']?></td>
            <td>&nbsp;<?=$row['cp_dropdate']?></td>
        	<td>&nbsp;<a href="equip_drophistory.php?id=<?=$row['id']?>&ac=show">详细</a></td>
        </tr>
       <?php
	   }
	   }
	   echo "</table>";
	  
	   $csql->close();
	   ?>
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
