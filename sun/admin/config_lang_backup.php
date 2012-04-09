<?php
require('ppframe.php');
$file = GetTemplate('i18n.php');
$dir = dirname($file);
chdir($dir);
if ($action == 1) {	#±¸·Ý
	copy('i18n.php','i18n.bak.php');
	if ($modes && is_array($modes)) {
		foreach ($modes as $k => $v) {
			if (file_exists('i18n_'.$v.'.php')) {
				copy('i18n_'.$v.'.php','i18n_'.$v.'.bak.php');
			}
		}
	}
	ShowMessage('do.success');
}else if ($action == 2) {	#»Ö¸´
	Iimport('Config');
	$Config_obj = new Config('__lang_array',$dir . '/i18n.php');
	if (file_exists('i18n.bak.php')) {
		#i18n»Ö¸´
		require('i18n.php');
		$Config_obj -> LoadConfigNoCheck($__lang_array);
		require('i18n.bak.php');
		$Config_obj -> LoadConfig($__lang_array);
		$Config_obj -> ReConfig();
		#i18n»Ö¸´
	}
	
	if ($modes && is_array($modes)) {
		foreach ($modes as $k => $v) {
			if (file_exists('i18n_'.$v.'.php') && file_exists('i18n_'.$v.'.bak.php')) {
				$Config_obj = new Config('__lang_array',$dir . '/i18n_'.$v.'.php');
				require('i18n_'.$v.'.php');
				$Config_obj -> LoadConfigNoCheck($__lang_array);
				require('i18n_'.$v.'.bak.php');
				$Config_obj -> LoadConfig($__lang_array);
				$Config_obj -> ReConfig();
			}
		}
	}
	ShowMessage('do.success');
}else {
	exit('Forbidden');
}
?>