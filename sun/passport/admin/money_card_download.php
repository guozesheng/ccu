<?php
require('ppframe.php');

if (isset($Submit)) {
	Iimport('Lister_Passport_Moneycard');
	$lister_passport_moneycard = new Lister_Passport_Moneycard();
	if ($mtype) {
		$lister_passport_moneycard -> SetWhere("`mtype`='$mtype'");
	}
	if ($mnum) {
		$lister_passport_moneycard -> SetWhere("`mnum`='$mnum'");
	}
	if (in_array($state,array(0,1,2))) {
		$lister_passport_moneycard -> SetWhere("`state`=$state");
	}
	if ($date) {
		$lister_passport_moneycard -> SetWhere("`date`='$date'");
	}
	$lister_passport_moneycard -> CreateWhere();
	if($lister_passport_moneycard -> ExecTotalRecord() < 1) {
		ShowMessage('no.moneycard');
	}
	if ($record) {	//±ê¼Ç×´Ì¬
		if ($state != 0) {
			ShowMessage('connt.do');
		}
		Iimport('Element_Passport_Moneycard');
		$element_passport_moneycard = new Element_Passport_Moneycard();
		$element_passport_moneycard -> SetUpdate(array('state'=>1));
		if($element_passport_moneycard -> DoCommonUpdate($lister_passport_moneycard ->Where)) {
			ShowMessage('do.success','money_card_main.php');
		}else {
			ShowMessage('do.fail');
		}
	}else {		//ÏÂÔØ
		$lister_passport_moneycard -> GetList();
		$filename = 'moneycard_download.txt';
		header("Content-type: application/octet-stream"); 
		header('Content-disposition: attachment; filename='.$filename);
		
		echo 'CardNO'."\t".'Password'."\t".'Moneytype'."\t".'Num'."\t".'CreateTime'."\t".'TimeOut';
		echo "\r\n";
		foreach ($lister_passport_moneycard -> Larray as $k => $v) {
			echo $v['cardno'] . "\t";
			echo $v['password'] . "\t";
			echo GetMoneyName($v['mtype']) . "\t";
			echo $v['mnum'] . "\t";
			echo GetMyDate('Ymd H:i:s',$v['createtime'])."\t";
			echo $v['timeout'] ? GetMyDate('Ymd H:i:s',$v['timeout']) : 'Never';
			echo "\r\n";
		}
	}
	
}else {
	$date = GetMyDate('Ymd');
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('money_card_download');
}
?>