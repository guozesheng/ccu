<?php
require('ppframe.php');

#Äê¼¶
$grades = GetGradeS();

Iimport('Lister_Passport_College');

	$lister_lister = new Lister_Passport_College();
	if ($name) {
		if ($jq) {
			$lister_lister -> SetWhere("`name` like '$name'");
		}else {
			$lister_lister -> SetWhere("`name` like '%$name%'");
		}
	}
	if ($code) {
		$lister_lister -> SetWhere("`code`='$code'");
	}
	$lister_lister -> CreateWhere();
	$pagesize = 20;
	Iimport('Page');
	$p = new Page($lister_lister -> ExecTotalRecord(),$page,$pagesize);
	$p -> SetDpages();
	$pagelink = $p -> GetPageLink();
	$lister_lister -> SetLimit($p -> Limit);
	$lister_lister_lister =  $lister_lister -> GetList();

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('college_main');
?>