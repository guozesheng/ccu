<?php
/*
Runtime Envirment file
λ��: /passport
*/
require('../rte.php');
define('MODULE','passport');
define('WORK_DIR',ROOT.MODULE."/");
require(WORK_DIR.'config/baseconfig.php');
$Lang -> LoadLangFromFile('i18n_passport');
Iimport('PassPort_User');
$passport = new PassPort_User();
$passport -> ExitLogin();

$return = $return ? $return : ($forward ? $forward : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $base_config['passport_root']));

//passport api
if ($base_config['passport_method'] == 2) {	
	#����Լ��
	//�������
	$inter_action = 'quit';
	//��������
	$inter_db = array(
		'username' => '',
		'password'=> '',
		'email' => '',
		'time' => $timestamp,
	);
	//��ת��ַ
	$forward = $return ? $return : $forward;
	$inter_forward = $forward ? $forward : $base_config['passport_root'];
	//��֤ǩ��,��ģ���м���.
	$Inter_verify = '';
	#����Լ��
	
	$interface_file = WORK_DIR.'api/passport_interface/'.$base_config['passport_api_program'].'_interface.php';
	define('passport_safe',1);
	require($interface_file);
	#��֤������
	if ($base_config['passport_server'] != 1) {	//��֤�����������
		$return = $inter_url;
	}
}
//passport api

#��֤������
if ($base_config['passport_server'] == 1) {	//��֤�����������
	define('ppfapi_safe',true);
	$ppf_api_userdata = array();
	$ppf_api_return = $inter_url ? $inter_url : $return;
	$ppf_api_usepost = !$rtc['uc_use'];
	require(WORK_DIR.'ppfapi/interface_logout.php');
	$return = $ppf_api_gourl ? $ppf_api_gourl : $return;
}
#��֤������

#UC���Ϻ��Message!
if ($rtc['uc_use']) {
	require(ROOT.'config/config.uc.php');
	if($rtc['uc_version'] == '10') {		#uc 1.0
		require(FRAME_ROOT.'uc_client.1.0/client.php');
	}else {			#uc 1.5
		require(FRAME_ROOT.'uc_client.1.5/client.php');
	}
	$message = uc_user_synlogout();
	$gt = 3;
}else {
	$message = 'passport.logout.success';
	$gt = 0;
}

if($return) {
	ShowMessage('passport.logout.success',$return,0,$gt,$message);
}else {
	if($_SERVER['HTTP_REFERER']) {
		$return = $_SERVER['HTTP_REFERER'];
	}else {
		$return = './';
	}
	ShowMessage('passport.logout.success',$return,0,$gt,$message);
}
?>