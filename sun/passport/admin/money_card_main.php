<?php
require('ppframe.php');
Iimport('Lister_Passport_Moneycard');

$lister_passport_moneycard = new Lister_Passport_Moneycard();
if ($cardno) {
	$lister_passport_moneycard -> SetWhere("`cardno`='$cardno'");
}
if ($password) {
	$lister_passport_moneycard -> SetWhere("`password`='$password'");
}
if ($mtype) {
	$lister_passport_moneycard -> SetWhere("`mtype`='$mtype'");
}
if ($mnum) {
	$lister_passport_moneycard -> SetWhere("`mnum`='$mnum'");
}
$state = intval($state);
if (in_array($state,array(0,1,2))) {
	$lister_passport_moneycard -> SetWhere("`state`='$state'");
}

$lister_passport_moneycard -> CreateWhere();

$pagesize = 10;
Iimport('Page');
$p = new Page($lister_passport_moneycard -> ExecTotalRecord(),$page,$pagesize);
$p -> SetDpages();
$pagelink = $p -> GetPageLink();
$lister_passport_moneycard -> SetLimit($p -> Limit);
$lister_moneycard =  $lister_passport_moneycard -> GetList();

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('money_card_main');
?>