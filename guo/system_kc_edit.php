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
<title>��Ʒ������</title>
<style type="text/css">
.rtext {background:transparent;border:0px;color:red;font-weight:bold;font-family:Verdana, Arial, Helvetica, sans-serif;}
</style>
</head>
<?php
if ($action=='save'){//������ⵥ����¼
 if($labid=='' || $kc_number=='' || $pid==''){
 showmsg('ִ���˴��зǷ��������ļ�','-1');
 exit();
 }
$bsql=New Dedesql(false);
$query="select * from #@__mainkc where p_id='$pid'";
$bsql->SetQuery($query);
$bsql->Execute();
$rowcount=$bsql->GetTotalRow();
if ($rowcount==0){
 ShowMsg('�Ƿ�������û�д˲�Ʒ��Ϣ!','-1');
 exit();
}
else{
 $bsql->executenonequery("update #@__mainkc set number='$kc_number',l_id='$labid' where p_id='".$pid."'");
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 WriteNote('�޸Ĳ�Ʒ'.get_name($pid,'name').'���ϳɹ�',$logindate,$loginip,$username);
 ShowMsg('��Ʒ��Ϣ�ѳɹ��޸�','system_kc.php');
$bsql->close();
exit();
    }
}
 else{
 if($pid=='' || $lid==''){
 echo "<script language='javascript'>alert('�Ƿ�����');history.go(-1);</script>";
 exit();}
?>
<body onload="form1.seek_text.focus()">
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
      <td><strong><strong>&nbsp������-�޸Ŀ������</strong>&nbsp;&nbsp;- <a href="system_kc.php">�鿴���</a> - <a href="system_basic_cp.php?action=seek">��Ʒ������Ϣ��</a></td>
     </tr><form action="system_kc_edit.php?action=save" method="post" name="form1">
     <tr>
      <td bgcolor="#FFFFFF">
       <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_border">
    <tr height="30">
    <td class="cellcolor">��Ʒ����:</td>
    <td class="cellcolor">&nbsp;<input type="text" name="pid" value="<?php echo $pid; ?>" readonly class="rtext" size="10">
	</td>
  </tr>
  <tr>
    <td class="cellcolor" width="30%">��Ʒ����:<br></td>
    <td>&nbsp;<input type="text" name="seek_text" value="<?php echo get_name($pid,'name')?>" readonly>&nbsp;(�޸������뵽<a href="system_basic_cp.php?action=seek">��Ʒ������Ϣ</a>)
	<input type="hidden" name="seek_number" value=""/>
	</td>
  </tr> 
  <tr>
    <td class="cellcolor" width="30%">���ڲֿ�:<br></td>
    <td>&nbsp;<?php getlab($lid) ?>
	</td>
  </tr> 
  <tr>
    <td class="cellcolor" width="30%">���п������:<br></td>
    <td>&nbsp;<input type="text" name="kc_number" size="5" value="<?php echo $n; ?>">&nbsp;<?php echo get_name(get_name($pid,'dwname'),'dw')?>
	</td>
  </tr>   
  <tr id="product_date" style="display:block;">
   <td colspan="2">
   &nbsp;����������Ϣ: <font color=red><?php echo "���:".get_name($pid,'gg')." ����:".get_name(get_name($pid,'bcate'),'categories')."->".get_name(get_name($pid,'scate'),'categories')." ������:".get_name($pid,'gys');?></font>
   </td>
  </tr> 
  <tr>
    <td class="cellcolor">&nbsp;</td>
    <td>&nbsp;<input type="submit" value="����<?php echo get_name($pid,'name')?>������"></td>
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
