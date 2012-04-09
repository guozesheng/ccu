<?php
require('ppframe.php');

if (!$passport -> U['class']) {
	$base_need = 1;
	$base_class = GetStudentClassList(0,0,'',0,1);
}
$add_class = GetStudentClassList(1,0,'',0,1);

Iimport('template');
$tpl = new Template();
$tpl -> LanguageAssign('title','passport');
$tpl -> DisPlay('class_apply');
?>