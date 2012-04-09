<?php
/*
Runtime Envirment file
位置: /admin
*/
require(dirname(__FILE__)."/../rte.php");

#程序自动识别MODULE
$MODULE_FOR_DEFINE = explode('/',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
define('MODULE',$MODULE_FOR_DEFINE[0]);
#固定MODULE
#define('MODULE','admin');

define('TEMPLATE_DIR',ROOT.MODULE.'/');
define('WORK_DIR',ROOT.MODULE.'/');
define('Admin_Safe',MODULE);
Iimport('Admin_User');
$Admin = new Admin_User();
if(!$Admin -> IsLogined()) {
	$gurl = GetRtUri();
	if(ereg('login.php|index_menu.php|index.php',$gurl) || $gurl == '/admin/' || eregi('admin\/$',$gurl)){
		$gurl = 'login.php';
	}else {
		$gurl = urlencode('index.php?go='.$gurl);
		$gurl = "login.php?gurl={$gurl}";
	}
	ShowMessage('please.login',$gurl,0,0);
}

#权限检测
if (!CheckPriv()) {
	ShowMessage('no.auth');
}
?>