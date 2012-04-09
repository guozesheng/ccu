<?php
$menu = array(
	'基础管理' => array(
		array(
			'后台首页' => 'index_body.php',
			'基础配置' => 'config_base.php'
		),
		array(
			'管理员管理' => 'sys_user_main.php',
			'语言包管理' => 'config_lang_main.php'
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
			'批量管理' => 'user_bat_main.php',
			'UC 整合' => 'ucenter_combine.php',
		),
	),
	'积分管理' => array(
		array(
			'充值订单管理' => 'money_order_main.php',
			'积分对账单' => 'money_jifen_account.php'
		),
		array(
			'积分转换' => 'money_jifenchange_main.php',
			'调整会员积分' => 'user_jifen_do.php'
		),
	),
	'充值卡管理' => array(
		array(
			'查看充值卡' => 'money_card_main.php?state=3',
			'生成充值卡' => 'money_card_add.php'
		),
		'充值卡下载' => 'money_card_download.php',
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
	'部门模型管理' => array(
		array(
			'部门管理' => 'user_buss_main.php',
			'部门添加' => 'user_buss_add.php', 
		)
	),
);
?>