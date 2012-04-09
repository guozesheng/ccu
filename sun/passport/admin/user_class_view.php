<?php
require('ppframe.php');
$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error');
}
#load class!
Iimport('Element_Passport_Class');
$element_passport_class = new Element_Passport_Class();
$element_passport_class ->Load($id);
if (!$element_passport_class -> E) {
	ShowMessage('id.error');
}
if (isset($Submit)) {

	if (!CheckPriv('','user_class_edit')) {
		ShowMessage('no.auth');
	}
	
	if (strlen($input['classname']) < 4) {
		ShowMessage('name.too.short');
	}
	
	if ($element_passport_class -> E['ctype'] == 0) {
		if (!$input['grade']) {
			ShowMessage('please.select.grade');
		}
		if ($input['cid'] < 1) {
			ShowMessage('please.select.college');
		}
	}else {

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
	$input['id'] = $id;
	
	$element_passport_class -> SetUpdate($input);
	if ($element_passport_class -> DoUpdate()) {
		ShowMessage('do.success','user_class_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
#Äê¼¶
$grades = GetGradeS();

$E = $element_passport_class -> E;
Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_class_view');
}
?>