<?php
require('ppframe.php');
//$file = WORK_DIR.'template/ppframe/i18n_passport.php';
$file = GetTemplate('i18n_passport.php');
require($file);
$file_note_writeable = is_writeable($file) ? 0 : 1;
if (isset($Submit)) {
	Iimport('Config');
	$Config_obj = new Config('__lang_array',$file);
	$Config_obj -> LoadConfigNoCheck($__lang_array);
	if (!$add) {
		$Config_obj -> LoadConfig($input);
		$Config_obj -> EnableDB(false);
		$Config_obj -> ReConfig();
	}else {
		$array = array();
		foreach ($ikey as $k => $v) {
			if ($v && $ivalue[$k]) {
				$array[$v] = $ivalue[$k];
			}
		}
		$Config_obj -> LoadConfig($array);
		$Config_obj -> EnableDB(false);
		$Config_obj -> ReConfig();
	}
	ShowMsg('setconfig.success','config_lang_main.php',1);
}else {
	$lang_to_use = $__lang_array;
	
	$pagesize = 10;
	
	Iimport('Page');
	$p = new Page(count($lang_to_use),$page,$pagesize);
	$p -> SetDpages();
	$pagelink = $p -> GetPageLink();
	
	$lang_to_use = array_splice($lang_to_use,$p -> Limit_st,$pagesize);
	
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('config_lang_main');
}
?>