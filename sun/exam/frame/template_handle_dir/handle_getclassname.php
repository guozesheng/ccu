<?php
/**
 * 获得分类名称
 *
 * @param number $id
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function GetClassName($id) {
	if ($id<1) {
		return '';
	}
	!class_exists('Element_Exam_Class') && Iimport('Element_Exam_Class');
	$element_element = new Element_Exam_Class();
	$element_element -> EnableCache(true);
	$element_element -> Load($id);
	return $element_element -> E['name'];
}