<?php
require('ppframe.php');

if (isset($Submit)) {
	//лМ╪ссц╩╖
	if (!ereg('^[a-zA-Z0-9\_]{3,12}$',$input['userid'])) {
		ShowMessage('username.error');
	}
	if (strlen($input['password'])<6) {
		ShowMessage('password.too.short');
	}
	if ($input['password'] != $input['password2']) {
		ShowMessage('2.password.not.eq');
	}
	
	if (is_array($input['priv'])) {
		$input['privkey'] = ' ' . trim(implode(' ',array_unique($input['priv']))) . ' ';
	}else {
		$input['privkey'] = '';
	}
	
	$input['u_discript'] = SafeHtml($input['u_discript']);
	
	unset($input['priv']);
	
	$temp_adminuser = new Admin_User();
	$temp_adminuser -> ReSet();
	$temp_adminuser -> SetInsertInfo($input);
	if ($temp_adminuser -> DoRecordUser(array('userid','name','password','loginban','telphone','ceilphone','email','privkey','u_discript'))) {
		ShowMessage('do.success','sys_user_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
	
	$authdb = PrivsScan();
	
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('sys_user_add');
}
?>