<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')
ShowMsg('�Ƿ�����������ȷִ�д��ļ���','-1');
if($action=='save'){
if($s_name==''){
ShowMsg('������ְ��������','-1');
exit();
}
 $addsql="update #@__staff set s_name='$s_name',s_address='$s_address',s_phone='$s_phone',s_part='$s_part',s_way='$s_way',s_money='$s_money',s_utype='$s_utype',s_duty='$s_duty' where id='$id'";
 $message= "�޸Ĺ�˾ְ��".$s_name."���ϳɹ�";
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->ExecuteNoneQuery("insert into #@__recordline(message,date,ip,userid) values('{$message}','{$logindate}','{$loginip}','$username')");
 $asql->close();
 showmsg('�ɹ��޸���ְ��������','system_worker.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>ְ������</title>
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
<?php
$esql=New Dedesql(false);
$query="select * from #@__staff where id='$id'";
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
      <td><strong>&nbsp;��˾Ա�������޸�</strong></td>
     </tr>
	 <form action="system_worker_edit.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;Ա������:</td>
		 <td>
		 &nbsp;<input type="text" name="s_name" size="10" value="<?php echo $row['s_name'] ?>" id="need"><input type="hidden" name="id" value="<?php echo $id; ?>">
		 </td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;Ա����ϵ��ַ:</td>
		 <td>
		 &nbsp;<input type="text" name="s_address" size="30" value="<?php echo $row['s_address'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;Ա����ϵ�绰:</td>
		 <td>
		 &nbsp;<input type="text" name="s_phone" size="15" value="<?php echo $row['s_phone'] ?>"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;���ڲ���:</td>
		 <td>
		 &nbsp;<input type="text" name="s_part" size="20" value="<?php echo $row['s_part'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;����ְ��:</td>
		 <td>
		 &nbsp;<input type="text" name="s_duty" size="20" value="<?php echo $row['s_duty'] ?>"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;��ɷ�ʽ:</td>
		 <td>
		 <?php
		 if ($cfg_way=='1'){
		  if ($row['s_way']==0){
		 ?>
		 &nbsp;<select name="s_way" onchange="cway(this.value)"><option value="0" selected>�����ܶ�İٷֱ�</option><option value="1">�̶����(����)</select>
		 <?php
		 }
		 else
		 echo "&nbsp;<select name=\"s_way\" onchange=\"cway(this.value)\"><option value=\"0\">�����ܶ�İٷֱ�</option><option value=\"1\" selected>�̶����(����)</select>";
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
		  if ($row['s_way']==0){
		 ?>
		 &nbsp;<input type="text" name="s_money" size="5" value="<?php echo $row['s_money'] ?>">
		 <input type="text" name="s_e" size="5" style="border:0px;background:transparent;" value="%" readonly>
		 <?php
		 }
		 else
		 echo "&nbsp;<input type=\"text\" name=\"s_money\" size=\"5\" value=\"".$row['s_money']."\">
		 <input type=\"text\" name=\"s_e\" size=\"5\" style=\"border:0px;background:transparent;\" value=\"Ԫ/��\" readonly>";
		 }
		 else
		 echo "&nbsp;";
		 ?>
		 </td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;Ա������(���Ͳ�ͬȨ�޲�ͬ):</td>
		 <td>
		 &nbsp;<?php getusertype($row['s_utype'],1);?></td>
	    </tr>							
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" �޸�Ա������ "></td>
	    </tr>
		</form>
	   </table>
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
