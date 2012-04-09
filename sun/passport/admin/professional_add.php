<?php
require('ppframe.php');

if (isset($Submit)) {
	if (strlen($input['pname']) < 4) {
		ShowMessage('name.too.short');
	}
	if ($input['cid'] < 1) {
		ShowMessage('college.empty');
	}
	
	$input['discript'] = SafeHtml($input['discript']);
	Iimport('Element_Passport_Professional');
	$element_element = new Element_Passport_Professional();
	$element_element -> SetInsert($input);
	if ($element_element -> DoRecord()) {
		ShowMessage('do.success','professional_main.php');
	}else {
		ShowMessage('do.fail');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('professional_add');
}
?>