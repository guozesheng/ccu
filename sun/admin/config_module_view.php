<?php
require('ppframe.php');
$id = intval($id);
if($id < 1) {
	ShowMsg('error id',-1);
	exit;
}
if(in_array($_POST['key'],array('config','temp','cache','admin','template','project'))) {
	ShowMsg('protected',-1);
	exit;
}
Iimport('element');
$safearray = array('id','key','name','run','author','lastmodify','business','adminroot','adminmenu','adminmenuajax','sysmodule');
$array = array(
	'table' => '##__frame_module',
	'prikey' => 'id',
	'unikey' => 'key',
	'auto' => true,
	'usecase' => true,
	'safearray' => $safearray,
);
if(isset($Submit)) {
	$El = new Element($array);
	$El -> SetUpdate($_POST);
	if($El -> DoUpdate()) {
		$El -> LoadFromDb($id);
		ShowMsg('success',-1);
		exit;
	}else {
		ShowMsg('failed',-1);
		exit;
	}
}else {
	$El = new Element($array);
	$El -> Load($id);
	Iimport('template');
	$tpl = new Template();
	$tpl -> Assign('title','View/Modify Module');
	$tpl -> Assign('element',$El->Elements);
	$tpl -> DisPlay('config_module_view.htm');
}
?>