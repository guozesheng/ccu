<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')
ShowMsg('�Ƿ�����������ȷִ�д��ļ���','-1');
if($action=='save'){
if($g_name==''){
ShowMsg('�������Ա������','-1');
exit();
}
 $addsql="update #@__guest set g_name='$g_name',g_sex='$g_sex',g_address='$g_address',g_phone='$g_phone',g_qq='$g_qq',g_birthday='$g_birthday',g_card='$g_card',g_group='$g_group' where id='$id'";
 $message= "�޸Ļ�Աְ��".$s_name."���ϳɹ�";
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->ExecuteNoneQuery("insert into #@__recordline(message,date,ip,userid) values('{$message}','{$logindate}','{$loginip}','$username')");
 $asql->close();
 showmsg('�ɹ��޸��˻�Ա������','system_guest.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>��Ա����</title>
</head>
<body>
<?php
$esql=New Dedesql(false);
$query="select * from #@__guest where id='$id'";
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
      <td><strong>&nbsp;�ͻ������޸�</strong></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF"><form action="guest_edit.php?action=save" method="post">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��Ա����:</td>
		 <td>
		 &nbsp;<input type="text" name="g_name" size="10" id="need" value="<?php echo $row['g_name'] ?>">
		 <input type="hidden" name="id" value="<?php echo $id ?>">
		 </td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;�Ա�:</td>
		 <td>
		 <?php
		 if($row['g_sex']=='��')
		 echo "&nbsp;<input type=\"radio\" name=\"g_sex\" value=\"��\" checked>��<input type=\"radio\" name=\"g_sex\" value=\"Ů\">Ů";
		 else
		 echo "&nbsp;<input type=\"radio\" name=\"g_sex\" value=\"��\">��<input type=\"radio\" name=\"g_sex\" value=\"Ů\" checked>Ů";
		 ?>
		 </td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ϵ��ַ:</td>
		 <td>
		 &nbsp;<input type="text" name="g_address" size="25" value="<?php echo $row['g_address'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ϵ�绰:</td>
		 <td>
		 &nbsp;<input type="text" name="g_phone" size="15" value="<?php echo $row['g_phone'] ?>"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;��ʱ��ϵ(QQ):</td>
		 <td>
		 &nbsp;<input type="text" name="g_qq" size="15" value="<?php echo $row['g_qq'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;����:</td>
		 <td>
		 &nbsp;<input type="text" name="g_birthday" size="20" value="<?php echo $row['g_birthday'] ?>"> (��ʽ:2008-08-01)</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��Ա����:</td>
		 <td>
		 &nbsp;<input type="text" name="g_card" size="20" value="<?php echo $row['g_card'] ?>"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;���ڷ���:</td>
		 <td>
		 <?php
		 getgroup($row['g_group']);
		 ?>
		 </td>
	    </tr>		
		<tr>
		 <td id="row_style">&nbsp;����Ա:</td>
		 <td>
		 &nbsp;<?php echo $row['g_people'] ?>
		 </td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;����ʱ��:</td>
		 <td>
		 &nbsp;<?php echo $row['g_dtime']; ?></td>
	    </tr>							
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" �޸Ļ�Ա���� "></td>
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
