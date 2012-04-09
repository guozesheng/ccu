<?php
/*
Runtime Envirment file
位置: /passport
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
	#变量约定
	//动作标记
	$inter_action = 'quit';
	//传送数据
	$inter_db = array(
		'username' => '',
		'password'=> '',
		'email' => '',
		'time' => $timestamp,
	);
	//跳转地址
	$forward = $return ? $return : $forward;
	$inter_forward = $forward ? $forward : $base_config['passport_root'];
	//验证签名,在模块中计算.
	$Inter_verify = '';
	#变量约定
	
	$interface_file = WORK_DIR.'api/passport_interface/'.$base_config['passport_api_program'].'_interface.php';
	define('passport_safe',1);
	require($interface_file);
	#认证服务器
	if ($base_config['passport_server'] != 1) {	//认证服务器服务端
		$return = $inter_url;
	}
}
//passport api

#认证服务器
if ($base_config['passport_server'] == 1) {	//认证服务器服务端
	define('ppfapi_safe',true);
	$ppf_api_userdata = array();
	$ppf_api_return = $inter_url ? $inter_url : $return;
	$ppf_api_usepost = !$rtc['uc_use'];
	require(WORK_DIR.'ppfapi/interface_logout.php');
	$return = $ppf_api_gourl ? $ppf_api_gourl : $return;
}
#认证服务器

#UC整合后的Message!
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