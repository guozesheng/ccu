<?php
require('ppframe.php');
$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error');
}
Iimport('Element_Passport_Buss');
$element_element = new Element_Passport_Buss();
$element_element -> Load($id);
if ($element_element -> E['id'] < 1) {
	ShowMessage('id.error');
}
$view = $element_element -> E;
if (isset($Submit)) {
	if (strlen($input['name']) < 4) {
		ShowMessage('name.too.short');
	}
	$input['istop'] = $input['istop'] ? 1 : 0;
	$input['upid'] = intval($input['upid']);
	if (!$input['istop'] && $input['upid']<1) {
		ShowMessage('upid.empty');
	}
	
	if ($input['istop']) {
		$input['upid'] = 0;
	}
	
	$input['id'] = $id;
	
	if ($input['upid'] == $input['id']) {
		ShowMessage('upid.error');
	}
	
	$element_element -> SetUpdate($input);
	
	if($element_element -> DoUpdate()) {
		if($id>0) {
			$ids = GetBussIds($id);
			$ids = $ids ? ','.$ids.',' : '';
			if ($ids) {
				$element_element -> SetUpdate(array('id'=>$id,'ids'=>$ids));
				$element_element -> DoUpdate();
			}
		}
		ShowMessage('update.success','user_buss_main.php');
	}else {
		ShowMessage('update.fail');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('user_buss_view');
}
?>