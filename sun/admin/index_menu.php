<?php
require('ppframe.php');
require(GetTemplate('admin_menu_array.php'));

//����û�е�Ȩ��
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
$tp -> DisPlay('index_menu');
?>