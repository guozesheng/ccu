<?php
/**
 * 退出登陆Interface
 * 包含此文件即可实现认证服务器退出登陆
 * 协议变量：
 * 1、$ppf_api_userdata 用户数据数组 键 => 值 对
 * 2、$ppf_api_return 退出登陆后返回URL
 * 
 * 协议变量的意思是，在包含此Interface 之前先准备好这两个变量。
 *
 * 你需要做的是：
 * 1、define ppfapi_safe
 * 2、准备好用户数据数组$ppf_api_userdata （可选）
 * 3、准备好返回URL$ppf_api_return
 */

#包含此文件前，请先define ppfapi_safe
!defined('ppfapi_safe') && exit('Forbidden');

require(dirname(__FILE__) . '/frame/inc_ppf_api.php');
require(dirname(__FILE__) . '/config/c_server.php');

if ($ppf_api_config_server['clients'][1]['key'] && $ppf_api_config_server['clients'][1]['api']) {
	$ppf_api = new PPF_Api();
	$ppf_api -> SetKey($ppf_api_config_server['clients'][1]['key']);
	$ppf_api -> SetParams(
		array(
			'action' => 'logout',
			'data' => $ppf_api_userdata,	//协议变量,退出是协议变量一般只需用户UID和Username，甚至什么都不需要！
			'time' => time(),
			'lang' => $ppf_api_config_server['language'],
			'step' => 1,
			'server' => $ppf_api_config_server['clients'][2] ? $ppf_api_config_server['server'] : 0,
			'return' => $ppf_api_return,	//协议变量
		),1,1
	);
	
	
	if ($ppf_api_usepost) {
		$ppf_api -> CreateApi($ppf_api_config_server['clients'][1]['api']);
	}else {
		#ppfapi 跳转地址 在目标应用中截获跳转
		$ppf_api_gourl = $ppf_api -> CreateApiUrl($ppf_api_config_server['clients'][1]['api']);
	}
}else {
	//do nothing!
}
?>