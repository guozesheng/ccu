<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
if($g_name==''){
ShowMsg('�������Ա������','-1');
exit();
}
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $addsql="insert into #@__guest(g_name,g_sex,g_address,g_phone,g_qq,g_birthday,g_card,g_group,g_people,g_dtime) values('$g_name','$g_sex','$g_address','$g_phone','$g_qq','$g_birthday','$g_card','$g_group','$g_people','$logindate')";
 $message="��ӻ�Ա".$g_name."�ɹ�";
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ�����˻�Ա','system_guest.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfs_softname;?>��Ա����</title>
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
      <td><strong>&nbsp;�ͻ�����</strong>&nbsp;&nbsp;<a href="system_guest.php?action=new">��ӻ�Ա</a> | <a href="system_guest.php">�鿴��Ա</a></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF">
	  <?php if($action=='new'){ ?><form action="system_guest.php?action=save" method="post">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��Ա����:</td>
		 <td>
		 &nbsp;<input type="text" name="g_name" size="10" id="need"></td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;�Ա�:</td>
		 <td>
		 &nbsp;<input type="radio" name="g_sex" value="��" checked>��<input type="radio" name="g_sex" value="Ů">Ů</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ϵ��ַ:</td>
		 <td>
		 &nbsp;<input type="text" name="g_address" size="25"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ϵ�绰:</td>
		 <td>
		 &nbsp;<input type="text" name="g_phone" size="15"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;��ʱ��ϵ(QQ):</td>
		 <td>
		 &nbsp;<input type="text" name="g_qq" size="15"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;����:</td>
		 <td>
		 &nbsp;<input type="text" name="g_birthday" size="20"> (��ʽ:2008-08-01)</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��Ա����:</td>
		 <td>
		 &nbsp;<input type="text" name="g_card" size="20"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;���ڷ���:</td>
		 <td>
		 <?php
		 getgroup();
		 ?>
		 </td>
	    </tr>		
		<tr>
		 <td id="row_style">&nbsp;����Ա:</td>
		 <td>
		 &nbsp;<input type="text" name="g_people" size="10" value="<?php echo str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID'])?>" readonly>
		 </td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;����ʱ��:</td>
		 <td>
		 &nbsp;<?php echo getDatetimeMk(time()); ?></td>
	    </tr>									
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" ��ӻ�Ա "></td>
	    </tr>
	   </table></form>
	   <?php
	    } 
		else
		{
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__guest");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;û���κλ�Ա,����<a href=system_guest.php?action=new>��ӻ�Ա</a>��</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>����</td><td>�Ա�</td><td>��ϵ��ַ</td><td>��ϵ�绰</td><td>QQ/MSN</td><td>����</td><td>��Ա����</td><td>����</td><td>����Ա</td><td>���ʱ��</td><td>����</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr><td>".$row['id']."</td><td>&nbsp;".$row['g_name']."</td><td>".$row['g_sex']."</td><td>&nbsp;".$row['g_address']."</td><td>&nbsp;".$row['g_phone']."</td><td>".$row['g_qq']."</td><td>&nbsp;".$row['g_birthday']."</td><td>&nbsp;".$row['g_card']."</td><td>&nbsp;".getgroup($row['g_group'],'group')."</td><td>".$row['g_people']."</td><td>".$row['g_dtime']."</td><td><a href=guest_edit.php?id=".$row['id'].">��</a> | <a href=guest_del.php?id=".$row['id'].">ɾ</a></td></tr>";
	   }
	   }
	   echo "</table>";
	  
	   $csql->close();
		}
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
