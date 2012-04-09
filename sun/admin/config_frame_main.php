<?php
require('ppframe.php');
//权限检测
$config_file = ROOT.'config/rtc.php';
if (isset($Submit)) {
	//设置主配置
	Iimport('Config');
	$Config_obj = new Config('rtc',$config_file);
	$Config_obj -> LoadConfigNoCheck($rtc);
	$Config_obj -> LoadConfig($rtconfig);
	$Config_obj -> EnableDB(false);
	$Config_obj -> ReConfig();
	ShowMsg('setconfig.success',-1);
	//
}else {
	//载入主配置
	Iimport('Template');
	$tp = new Template();
	$tp -> DisPlay('config_frame_main.htm');
}
?>