<?php
//require(dirname(__FILE__)."/include/config.php");
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>����������</title>
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
      <td><strong>&nbsp;����������</strong>&nbsp;&nbsp;<a href="cate.php?action=new">��Ӳ������</a> | <a href="cate.php">�鿴�����б�</a></td>
     </tr>
	 <form action="cate.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
              <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
<tr class='row_color_head'>
<td>ID</td><td>����</td><td>����</td>
</tr>
<tr><td>ID��:1</td><td><img src=images/cate.gif align=absmiddle>&nbsp;����</td><td>��ϵͳ��֧���Զ���������</td></tr>
<tr><td>ID��:2</td><td><img src=images/cate.gif align=absmiddle>&nbsp;֧��</td><td>&nbsp;</td></tr>
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
