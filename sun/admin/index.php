<?php
require('ppframe.php');
if(empty($go)) {
	$go = 'index_body.php';
}
require(GetTemplate('admin_menu_array.php'));

foreach ($menu as $k => $v) {
	if (!CheckPriv('',$v)) {
		unset($menu[$k]);
	}
}

Iimport('lister');
$array = array(
	'table' => '##__frame_module',
	'where' => 1,
);
$lister = new Lister($array);
$modules = $lister -> GetLister();
Iimport('Template');
$tp = new Template();
$tp -> DisPlay('index');
?>