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
<title><?php echo $cfg_softname;?>ϵͳ���ȱ����ѯ</title>
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
	    <tr><form action="system_kc_lost.php?action=seek" name="form1" method="post">
		 <td>
	  <strong>&nbsp;ϵͳ���ȱ������</strong>
	     </td>
		 <td align="right">
		 <?php if($action=='seek')
		 echo "ֻ��ʾ������<input type=\"text\" name=\"stext\" size=\"5\" VALUE=\"".$stext."\">�ļ�¼<input type=\"submit\" value=\"��ʼ����\">";
		 else
		 echo "ֻ��ʾ������<input type=\"text\" name=\"stext\" size=\"5\" VALUE=\"5\">�ļ�¼<input type=\"submit\" value=\"��ʼ����\">";
		 ?>
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
   if($stext=='' || !is_numeric($stext) || $stext<0)echo "<script>alert('��������ȷ������');history.go(-1);</script>";
	$query="select * from #@__mainkc,#@__basic where  #@__mainkc.number<'$stext' and #@__mainkc.p_id=#@__basic.cp_number";
	   }
   else
    $query="select * from #@__mainkc,#@__basic where #@__mainkc.number<5  and #@__mainkc.p_id=#@__basic.cp_number";
$csql=New Dedesql(false);
$dlist = new DataList();
$dlist->pageSize = $cfg_record;
//����GET������
if($action=='seek'){
$dlist->SetParameter("action",$action);
$dlist->SetParameter("stext",$stext);
}
$dlist->SetSource($query);
	   echo "<tr class='row_color_head'><td>����</td><td>����</td><td>���</td><td>����</td><td>��λ</td><td>����</td><td>��Ӧ��</td><td>���ڲֿ�</td><td>���</td><td>ѡ��</td></tr>";
	   $mylist = $dlist->GetDataList();
       while($row = $mylist->GetArray('dm')){
	   echo "<tr onMouseMove=\"javascript:this.bgColor='#EBF1F6';\" onMouseOut=\"javascript:this.bgColor='#FFFFFF';\">\r\n<td>".$row['p_id']."</td><td>&nbsp;".$row['cp_name']."</td><td>".$row['cp_gg']."</td><td>".get_name($row['cp_categories'],'categories').">".get_name($row['cp_categories_down'],'categories')."</td><td>".get_name($row['cp_dwname'],'dw')."</td><td>��".$row['cp_jj']."</td><td><a href='' title='�鿴��ù�Ӧ�̵�����'>".get_name($row['p_id'],'gys')."</a></td><td><a href='' title='�鿴�˲ֿ����в�Ʒ'>".get_name($row['l_id'],'lab')."</a></td><td><font color=red>".$row['number']."</font></td><td><a href='system_kc_edit.php?pid=".$row['cp_number']."&lid=".$row['l_id']."&n=".$row['number']."'>�޸�</a>|<a href='system_kc_del.php?action=del&id=".$row['cp_number']."'>ɾ��</a><input type='checkbox' name='sel_pro".$row['id']."' value='".$row['id']."'></td>\r\n</tr>";
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
