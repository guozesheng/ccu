<?php
require(dirname(__FILE__)."/../include/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="../style/main.css" rel="stylesheet" type="text/css" />
<title>系统菜单</title>
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
      <td><strong>&nbsp;<?php echo $cfg_au_version;?>常规操作</strong></td>
     </tr>
     <tr>
      <td id="row_style">
	  <a href="equip.php?action=seek"><img src="../images/normal_1.gif" border="0"></a>
	  <img src="../images/arrow_to.gif" border="0">
	  <a href="equip_bolist.php?ac=noreturn"><img src="../images/normal_4.gif" border="0"></a>
	  </td>
     </tr>
     <tr>
      <td id="row_style">
	  <a href="equip_droplist.php"><img src="../images/normal_3.gif" border="0"></a>
	  <img src="../images/arrow_to.gif" border="0">
	  <a href="equip_repairlist.php"><img src="../images/normal_5.gif" border="0"></a>
      </td>
     </tr>
	 <tr>
	  <td>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
        <tr>
         <td colspan="2" style="height:35px;background-color:#4176BF;font:bold 13px Arial;">&nbsp;<?php echo $cfg_au_version;?>开发</td>
        </tr>
        <tr class="row_color_normal">
         <td width="30%">&nbsp;&nbsp;版权所有</td>
         <td>&nbsp;<?php echo "<a href='{$cfg_au_homepage}' target='_blank'>".$cfg_au_company."</a>"; ?></td>
        </tr>
        <tr class="row_color_gray">
         <td>&nbsp;&nbsp;总策划兼项目负责人</td>
         <td><?php echo $cfg_au_name; ?></td>
        </tr>
        <tr class="row_color_normal">
         <td>&nbsp;&nbsp;产品官方网站</td>
         <td>&nbsp;<?php echo "<a href='{$cfg_au_homepage}' target='_blank'>".$cfg_au_homepage."</a>"; ?></td>
        </tr>
        <tr class="row_color_gray">
         <td>&nbsp;&nbsp;产品官方论坛</td>
         <td>&nbsp;<?php echo "<a href='{$cfg_au_bbsurl}' target='_blank'>".$cfg_au_bbsurl."</a>"; ?></td>
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
copyright();
?>
</body>
</html>
