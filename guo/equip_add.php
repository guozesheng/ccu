<?php
require(dirname(__FILE__)."/include/config_rglobals.php");
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/page.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<!--<script language="javascript" src="include/calendar.js"></script>-->
<script language="javascript" src="include/py.js"></script>
<script language="javascript" type="text/javascript" src="include/WdatePicker.js"></script>
<title>����������Ϣ¼��</title>
<script language = "JavaScript">
var onecount; 
onecount = 0; 
subcat = new Array();
<?php
$count=0;
$rsql=New Dedesql(false);
$rsql->SetQuery("select * from #@__categories where reid!=0");
$rsql->Execute();
while($rs=$rsql->GetArray()){
?>
subcat[<?php echo $count;?>] = new Array("<?php echo $rs['categories'];?>","<?php echo $rs['reid'];?>","<?php echo $rs['id'];?>");
<?php 
    $count++;
}
$rsql->close();
?>
onecount=<?php echo $count?>; 
function getCity(locationid) 
{ 
    document.form1.cp_categories_down.length = 0; 

    var locationid=locationid; 

    var i; 
    document.form1.cp_categories_down.options[0] = new Option('==��ѡ������ӷ���==',''); 
    for (i=0;i < onecount; i++) 
    { 
        if (subcat[i][1] == locationid) 
        { 
        document.form1.cp_categories_down.options[document.form1.cp_categories_down.length] = new Option(subcat[i][0], subcat[i][2]);
        } 
    } 

} 

</script>
<script type="text/vbscript"> 
function vbChr(c) 
vbChr = chr(c) 
end function 

