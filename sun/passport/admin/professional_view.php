<?php
require('ppframe.php');
$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error');
}
#load class!
Iimport('Element_Passport_Professional');
$element_element = new Element_Passport_Professional();
$element_element ->Load($id);
if (!$element_element -> E) {
	ShowMessage('id.error');
}
if (isset($Submit)) {
	if (strlen($input['pname']) < 4) {
		ShowMessage('name.too.short');
	}
	if ($input['cid'] < 1) {
		ShowMessage('college.empty');
	}
	
	$input['discript'] = SafeHtml($input['discript']);
	$input['id'] = $id;
	
	$element_element -> SetUpdate($input);
	if ($element_element -> DoUpdate()) {
		ShowMessage('do.success','professional_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
	$E = $element_element -> E;
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('professional_view');
}
?>