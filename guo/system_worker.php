<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
if($s_name==''){
ShowMsg('������ְ��������','-1');
exit();
}
 $addsql="insert into #@__staff(s_name,s_address,s_phone,s_part,s_way,s_money,s_utype,s_duty) values('$s_name','$s_address','$s_phone','$s_part','$s_way','s_money','$s_utype','$s_duty')";
 $message="���ְԱ".$s_name."�ɹ�";

 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('�ɹ�����˹�˾ְ��','system_worker.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfs_softname;?>ְԱ����</title>
<script language="javascript">
function cway(value){
if(value==0)
document.forms[0].s_e.value="%";
else
document.forms[0].s_e.value="Ԫ/��";
}
</script>
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
      <td><strong>&nbsp;��˾ְ������</strong>&nbsp;&nbsp;<a href="system_worker.php?action=new">��ӹ�˾ְ��</a> | <a href="system_worker.php">�鿴��˾ְԱ</a></td>
     </tr>
	 <form action="system_worker.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
	  <?php if($action=='new'){ ?>
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;Ա������:</td>
		 <td>
		 &nbsp;<input type="text" name="s_name" size="10" id="need"></td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;Ա����ϵ��ַ:</td>
		 <td>
		 &nbsp;<input type="text" name="s_address" size="30"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;Ա����ϵ�绰:</td>
		 <td>
		 &nbsp;<input type="text" name="s_phone" size="15"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;���ڲ���:</td>
		 <td>
		 &nbsp;<input type="text" name="s_part" size="20"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;����ְ��:</td>
		 <td>
		 &nbsp;<input type="text" name="s_duty" size="20"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;��ɷ�ʽ:</td>
		 <td>
		 <?php
		 if ($cfg_way=='1'){
		 ?>
		 &nbsp;<select name="s_way" onchange="cway(this.value)"><option value="0">�����ܶ�İٷֱ�</option><option value="1">�̶����(����)</select>
		 <?php
		 }
		 else
		 echo "&nbsp;Ա����ɹ��ܱ�����Ա����!";
		 ?>
		 </td>
	    </tr>		
		<tr>
		 <td id="row_style">&nbsp;��ɶ�(Ϊ�ձ�ʾ��ӵĴ�Ա�������):</td>
		 <td>
		 <?php
		 if ($cfg_way=='1'){
		 ?>
		 &nbsp;<input type="text" name="s_money" size="5">
		 <input type="text" name="s_e" size="5" style="border:0px;background:transparent;" value="%" readonly>
		 <?php
		 }
		 else
		 echo "&nbsp;";
		 ?>
		 </td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;Ա������(���Ͳ�ͬȨ�޲�ͬ):</td>
		 <td>
		 &nbsp;<?php getusertype('',0) ?></td>
	    </tr>									
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" ���ְ�� "></td>
	    </tr>
		</form>
	   </table>
	   <?php
	    } 
		else
		{
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__staff");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;��˾�ﻹû���κ�ְԱ,����<a href=system_worker.php?action=new>���ְ��</a>��</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>����</td><td>��ϵ��ַ</td><td>��ϵ�绰</td><td>����</td><td>ְ��</td><td>�û�����</td><td>����</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr><td>ID��:".$row['id']."</td><td>&nbsp;".$row['s_name']."</td><td>&nbsp;".$row['s_address']."</td><td>&nbsp;".$row['s_phone']."</td><td>&nbsp;".$row['s_part']."</td><td>&nbsp;".$row['s_duty']."</td><td>&nbsp;".getusertype($row['s_utype'])."</td><td><a href=system_worker_edit.php?id=".$row['id'].">�޸�</a> | <a href=system_worker_del.php?id=".$row['id'].">ɾ��</a></td></tr>";
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
