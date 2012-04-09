<?php
require('ppframe.php');

$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error',-1);
}
if ($id == 1 && $Admin -> U_ID != 1) {
	ShowMessage('no.author');
}

$temp_adminuser = new Admin_User();
$temp_adminuser -> ReSet();
$temp_adminuser -> LoadFromDBUsePriID($id);
if ($temp_adminuser -> U_ID<1) {
	ShowMessage('user.not.exist');
}
if (isset($Submit)) {
	//更新用户
	#更新密码
	if ($input['password']) {
		if ($input['password'] != $input['password2']) {
			ShowMessage('2.passowrd.not.eq');
		}
	}else {
		unset($input['password']);
	}
	
	if (!$input['userid']) {
		unset($input['userid']);
	}
	
	if (is_array($input['priv'])) {
		$input['privkey'] = ' ' . trim(implode(' ',array_unique($input['priv']))) . ' ';
	}else {
		$input['privkey'] = '';
	}
	
	$input['u_discript'] = SafeHtml($input['u_discript']);
	
	unset($input['priv']);
	$temp_adminuser -> SetUpdateInfo($input);
	if ($temp_adminuser -> DoReRecord(array('userid','name','password','loginban','telphone','ceilphone','email','privkey','u_discript'))) {
		ShowMessage('update.success');
	}else {
		ShowMessage('update.fail');
	}
}else {
	$view_adminuser = $temp_adminuser -> U;
	unset($temp_adminuser);
	$authdb = PrivsScan();
	
	foreach ($view_adminuser['privkey'] as $k => $v) {
		$checked[$v] = 'checked';
	}
	
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('sys_user_view');
}
?>