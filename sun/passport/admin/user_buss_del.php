<?php
require('ppframe.php');

$id = intval($id);
	
Iimport('Element_Passport_Buss');
	
$element_element = new Element_Passport_Buss();
if($element_element -> DoComonRemove("ids like '%,$id,%'")) {
	ShowMessage('do.success','user_buss_main.php');
}else {
	ShowMessage('do.fail');
}
?>