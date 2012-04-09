<?php
$menu = array(
	'基础管理' => array(
		array(
			'后台首页' => 'index_body.php',
			'基础配置' => 'config_base.php'
		),
		array(
			'语言包管理' => 'config_lang_main.php',
			'管理员管理' => 'sys_user_main.php'
		),
	),
	'考试系统' => array(
		array(
			'题库管理' => 'tiku_main.php',
			'题型管理' => 'tixing_main.php',
		),
		array(
			'试题管理' => 'shiti_main.php',
			'批量导入' => 'csv_main.php'
		),
		array(
			'试卷管理' => 'shijuan_main.php',
			'分类管理' => 'class_main.php'
		),
		array(
			'考卷管理' => 'kaojuan_main.php',
			'考卷批处理' => 'kaojuan_bat_main.php'
		),
		array(
			'成绩总汇导出' => 'chengji_export_main.php',
		),
		'题目报错' => 'shiti_errorreport_main.php',
		'我的展示' => 'my_discript.php',
	),
);
?>