function vbAsc(n) 
vbAsc = asc(n) 
end function 
</script> 
</head>
<?php
if ($action=='save'){
 if($cp_name=='') echo "<script language='javascript'>alert('����������������!');history.go(-1)</script>";
 if($cp_gg=='') echo "<script language='javascript'>alert('�����������Ĺ��!');history.go(-1)</script>";
 if($cp_categories=='') echo "<script language='javascript'>alert('�����������ķ���!');history.go(-1)</script>";
 if($cp_categories_down=='') echo "<script language='javascript'>alert('�������������ӷ���!');history.go(-1)</script>";
 if($cp_dwname=='') echo "<script language='javascript'>alert('�����������Ļ�����λ!');history.go(-1)</script>";
 if($cp_jj=='' || $cp_sale=='') echo "<script language='javascript'>alert('��������Ϊ������!');history.go(-1)</script>";
 if(!(is_numeric($cp_jj) && is_numeric($cp_sale) )) echo "<script language='javascript'>alert('�۸����Ϊ����!');history.go(-1)</script>";
$bsql=New Dedesql(false);
$query="select * from #@__basic where cp_name='$cp_name' and cp_gg='$cp_gg'";
$bsql->SetQuery($query);
$bsql->Execute();
$rowcount=$bsql->GetTotalRow();
if ($rowcount>=1){
 ShowMsg('���������ƺ͹�������ݿ����Ѿ�����,���������!','-1');
 exit();
}
else{
$addquery="insert into #@__basic(cp_number,cp_tm,cp_name,cp_gg,cp_categories,cp_categories_down,cp_dwname,cp_jj,cp_sale,cp_saleall,cp_sdate,cp_edate,cp_gys,cp_helpword,cp_bz) values('$cp_number','$cp_tm','$cp_name','$cp_gg','$cp_categories','$cp_categories_down','$cp_dwname','$cp_jj','$cp_sale','$cp_saleall','$cp_sdate','$cp_edate','$cp_gys','$cp_helpword','$cp_bz')";
$bsql->ExecuteNoneQuery($addquery);
ShowMsg('�ɹ�д��һ������������Ϣ.','equip_add.php');
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 WriteNote('�������������Ϣ'.$cp_name.' �ɹ�',$logindate,$loginip,$username);
$bsql->close();
exit();
    }
}
else if($action=='seek'){ //�б�
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
      <td><strong>&nbsp;����������Ϣ����</strong>(ע:��ɫ����Ϊ����ѡ��)&nbsp;&nbsp;- <a href="equip_add.php">�������Ǽ�</a> - <a href="equip_add.php?action=seek">����������Ϣ��ѯ</a></td>
     </tr><form action="equip_add.php?action=save" method="post" name="form1">
     <tr>
      <td bgcolor="#FFFFFF">
<?php
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
$query="select * from #@__basic";
$csql=New Dedesql(false);
$dlist = new DataList();
$dlist->pageSize = $cfg_record;
//$dlist->SetParameter("form",$form);
//$dlist->SetParameter("field",$field);//����GET������
$dlist->SetSource($query);
	   echo "<tr class='row_color_head'><td>ID</td><td>����</td><td>���</td><td>����</td><td>��λ</td><td>����</td><td>��Ӧ��</td><td>���Ǵ�</td><td>����</td></tr>";
	   $mylist = $dlist->GetDataList();
       while($row = $mylist->GetArray('dm')){
	   echo "<tr class='row_content'><td>".$row['id']."</td><td>&nbsp;".$row['cp_name']."</td><td>&nbsp;".$row['cp_gg']."</td><td>&nbsp;".get_name($row['cp_categories'],'categories').">".get_name($row['cp_categories_down'],'categories')."</td><td>&nbsp;".get_name($row['cp_dwname'],'dw')."</td><td>&nbsp;��".$row['cp_jj']."</td><td>".$row['cp_gys']."</td><td>".$row['cp_helpword']."</td><td><a href=system_basic_edit.php?id=".$row['id'].">�޸�</a> | <a href=system_basic_del.php?id=".$row['id'].">ɾ��</a></td></tr>";
	   }
	   echo "<tr><td colspan='8'>&nbsp;".$dlist->GetPageList($cfg_record)."</td></tr></table>";
	  
	   $csql->close();
   echo " </td></tr></table>
 </td></tr><tr>
    <td id=\"table_style\" class=\"l_b\">&nbsp;</td>
    <td>&nbsp;</td>
    <td id=\"table_style\" class=\"r_b\">&nbsp;</td>
  </tr>
</table>";
 }
 else{
?>
<body onload="form1.cp_tm.focus()">
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
      <td><strong>&nbsp;����������Ϣ����</strong>(ע:��ɫ����Ϊ����ѡ��)&nbsp;&nbsp;- <a href="equip_add.php">�������Ǽ�</a> - <a href="equip_add.php?action=seek">����������Ϣ��ѯ</a></td>
     </tr><form action="equip_add.php?action=save" method="post" name="form1">
     <tr>
      <td bgcolor="#FFFFFF">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_border">
   <tr>
    <td class="cellcolor">����ID:</td>
    <td>&nbsp;<input type="text" name="cp_number" value="<?php echo GetMkTime(time()) ?>" style="background:transparent;border:0px;color:red;font-weight:bold;font-family:Verdana, Arial, Helvetica, sans-serif;" readonly></td>
  </tr>
  <tr>
    <td class="cellcolor" width="30%">����������:<br>(��������ɨ���ǿ�ֱ��ɨ��)</td>
    <td>&nbsp;<input type="text" name="cp_tm"></td>
  </tr>  
  <tr>
    <td class="cellcolor" width="30%">��������:</td>
    <td>&nbsp;<input type="text" name="cp_name" id="need" onblur="pinyin(this.value)"></td>
  </tr>
  <tr>
    <td class="cellcolor">�������:</td>
    <td>&nbsp;<input type="text" name="cp_gg" id="need"></td>
  </tr>
  <tr>
    <td class="cellcolor">������������:</td>
    <td>&nbsp;
	<?php
    getcategories(0,'');
	?>&nbsp;<a href="system_class.php"><font color="#0000FF">������������</font></a></td>
  </tr>
  <tr>
    <td class="cellcolor">��������</td>
    <td>&nbsp;<input type="text" name="cp_sale" id="need"></td>
  </tr>
  <tr>
    <td class="cellcolor">��λ:</td>
    <td>&nbsp;<?php getdw() ?><a href="system_dw.php"><font color="#0000FF">����������λ</font></a></td>
  </tr>
  <tr>
    <td class="cellcolor">�����۸�:</td>
    <td>&nbsp;<input type="text" name="cp_jj" id="need"></td>
  </tr>
  <tr>
    <td class="cellcolor">��������:</td>
    <td>&nbsp;<input class="Wdate" type="text" name="cp_sdate" onclick="WdatePicker()">
	&nbsp;���������ѡ��ʱ��	</td>
  </tr>
  <tr>
    <td class="cellcolor">��������:</td>
    <td>&nbsp;<input class="Wdate" type="text" name="cp_edate" onclick="WdatePicker()">&nbsp;���������ѡ��ʱ��</td>
  </tr>
  <tr>
    <td class="cellcolor">��Ӧ��:</td>
    <td>&nbsp;<input class="SeOem" type="text" name="cp_gys" onclick="window.open('select_gys.php?form=form1&field=cp_gys','selected','directorys=no,toolbar=no,status=no,menubar=no,resizable=no,width=250,height=270,top=200,left=520')" readonly>&nbsp;<a href="system_gys.php"><font color="#0000FF">����Ӧ��</font></a></td>
  </tr>

  <tr>
    <td class="cellcolor">���Ǵ�:</td>
    <td>&nbsp;<input type="text" name="cp_helpword">&nbsp;(���ڿ�����Ѱ������,�粻������ƴ��������ĸ��¼)</td>
  </tr>    
  <tr>
    <td class="cellcolor">��ע:</td>
    <td>&nbsp;<textarea rows="5" cols="30" name="cp_bz"></textarea><input type="hidden" name="cp_saleall" value="0" /></td>
  </tr>
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;<input type="submit" value=" �Ǽ������� "></td>
  </tr></form>
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
}
copyright();
?>
</body>
</html>
