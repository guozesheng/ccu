<?php
require('ppframe.php');

if (isset($Submit)) {
	Iimport('Config');
	$Config_obj = new Config('base_config',WORK_DIR.'config/baseconfig.php');
	$Config_obj -> LoadConfigNoCheck($base_config);
	
//	$config['username_pattern'] = stripslashes($config['username_pattern']);
	$Config_obj -> LoadConfig($config);
	$Config_obj -> EnableDB(false);
	$Config_obj -> ReConfig();
	
	ShowMessage('setconfig.success');
}else {
	
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('config_base');
}

?>