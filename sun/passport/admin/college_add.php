<?php
require('ppframe.php');

if (isset($Submit)) {
	if (strlen($input['name']) < 4) {
		ShowMessage('name.too.short');
	}
	
	$input['discript'] = SafeHtml($input['discript']);
	Iimport('Element_Passport_College');
	$element_element = new Element_Passport_College();
	$element_element -> SetInsert($input);
	if ($element_element -> DoRecord()) {
		ShowMessage('do.success','college_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('college_add');
}
?>