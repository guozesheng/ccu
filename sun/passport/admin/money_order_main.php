<?php
require('ppframe.php');

#
Iimport('Lister_Passport_Order');
$list_passport_order = new Lister_Passport_Order();

if ($state) {
	if ($state == 1) {
		$list_passport_order -> SetWhere("(`state`=0 or `state`=1)");
	}else {
		$list_passport_order -> SetWhere("`state`=$state");
	}
}
if ($orderno) {
	if ($jq) {
		$list_passport_order -> SetWhere("`orderno` like '$orderno'");
	}else {
		$list_passport_order -> SetWhere("`orderno` like '%$orderno%'");
	}
}
$list_passport_order -> CreateWhere();
$maxnum = $list_passport_order -> ExecTotalRecord();
Iimport('Page');

$pagesize = 10;

$p = new Page($maxnum,$page,$pagesize);

$p -> SetDpages();
$pagelink = $p -> GetPageLink();

$list_passport_order -> SetLimit($p -> Limit);

$list_passport_order = $list_passport_order -> GetList();

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('money_order_main');

?>