<?php
require('ppframe.php');
$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error',-1);
}
if ($id == 1 && $Admin -> U_ID != 1) {
	ShowMessage('no.auth');
}

$input['uid'] = $id;

$temp_adminuser = new Exam_AdminUser();

$temp_adminuser -> ReSet();
$temp_adminuser -> LoadFromDBUsePriID($id);
if ($temp_adminuser -> U_ID<1) {
	ShowMessage('user.not.exist');
}

if (isset($Submit)) {
	//Ìí¼ÓÓÃ»§
	$input['uid'] = intval($input['uid']);
	if (empty($input['uid'])) {
		ShowMessage('lack.uid');
	}
	if ($input['uid'] == 1) {
		ShowMessage('connt.do');
	}
	
	if (!ereg('^[a-zA-Z0-9\_]{3,12}$',$input['userid'])) {
		ShowMessage('username.error');
	}
	
	if (is_array($input['priv'])) {
		$input['privkey'] = ' ' . trim(implode(' ',array_unique($input['priv']))) . ' ';
	}else {
		$input['privkey'] = '';
	}
	
	unset($input['priv']);
	
	if ($input['tikus'] && is_array($input['tikus'])) {
		$input['tikus'] = ' ' . implode(' ',$input['tikus']) . ' ';
	}else {
		$input['tikus'] = '';
	}
	
	$temp_adminuser -> SetUpdateInfo($input);
	if ($temp_adminuser -> DoReRecord(array('uid','userid','name','loginban','privkey','tikus'))) {
		ShowMessage('do.success','sys_user_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
	$view_adminuser = $temp_adminuser -> U;
	unset($temp_adminuser);
	
	foreach ($view_adminuser['privkey'] as $k => $v) {
		$checked[$v] = 'checked';
	}
	
	$authdb = PrivsScan(WORK_DIR.'admin/');
	$tiku_lister = GetTiKuLister();
	
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('sys_user_view');
}
?>