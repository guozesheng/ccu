<?php 
require_once(dirname(__FILE__)."/include/config_base.php");
require_once(dirname(__FILE__)."/include/config_rglobals.php");
require_once(dirname(__FILE__)."/include/pub_db_mysql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<head>
<title>viooma menu</title>
<style type="text/css">
body {background-color:#3179bd;}
</style>
<link href="style/menu.css" rel="stylesheet" type="text/css" />
</head>
<script language="javascript">
function getObject(objectId) {
 if(document.getElementById && document.getElementById(objectId)) {
 // W3C DOM
 return document.getElementById(objectId);
 }
 else if (document.all && document.all(objectId)) {
 // MSIE 4 DOM
 return document.all(objectId);
 }
 else if (document.layers && document.layers[objectId]) {
 // NN 4 DOM.. note: this won't find nested layers
 return document.layers[objectId];
 }
 else {
 return false;
 }
}

function showHide(objname){
    var obj = getObject(objname);
    if(obj.style.display == "none"){
		obj.style.display = "block";
	}else{
		obj.style.display = "none";
	}
}
</script>
<base target="main">
<body>
<div class="menu">
<?php 
//获取用户权限
//$csql=NEW Dedesql(false);
//$currentname=str_replace($cfg_cookie_encode,'',$_COOKIE['VioomaUserID']);
//$query="select content from #@__usertype where rank in (select rank from #@__boss where boss='$currentname')";
//$rankinfo=$csql->GETONE($query);
//if (!is_array($rankinfo)){
        //$csql->Close();
		//ShowMsg("参数错误或用户已经被删除！","-1");
	//	exit();
//}
//GetMenus($rankinfo['content']);
//$csql->close();
if($c=='')$c=1;
$endmenus = "
<dl>
    <dt><a href='###' onclick=showHide('items20') target='_self' class='top'>进销存系统帮助</a></dt>
    <dd id='items20' style='display:block;'>
			<ul>
<li><a href='http://www.viooma.com/help/install_faq.html' target='main'>安装说明</a></li>
<li><a href='http://bbs.viooma.com/' target='_blank'>官方论坛</a></li>
<li><a href='#' target='_self'>技术支持QQ:12612019</a></li>
  			</ul>
		</dd>
	</dl>
";
$msql=new Dedesql(false);
$query="select name from #@__menu where id='$c'";
$menuinfo=$msql->GetOne($query);
$menus="
<dl>
    <dt><a href='###' onclick=showHide('items".$c."') target='_self' class='top'>".$menuinfo['name']."</a></dt>
    <dd id='items".$c."' style='display:block;'>
			<ul>
~Item~
  			</ul>
		</dd>
	</dl>
";
$msql->Setquery("select name,url from #@__menu where reid='$c'");
$msql->Execute();
while($row=$msql->GetArray())
{
$iteminfo=$iteminfo."
<li><a href='".$row['url']."' target='main'>".$row['name']."</a></li>
";
}
$menus=str_replace("~Item~",$iteminfo,$menus);
echo $menus.$endmenus;
$msql->close();
?>
</div>
</body>
</html>