<?php
/*
Runtime Envirment file
位置: /passport
*/
require('../rte.php');
define('MODULE','passport');
define('WORK_DIR',ROOT.MODULE."/");
require(WORK_DIR.'config/baseconfig.php');
if ($base_config['ip_ban_reg'] && !CheckIpBan()) {
	ShowMessage('your.ip.not.allow');
}
$Lang -> LoadLangFromFile('i18n_passport');
if (!$base_config['reg_open'] || $base_config['passport_method'] == 1) {
	ShowMessage('reg.function.closed.<br />.why.:'.$base_config['why_closereg'],-1,1);
}

$return = $return ? $return : ($forward ? $forward : '');

$username = trim($username);

#整合UC后跳转到UC其他网站登陆 PPF不再提供整合UC后的注册服务 Because UC其它应用可能做得更加全面 你可以在后台关闭注册功能，或者修改注册地址到其它应用的注册入口。
$var_forward = $rtc['passport_uc_return'] ? $rtc['passport_uc_return'] : 'forward';
if ($rtc['uc_use']) {
	$reg_url = ereg('?',$rtc['passport_reg']) ? $rtc['passport_reg'] . '&' . $var_forward . '=' . urlencode($return) : $rtc['passport_reg'] . '?' . $var_forward . '=' . urlencode($return);
	ShowMessage('',$reg_url,0,0);
}

#获得自定义字段
$selfdef_fields = GetTableFields($rtc['passport_table'],'selfdef_%');
$mydef_fields = GetTableFields($rtc['passport_table'],'mydef_%');
#获得自定义字段

if(isset($Submit)) {
	if ($base_config['reg_gdcode']) {
		Iimport('CheckNum');
		$checknum = new CheckNum();
		if (!$checknum -> Check($gdcode)) {
			ShowMessage('gdcode.error',-1,1);
		}
	}
	
	if(strlen($username)<$base_config['username_len']) {
		ShowMessage('username.too.short',-1,1);
		exit;
	}
	
	if($base_config['username_ban']) {
		$userban = explode(',',$base_config['username_ban']);
		if (is_array($userban)) {
			foreach ($userban as $k => $v) {
				if ($v && eregi($v,$username)) {
					$usershow = str_replace($v,"<font color=red>$v</font>",$username);
					ShowMessage("username.{$usershow}.connt.reg");
				}
			}
		}
	}
	
	if ($base_config['pattern_method'] == 'ereg') {
		if (!ereg($base_config['username_pattern'],$username)) {
			ShowMessage('username.not.allow',-1,1);
		}
	}else if ($base_config['pattern_method'] == 'preg') {
		if (!preg_match($base_config['username_pattern'],$username)) {
			ShowMessage('username.not.allow',-1,1);
		}
	}
	
	if(strlen($password)<$base_config['pass_len']) {
		ShowMessage('password.too.short',-1,1);
		exit;
	}
	if($password != $password2) {
		ShowMessage('two.password.not.eq',-1,1);
		exit;
	}
	
	if(!eregi('^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $email)) {
		ShowMessage('email.error',-1,1);
	}
	
	Iimport('PassPort_User');
	$passport = new PassPort_User();
	$passport -> ReSet();
	$passport -> LoadFromDBuseUniqID($username);
	if($passport->U_ID>0) {
		ShowMessage('username.used',-1,1);
		exit;
	}
	
	if ($base_config['email_check']) {
		$passport -> ReSet();
		$passport -> SetAllConfig(array('uniqueid'=>'email'));
		$passport -> LoadFromDBuseUniqID($email);
		if ($passport -> U_ID > 0) {
			ShowMessage('email.used',-1,1);
		}else {
			$passport -> SetAllConfig(array('uuniqueid'=>'username'));
		}
	}
	$base_config['passport_reg_expired'];
	
	if ($base_config['passport_reg_expired']) {
		$input['u_expired'] = $timestamp + $base_config['passport_reg_expired'] * 24 * 3600;
	}else {
		$input['u_expired'] = 0;
	}
	
	$input =array_merge((array)$input,array('username'=>$username,'password'=>$password,'email'=>$email,'regtime'=>$timestamp,'regip'=>GetIP(),'group'=>intval($base_config['default_group'])));
	$passport -> SetInsertInfo($input);
	$safearray=array('username','password','email','regtime','regip','group','u_expired');
	#添加safearray
	foreach ($selfdef_fields as $k => $v) {
		$safearray[] = $v[0];
	}
	foreach ($mydef_fields as $k => $v) {
		$safearray[] = $v[0];
	}
	if($passport -> DoRecordUser($safearray)) {
		$passport -> ReSet();
		$passport -> SetAllConfig(array('uniqueid'=>'username'));
		$passport -> LoadFromDBuseUniqID($username);
		$passport -> PassCheckRebuild();
		$passport -> PutLoginedInfo();
		#注册货币添加
		if (is_array($rtc['passport_money'])) {
			foreach ($rtc['passport_money'] as $k => $v) {
				if (intval($rtc['passport_money_start'][$v]) > 0) {
					AddMoney(intval($rtc['passport_money_start'][$v]),$passport->U_ID,$v,'reg.start.'.$money_types[$v]);
				}
			}
		}
		
		#通行证及其外部登陆
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
			require(WORK_DIR.'ppfapi/interface_login.php');
			$return = $ppf_api_gourl ? $ppf_api_gourl : $return;
		}
		#认证服务器
		
		ShowMessage('passport.reg.success',$return);
	}else {
		ShowMessage('passport.reg.faile',-1,1);
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> LanguageAssign('title','passport.reg');
	$tpl -> DisPlay('reg');
}
?>