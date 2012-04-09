<?php
require('ppframe.php');
if (!$rtc['passport_onlinepay']) {
	ShowMessage('onlinepay.close');
}
$id = intval($id);
if ($id<1) {
	ShowMessage('id.error');
}
Iimport('Element_Passport_Order');
$element_passport_order = new Element_Passport_Order();
$element_passport_order -> SetCacheState(false);
$element_passport_order -> Load($id);

if (empty($element_passport_order -> E)) {
	ShowMessage('order.error');
}
if ($element_passport_order -> E['state'] >= 2) {
	ShowMessage('order.already.pay');
}

#在线支付筛选
if (!$rtc['olpay_info']['alipay']['use'] || !$rtc['olpay_info']['alipay']['username'] || !$rtc['olpay_info']['alipay']['partnerid'] || !$rtc['olpay_info']['alipay']['key']) {
	unset($rtc['olpay_info']['alipay']);
}
if (!$rtc['olpay_info']['tenpay']['use'] ||!$rtc['olpay_info']['tenpay']['spid'] || !$rtc['olpay_info']['tenpay']['spkey']) {
	unset($rtc['olpay_info']['tenpay']);
}
if (!$rtc['olpay_info']['paypal']['use'] || !$rtc['olpay_info']['paypal']['username'] || !$rtc['olpay_info']['paypal']['code']) {
	unset($rtc['olpay_info']['paypal']);
}
if (!$rtc['olpay_info']['99bill']['use'] || !$rtc['olpay_info']['99bill']['username'] || !$rtc['olpay_info']['99bill']['code']) {
	unset($rtc['olpay_info']['99bill']);
}

if (count($rtc['olpay_info']) < 1) {
	ShowMessage('olpay.info.error','order.php');
}
if (!key_exists($paytool,$rtc['olpay_info'])) {
	$paytool = '';
}
if (count($rtc['olpay_info']) == 1) {
	$paytool = array_shift(array_keys($rtc['olpay_info']));
}
$order = $element_passport_order -> E;
if ($paytool) {
	#直接跳转
	switch ($paytool) {
		case 'tenpay':
		#query
		Iimport('TenPay');
		$tenpay = new TenPay();
		$order_no = $tenpay->SpId.GetMyDate('Ymd').str_pad($id,10,0,STR_PAD_LEFT);
		#update order_no
		$element_passport_order -> SetUpdate(array('id'=>$id,'orderno'=>$order_no));
		$element_passport_order -> DoUpdate();
		#
		$tenpay -> SetInfo(
			array(
				'returnurl' =>$rtc['passport_root'].'api/tenpay.php',// urlencode($base_config['passport_root'].'tenpay.php'),
				'payfee' => $order['fee']*100,
				'desc' => $order['title'],
				'trid' => $order_no,	//订单号
				'billno' => $id,	//内部订单号
				'attach' => 'my_magic_string',
			)
		);
		
		$pay_request = $tenpay -> CreatePayRequest();
		break;
		case 'alipay':
		Iimport('AliPay');
//		if ($order['state'] == 0) {
//			$order_no = str_pad($order['uid'],10,0,STR_PAD_LEFT).GetMyDate('Ymd').$timestamp.$id;
//			#update order_no
//			$element_passport_order -> SetUpdate(array('id'=>$id,'orderno'=>$order_no,'state'=>1));
//			$element_passport_order -> DoUpdate();
//		}else {
			$order_no = $order['orderno'];
//		}
		
		#
		$alipay = new AliPay();
		$alipay -> SetParams(
			array(
				'subject'=>$order['title'],
				'body' => $order['descrip'],
				'out_trade_no' => $order_no,
				'total_fee' => $order['fee'],
			)
		);
		$pay_request = $alipay -> CreateUrl();
		break;
		case '99bill':
		die('developing');
		break;
		case 'paypal':
		die('developing');
		break;
		default:
		ShowMessage('unknow.pay.tool');
		break;
	}
	
	if ($pay_request) {
		header('Location:'.$pay_request);
		exit;
	}else {
		ShowMessage('create.pay.request.fail');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('orderpay');
}
?>