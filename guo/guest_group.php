<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
 if($groupname=='') {
 showmsg('�������Ա��������','-1');
 exit();
 }
 if(!is_numeric($sub) || $sub=='' || $sub>10 || $sub<1){
  showmsg('�ۿ�ֻ����1��10֮�����,����С��','-1');
 exit();
 }
 $addsql="insert into #@__group(groupname,sub) values('$groupname','$sub')";
 $message="��ӻ�Ա����".$groupname."�ɹ�";
  
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ�����˻�Ա����','guest_group.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>��Ա�������</title>
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
      <td><strong>&nbsp;��Ա�������</strong>&nbsp;&nbsp;<a href="guest_group.php?action=new">��ӻ�Ա����</a> | <a href="guest_group.php">�鿴��Ա����</a></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF"><form action="guest_group.php?action=save" method="post">
	  <?php if($action=='new'){ ?>
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;��Ա��������:</td>
		 <td>
		 &nbsp;<input type="text" name="groupname" size="20"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;�˷����ۿ�:</td>
		 <td>
		 &nbsp;<input type="text" name="sub" size="2" value="10">��</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" ��ӻ�Ա���� "></td>
	    </tr>
	   </table></form>
	   <?php
	    } 
		else
		{
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__group");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;ϵͳ�ﻹû���κλ�Ա����,����<a href=guest_group.php?action=new>��ӷ���</a>��</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>��������</td><td>�ۿ�</td><td>����</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr><td>ID��:".$row['id']."</td><td><img src=images/cate.gif align=absmiddle>&nbsp;".$row['groupname']."</td><td>".$row['sub']."��</td><td><a href=group_edit.php?id=".$row['id'].">�޸�</a> | <a href=group_del.php?id=".$row['id'].">ɾ��</a></td></tr>";
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
