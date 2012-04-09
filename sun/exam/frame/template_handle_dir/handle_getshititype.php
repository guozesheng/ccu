<?php
/**
 * 使用试题试题题型id获得试题的题型的实际名称。考虑语言包
 *
 * @param number $t
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function GetShitiType($t) {
	global $tixing_db;
	return GetMsg($tixing_db[$t]);
}