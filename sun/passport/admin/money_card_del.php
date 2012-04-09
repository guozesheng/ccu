<?php
require('ppframe.php');

Iimport('Element_Passport_Moneycard');
$element_passport_moneycard = new Element_Passport_Moneycard();
$element_passport_moneycard -> Load($cardno);
if (empty($element_passport_moneycard -> E)) {
	ShowMessage('id.error');
}
if ($element_passport_moneycard -> DoRemove($cardno)) {
	ShowMessage('del.success','money_card_main.php');
}else {
	ShowMessage('del.fail');
}
?>