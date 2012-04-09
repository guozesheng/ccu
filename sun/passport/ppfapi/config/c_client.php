<?php
/**
 * Client 端配置文件
 */
$ppf_api_config_client = array(
	'key' => 'abclasdkfa',	//必须与Server中设置的该Client的Key完全相同！
	'language' => 'gbk',	//小写  Client 端语言版本 当Server和Client 语言版本不同的时候，需要转码
	'fields' => array(	//字段对照表	使用这个对照表来获取Server端用户数据，可以无限扩展!
		//'Client中字段名' => 'Server中字段名'
		//务必修改并核实字段对应关系!系统运行将依赖这个字段对应关系。
		'uid' => 'uid',
		'username' => 'username',
		'password' => 'password',
		'email' => 'email',
	),
);
?>