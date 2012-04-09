<?php
require('ppframe.php');

#»ý·Ö×ª»»ÅäÖÃ
$file = WORK_DIR.'config/jifenchange_config.php';
@include($file);
/*
$_jifen_change = array(
	'f_t' => array(
		'f' => id,
		't' => id',
		'fn' => num,
		'tn' => num,
	)
)

*/
Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('money_jifenchange_main');
?>