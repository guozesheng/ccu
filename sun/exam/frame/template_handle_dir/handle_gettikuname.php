<?php
/**
 * 从题库id获得题库名称
 *
 * @param number $id
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function GetTikuName($id) {
	if ($id<1) {
		return '';
	}
	!class_exists('Element_Exam_Tiku') && Iimport('Element_Exam_Tiku');
	$element_element = new Element_Exam_Tiku();
	$element_element -> EnableCache(true);
	$element_element -> Load($id);
	return $element_element -> E['name'];
}