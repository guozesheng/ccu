<?php
$menu = array(
	'基础管理' => array(
		array(
			'后台首页' => 'index_body.php',
			'基础配置' => 'config_base.php'
		),
		array(
			'管理员管理' => 'sys_user_main.php'
		),
	),
	'用户管理' => array(
		array(
			'用户管理' => 'user_main.php',
			'用户组管理' => 'user_group_main.php'
		),
		array(
			'自定义字段' => 'user_selfdef_main.php',
			'批量入库' => 'user_import_main.php'
		),
		array(
			'批量管理' => 'user_bat_main.php'
		),
	),
	'院系/专业/班级管理' =>array(
		array(
			'院系管理' => 'college_main.php',
			'添加院系' => 'college_add.php'
		),
		array(
			'专业管理' => 'professional_main.php',
			'添加专业' => 'professional_add.php',
		),	
		array(
			'班级管理' => 'user_class_main.php',
			'添加班级' => 'user_class_add.php')
		,
		'班级申请管理' => 'user_class_apply.php',
	),
);
?>