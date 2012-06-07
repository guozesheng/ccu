<title>Web进销存 -Powered by viooma.com</title>
<?php 
if(is_dir(dirname(__FILE__)."/install")){
  echo "·如果你还没安装本程序，请运行<a href='install/index.php'> install/index.php </a> 进入安装&gt;&gt;<br/><br/>";
  echo "·如果你已经安装好程序，请删除或者重新命名 install 目录!  <br/><br/>";
  exit();
}
require_once(dirname(__FILE__)."/include/checklogin.php");
?>
<frameset rows="90,*" cols="*" frameborder="0" framespacing="0">
 <frame src="top.php" frameborder="0" noresize="noresize" scrolling="no"/>
 <frameset cols="180,*" frameborder="0">
  <frame src="menu.php" name="menu" noresize="noresize" frameborder="0"/>
  <frame src="main.php" name="main" noresize="noresize" frameborder="0"/>
 </frameset>
</frameset><noframes></noframes>