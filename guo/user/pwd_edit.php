<?php
require(dirname(__FILE__)."/../include/config_base.php");
require(dirname(__FILE__)."/../include/config_rglobals.php");
require(dirname(__FILE__)."/include/checklogin.php");

if($action == "edit")
{
	if ($newpwd != $repwd)
	{
		ShowMsg('������������Ĳ�һ�£�', '-1');
		exit();
	}
	if ($newpwd == null)
	{
		ShowMsg('���벻��Ϊ��!', '-1');
		exit();
	}
	
	$asql=New Dedesql(false);
	
	$sql = "select password from #@__boss where boss = '$_SESSION[boss]'";
	$row = $asql->GetOne($sql);
	
	if(md5($oldpwd) != $row['password'])
	{
		ShowMsg('��������֤����', '-1');
		exit();
	}
	
	$b_passwd = md5($newpwd);
	$addsql = "update #@__boss set password='$b_passwd' where boss = '$_SESSION[boss]'";
	
	$asql->ExecuteNoneQuery($addsql);
	$asql->close();
	
	ShowMsg('�����޸ĳɹ���', 'main.php');
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="../style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../include/ajax.js"></script>
<title>�����޸�</title>
</head>
<body>
<?php
$esql=New Dedesql(false);
$query="select * from #@__boss where boss='$_SESSION[boss]'";
$esql->SetQuery($query);
$esql->Execute();
if($esql->GetTotalRow()==0){
ShowMsg('�Ƿ����ò���,������','-1');
exit();
} 
$row=$esql->GetOne($query);
$esql->close();
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
      <td><strong>&nbsp;�����޸�</strong></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF"><form action="pwd_edit.php?action=edit" method="post">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td width="23%" id="row_style">&nbsp;�û�����:</td>
		 <td>&nbsp;<?php echo $row['name'] ?></td>
	    </tr>
        <tr>
        <td id="row_style">&nbsp;����:</td>
        <td width="180">&nbsp;<?=$row['boss']?></td>
        <td align="left" id="jobnumchk">&nbsp;</td>
        </tr>
        <tr>
        	<td id="row_style">&nbsp;�����룺</td>
            <td>&nbsp;<input type="password" name="oldpwd" /></td>
        </tr>
        <tr>
        	<td id="row_style">&nbsp;�����룺</td>
            <td>&nbsp;<input type="password" name="newpwd" /></td>
        </tr>
        <tr>
        	<td id="row_style">&nbsp;�ٴ��ظ���</td>
            <td>&nbsp;<input type="password" name="repwd" /></td>
        </tr>
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" �޸����� "></td>
        </tr>
	   </table></form>
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
