<?php
//require(dirname(__FILE__)."/include/config.php");
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
 if($reid==''){//��Ӷ�������
 $addsql="insert into #@__categories(categories,reid) values('$categories','0')";
 $message="��Ӷ�������".$categories."�ɹ�";
 }
 else{//����ӷ���
 $addsql="insert into #@__categories(categories,reid) values('$categories','$reid')";
 $message="����ӷ���".$categories."�ɹ�";
 }
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ���Ӳ�Ʒ����','system_class.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>�������</title>
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
      <td><strong>&nbsp;��Ʒ��������</strong>&nbsp;&nbsp;<a href="system_class.php?action=new">��Ӷ�������</a> | <a href="system_class.php">�鿴��Ʒ�����б�</a>&nbsp;<font color=red>ע��ɾ����������ʱ����ͬ�ӷ���һ��ɾ��</font></td>
     </tr>
	 <form action="system_class.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
	  <?php if($action=='new'){ ?>
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��������:</td>
		 <td>&nbsp;<input type="text" name="categories" size="20"><input type="hidden" name="reid" value="<?php echo $reid; ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <?php $submitstring=($reid=='')?' ��Ӷ������� ':' ����ӷ��� ';?>
		 <td>&nbsp;<input type="submit" name="submit" value="<?php echo $submitstring;?>"></td>
	    </tr>
		</form>
	   </table>
	   <?php
	    } 
		else
		{
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__categories where reid=0");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;ϵͳ�ﻹû���κη���,����<a href=system_class.php?action=new>��Ӷ�������</a>��</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>����</td><td>����</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr><td>ID��:".$row['id']."</td><td><img src=images/cate.gif align=absmiddle>&nbsp;".$row['categories']."</td><td><a href=system_cate_edit.php?id=".$row['id'].">�޸�</a> | <a href=system_cate_del.php?id=".$row['id'].">ɾ��</a> | <a href=system_class.php?action=new&reid=".$row['id'].">������</a></td></tr>";
	     $csql1=New Dedesql(false);
	     $csql1->SetQuery("select * from #@__categories where reid='".$row['id']."'");
		 $csql1->Execute();
		 while($row1=$csql1->GetArray()){
		 echo "<tr class='row_color_gray'><td>&nbsp;&nbsp;ID��:".$row1['id']."</td><td> �� ".$row1['categories']."</td><td><a href=system_cate_edit.php?id=".$row1['id'].">�޸�</a> | <a href=system_cate_del.php?id=".$row1['id'].">ɾ��</a></td></tr>";
		 } $csql1->close();
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
