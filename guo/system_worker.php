<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
if($s_name==''){
ShowMsg('请输入职工的姓名','-1');
exit();
}
 $addsql="insert into #@__staff(s_name,s_address,s_phone,s_part,s_way,s_money,s_utype,s_duty) values('$s_name','$s_address','$s_phone','$s_part','$s_way','s_money','$s_utype','$s_duty')";
 $message="添加职员".$s_name."成功";

 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('成功添加了公司职工','system_worker.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfs_softname;?>职员管理</title>
<script language="javascript">
function cway(value){
if(value==0)
document.forms[0].s_e.value="%";
else
document.forms[0].s_e.value="元/件";
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
      <td><strong>&nbsp;公司职工管理</strong>&nbsp;&nbsp;<a href="system_worker.php?action=new">添加公司职工</a> | <a href="system_worker.php">查看公司职员</a></td>
     </tr>
	 <form action="system_worker.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
	  <?php if($action=='new'){ ?>
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;员工姓名:</td>
		 <td>
		 &nbsp;<input type="text" name="s_name" size="10" id="need"></td>
	    </tr>
	    <tr>
		 <td id="row_style">&nbsp;员工联系地址:</td>
		 <td>
		 &nbsp;<input type="text" name="s_address" size="30"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;员工联系电话:</td>
		 <td>
		 &nbsp;<input type="text" name="s_phone" size="15"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;所在部门:</td>
		 <td>
		 &nbsp;<input type="text" name="s_part" size="20"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;所任职务:</td>
		 <td>
		 &nbsp;<input type="text" name="s_duty" size="20"></td>
	    </tr>	
		<tr>
		 <td id="row_style">&nbsp;提成方式:</td>
		 <td>
		 <?php
		 if ($cfg_way=='1'){
		 ?>
		 &nbsp;<select name="s_way" onchange="cway(this.value)"><option value="0">销售总额的百分比</option><option value="1">固定提成(按件)</select>
		 <?php
		 }
		 else
		 echo "&nbsp;员工提成功能被管理员禁用!";
		 ?>
		 </td>
	    </tr>		
		<tr>
		 <td id="row_style">&nbsp;提成额(为空表示添加的此员工不提成):</td>
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
		 <td id="row_style">&nbsp;员工类型(类型不同权限不同):</td>
		 <td>
		 &nbsp;<?php getusertype('',0) ?></td>
	    </tr>									
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" 添加职工 "></td>
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
	   echo "<tr><td>&nbsp;公司里还没有任何职员,请先<a href=system_worker.php?action=new>添加职工</a>。</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>姓名</td><td>联系地址</td><td>联系电话</td><td>部门</td><td>职务</td><td>用户类型</td><td>操作</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr><td>ID号:".$row['id']."</td><td>&nbsp;".$row['s_name']."</td><td>&nbsp;".$row['s_address']."</td><td>&nbsp;".$row['s_phone']."</td><td>&nbsp;".$row['s_part']."</td><td>&nbsp;".$row['s_duty']."</td><td>&nbsp;".getusertype($row['s_utype'])."</td><td><a href=system_worker_edit.php?id=".$row['id'].">修改</a> | <a href=system_worker_del.php?id=".$row['id'].">删除</a></td></tr>";
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
