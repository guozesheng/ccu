<?php
/*
Runtime Envirment file
位置: /admin
*/
require('../../rte.php');
#程序自动识别MODULE
$MODULE_FOR_DEFINE = explode('/',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
define('MODULE',$MODULE_FOR_DEFINE[0]);
#固定MODULE
//define('MODULE','exam');

define('WORK_DIR',ROOT.MODULE.'/');

#程序自动判断TEMPLATE_DIR
define('TEMPLATE_DIR',WORK_DIR.str_replace(str_replace('\\','/',WORK_DIR),'',str_replace('\\','/',dirname(__FILE__))).'/');
#固定TEMPLATE_DIR
//define('TEMPLATE_DIR',WORK_DIR.'admin/');

require(WORK_DIR.'config/baseconfig.php');
require(WORK_DIR.'config/config_txdb.php');
require(WORK_DIR.'rte_functions.php');
define('Admin_Safe',MODULE);
Iimport('Exam_AdminUser');
$Admin = new Exam_AdminUser();

//载入语言包

$Lang -> LoadLangFromFile('i18n_exam');

if(!$Admin -> IsLogined()) {
	//need to rewrite!
	$gurl = GetRtUri();
	if(ereg('login.php|index_menu.php',$gurl)){
		$gurl = $rtc['admin_host']."index.php?go=index_body.php"; 
	}else {
		$gurl = urlencode($rtc['admin_host']."index.php?go=".urlencode(GetRtFullUrl()));
	}
	ShowMessage('please.login',$rtc['admin_host']."login.php?gurl={$gurl}",0,0);
	exit;
}
$Admin -> LoadFromDBUsePriID($Admin->U_ID);
//
if ($Admin -> U['loginban']) {
	ShowMessage('user.connt.login');
}

CheckAdminAuth();
//header('Cache-control:no-cache');

if ($rtc['admin_host_auto']) {
	$admin_host_self = GetRtFullUrl();
	$admin_host_self = substr($admin_host_self,0,strrpos($admin_host_self,'/')+1);
}else {
	$admin_host_self = $base_config['admin_host_self'];
}
require(GetTemplate('ajax_exam_admin_menu_array.php'));
if(empty($base_config['kaojuan_autotime']))  $base_config['kaojuan_autotime'] = 300;
if(empty($base_config['exam_online_timelimit'])) $base_config['exam_online_timelimit'] = 600;
if (empty($base_config['kaojuan_before_select']) || $base_config['kaojuan_before_select'] > $base_config['kaojuan_livetime'] * 60) {
	$base_config['kaojuan_before_select'] = $base_config['kaojuan_livetime'] * 30;
}