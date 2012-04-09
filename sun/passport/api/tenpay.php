<?php
require('../../rte.php');
error_reporting(0);

Iimport('TenPay');
$tenpay = new TenPay();
$notifycheck = $tenpay->Tenpay_Notify();
if ($notifycheck == 1) {
	#成功 业务逻辑
	Iimport('Element_Passport_Order','passport');
	$element_passport_order = new Element_Passport_Order();
	$element_passport_order -> Load('',$tenpay -> TransactionId);
	if (!$element_passport_order->E) {
		$tenpay -> ShowExitMsg(PrepareMsg('order.not.exist'));
	}
	if ($element_passport_order -> E['state'] != 2) {
		#update order
		$element_passport_order -> SetUpdate(array(
			$element_passport_order ->UniKey => $tenpay -> TransactionId,
			'state' => 2,
			'tool' => 'tenpay',
			'payer'=> '',
			'paytime' => $timestamp,
			)
		);
		$element_passport_order -> DoUpdate();
		#pay
		AddMoney($element_passport_order->E['num'],$element_passport_order->E['uid'],$element_passport_order->E['mtype'],$element_passport_order->E['title'].'orderno:'.$tenpay -> TransactionId);
	}
	#
	$tenpay -> ShowExitMsg(PrepareMsg('pay.success'));
}else if ($notifycheck == -1) {
	#md5校验错误
	$tenpay -> ShowExitMsg(PrepareMsg('md5check.fail'));
}else if ($notifycheck == -2) {
	#错误的商户号
	$tenpay -> ShowExitMsg(PrepareMsg('spid.error'));
}else if ($notifycheck == -3) {
	#支付失败
	$tenpay -> ShowExitMsg(PrepareMsg('pay.fail'));
}else {
	#未知错误!
	$tenpay -> ShowExitMsg(PrepareMsg('pay.fail'));
}

function PrepareMsg($msg) {
	return $GLOBALS['rtc']['passport_root'] .'tenpayshow.php?msg='.urlencode(GetMsg($msg));
}
?>