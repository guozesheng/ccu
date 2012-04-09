<?php
require('ppframe.php');
//
if (!$base_config['passport_moneycard_use']) {
	ShowMessage('system.forbidden',-1,1);
}

if (isset($do)) {
	if ($base_config['passport_moneycard_gdcode']) {
		Iimport('CheckNum');
		$checknum = new CheckNum();
		if (!$checknum -> Check($gdcode)) {
			ShowMessage('gdcode.error',-1,1);
		}
	}
	Iimport('MoneyCard');
	$moneycard = new MoneyCard();
	#需要验证卡号
	if (!$base_config['passport_moneycard_dotype']) {
		$cardno = trim($cardno);
		if (!$moneycard -> CheckCard($cardno)) {
			ShowMessage('cardno.error',-1,1);
		}
	}
	#
	$password = trim($password);
	if (!$moneycard -> CheckCard($password)) {
		ShowMessage('password.error',-1,1);
	}
	#充值
	Iimport('Element_Passport_Moneycard');
	$element_passport_moneycard = new Element_Passport_Moneycard();
	$element_passport_moneycard -> EnableCache(false);
	$element_passport_moneycard -> Load('',$password);
	if (empty($element_passport_moneycard -> E)) {
		ShowMessage('password.error.1',-1,1);
	}
	if (!$base_config['passport_moneycard_dotype']) {
		if ($cardno != $element_passport_moneycard -> E['cardno']) {
			ShowMessage('password.error.2',-1,1);
		}
	}
	if ($password != $element_passport_moneycard -> E['password']) {
		ShowMessage('password.error.3',-1,1);
	}
	if ($element_passport_moneycard -> E['state'] == 2) {
		ShowMessage('already.used',-1,1);
	}
	if ($element_passport_moneycard -> E['timeout'] && $timestamp > $element_passport_moneycard -> E['timeout']) {
		ShowMessage('already.timeout',-1,1);
	}
	if ($element_passport_moneycard -> E['mnum'] < 1) {
		ShowMessage('error.4',-1,1);
	}
	if (!in_array($element_passport_moneycard -> E['mtype'],$rtc['passport_money'])) {
		ShowMessage('error.5',-1,1);
	}
	#充值
	if(AddMoney($element_passport_moneycard -> E['mnum'],$passport -> U_ID,$element_passport_moneycard ->E['mtype'],'money_card.'.$element_passport_moneycard -> E['cardno'],true)) {
		$element_passport_moneycard -> SetUpdate(array('cardno' => $element_passport_moneycard -> E['cardno'],'state' => 2,'useruse' => $passport -> U_ID));
		$element_passport_moneycard -> DoUpdate();
		ShowMessage('do.success.<br />.'.$element_passport_moneycard ->E['mnum'].'.'.GetMoneyName($element_passport_moneycard->E['mtype']),'jifen.php',1);
	}else {
		ShowMessage('do.fail.110',-1,1);
	}
	#
}else {
	exit('Forbidden');
}