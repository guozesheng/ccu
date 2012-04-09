<?php
/**
 * PPF API Client
 * 此文件的功能：
 * 1、接受ppfapi server 发送的请求，经过合法性验证后完成用户同步、保持会话等
 * 2、重新返回ppfapi server 地址
 */

require('./ppframe.php');
require('./config/c_client.php');
$ppf_api = new PPF_Api();
$ppf_api -> SetKey($ppf_api_config_client['key']);
$_POST = array_merge($_GET,$_POST);
$ppf_api -> SetParams($_POST,1,1);
$sign = $ppf_api -> CreateApiSign();
if ($_POST['sign'] == $sign && $_POST['action'] && ($_POST['action'] == 'logout' || $_POST['data']) && $_POST['time'] && $_POST['step'] && $_POST['return'] && $_POST['lang']) {
	if ($_POST['action'] != 'logout') {
		$ppf_api_encoder = new PPF_API_Encoder();
		$ppf_api_encoder -> SetKey($ppf_api -> Key);
		$str = $ppf_api_encoder -> Decode($_POST['data']);
		$userdata = array();
		parse_str($str,$userdata);
	}
	
	if ($_POST['action'] == 'login') {		//登陆API
		if (strtolower($_POST['lang']) != strtolower($ppf_api_config_client['language'])) {
			#$userdata 编码转换,Just Try。如果不支持，就忽略了,在服务端与客户端编码不同时可能出现乱码。
			foreach ($userdata as $k => $v) {
				if (function_exists('mb_convert_encoding')) {
					$userdata[$k] = mb_convert_encoding($v,strtoupper($ppf_api_config_client['language']),strtoupper($_POST['lang']));
				}else if (function_exists('iconv')) {
					$userdata[$k] = iconv(strtoupper($_POST['lang']),strtoupper($ppf_api_config_client['language']),$v);
				}else {
					// ignore
				}
			}
		}
		
		##会话保持与用户同步
		include('mod/ppf_login.php');
		##会话保持与用户同步
		
	}else if ($_POST['action'] == 'logout') {		//退出API
		##会话清除
		include('mod/ppf_logout.php');
		##会话清除
	}
	
	if ($_POST['server']) {
		#返回认证服务器
		$server = $_POST['server'];
		unset($_POST['server']);
		$_POST['time'] = time();
		unset($_POST['lang']);
		$ppf_api -> SetParams($_POST,1,1);
		$ppf_api -> CreateApi($server);
		exit;
	}else {
		#直接返回 return
		header("Location: {$_POST['return']}");
		echo "<meta http-equiv='refresh' content='0;url={$_POST['return']}'>";
		exit;
	}
}else {
	exit('Forbidden');
}
?>