<?php
require('ppframe.php');
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
	
	Iimport('Element_Passport_Buss');
	
	$element_element = new Element_Passport_Buss();
	
	$element_element -> SetInsert($input);
	
	if($element_element -> DoRecord()) {
		$id = $element_element -> E_InsertId;
		if($id>0) {
			$ids = GetBussIds($id);
			$ids = $ids ? ','.$ids.',' : '';
			if ($ids) {
				$element_element -> SetUpdate(array('id'=>$id,'ids'=>$ids));
				$element_element -> DoUpdate();
			}
		}
		ShowMessage('add.success','user_buss_main.php');
	}else {
		ShowMessage('add.fail');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('user_buss_add');
}
?>