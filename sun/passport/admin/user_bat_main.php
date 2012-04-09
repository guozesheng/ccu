<?php
require('ppframe.php');
#获得自定义字段
$selfdef_fields = GetTableFields($rtc['passport_table'],'selfdef_%');
$mydef_fields = GetTableFields($rtc['passport_table'],'mydef_%');
#获得自定义字段

if (isset($Submit)) {
	Iimport('Lister_Passport_User');
	$lister_lister = new Lister_Passport_User();
	if ($input['username']) {
		$lister_lister -> SetWhere("username like '%$input[username]%'");
	}
	if (isset($input['u_namecheck'])) {
		if ($input['u_namecheck']) {
			$lister_lister -> SetWhere("u_namecheck=1");
		}else {
			$lister_lister -> SetWhere("u_namecheck=0");
		}
	}
	if ($input['u_expired']) {
		$lister_lister -> SetWhere("u_expired>0 AND u_expired<$timestamp");
	}
	if (isset($input['group'])) {
		$lister_lister -> SetWhere("`group`='$input[group]'");
	}
	if ($input['u_lastlogin']) {
		$u_lastlogin = $input['u_lastlogin'] * 24 * 3600;
		$lister_lister ->SetWhere("{$timestamp}-u_lastlogin>$u_lastlogin");
	}
	if ($input['u_cid']) {
		$lister_lister -> SetWhere("u_cid='$input[u_cid]'");
	}
	if ($input['u_pid']) {
		$lister_lister -> SetWhere("u_pid='$input[u_pid]'");
	}
	if ($input['u_bid']) {	//限制部门
		$u_bids = GetBussSunIds($input['u_bid']);
		if ($u_bids) {
			$lister_user -> SetWhere("`u_bid` in ($u_bids)");
		}
	}
	if ($input['grade']) {
		$lister_lister -> SetWhere("grade='$input[grade]'");
	}
	if ($input['class']) {
		$lister_lister -> SetWhere("`class`='$input[class]'");
	}
	foreach ($selfdef_fields as $k => $v) {
		if ($input[$v[0]]) {
			$lister_lister -> SetWhere("$v[0]='{$input[$v[0]]}'");
		}
	}
	foreach ($mydef_fields as $k => $v) {
		if ($input[$v[0]]) {
			$lister_lister -> SetWhere("$v[0]='{$input[$v[0]]}'");
		}
	}
	$lister_lister -> CreateWhere();
	if ($only_test) {
		echo 'Affected:<br />';
		echo $lister_lister -> ExecTotalRecord();
		echo 'User';
		exit;
	}
	if ($do == 2) {	#硬删除
		
		if (!CheckPriv('','user_bat_do2')) {
			echo 'no.auth';
			exit;
		}
		
		Iimport('Element');
		$array = array(
			'table' => $GLOBALS['rtc']['passport_table'] ? $GLOBALS['rtc']['passport_table'] : '##__passport',
			'prikey' => 'uid',
			'safearray' => array(
				'uid',
				'u_loginban',
			),
		);
		$element_element = new Element($array);
		if($element_element -> DoComonRemove($lister_lister -> Where)) {
			echo '<font color=green>SUCCESS</font>';
			exit;
		}else {
			echo '<font color=red>FAIL</font>';
			exit;
		}
	}else if($do == 1){	#限制登陆
		if (!CheckPriv('','user_bat_do1')) {
			echo 'no.auth';
			exit;
		}
		Iimport('Element');
		$array = array(
			'table' => $GLOBALS['rtc']['passport_table'] ? $GLOBALS['rtc']['passport_table'] : '##__passport',
			'prikey' => 'uid',
			'safearray' => array(
				'uid',
				'u_loginban',
			),
		);
		$element_element = new Element($array);
		$element_element -> SetUpdate(array('u_loginban'=>1));
		if($element_element -> DoCommonUpdate($lister_lister -> Where)){
			echo '<font color=green>SUCCESS</font>';
			exit;
		}else {
			echo '<font color=red>FAIL</font>';
			exit;
		}
	}else {
		echo 'Unknow Action';
	}
}else {
	$grades = GetGradeS();
	
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('user_bat_main');
}
?>