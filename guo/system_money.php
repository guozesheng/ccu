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
      <td>
	   <table width="100%" border="0" cellspacing="0">
	    <tr><form action="system_money.php?action=seek" name="form1" method="post">
		 <td>
	  <strong>&nbsp;�������</strong> -<a href="add_money.php">�ֶ�����˻�</a>
	     </td>
		 <td align="right">���ڶΣ�
		 <?php 
		 if($action=='seek'){
		 echo "<input type=\"text\" name=\"cp_sdate\" size=\"15\" VALUE=\"".$cp_sdate."\" onclick=\"setday(this)\"> �� 
		 <input type=\"text\" name=\"cp_edate\" size=\"15\" VALUE=\"".$cp_edate."\" onclick=\"setday(this)\">";
		 $hurl="system_money.php?action=seek&cp_sdate='$cp_sdate'&cp_edate='$cp_edate'&atype=";}
		 else{
		 echo "<input type=\"text\" name=\"cp_sdate\" size=\"15\" VALUE=\"����ѡ������\" onclick=\"setday(this)\"> �� 
		 <input type=\"text\" name=\"cp_edate\" size=\"15\" VALUE=\"����ѡ������\" onclick=\"setday(this)\">";
		 $hurl="system_money.php?atype=";}
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
	   $asql=New dedesql(false);
	   $aquery="select sum(amoney) as imoney from #@__accounts where atype='����'";
	   $aquery1="select sum(amoney) as omoney from #@__accounts where atype='֧��'";
	   $asql->setquery($aquery);
	   $asql->execute();
	   $rs=$asql->getone();
	   $imoney=$rs['imoney'];
	   
	   $asql->setquery($aquery1);
	   $asql->execute();
	   $rs=$asql->getone();
	   $omoney=$rs['omoney'];
	   $asql->close();
	   if($imoney<$omoney)
	   $moneystring="���𣺣�".($omoney-$imoney)."Ԫ�������룺��".$imoney."����֧������".$omoney;
	   elseif($imoney-$omoney==0)
	   $moneystring="��֧ƽ�⣬�����룺��".$imoney."����֧������".$omoney;
	   else
	   $moneystring="ӯ������".($imoney-$omoney)."Ԫ�������룺��".$imoney."����֧������".$omoney;
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
	   if($action=='seek'){
	   if($cp_sdate=='' || $cp_edate=='' || $cp_sdate=='����ѡ������' || $cp_edate=='����ѡ������' || $cp_sdate>$cp_edate)echo "<script>alert('��ѡ����ȷ��ʱ���');history.go(-1);</script>";
	    if($atype!='')
	   $query="select * from #@__accounts where dtime between '$cp_sdate' and '$cp_edate' and atype='$atype' order by dtime desc";
		else
	   $query="select * from #@__accounts where dtime between '$cp_sdate' and '$cp_edate' order by dtime desc";
	   }
	   else{
	   if($atype!='')
	   $query="select * from #@__accounts where atype='$atype' order by dtime desc";
	   else
       $query="select * from #@__accounts order by dtime desc";
	   }
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
       echo "<tr><td colspan='8' align='right'>".$moneystring."&nbsp;&nbsp;</td></tr>";
	   echo "<tr class='row_color_head'><td>ID��</td><td>��Ŀ</td><td>�˻�</td><td>�����û�</td><td>����</td><td>���</td><td>��ע</td><td>ѡ��</td></tr>";
	   $mylist = $dlist->GetDataList();
       while($row = $mylist->GetArray('dm')){
	   $cmoney+=$row['amoney'];
	   echo "<tr onMouseMove=\"javascript:this.bgColor='#EBF1F6';\" onMouseOut=\"javascript:this.bgColor='#FFFFFF';\">\r\n<td>".$row['id']."</td><td>&nbsp;<a href='$hurl".$row['atype']."'>".$row['atype']."</td><td>".getbank($row['abank'])."</td><td>".$row['apeople']."</td><td>".$row['dtime']."</td><td>��".$row['amoney']."</td><td>".$row['atext']."</td><td><input type='checkbox' name='sel_pro".$row['id']."' value='".$row['id']."'></td>\r\n</tr>";
	   }
	   echo "<tr><td colspan=\"8\">&nbsp;&nbsp;�ܼƣ�&nbsp;��".$cmoney."Ԫ</td></tr>";
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
