<?php
require('ppframe.php');
if(isset($Submit)) {
	if($passport -> PassWordEnCode($oldpass) != $passport -> U['password']) {
		ShowMessage('password.error');
		exit;
	}
	if($password != $password2) {
		ShowMessage('password.not.eque');
		exit;
	}
	if(strlen($password) < 6) {
		ShowMessage('password.too.short');
		exit;
	}
	$_POST['uid'] = $passport -> U_ID;
	$passport -> SetUpdateInfo($_POST);
	$safearray = array('id','password');
	if($passport -> DoReRecord($safearray)) {
		$passport -> LoadFromDBUsePriID($passport->U_ID);
		$passport -> PutLoginedInfo();
		ShowMessage('success');
	}else {
		ShowMessage('fail');
	}
}else {
	Iimport('template');
	$tpl = new Template();
	$tpl -> LanguageAssign('title','passport.password.update');
	$tpl -> DisPlay('update_password');
}
?>