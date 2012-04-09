<?php
/*
Runtime Envirment file
λ��: /passport/admin
*/
require(dirname(__FILE__).'/../../rte.php');

#�����Զ�ʶ��MODULE
$MODULE_FOR_DEFINE = explode('/',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
define('MODULE',$MODULE_FOR_DEFINE[0]);
#�̶�MODULE
//define('MODULE','passport');

define('WORK_DIR',ROOT.MODULE.'/');

#�����Զ��ж�TEMPLATE_DIR
define('TEMPLATE_DIR',WORK_DIR.str_replace(str_replace('\\','/',WORK_DIR),'',str_replace('\\','/',dirname(__FILE__))).'/');
#�̶�TEMPLATE_DIR
//define('TEMPLATE_DIR',WORK_DIR.'admin/');

require(WORK_DIR.'config/baseconfig.php');
require(WORK_DIR.'rte_functions.php');
$Lang -> LoadLangFromFile('i18n_passport');
define('Admin_Safe',MODULE);
Iimport('Passport_AdminUser');
$Admin = new Passport_AdminUser();
if(!$Admin -> IsLogined()) {
	//need to rewrite!
	$gurl = GetRtUri();
	if(ereg('login.php|index_menu.php',$gurl)){
		$gurl = $rtc['admin_host']."index.php?go=index_body.php"; 
	}else {
		$gurl = urlencode($rtc['admin_host']."index.php?go=".urlencode(GetRtFullUrl()));
	}
	ShowMessage('please.login',$rtc['admin_host']."login.php?gurl={$gurl}");
	exit;
}
$Admin -> LoadFromDBUsePriID($Admin->U_ID);
//

if (!CheckPriv()) {
	ShowMessage('no.auth');
}
if ($Admin -> U['loginban']) {
	ShowMessage('user.connt.login');
}
//header('Cache-control:no-cache');

require(GetTemplate('ajax_passport_admin_menu_array.php'));
if ($rtc['admin_host_auto']) {
	$admin_host_self = GetRtFullUrl();
	$admin_host_self = substr($admin_host_self,0,strrpos($admin_host_self,'/')+1);
}else {
	$admin_host_self = $base_config['admin_host_self'];
}
?>