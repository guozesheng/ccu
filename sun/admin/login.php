<?php
require(dirname(__FILE__)."/../rte.php");
define('MODULE','admin');
define('WORK_DIR',ROOT.MODULE."/");
Iimport('Admin_User');
$Admin = new Admin_User();
if(isset($Submit)) {
	
	if ($rtc['admin_gdcheck']) {
		Iimport('CheckNum');
		$checknum = new CheckNum();
		if (!$checknum -> Check($gdcode)) {
			ShowMessage('gdcode.error');
		}
	}
	
	if($Admin->CheckUserUniqID($username,$password)) {
		if ($Admin ->U['loginban']) {
			ShowMessage('login.forbidden');
		}
		$Admin -> PassCheckRebuild();
		$Admin -> PutLoginedInfo();
		!$gurl && $gurl = './';
		ShowMessage('login.success',$gurl,0,0);
		exit;
	}else {
		ShowMessage('login.fail');
	}
}else {
	Iimport('template');
	$tp = new Template();
	$tp -> DisPlay('login');
}
?>