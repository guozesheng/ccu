<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
if($g_name==''){
ShowMsg('�������û�������','-1');
exit();
}
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $passwd=md5($g_jobnum);
 $addsql = "insert into #@__boss(`id` ,`boss` ,`password` ,`sex` ,`phone` ,`logindate` ,`loginip` ,`errnumber` ,`rank`) values (NULL ,  '$g_jobnum',  '$passwd',  '$g_sex',  '$g_phone',  '$g_time',  '0.0.0.0',  '1',  '$g_level')";
 $message="����û�".$g_name."�ɹ�";
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ�������û�','system_guest.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="include/ajax.js"></script>
<title><?php echo $cfs_softname;?>�û�����</title>
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
      <td><strong>&nbsp;�û�����</strong>&nbsp;&nbsp;<a href="system_guest.php?action=new">����û�</a> | <a href="system_guest.php">�鿴�û�</a></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF">
	  <?php if($action=='new'){ ?><form action="system_guest.php?action=save" method="post">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td width="23%" id="row_style">&nbsp;�û�����:</td>
		 <td colspan="2">
		 &nbsp;<input type="text" name="g_name" size="10" id="need"></td>
	    </tr>
        <tr>
        <td id="row_style">&nbsp;����:</td>
        <td width="180">
        	&nbsp;<input type="text" name="g_jobnum" onblur="checkuser(this.value)" />
        </td>
        <td align="left" id="jobnumchk">&nbsp;</td>
        </tr>
        <tr>
        <td id="row_style">&nbsp;����:</td>
        <td colspan="2">
        	&nbsp;<input type="radio" name="g_level" value="1" />ʵ���ҹ���Ա<input type="radio" name="g_level" value="2" checked="checked" />��ʦ
        </td>
        </tr>
	    <tr>
		 <td id="row_style">&nbsp;�Ա�:</td>
		 <td colspan="2">
		 &nbsp;<input type="radio" name="g_sex" value="��" checked>��<input type="radio" name="g_sex" value="Ů">Ů</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ϵ�绰:</td>
		 <td colspan="2">
		 &nbsp;<input type="text" name="g_phone" size="15"></td>
	    </tr>	
		<tr>
		  <td id="row_style">&nbsp;����ʱ��:</td>
		  <td colspan="2">
		    &nbsp;<input type="text" name="g_time" value="<?php echo getDatetimeMk(time()); ?>" readonly="readonly" />
		    </td>
		  </tr>									
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td colspan="2">&nbsp;<input type="submit" name="submit" value=" ����û� "></td>
	    </tr>
	   </table></form>
	   <?php
	    } 
		else
		{
       echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__boss");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;û���κ��û�,����<a href=system_guest.php?action=new>����û�</a>��</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>����</td><td>����</td><td>�Ա�</td><td>��ϵ�绰</td><td>�ϴε�¼����</td><td>��¼IP</td><td>����</td><td>����</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr class='row_content'><td>".$row['id']."</td><td>&nbsp;".$row['name']."</td><td>".$row['boss']."</td><td>&nbsp;".$row['sex']."</td><td>&nbsp;".$row['phone']."</td><td>".$row['logindate']."</td><td>&nbsp;".$row['loginip']."</td><td>&nbsp;".$row['rank']."</td><td><a href=guest_edit.php?id=".$row['id'].">��</a> | <a href=guest_del.php?id=".$row['id'].">ɾ</a></td></tr>";
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
