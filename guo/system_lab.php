<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
if($l_name==''){
ShowMsg('������ֿ������','-1');
exit();
}
 $addsql="insert into #@__lab(l_name,l_city,l_mang,l_default) values('$l_name','$l_city','$l_mang','$l_default')";
 $message="��Ӳֿ�".$s_name."�ɹ�";

 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ�����˲ֿ�','system_lab.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfs_softname;?>�ֿ����</title>
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
      <td><strong>&nbsp;�ֿ����</strong>&nbsp;&nbsp;<a href="system_lab.php?action=new">����²ֿ�</a> | <a href="system_lab.php">�鿴�ֿ�</a></td>
     </tr>
	 <form action="system_lab.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
	  <?php if($action=='new'){ ?>
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;�ֿ�����:</td>
		 <td>
		 &nbsp;<input type="text" name="l_name" size="20" id="need"></td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;���ڳ���:</td>
		 <td>
		 &nbsp;<input type="text" name="l_city" size="20"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;������:</td>
		 <td>
		 &nbsp;<input type="text" name="l_mang" size="15"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;�Ƿ�Ĭ�ϲֿ�:</td>
		 <td>
		 &nbsp;<select name="l_default"><option value="1">��</option><option value="0" selected>��</option></select>&nbsp;ֻ�ܱ���һ��Ĭ�ϲֿ�</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" ��Ӳֿ� "></td>
	    </tr>
		</form>
	   </table>
	   <?php
	    } 
		else
		{
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__lab");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;��û��Ӳֿ�,����<a href=system_lab.php?action=new>��Ӳֿ�</a>��</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>�ֿ�����</td><td>���ڳ���</td><td>������</td><td>Ĭ��</td><td>�޸�</td></tr>";
	   while($row=$csql->GetArray()){
	   if ($row['l_default']==1)
	    $default_yes="<img src=images/yes.png>";
		else
		$default_yes="&nbsp;";
	   echo "<tr><td>ID��:".$row['id']."</td><td>&nbsp;".$row['l_name']."</td><td>&nbsp;".$row['l_city']."</td><td>&nbsp;".$row['l_mang']."</td><td>&nbsp;".$default_yes."</td><td><a href=system_lab_edit.php?id=".$row['id'].">�޸�</a> | <a href=system_lab_del.php?id=".$row['id'].">ɾ��</a></td></tr>";
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
