<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
require(dirname(__FILE__)."/include/page.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="include/calendar.js"></script>
<title><?php echo $cfg_softname;?>ϵͳ��־</title>
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
      <td>
	   <table width="100%" border="0" cellspacing="0">
	    <tr><form action="system_note.php?action=seek" name="form1" method="post">
		 <td>
	  <strong>&nbsp;ϵͳ��־����</strong>
	     </td>
		 <td align="right">���ڶΣ�
		 <?php 
		 if($action=='seek')
		 echo "<input type=\"text\" name=\"cp_sdate\" size=\"15\" VALUE=\"".$cp_sdate."\" onclick=\"setday(this)\"> �� 
		 <input type=\"text\" name=\"cp_edate\" size=\"15\" VALUE=\"".$cp_edate."\" onclick=\"setday(this)\">";
		 else
		 echo "<input type=\"text\" name=\"cp_sdate\" size=\"15\" VALUE=\"����ѡ������\" onclick=\"setday(this)\"> �� 
		 <input type=\"text\" name=\"cp_edate\" size=\"15\" VALUE=\"����ѡ������\" onclick=\"setday(this)\">";
		 ?>
		 <input type="submit" value="��ʼ����">
		 &nbsp;&nbsp;
		 </td>
		</tr></form>
	   </table>
	  </td>
     </tr>
	 <form method="post" name="sel">
     <tr>
      <td bgcolor="#FFFFFF">
       <?php
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
	   if($action=='seek'){
	   if($cp_sdate=='' || $cp_edate=='' || $cp_sdate=='����ѡ������' || $cp_edate=='����ѡ������' || $cp_sdate>$cp_edate)echo "<script>alert('��ѡ����ȷ��ʱ���');history.go(-1);</script>";
	   $query="select * from #@__recordline where date between '$cp_sdate' and '$cp_edate' order by date desc";
	   }
	   else
       $query="select * from #@__recordline order by date desc";
$csql=New Dedesql(false);
$dlist = new DataList();
$dlist->pageSize = $cfg_record;
//����GET������
if($action=='seek'){
$dlist->SetParameter("action",$action);
$dlist->SetParameter("cp_sdate",$cp_sdate);
$dlist->SetParameter("cp_edate",$cp_edate);
}
$dlist->SetSource($query);
	   echo "<tr class='row_color_head'><td>ID��</td><td>������Ϣ</td><td>IP��ַ</td><td>�����û�</td><td>����</td><td>ѡ��</td></tr>";
	   $mylist = $dlist->GetDataList();
       while($row = $mylist->GetArray('dm')){
	   echo "<tr onMouseMove=\"javascript:this.bgColor='#EBF1F6';\" onMouseOut=\"javascript:this.bgColor='#FFFFFF';\">\r\n<td>".$row['id']."</td><td>&nbsp;".$row['message']."</td><td>".$row['ip']."</td><td>".$row['userid']."</td><td>".$row['date']."</td><td><input type='checkbox' name='sel_pro".$row['id']."' value='".$row['id']."'></td>\r\n</tr>";
	   }
	   echo "<tr><td colspan='8'>&nbsp;".$dlist->GetPageList($cfg_record)."</td></tr>";
	   echo "</table>";
	   $csql->close();
	   ?>
	  </td>
     </tr></form>
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
</body>
</html>
