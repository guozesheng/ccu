<?php
/**
 * ʹ��������������id�����������͵�ʵ�����ơ��������԰�
 *
 * @param number $t
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function GetShitiType($t) {
	global $tixing_db;
	return GetMsg($tixing_db[$t]);
}