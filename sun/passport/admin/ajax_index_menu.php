<?php
require('ajax_ppframe.php');
require(GetTemplate('ajax_passport_admin_menu_array.php'));
//丢掉没有的权限
foreach ($menu as $k => $v) {
	foreach ($v as $kk => $vv) {
		if (is_array($vv)) {
			foreach ($vv as $kkk => $vvv) {
				if (!CheckPriv('',$vvv)) {
					unset($menu[$k][$kk][$kkk]);
				}
			}
		}else {
			if (!CheckPriv('',$vv)) {
				unset($menu[$k][$kk]);
			}
		}
		if (!$menu[$k][$kk]) {
			unset($menu[$k][$kk]);
		}
	}
	if (!$menu[$k]) {
		unset($menu[$k]);
	}
}

if (empty($menu)) {
	EchoMsg('<font color=red>.no.auth.</font>');
	exit;
}

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('ajax_index_menu');
?>