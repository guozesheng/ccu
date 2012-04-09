<?php
require('ppframe.php');

$file = WORK_DIR.'config/jifenchange_config.php';

@include($file);

Iimport('Element_Passport_Money');
$element_passport_money = new Element_Passport_Money();
$element_passport_money -> Load($passport ->U_ID);
$element_passport_money = $element_passport_money ->E;

if (isset($Submit)) {
	$num = intval($num);
	if (!array_key_exists($change,$_jifen_change)) {
		ShowMessage('connt.do');
	}
	if ($element_passport_money[$money_types[$_jifen_change[$change]['f']]] < $num) {
		ShowMessage('no.enough.money.connt.do');
	}
	
	if ($num < $_jifen_change[$change]['fn']) {
		ShowMessage('money.too.little');
	}
	#do change!
	
	#use change
	$why = 'monenychangeto'.GetMsg($money_types[$_jifen_change[$change]['t']]);
	$c1 = UseMoney($num,$passport->U_ID,$_jifen_change[$change]['f'],$why);
	#add tochange!
	$why = 'moneychangefrom'.GetMsg($money_types[$_jifen_change[$change]['t']]);
	$tonum = intval($num * $_jifen_change[$change]['tn'] /$_jifen_change[$change]['fn']);
	$c2 = AddMoney($tonum,$passport->U_ID,$_jifen_change[$change]['t'],$why);
	
	if ($c1 == $c2 && $c1 == 1) {
		ShowMessage('do.success','jifen.php');
	}else {
		ShowMessage('some.error.maybe');
	}
	
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> LanguageAssign('title','jifenchanghe');
	$tpl -> DisPlay('jifen_change');
}

?>