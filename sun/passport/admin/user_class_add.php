<?php
require('ppframe.php');

if (isset($Submit)) {
	if (strlen($input['classname']) < 4) {
		ShowMessage('name.too.short');
	}
	if (isset($input['ctype'])) {
		if ($input['ctype'] == 0) {
			if (!$input['grade']) {
				ShowMessage('please.select.grade');
			}
			if ($input['cid'] < 1) {
				ShowMessage('please.select.college');
			}
		}else {
			
		}
	}else {
		ShowMessage('please.select.type');
	}
	
	if ($input['allowapply']) {
		$input['allowapply'] = 1;
	}else {
		$input['allowapply'] = 0;
	}
	if ($input['applysh']) {
		$input['applysh'] = 1;
	}else {
		$input['applysh'] = 0;
	}
	if ($input['apmoney']) {
		$input['apmoney'] = intval($input['apmoney']);
	}else {
		$input['apmoney'] = 0;
	}
	Iimport('Element_Passport_Class');
	$element_passport_class = new Element_Passport_Class();
	$element_passport_class -> SetInsert($input);
	if ($element_passport_class -> DoRecord()) {
		ShowMessage('do.success','user_class_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
#Äê¼¶
$grades = GetGradeS();

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_class_add');
}
?>