<?php
require('ppframe.php');

//if ($base_config['passport_method'] == 1 ||!$base_config['passport_chongzhi']) {
//	ShowMessage('system.forbidden',-1,1);
//}

Iimport('Lister_Passport_Order');
$lister_passport_order = new Lister_Passport_Order();
$lister_passport_order -> EnableCache(false);
$lister_passport_order -> SetWhere("`uid`='{$passport->U_ID}'");

if (in_array($state,array(1,2))) {
	if ($state == 1) {
		$lister_passport_order -> SetWhere("`state`<>2");
	}else if($state == 2){
		$lister_passport_order -> SetWhere("`state`=2");
	}
}else {
	unset($_GET['state']);
}
$lister_passport_order -> CreateWhere();

Iimport('Page');
$pagesize = 20;

$p = new Page($lister_passport_order->GetTotalRecord(),$page,$pagesize);
$p -> SetDpages();
$pagelink = $p -> GetPageLink();

$lister_passport_order -> SetLimit($p -> Limit);

$lister_passport_order = $lister_passport_order -> GetList();

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('order');

?>