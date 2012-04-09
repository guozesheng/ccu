<?php
!defined('MODULE') && exit('Forbidden');
function SequencePrint($array,$temp='') {
	!$temp && $temp = 'sequenceprint_template_shijuanclass';
	!class_exists('Template') && Iimport('Template');
	$tpl = new Template();
	$tpl -> Assign('__fun_lister_sp',$array);
	return $tpl -> Parse($temp);
}