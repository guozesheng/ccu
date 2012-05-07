<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save'){
 if($groupname=='') {
 showmsg('请输入用户分组名称','-1');
 exit();
 }
 if(!is_numeric($sub) || $sub=='' || $sub>10 || $sub<1){
  showmsg('级别错误','-1');
 exit();
 }
 $addsql="insert into #@__group(groupname,sub) values('$groupname','$sub')";
 $message="添加用户分组".$groupname."成功";
  
 $loginip=getip();
 $logindate=getdatetimemk(time());
 $username=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
 $asql=New Dedesql(false);
 $asql->ExecuteNoneQuery($addsql);
 $asql->close();
 WriteNote($message,$logindate,$loginip,$username);
 showmsg('成功添加了用户分组','guest_group.php');
 exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>用户分组管理</title>
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
      <td><strong>&nbsp;用户分组管理</strong>&nbsp;&nbsp;<a href="guest_group.php?action=new">添加用户分组</a> | <a href="guest_group.php">查看用户分组</a></td>
     </tr>
     <tr>
      <td bgcolor="#FFFFFF"><form action="guest_group.php?action=save" method="post">
	  <?php if($action=='new'){ ?>
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
		 <td id="row_style">&nbsp;用户分组名称:</td>
		 <td>
		 &nbsp;<input type="text" name="groupname" size="20"></td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;此分组权限:</td>
		 <td>
		 &nbsp;<input type="hidden" name="sub" value="2">老师</td>
	    </tr>
		<tr>
		 <td id="row_style">&nbsp;</td>
		 <td>&nbsp;<input type="submit" name="submit" value=" 添加用户分组 "></td>
	    </tr>
	   </table></form>
	   <?php
	    } 
		else
		{
       echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"table_border\">";
       $csql=New Dedesql(false);
	   $csql->SetQuery("select * from #@__group");
	   $csql->Execute();
	   $rowcount=$csql->GetTotalRow();
	   if($rowcount==0)
	   echo "<tr><td>&nbsp;系统里还没有任何用户分组,请先<a href=guest_group.php?action=new>添加分组</a>。</td></tr>";
	   else{
	   echo "<tr class='row_color_head'><td>ID</td><td>分组名称</td><td>级别</td><td>操作</td></tr>";
	   while($row=$csql->GetArray()){
	   echo "<tr class='row_content'><td>".$row['id']."</td><td><img src=images/cate.gif align=absmiddle>&nbsp;".$row['groupname']."</td><td>";
	   if ($row['sub'] == 1)
	   {
		   echo "管理员";
	   }
	   else 
	   {
		   echo "老师";
	   }
	   echo"</td><td><a href=group_edit.php?id=".$row['id'].">修改</a> | <a href=group_del.php?id=".$row['id'].">删除</a></td></tr>";
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
