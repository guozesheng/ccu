<?php
#alipay �ӿ��ļ�
require('../../rte.php');
error_reporting(0);

Iimport('AliPay');
$alipay = new AliPay();
if ($alipay -> Alipay_Notify()) {	//�ɹ�����
	
	if($_POST['trade_status'] == 'TRADE_FINISHED' 
	|| $_POST['trade_status'] == 'TRADE_SUCCESS' 	//�����ʺ�
	) {
   		//����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����
		
		echo 'success';
		
		#do order!
		
		Iimport('Element_Passport_Order','passport');
		$element_passport_order = new Element_Passport_Order();
		$element_passport_order -> Load('',$_POST['out_trade_no']);
		if (!$element_passport_order->E) {
			exit;
		}
		if ($element_passport_order -> E['state'] != 2) {
			#update order
			$element_passport_order -> SetUpdate(array(
				$element_passport_order ->UniKey => $_POST['out_trade_no'],
					'state' => 2,
					'tool' => 'alipay',
					'payer'=> $_POST['buyer_email'],
					'paytime' => $timestamp,
					)
				);
			$element_passport_order -> DoUpdate();
				#pay
			AddMoney($element_passport_order->E['num'],$element_passport_order->E['uid'],$element_passport_order->E['mtype'],$element_passport_order->E['title'].' orderID:'.$_POST['out_trade_no']);
		}
	}else {
		echo 'fail';
	}
}else {
	echo 'fail';
}
?>