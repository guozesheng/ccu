<?php
require('ppframe.php');

Iimport('Lister_Passport_ClassSh');
$lister_passport_classsh = new Lister_Passport_ClassSh();
$lister_passport_classsh -> SetConfig(
	array(
		'table' => '##__passport_classsh a left join ##__passport_class b on a.classid=b.id left join '.$rtc['passport_table'].' c on a.uid=c.'.$rtc['passport_prikey'],
		'orderby' => 'time desc',
		'els' => array(
			'a.*','b.id as classid','b.classname','b.ctype','b.grade',"c.$rtc[passport_uniqueid]",
		),
	)
);

if (isset($ctype)) {
	if($ctype) $lister_passport_classsh -> SetWhere("b.`ctype`=1");
	else $lister_passport_classsh -> SetWhere("b.`ctype`=0");
}
if ($grade) {
	$lister_passport_classsh -> SetWhere("b.`grade`=$grade");
}
if ($classname) {
	if ($jq) {
		$lister_passport_classsh -> SetWhere("b.`classname` like '$classname'",'classname');
	}else {
		$lister_passport_classsh -> SetWhere("b.`classname` like '%$classname%'",'classname');
	}
}
if ($username) {
	if ($jq2) {
		$lister_passport_classsh -> SetWhere("c.`$rtc[passport_uniqueid]` like '$username'");
	}else {
		$lister_passport_classsh -> SetWhere("c.`$rtc[passport_uniqueid]` like '%$username%'");
	}
}
if (isset($sh)) {
	if ($sh == 0) {
		$lister_passport_classsh -> SetWhere("a.`sh`=0",'sh');
	}else if ($sh == 1) {
		$lister_passport_classsh -> SetWhere("a.`sh`=1",'sh');
	}else if ($sh == 2) {
		$lister_passport_classsh -> SetWhere("a.`sh`=2",'sh');
	}
}

$lister_passport_classsh -> CreateWhere();
$pagesize = 20;
Iimport('Page');
$p = new Page($lister_passport_classsh -> ExecTotalRecord(),$page,$pagesize);
$p -> SetDpages();
$pagelink = $p -> GetPageLink();
$lister_passport_classsh -> SetLimit($p->Limit);
$lister_classsh = $lister_passport_classsh -> GetList();

$grades = GetGradeS();
Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_class_apply');
?>