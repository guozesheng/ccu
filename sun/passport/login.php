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

if ($base_config['ip_ban_login'] && !CheckIpBan()) {
	ShowMessage('your.ip.not.allow');
}

$return = $return ? $return : ($forward ? $forward : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $base_config['passport_root']));

#整合UC后跳转到UC其他网站登陆 废弃
//if ($rtc['uc_use']) {
//	ShowMessage('',GetLoginurl(),0,0);
//}

if (isset($Submit)) {
	$username = trim($username);
	if ($rtc['login_gdcode']) {
		Iimport('CheckNum');
		$checknum = new CheckNum();
		if (!$checknum -> Check($gdcode)) {
			ShowMessage('gdcode.error',-1,1);
		}
	}
	
	Iimport('PassPort_User');
	$passport = new PassPort_User();
	$passport -> ReSet();
	if ($rtc['uc_use']) {		#使用UC登陆
		//载入UC_Client
		require(ROOT.'config/config.uc.php');
		if($rtc['uc_version'] == '10') {		#uc 1.0
			require(FRAME_ROOT.'uc_client.1.0/client.php');
		}else {			#uc 1.5
			require(FRAME_ROOT.'uc_client.1.5/client.php');
		}
		list($uid, $username, $password, $email) = uc_user_login($username, $password);
		if ($uid > 0) {		#登陆成功
			$passport -> LoadFromDBUsePriID($uid);
			if ($passport -> U_ID <= 0) {	#用户不存在
				$passport -> LoadFromDBuseUniqID($username);		#尝试用户名!
				if ($passport -> U_ID <= 0) {
					$passport -> SetInsertInfo(
						array(
							'uid' => $uid,
							'username' => $username,
							'password' => $password,
							'email' => $email,
						)
					);
					$passport -> DoRecordUser();			#新建一个用户
					$passport -> LoadFromDBUsePriID($uid);
				}
			}
			$passport -> PassCheckRebuild();
			$passport -> PutLoginedInfo();
			$passport -> UpdateLastLogin();
		}else {		#登陆失败
			ShowMessage('passport.login.fail');
		}
	}else {
		if($passport -> CheckUserUniqID($username,$password)) {
			#禁止登陆
			if ($passport -> U['u_loginban']) {
				ShowMessage('forbidden.login');
			}
			
			#用户过期
			if ($base_config['expired_ban'] && $passport -> U['u_expired'] && $passport -> U['u_expired'] < $timestamp) {
				ShowMessage('user.expired.forbidden.login');
			}
			
			$passport -> PassCheckRebuild();
			$passport -> PutLoginedInfo();
			$passport -> UpdateLastLogin();
		}else {
			ShowMessage('passport.login.fail');
		}
	}
	
	#后续处理,pw & dz api & ppfapi
	//passport api
	if ($base_config['passport_method'] == 2 && $base_config['passport_api_address']) {
		#变量约定
		//动作标记
		$inter_action = 'login';
		//传送数据
		$inter_db = array(
			'username' => $username,
			'password'=> $passport -> U[$passport -> U_PasswordKey],
			'email' => $passport -> U['email'] ? $passport -> U['email'] : '@' ,
			'time' => $timestamp,
		);
		//跳转地址
		$inter_forward = $forward ? $forward : ($return ? $return : $base_config['passport_root']) ;
			
		//验证签名,在模块中计算.
		$Inter_verify = '';
		
		$interface_file = WORK_DIR.'api/passport_interface/'.$base_config['passport_api_program'].'_interface.php';
		define('passport_safe',1);
		require($interface_file);
			
		#变量约定
		if ($base_config['passport_server'] != 1) {		//不使用认证服务器功能
			$return = $inter_url;
		}
	}
	//passport api
		
	#认证服务器
	if ($base_config['passport_server'] == 1) {	//认证服务器服务端
		define('ppfapi_safe',true);
		$ppf_api_userdata = array(
			'uid' => $passport -> U_ID,
			'username' => $passport -> U_Uname,
			'password' => $password,
			'email' => $passport -> U['email'],
		);
		$ppf_api_return = $inter_url ? $inter_url : $return;
		$ppf_api_usepost = !$rtc['uc_use'];
		require(WORK_DIR.'ppfapi/interface_login.php');
		$return = $ppf_api_gourl ? $ppf_api_gourl : $return;
	}
	#认证服务器
	
	#UC整合后的Message!
	if ($rtc['uc_use']) {
		$message = uc_user_synlogin($uid);
		$gt = 3;
	}else {
		$message = '';
		$gt = 0;
	}
	#
	if($return) {
		ShowMessage('passport.login.success',$return,0,$gt,$message);
	}else {
		if($_SERVER['HTTP_REFERER']) {
			$return = $_SERVER['HTTP_REFERER'];
		}else {
			$return = './';
		}
		ShowMessage('passport.login.success',$return,0,$gt,$message);
	}
}else {
	Iimport('template');
	$tpl = new Template();
	$tpl -> LanguageAssign('title','passport.login');
	$tpl -> DisPlay('login');
}
?>