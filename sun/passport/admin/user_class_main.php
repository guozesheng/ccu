<?php
require('ppframe.php');

#Äê¼¶
$grades = GetGradeS();

Iimport('Lister_Passport_Class');

	$lister_passport_class = new Lister_Passport_Class();
	if (isset($ctype)) {
		if($ctype) $lister_passport_class -> SetWhere("`ctype`=1");
		else $lister_passport_class -> SetWhere("`ctype`=0");
	}
	if ($grade) {
		$lister_passport_class -> SetWhere("`grade`=$grade");
	}
	if ($classname) {
		if ($jq) {
			$lister_passport_class -> SetWhere("`classname` like '$classname'",'classname');
		}else {
			$lister_passport_class -> SetWhere("`classname` like '%$classname%'",'classname');
		}
	}
	if ($cid) {
		$lister_passport_class -> SetWhere("`cid`='$cid'");
	}
	
	$lister_passport_class -> CreateWhere();
	$pagesize = 20;
	Iimport('Page');
	$p = new Page($lister_passport_class -> ExecTotalRecord(),$page,$pagesize);
	$p -> SetDpages();
	$pagelink = $p -> GetPageLink();
	$lister_passport_class -> SetLimit($p -> Limit);
	$lister_class =  $lister_passport_class -> GetList();

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_class_main');
?>