<?php

/**
 * �������Ŀչʾ
 *
 * @param number $k	key
 * @param array $array ��������Array
 * @param string $tag ��tag
 * @param string $tpl ģ��С�ļ�
 */
!defined('MODULE') && exit('Forbidden');
function GetExamShitiView($k,$array,$tag,$temp) {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> Assign('_f_k',$k);
	$tpl -> Assign('_f_a',$array);
	$tpl -> Assign('_f_t',$tag);
	return $tpl -> Parse($temp);
}