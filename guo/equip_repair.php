<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="include/ajax.js"></script>
<title>����ά�������б�</title>
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
      <td><strong>&nbsp;����ά��</strong>&nbsp;&nbsp;<a href="equip_repair.php">ˢ��</a> | <a href="equip_repair.php?action=seek">�����б�</a></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF">
	   <?php
       echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   if ($_GET['action'] == "seek")
	   {
		   $csql->SetQuery("select * from #@__repair_ask ");
	   }
	   else if ($_GET['action'] == "do")
	   {
		   $csql->SetQuery("select * from #@__repair_ask where isallow != 1");
	   }
	   else 
	   {
		   $csql->SetQuery("select * from #@__repair_ask where  isallow = 1 ");
	   }
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;û���κ�ά�����롣</td></tr>";
	   else{	?>
       <tr class="row_color_head">
       	<td>ID</td>
        <td>��������</td>
        <td>������</td>
        <td>����ʱ��</td>
        <td>�Ƿ�ά��</td>
        <td>����ʱ��</td>
        <td>����</td>
       </tr>
       <?php
	   	while ($row=$csql->GetArray())
		{
			$basicsql=New Dedesql(false);
	   		$basicsql->SetQuery("select * from #@__basic where id = '$row[basic_id]'");
	   		$basicsql->Execute();
			$basicrow=$basicsql->GetArray();
			
			$bosssql=New Dedesql(false);
	   		$bosssql->SetQuery("select * from #@__boss where boss = '$row[boss_id]'");
	   		$bosssql->Execute();
			$bossrow=$bosssql->GetArray();
	   ?>
       	<tr class='row_content'>
        	<td>&nbsp;<?=$row['id']?></td>
        	<td>&nbsp;<?=$basicrow['cp_name']?></td>
        	<td>&nbsp;<?=$bossrow['name']?></td>
        	<td>&nbsp;<?=$row['askdate']?></td>
            <td>&nbsp;<?php if ($row['isallow'] == 0) echo "δ��׼"; else if ($row['isallow'] == 1) echo "<font color=\"#0000FF\">��ά��</font>"; else if ($row['isallow'] == 2) echo "<font color=\"#FF0000\">�Ѿܾ�</font>"; ?></td>
            <td>&nbsp;
            <?php
				if ($row['allowdate'] != 0)
				{
					echo $allowdate;
				}
				else 
				{
					echo "-";
				}
			?>
            </td>
        	<td>&nbsp;<a href="equip_repairshow.php?id=<?=$row['id']?>&ac=show">��ϸ</a></td>
        </tr>
       <?php
	   $basicsql->close();
	   $bosssql->close();
	   }
	   }
	   echo "</table>";
	  
	   $csql->close();
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
