<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
if($g_name==''){
ShowMsg('�����빩Ӧ�̵�����','-1');
exit();
}
 $addsql="insert into #@__gys(g_name,g_people,g_address,g_phone,g_qq) values('$g_name','$g_people','$g_address','$g_phone','$g_qq')";
 $message="��ӹ�Ӧ��".$g_name."�ɹ�";

 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ�����˹�Ӧ��','system_gys.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>��Ӧ����</title>
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
      <td><strong>&nbsp;��Ӧ�̹���</strong>&nbsp;&nbsp;<a href="system_gys.php?action=new">����µĹ�Ӧ��</a> | <a href="system_gys.php">�鿴��Ӧ���б�</a></td>
     </tr>
	 <form action="system_gys.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
	  <?php if($action=='new'){ ?>
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��Ӧ������:</td>
		 <td>
		 &nbsp;<input type="text" name="g_name" size="30" id="need"></td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;��Ӧ�̵�ַ:</td>
		 <td>
		 &nbsp;<input type="text" name="g_address" size="30"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ϵ��:</td>
		 <td>
		 &nbsp;<input type="text" name="g_people" size="10"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��Ӧ�̵绰:</td>
		 <td>
		 &nbsp;<input type="text" name="g_phone" size="15"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;��ʱ��ϵ(QQ):</td>
		 <td>
		 &nbsp;<input type="text" name="g_qq" size="20"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" ��ӹ�Ӧ�� "></td>
	    </tr>
		</form>
	   </table>
	   <?php
	    } 
		else
		{
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__gys");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;ϵͳ�ﻹû���κι�Ӧ��,����<a href=system_gys.php?action=new>��ӹ�Ӧ��</a>��</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>����</td><td>��ϵ��</td><td>��ϵ��ַ</td><td>��ϵ�绰</td><td>QQ</td><td>����</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr class='row_color_gray'><td>ID��:".$row['id']."</td><td>&nbsp;".$row['g_name']."</td><td>&nbsp;".$row['g_people']."</td><td>&nbsp;".$row['g_address']."</td><td>&nbsp;".$row['g_phone']."</td><td>&nbsp;".$row['g_qq']."</td><td><a href=system_gys_edit.php?id=".$row['id'].">�޸�</a> | <a href=system_gys_del.php?id=".$row['id'].">ɾ��</a></td></tr>";
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
