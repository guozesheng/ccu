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
	ShowMessage('not.exist');
}
if($element_element -> DoRemove($id)) {
	ShowMessage('do.success','professional_main.php');
}else {
	ShowMessage('do.fail');
}