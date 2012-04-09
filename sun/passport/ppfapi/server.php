<?php
/**
 * PPF API Server
 * 
 */
require('./ppframe.php');
require('./config/c_server.php');
$_POST = array_merge($_GET,$_POST);
$step = $_POST['step'];
if ($ppf_api_config_server['clients'][$step]) {
	$ppf_api = new PPF_Api();
	$ppf_api -> SetKey($ppf_api_config_server['clients'][$step]['key']);
	$ppf_api -> SetParams($_POST,1,1);
	$sign = $ppf_api -> CreateApiSign();
	if ($sign == $_POST['sign'] && $_POST['action'] && ($_POST['action'] == 'logout' || $_POST['data']) && $_POST['time'] && $_POST['step'] && $_POST['return']) {		//认证通过
		if ($ppf_api_config_server['clients'][$step+1]) {		//还有Client
			if ($_POST['action'] != 'logout') {
				$ppf_api_encoder = new PPF_API_Encoder();
				$ppf_api_encoder -> SetKey($ppf_api -> Key);
				$str = $ppf_api_encoder -> Decode($_POST['data']);
				if (!$str) {
					exit('Data Error');
				}
				$data = array();
				parse_str($str,$data);
			}
			
			$ppf_api -> SetKey($ppf_api_config_server['clients'][$step+1]['key']);
			$_POST['data'] = $data;
			$_POST['lang'] = $ppf_api_config_server['language'];
			$_POST['server'] = $ppf_api_config_server['clients'][$step+2] ? $ppf_api_config_server['server'] : 0;
			$_POST['time'] = time();
			$_POST['step'] = $step + 1;
			$ppf_api -> SetParams($_POST,1,1);
			$ppf_api -> CreateApi($ppf_api_config_server['clients'][$step+1]['api']);
			exit;
		}else {				//没有其它的Client了，直接返回。
			header("Location: {$_POST['return']}");
			echo "<meta http-equiv='refresh' content='0;url={$_POST['return']}'>";
			exit;
		}
	}else {
		exit('Forbidden');
	}
}else {
	exit('Forbidden');
}
?>