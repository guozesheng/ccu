<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
if($atext==''){
ShowMsg('������������ԭ��','-1');
exit();
}
if($amoney=='' || !is_numeric($amoney) || $amoney<0){
ShowMsg('��ȷ����������ȷ�Ľ��','-1');
exit();
}
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
  $addsql="insert into #@__accounts(atype,amoney,abank,dtime,apeople,atext) values('$atype','$amoney','$abank','$logindate','$username','$atext')";
 $message="�ֶ��������ɹ�";
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ����������','system_money.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfs_softname;?>�˻�����</title>
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
      <td><strong>&nbsp;�������</strong>&nbsp;&nbsp;<a href="system_money.php">�鿴��ϸ��</a></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF"><form action="add_money.php?action=save" method="post">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��Ŀ:</td>
		 <td>
		 &nbsp;<select name="atype"><option value="����">����</option><option value="֧��">֧��</option></select>
		 </td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;����:</td>
		 <td>
		 <?php
		 $bank=new dedesql(false);
		 $bank->setquery("select * from #@__bank");
		 $bank->execute();
		 $r=$bank->gettotalrow();
		 if($r==0)
		 echo "û�����й�ѡ��,����<a href='bank.php?action=new'>���</a>";
		 else{
		 echo "<select name='abank'>";
		 while($row=$bank->getArray()){
		 if($row['bank_default']==1)
		 echo "<option selected value='".$row['id']."'>".$row['bank_name']."</option>";
		 else
		 echo "<option value='".$row['id']."'>".$row['bank_name']."</option>";
		 }
		 echo "</select>";
		 }
		 $bank->close();
		 ?>
		 &nbsp;
		 </td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;���:</td>
		 <td>
		 &nbsp;<input type="text" name="amoney" size="10">&nbsp;Ԫ</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ע:</td>
		 <td>
		 &nbsp;<textarea name="atext" rows="2" cols="40"></textarea></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" ������� "></td>
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
