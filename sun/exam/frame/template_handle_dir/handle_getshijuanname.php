<?php
/**
 * »ñµÃÊÔ¾íÃû
 * 
 * @param number $id ÊÔ¾íid
 */
!defined('MODULE') && exit('Forbidden');
function GetShijuanName($id) {
	if ($id<1) {
		return '';
	}
	!class_exists('Element_Exam_Shijuan') && Iimport('Element_Exam_Shijuan');
	$element_element = new Element_Exam_Shijuan();
	$element_element -> EnableCache(true);
	$element_element -> Load($id);
	return $element_element -> E['title'];
}