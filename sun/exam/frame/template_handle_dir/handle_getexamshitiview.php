<?php

/**
 * 获得子题目展示
 *
 * @param number $k	key
 * @param array $array 试题数据Array
 * @param string $tag 表单tag
 * @param string $tpl 模板小文件
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