<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')
ShowMsg('�Ƿ�����������ȷִ�д��ļ���','-1');
if($action=='save'){
if($g_name==''){
ShowMsg('�����빩Ӧ�̵�����','-1');
exit();
}
 $addsql="update #@__gys set g_name='$g_name',g_people='$g_people',g_address='$g_address',g_phone='$g_phone',g_qq='$g_qq' where id='$id'";
 $message= "�޸Ĺ�Ӧ��".$g_name."�ɹ�";
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->ExecuteNoneQuery("insert into #@__recordline(message,date,ip,userid) values('{$message}','{$logindate}','{$loginip}','$username')");
 $asql->close();
 showmsg('�ɹ��޸Ĺ�Ӧ������','system_gys.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>��Ӧ�̹���</title>
</head>
<body>
<?php
$esql=New Dedesql(false);
$query="select * from #@__gys where id='$id'";
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
      <td><strong>&nbsp;��Ӧ�������޸�</strong></td>
     </tr>
	 <form action="system_gys_edit.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��Ӧ������:</td>
		 <td>
		 &nbsp;<input type="text" name="g_name" size="30" value="<?php echo $row['g_name'] ?>"><input type="hidden" name="id" value="<?php echo $id; ?>">
		 </td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;��Ӧ�̵�ַ:</td>
		 <td>
		 &nbsp;<input type="text" name="g_address" size="30" value="<?php echo $row['g_address'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ϵ��:</td>
		 <td>
		 &nbsp;<input type="text" name="g_people" size="10" value="<?php echo $row['g_people'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��Ӧ�̵绰:</td>
		 <td>
		 &nbsp;<input type="text" name="g_phone" size="15" value="<?php echo $row['g_phone'] ?>"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;��ʱ��ϵ(QQ):</td>
		 <td>
		 &nbsp;<input type="text" name="g_qq" size="20" value="<?php echo $row['g_qq'] ?>"></td>
	    </tr>			
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" �޸Ĺ�Ӧ������ "></td>
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
