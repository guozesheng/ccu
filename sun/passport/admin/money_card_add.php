<?php
require('ppframe.php');

if (isset($Submit)) {
	#数量
	$mnum = intval($mnum);
	if ($mnum < 1) {
		ShowMessage('please.give.mnum');
	}
	#货币种类
	if (!in_array($mtype,$rtc['passport_money'])) {
		ShowMessage('money.error');
	}
	#生成数量
	$num = intval($num);
	if ($num < 1) {
		$num = 10;
	}
	#过期时间
	$timeout = $timeout * 30 * 24 * 3600;
	if (CreateMoneyCards($mnum,$mtype,$num,$timeout)) {
		ShowMessage('create.success','money_card_main.php');
	}else {
		ShowMessage('create.fail');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('money_card_add');
}
?>