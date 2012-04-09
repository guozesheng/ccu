<?php
require('ppframe.php');

if ($passport -> U['class']) {
	Iimport('Element_Passport_Class');
	$element_passport_class = new Element_Passport_Class();
	$element_passport_class -> Load($passport -> U['class']);
	$user_class = $element_passport_class -> E;
}
if ($passport -> U['classes']) {
	$classes = explode(' ',trim($passport->U['classes']));
	$add_class = GetStudentClassList(1,0,'',0,0,$passport->U['classes']);
}
if ($passport -> U['group']) {
	$base_group = $passport -> U['group'];
}else {
	$base_group = 0;
}
if ($passport -> U['groups']) {
	$add_groups = explode(' ',trim($passport->U['groups']));
}

@include(ROOT.'config/usergroup_config.php');

Iimport('template');
$tpl = new Template();
$tpl -> LanguageAssign('title','passport.index');
$tpl -> DisPlay('index');
?>