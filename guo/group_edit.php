<?php
//require(dirname(__FILE__)."/include/config.php");
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')
ShowMsg('�Ƿ�����������ȷִ�д��ļ���','-1');
if($action=='save'){
 if($groupname=='') {
 showmsg('�������Ա��������','-1');
 exit();
 }
 if(!is_numeric($sub) || $sub=='' || $sub>10 || $sub<1){
  showmsg('�ۿ�ֻ����1��10֮�����,����С��','-1');
 exit();
 }
 $addsql="update #@__group set groupname='$groupname',sub='$sub' where id='$id'";
 $message= "�޸Ļ�Ա����".$groupname."�ɹ�";
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 WriteNote($message,$logindate,$loginip,$username);
 $asql->close();
 showmsg('�ɹ��޸Ļ�Ա��������','guest_group.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>������λ����</title>
</head>
<body>
<?php
$esql=New Dedesql(false);
$query="select * from #@__group where id='$id'";
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
      <td><strong>&nbsp;��Ա�����޸�</strong></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF"><form action="group_edit.php?action=save" method="post">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��Ա��������:</td>
		 <td>
		 &nbsp;<input type="text" name="groupname" size="20" value="<?php echo $row['groupname'] ?>">
		 <input type="hidden" name="id" value="<?php echo $id; ?>">
		 </td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;�˷����ۿ�:</td>
		 <td>
		 &nbsp;<input type="text" name="sub" size="2" value="<?php echo $row['sub'] ?>">��</td>
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
