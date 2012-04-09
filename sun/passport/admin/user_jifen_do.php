<?php
require('ppframe.php');

!isset($mtype) && $mtype = 0;

if (isset($Submit)) {
	if (!in_array($mtype,$rtc['passport_money'])) {
		ShowMessage('money.type.error');
	}
	$uid = intval($uid);
	if (!$uid && $username) {
		Iimport('PassPort_User');
		$passport_user = new PassPort_User();
		$passport_user -> ReSet();
		$passport_user -> LoadFromDBuseUniqID($username);
		$uid = $passport_user -> U_ID;
	}
	if (empty($uid)) {
		ShowMessage('uid.empty');
	}
	$num = intval($num);
	if (empty($num)) {
		ShowMessage('money.num.empty');
	}
	if (strlen($bz) < 4) {
		ShowMessage('beizhu.empty');
	}
	
	if(UseMoney(-$num,$uid,$mtype,$bz) == 1) {
		ShowMessage('do.success');
	}else {
		ShowMessage('do.fail');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('user_jifen_do');
}