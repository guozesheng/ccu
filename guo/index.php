<title>Web������ -Powered by viooma.com</title>
<?php 
if(is_dir(dirname(__FILE__)."/install")){
  echo "������㻹û��װ������������<a href='install/index.php'> install/index.php </a> ���밲װ&gt;&gt;<br/><br/>";
  echo "��������Ѿ���װ�ó�����ɾ�������������� install Ŀ¼!  <br/><br/>";
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