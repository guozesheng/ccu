<?php
/**
 * PPF API Client
 * ���ļ��Ĺ��ܣ�
 * 1������ppfapi server ���͵����󣬾����Ϸ�����֤������û�ͬ�������ֻỰ��
 * 2�����·���ppfapi server ��ַ
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
	
	if ($_POST['action'] == 'login') {		//��½API
		if (strtolower($_POST['lang']) != strtolower($ppf_api_config_client['language'])) {
			#$userdata ����ת��,Just Try�������֧�֣��ͺ�����,�ڷ������ͻ��˱��벻ͬʱ���ܳ������롣
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
		
		##�Ự�������û�ͬ��
		include('mod/ppf_login.php');
		##�Ự�������û�ͬ��
		
	}else if ($_POST['action'] == 'logout') {		//�˳�API
		##�Ự���
		include('mod/ppf_logout.php');
		##�Ự���
	}
	
	if ($_POST['server']) {
		#������֤������
		$server = $_POST['server'];
		unset($_POST['server']);
		$_POST['time'] = time();
		unset($_POST['lang']);
		$ppf_api -> SetParams($_POST,1,1);
		$ppf_api -> CreateApi($server);
		exit;
	}else {
		#ֱ�ӷ��� return
		header("Location: {$_POST['return']}");
		echo "<meta http-equiv='refresh' content='0;url={$_POST['return']}'>";
		exit;
	}
}else {
	exit('Forbidden');
}
?>