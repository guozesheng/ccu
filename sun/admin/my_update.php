<?php
require('ppframe.php');

if (isset($Submit)) {
	
	if ($input['password']) {
		if ($Admin -> PassWordEnCode($input['old_password']) != $Admin -> U[$Admin->U_PasswordKey]) {
			ShowMessage('old.password.error');
		}
		if ($input['password'] != $input['password2']) {
			ShowMessage('2.password.not.eq');
		}
	}else {
		unset($input['password']);
	}
	
	$Admin -> SetUpdateInfo($input);
	
	if($Admin -> DoReRecord(array('name','password','telphone','ceilphone','email'))) {
		ShowMessage('update.success');
	}else {
		ShowMessage('update.fail');
	}
}else {
	$view_adminuser = $Admin -> U;
	unset($view_adminuser['passowrd']);
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('my_update');
}

?>