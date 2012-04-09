<?php
/*
Runtime Envirment file
位置: /passport/admin
*/
require(dirname(__FILE__).'/../../rte.php');

#程序自动识别MODULE
$MODULE_FOR_DEFINE = explode('/',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
define('MODULE',$MODULE_FOR_DEFINE[0]);
#固定MODULE
//define('MODULE','passport');

define('WORK_DIR',ROOT.MODULE.'/');

#程序自动判断TEMPLATE_DIR
define('TEMPLATE_DIR',WORK_DIR.str_replace(str_replace('\\','/',WORK_DIR),'',str_replace('\\','/',dirname(__FILE__))).'/');
#固定TEMPLATE_DIR
//define('TEMPLATE_DIR',WORK_DIR.'admin/');

require(WORK_DIR.'config/baseconfig.php');
require(WORK_DIR.'rte_functions.php');
$Lang -> LoadLangFromFile('i18n_passport');
define('Admin_Safe',MODULE);
Iimport('Passport_AdminUser');
$Admin = new Passport_AdminUser();
header('Cache-Control:no-cache,no-store,max-age=0,must-revalidate');
//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control:no-cache');
header("Content-Type:text/html;charset=$rtc[language]");

if(!$Admin -> IsLogined()) {
	EchoMsg('<font color=red>.no.login.</font>');
	exit;
}
$Admin -> LoadFromDBUsePriID($Admin->U_ID);

if (!CheckPriv()) {
	EchoMsg('<font color=red>.no.auth.</font>');
	exit;
}
if ($Admin -> U['loginban']) {
	EchoMsg('<font color=red>.user.connt.login.</font>');
	exit;
}
if ($rtc['admin_host_auto']) {
	$admin_host_self = GetRtFullUrl();
	$admin_host_self = substr($admin_host_self,0,strrpos($admin_host_self,'/')+1);
}else {
	$admin_host_self = $base_config['admin_host_self'];
}
?>