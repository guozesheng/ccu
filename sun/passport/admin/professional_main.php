<?php
require('ppframe.php');

#Äê¼¶
$grades = GetGradeS();

Iimport('Lister_Passport_Professional');
	$lister_lister = new Lister_Passport_Professional();
	$lister_lister -> SetConfig(
		array(
			'table' => '##__passport_professional a left join ##__passport_college b on a.cid=b.id',
			'els' => array(
				'a.id,a.pname,a.cid,a.pcode,a.xuezhi,b.name,b.code'
			)
		)
	);
	if ($pname) {
		if ($jq) {
			$lister_lister -> SetWhere("`pname` like '$pname'");
		}else {
			$lister_lister -> SetWhere("`pname` like '%$pname%'");
		}
	}
	if ($pcode) {
		$lister_lister -> SetWhere("`pcode`='$pcode'");
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
$tpl -> DisPlay('professional_main');
?>