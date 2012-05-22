<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')
ShowMsg('非法参数，请正确执行此文件。','-1');
if($action=='save'){
if($b_name==''){
ShowMsg('请输入用户的姓名','-1');
exit();
}
 $b_passwd=md5($b_boss);
 $addsql="update #@__boss set password='$b_passwd',boss='$b_boss',name='$b_name',sex='$b_sex',phone='$b_phone',rank='$b_rank' where id='$id'";
 $message= "修改用户".$s_name."资料成功";
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->ExecuteNoneQuery("insert into #@__recordline(message,date,ip,userid) values('{$message}','{$logindate}','{$loginip}','$username')");
 $asql->close();
 showmsg('成功修改了用户的资料','system_guest.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="include/ajax.js"></script>
<title><?php echo $cfg_softname;?>用户管理</title>
</head>
<body>
<?php
$esql=New Dedesql(false);
$query="select * from #@__boss where id='$id'";
$esql->SetQuery($query);
$esql->Execute();
if($esql->GetTotalRow()==0){
ShowMsg('非法调用参数,请重试','-1');
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
      <td><strong>&nbsp;用户资料修改</strong></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF"><form action="guest_edit.php?action=save" method="post">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td width="23%" id="row_style">&nbsp;用户姓名:</td>
		 <td>
		 &nbsp;<input type="text" name="b_name" size="10" id="need" value="<?php echo $row['name'] ?>">
		 <input type="hidden" name="id" value="<?php echo $id ?>">
		 </td>
	    </tr>
        <tr>
        <td id="row_style">&nbsp;工号:</td>
        <td width="180">
        	&nbsp;<input type="text" name="b_boss" value="<?=$row['boss']?>" onchange="checkuser(this.value)" />
        </td>
        <td align="left" id="jobnumchk">&nbsp;</td>
        </tr>
        <tr>
        <td id="row_style">&nbsp;级别:</td>
        <td colspan="2">
        	&nbsp;<input type="radio" name="b_level" value="1" <?php if($row['rank']=='1') echo"checked=\"checked\""; ?> />实验室管理员<input type="radio" name="b_level" value="2" <?php if($row['rank']!='1') echo"checked=\"checked\""; ?> />老师
        </td>
        </tr>
	    <tr>
		 <td id="row_style">&nbsp;性别:</td>
		 <td>
		 <?php
		 if($row['sex']=='男')
		 echo "&nbsp;<input type=\"radio\" name=\"b_sex\" value=\"男\" checked>男<input type=\"radio\" name=\"b_sex\" value=\"女\">女";
		 else
		 echo "&nbsp;<input type=\"radio\" name=\"b_sex\" value=\"男\">男<input type=\"radio\" name=\"b_sex\" value=\"女\" checked>女";
		 ?>
		 </td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;联系电话:</td>
		 <td>
		 &nbsp;<input type="text" name="b_phone" size="15" value="<?php echo $row['phone'] ?>"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;操作时间:</td>
		 <td>
		 &nbsp;<?=getDatetimeMk(time());?></td>
	    </tr>							
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" 修改用户资料 "></td>
        </tr>
	   </table></form>
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
