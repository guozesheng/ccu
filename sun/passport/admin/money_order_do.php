<?php
require('ppframe.php');

$id = intval($id);
if ($id<1) {
	ShowMessage('id.error');
}

Iimport('Element_Passport_Order');

$element_passport_order = new Element_Passport_Order();
$element_passport_order ->Load($id);

if (isset($Submit)) {
	if ($element_passport_order->E['state'] == 2) {
		ShowMessage('order.already.chongzhi.connt.do');
	}
	if (strlen($bz) < 4) {
		ShowMessage('please.give.beizhu');
	}
	#do ³äÖµ
	$element_passport_order -> SetUpdate(array(
		'id'=>$id,
		'state'=>2,
		'tool'=>'admin.'.$Admin->U_ID,
		'paytime' => $timestamp,
	));
	if ($element_passport_order -> DoUpdate()) {
		if(AddMoney($element_passport_order->E['num'],$element_passport_order->E['uid'],$element_passport_order->E['mtype'],$bz) == 1){
			ShowMessage('do.success','money_order_main.php');
		}else {
			ShowMessage('do.fail');
		}
	}else {
		ShowMessage('error');
	}
}else {
	$view = $element_passport_order -> E;
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('money_order_do');
}
?>