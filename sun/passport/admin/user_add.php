<?php
require('ppframe.php');
#获得自定义字段
$selfdef_fields = GetTableFields($rtc['passport_table'],'selfdef_%');
$mydef_fields = GetTableFields($rtc['passport_table'],'mydef_%');
#获得自定义字段

if (isset($Submit)) {
	Iimport('PassPort_User');
	if (strlen($input['username']) < 3) {
		ShowMessage('username.too.short');
	}
	if (strlen($input['password']) < 6) {
		ShowMessage('password.too.short');
	}
	$input['regtime'] = $timestamp;
	$input['regip'] = 'admin add';
	
	#
	Iimport('Lister_Passport_User');
	$lister_user = new Lister_Passport_User();
	$lister_user -> EnableCache(false);
	$lister_user -> SetWhere("`username` like '$input[username]'");
	$lister_user -> CreateWhere();
	if ($lister_user -> ExecTotalRecord() > 0) {
		ShowMessage('username.used');
	}
	#
	
	$input['group'] = intval($input['group']);
	if ($input['groups']) {
		$input['groups'] = ' '. implode(' ',$input['groups']) . ' ';
	}else {
		$input['groups'] = '';
	}
	
	if ($input['class']) {
		Iimport('Element_Passport_Class');
		$element_passport_class = new Element_Passport_Class();
		$element_passport_class -> Load($input['class']);
		if (!$element_passport_class -> E) {
			unset($input['class']);
		}else {
			$input['grade'] = $element_passport_class -> E['grade'];
		}
	}else {
		$input['class'] = 0;
	}
	
	if ($input['classes'] && is_array($input['classes'])) {
		$input['classes'] = ' ' . implode(' ',$input['classes']) . ' ';
	}else {
		$input['classes'] = '';
	}
	
	if ($input['u_expired']) {
		$input['u_expired'] = $timestamp + $input['u_expired'] * 3600 * 24;
	}else {
		$input['u_expired'] = 0;
	}
	
	$input['u_cardtype'] = intval($input['u_cardtype']);
	if ($input['u_cardid']) {
		if ($input['u_cardtype'] == 0) {
			Iimport('IdCard');
			$idcard = new IdCard($input['u_cardid']);
			if (!$idcard -> Check()) {
				ShowMessage('idcard.error');
			}
		}
		# card 重复问题！
		Iimport('Lister_Passport_User');
		$lister_lister = new Lister_Passport_User();
		$lister_lister -> SetWhere("`u_cardid`='{$input['u_cardid']}'");
		$lister_lister -> CreateWhere();
		if ($lister_lister -> ExecTotalRecord() > 0) {
			ShowMessage('idcard.repeat');
		}
		#
	}
	
	$input['u_loginban'] = 0;
	$input['u_bid'] = intval($input['u_bid']);
	$input['u_position'] = trim($input['u_position']);
	
	$temp_user = new PassPort_User();
	$temp_user -> SetInsertInfo($input);
	$safearray = array('password','safekey','email','username','regip','regtime','group','groups','grade','class','classes','u_cid','u_pid','u_expired','u_loginban','u_name','u_cardid','u_cardtype','u_namecheck','u_bid','u_position');
	foreach ($selfdef_fields as $k => $v) {
		$safearray[] = $v[0];
	}
	foreach ($mydef_fields as $k => $v) {
		$safearray[] = $v[0];
	}
	
	if($temp_user -> DoRecordUser($safearray)) {
		ShowMessage('add.user.success','user_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {

	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('user_add');
}
?>