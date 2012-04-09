<?php
require('ppframe.php');
if (isset($Submit)) {
	//пч╦деепР
	Iimport('Element_Passport_Buss');
	$element_element = new Element_Passport_Buss();
	if (is_array($input)) {
		foreach ($input as $k => $v) {
			$k = intval($k);
			if ($k > 0) {
				$element_element -> SetUpdate(array('id' => $k ,'orderkey' => intval($v)));
				$element_element -> DoUpdate();
			}
		}
	}
	ShowMessage('do.success','user_buss_main.php');
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('user_buss_main');
}
?>