<?php
require('ppframe.php');
$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error');
}
#load class!
Iimport('Element_Passport_College');
$element_element = new Element_Passport_College();
$element_element ->Load($id);
if (!$element_element -> E) {
	ShowMessage('id.error');
}
if (isset($Submit)) {
	if (strlen($input['name']) < 4) {
		ShowMessage('name.too.short');
	}
	
	$input['discript'] = SafeHtml($input['discript']);
	$input['id'] = $id;
	
	$element_element -> SetUpdate($input);
	if ($element_element -> DoUpdate()) {
		ShowMessage('do.success','college_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
	$E = $element_element -> E;
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('college_view');
}
?>