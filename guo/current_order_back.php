<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
</head>
<body oncontextmenu="return false">
<?php
if($action=='del'){
$dsql=New Dedesql(false);
$delstring="delete from #@__kcbackgys where id='$id'";
$dsql->executenonequery($delstring);
$dsql->close();
header("Location:current_order_back.php?action=normal&did=".$rid);
}
?>
	<table width="100%" border="0" cellpadding="0" cellspacing="2">
     <tr>
      <td><strong>&nbsp;�˻ع�Ӧ����ϸ��Ʒ</strong></td>
     </tr>
	 <form method="post" name="sel">
     <tr>
      <td bgcolor="#FFFFFF">
       <?php
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   if ($pid==''){//��ʼ״̬
	   if($action=='normal')
	   $csql->SetQuery("select * from #@__kcbackgys where rdh='$did'");
	   else
	   $csql->SetQuery("select * from #@__kcbackgys where id<0");
	   }
	   else{
	   if($action=='' && $did!=''){
	   //д���Ʒ��¼
	   $wsql=New Dedesql(false);
	   $writesql="select * from #@__basic where cp_number='$pid'";
	   $wsql->Setquery($writesql);
	   $wsql->Execute();
	   $wrs=$wsql->GetOne();
	   $wsql->ExecuteNoneQuery("insert into #@__kcbackgys(productid,number,labid,rdh,dtime) values('".$pid."','".$num."','','".$did."','".GetDateTimeMk(time())."')");

	   $wsql->close();
	   }
	   $csql->SetQuery("select * from #@__kcbackgys where rdh='$did'");
	   }
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>����</td><td>����</td><td>���</td><td>����</td><td>��λ</td><td>����</td><td>��Ӧ��<td>�������</td><td>ɾ��</tr>";
	   while($row=$csql->GetArray()){
	   $nsql=New dedesql(false);
	   $query1="select * from #@__basic where cp_number='".$row['productid']."'";
	   $nsql->setquery($query1);
	   $nsql->execute();
	   $row1=$nsql->getone();
	   $amoney+=$row1['cp_jj']*$row['number'];
	   $anum+=$row['number'];
	   echo "<tr onMouseMove=\"javascript:this.bgColor='#EBF1F6';\" onMouseOut=\"javascript:this.bgColor='#FFFFFF';\"><td>".$row['productid']."</td><td>&nbsp;".$row1['cp_name']."</td><td>".$row1['cp_gg']."</td><td>".get_name($row1['cp_categories'],'categories').">".get_name($row1['cp_categories_down'],'categories')."</td><td>".get_name($row1['cp_dwname'],'dw')."</td><td>��".$row1['cp_jj']."</td><td>".$row1['cp_gys']."</td><td>".$row['number']."</td><td><a href='current_order_back.php?action=del&id=".$row['id']."&rid=".$row['rdh']."'>ɾ��</a></td></tr>";
	   $nsql->close();
	   }
	   }
	   echo "<tr><td>&nbsp;����:</td><td></td><td></td><td></td><td>&nbsp;���:</td><td>��".$amoney."</td><td align='right'>����:</td><td>".$anum."</td></tr></table>";
	   $csql->close();
	   ?>
	  </td>
     </tr></form>
    </table>
</body>
</html>
