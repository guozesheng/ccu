<?php
require('ppframe.php');
//取得列表
Iimport('lister');
Iimport('page');
$array = array(
	'table' => '##__frame_module',
	'where' => 1,
	'limit' => 1,
);
$lister = new Lister($array);
$maxnum = $lister -> GetTotalRecord();
$p = new Page($maxnum,$page,20);
$p -> SetDpages();
$pagelink = $p -> GetPageLink();
$lister -> SetConfig(array('limit'=>$p->Limit));
function pf_lister(&$row) {
	if(isset($row['run'])){ 
		if($row['run']) $row['run'] = 'Y';
		else $row['run'] = 'N';
	}
	if(isset($row['business'])) {
		if($row['business']) $row['business'] = 'Y';
		else $row['business'] = 'N';
	}
	return $row;
}
$lister = $lister -> GetLister('pf_lister');
//列出表格
Iimport('template');
$tpl = new Template();
$tpl -> DisPlay('config_module_main.htm');
?>