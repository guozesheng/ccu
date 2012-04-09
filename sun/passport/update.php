<?php
require('ppframe.php');

if (!$base_config['passport_modify'] || $base_config['passport_method'] == 1) {
	ShowMessage('system.forbidden',-1,1);
}

#获得自定义字段
$selfdef_fields = GetTableFields($rtc['passport_table'],'selfdef_%');
$mydef_fields = GetTableFields($rtc['passport_table'],'mydef_%');
#获得自定义字段

if(isset($Submit)) {
//	$_POST['uid'] = $passport -> U_ID;
	if(!eregi('^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $input['email'])) {
		ShowMessage('email.error');
	}
	
	if ($passport -> U['u_namecheck'] == 1) {
		unset($input['u_cardid']);
		unset($input['u_cardtype']);
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
		$lister_lister -> SetWhere("`uid`<>'{$passport->U_ID}'");
		$lister_lister -> CreateWhere();
		if ($lister_lister -> ExecTotalRecord() > 0) {
			ShowMessage('idcard.repeat');
		}
		#
	}
	$passport -> SetUpdateInfo($input);
	
	$safearray = array(
		'email'=>'email',
		'u_name'=>'u_name',
		'u_cardid'=>'u_cardid',
		'u_cardtype'=>'u_cardtype',
	);
		
	if ($passport -> U['u_namecheck'] == 1) {
		unset($safearray['u_cardid']);
		unset($safearray['u_cardtype']);
	}
	
	foreach ($selfdef_fields as $k => $v) {
		if (ereg('_no_',$v[0]) && $passport->U[$v[0]]) {
			continue;
		}else {
			$safearray[] = $v[0];
		}
	}
	foreach ($mydef_fields as $k => $v) {
		if (ereg('_no_',$v[0]) && $passport->U[$v[0]]) {
			continue;
		}else {
			$safearray[] = $v[0];
		}
	}
	
	if($passport -> DoReRecord($safearray)) {
		ShowMessage('do.success');
	}else {
		ShowMessage('do.fail');
	}
}else {
	Iimport('template');
	$tpl = new Template();
	$tpl -> LanguageAssign('title','passport.update');
	$tpl -> DisPlay('update');
}
?>