<?php
!defined('MODULE') && exit('Forbidden');
function GetProfessionalElement($id,$n='pname') {
	Iimport('Element_Passport_Professional');
	$element_element = new Element_Passport_Professional();
	$element_element -> EnableCache(true);
	$element_element -> Load($id);
	if ($n == 'pname') {
		return $element_element -> E['pname'];
	}else if($element_element->E[$n]){
		return $element_element -> E[$n];
	}else {
		return '';
	}
}