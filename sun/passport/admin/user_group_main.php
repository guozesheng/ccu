<?php
require('ppframe.php');

#ำรปงื้ลไึร
$file = ROOT.'config/usergroup_config.php';
/*
$_usergroup = array(
	'id' => array(
		'id' => id,
		'name' => name',
	)
)
*/

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_group_main');
?>