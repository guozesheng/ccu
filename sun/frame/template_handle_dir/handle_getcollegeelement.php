<?php
!defined('MODULE') && exit('Forbidden');
function GetCollegeElement($id,$n='name') {
	Iimport('Element_Passport_College');
	$element_element = new Element_Passport_College();
	$element_element -> EnableCache(true);
	$element_element -> Load($id);
	if ($n == 'name') {
		return $element_element -> E['name'];
	}else if($element_element->E[$n]){
		return $element_element -> E[$n];
	}else {
		return '';
	}
}