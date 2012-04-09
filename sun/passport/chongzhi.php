<?php
require('ppframe.php');
//
if (!$rtc['passport_chongzhi']) {
	ShowMessage('system.forbidden',-1,1);
}

$rtc['passport_chongzhi_min'] = intval($rtc['passport_chongzhi_min']);
$rtc['passport_chongzhi_min'] = $rtc['passport_chongzhi_min'] ? $rtc['passport_chongzhi_min'] : 5;
$rtc['passport_moneyhuilv'] = intval($rtc['passport_moneyhuilv']);
$rtc['passport_moneyhuilv'] = $rtc['passport_moneyhuilv'] ? $rtc['passport_moneyhuilv'] : 1;

if (isset($Submit)) {
	
	$chongzhi_num = intval($chongzhi_num);
//	$chongzhi_num = 0.01;	//debug use
	if (!$chongzhi_num) {
		ShowMessage('please.give.chognzhi.num');
	}
	
	if ($chongzhi_num < $rtc['passport_chongzhi_min']) {
		ShowMessage('chognzhi.num.too.little');
	}
	
	Iimport('Element_Passport_Order');
	$element_passport_order = new Element_Passport_Order();
	$element_passport_order -> SetInsert(
		array(
			'uid' => $passport->U_ID,
			'orderno' => $order_no = str_pad($passport -> U_ID,10,0,STR_PAD_LEFT).GetMyDate('Ymd').$timestamp,
			'mtype' => $rtc['passport_onlinemoney'],
			'num' => intval($rtc['passport_moneyhuilv'] * $chongzhi_num) ,
			'fee' => $chongzhi_num,
			'time' => $timestamp,
			'state' => 0,
			'descrip' => GetMsg($rtc['site_name'].'.'.$money_types[$rtc['passport_onlinemoney']].'.online.pay'),
			'title' => $rtc['site_name'] . GetMsg($money_types[$rtc['passport_onlinemoney']].'.online.pay'),
		)
	);
	
	if ($element_passport_order -> DoRecord()) {
		$insert_id = $element_passport_order -> E_InsertId;
		$element_passport_order -> SetUpdate(array('id'=> $insert_id,'orderno'=>str_pad($passport -> U_ID,10,0,STR_PAD_LEFT).GetMyDate('Ymd').str_pad($insert_id,10,0,STR_PAD_LEFT)));
		$element_passport_order -> DoUpdate();
		if ($rtc['passport_onlinepay']) {
			//跳转在线充值
			ShowMessage('','orderpay.php?id='.$insert_id,0,0);
		}else {
			//跳转订单查看
			ShowMessage('','order.php',0,0);
		}
	}else {
		ShowMessage('error');
	}
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> LanguageAssign('title','jifen.chognzhi');
	$tpl -> DisPlay('chongzhi');
}