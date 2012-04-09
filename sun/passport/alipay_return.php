<?php
#alipay return file
require('../rte.php');
error_reporting(0);

Iimport('AliPay');
$alipay = new AliPay();
if ($alipay -> Alipay_Return()) {	//成功返回
	if($_GET['trade_status'] == 'TRADE_FINISHED' 
	|| $_GET['trade_status'] == 'TRADE_SUCCESS'		// 测试帐号
	) {
   		#do nothing just show
   		
   		Iimport('Element_Passport_Order','passport');
		$element_passport_order = new Element_Passport_Order();
		$element_passport_order -> Load('',$_GET['out_trade_no']);
		if (!$element_passport_order -> E) {
			ShowMessage('order.not.exist');
		}
		if ($element_passport_order -> E['state'] != 2) {
			#update order 记录异常
			$element_passport_order -> SetUpdate(array(
				$element_passport_order ->UniKey => $_GET['out_trade_no'],
					'state' => 3,
					'tool' => 'alipay',
					'payer'=> $_GET['buyer_email'],
					)
				);
			$element_passport_order -> DoUpdate();
			#not pay
//			UseMoney(-$element_passport_order->E['num'],$element_passport_order->E['uid'],$element_passport_order->E['mtype'],'buy.'.$element_passport_order->E['title']);
		} 
		#do not need to do!
		ShowMessage('pay.success',$rtc['passport_root'].'jifen.php');
	}else {
		ShowMessage('pay.fail',$rtc['passport_root'].'jifen.php');
	}
}else {
	ShowMessage('pay.fail',$rtc['passport_root'].'jifen.php');
}
?>