<?php
require('ppframe.php');
if(isset($Submit)) {
	if(in_array($_POST['key'],array('config','temp','cache','admin','template','project'))) {
		ShowMsg('protected',-1);
		exit;
	}
	Iimport('element');
	$safearray = array('key','name','run','author','lastmodify','business','adminroot','adminmenu','adminmenuajax','sysmodule');
	$array = array(
		'table' => '##__frame_module',
		'prikey' => 'id',
		'unikey' => 'key',
		'auto' => true,
		'usecase' => true,
		'safearray' => $safearray,
	);
	$El = new Element($array);
	$El -> SetInsert($_POST);
	if($El->DoRecord()) {
		ShowMsg('success',-1);
		exit;
	}else {
		ShowMsg('fail',-1);
		exit;
	}
}else {
	Iimport('template');
	$tp = new Template();
	$tp -> DisPlay('config_module_add.htm');
}
?>