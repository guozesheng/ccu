<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')
ShowMsg('�Ƿ�����������ȷִ�д��ļ���','-1');
if($action=='save'){
if($l_name==''){
ShowMsg('�������²ֿ�','-1');
exit();
}
if($l_default==1){
$sasql=New Dedesql(false);
$sasql->ExecuteNoneQuery("update #@__lab set l_default=0");
$sasql->close();
}
 
 $addsql="update #@__lab set l_name='$l_name',l_city='$l_city',l_mang='$l_mang',l_default='$l_default' where id='$id'";
 $message= "�޸Ĳֿ�".$l_name."���ϳɹ�";
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->ExecuteNoneQuery("insert into #@__recordline(message,date,ip,userid) values('{$message}','{$logindate}','{$loginip}','$username')");
 $asql->close();
 showmsg('�ɹ��޸��˲ֿ�����','system_lab.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>�ֿ����</title>
</head>
<body>
<?php
$esql=New Dedesql(false);
$query="select * from #@__lab where id='$id'";
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
      <td><strong>&nbsp;�ֿ������޸�</strong></td>
     </tr>
	 <form action="system_lab_edit.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;�ֿ�����:</td>
		 <td>
		 &nbsp;<input type="text" name="l_name" size="10" value="<?php echo $row['l_name'] ?>" id="need"><input type="hidden" name="id" value="<?php echo $id; ?>">
		 </td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;���ڳ���:</td>
		 <td>
		 &nbsp;<input type="text" name="l_city" size="30" value="<?php echo $row['l_city'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;������:</td>
		 <td>
		 &nbsp;<input type="text" name="l_mang" size="15" value="<?php echo $row['l_mang'] ?>"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;�Ƿ�Ĭ�ϲֿ�:</td>
		 <td>
		 <?php
		  if ($row['l_default']==0)
		 echo "&nbsp;<select name=\"l_default\"><option value=\"1\">��</option><option value=\"0\" selected>��</option></select>&nbsp;ֻ�ܱ���һ��Ĭ�ϲֿ�";
		 else
		 echo "&nbsp;<select name=\"l_default\"><option value=\"1\" selected>��</option><option value=\"0\">��</option></select>&nbsp;ֻ�ܱ���һ��Ĭ�ϲֿ�";
		 ?>
		 </td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" �޸Ĳֿ����� "></td>
	    </tr>
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
copyright();
?>
</body>
</html>
