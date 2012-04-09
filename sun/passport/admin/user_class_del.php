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
	ShowMessage('not.exist');
}
if($element_passport_class -> DoRemove($id)) {
	ShowMessage('do.success','user_class_main.php');
}else {
	ShowMessage('do.fail');
}