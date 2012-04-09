<?php
require('ppframe.php');

//if ($base_config['passport_method'] == 1) {
//	ShowMessage('system.forbidden',-1,1);
//}

$element_passport_money = GetUserMoneys($passport ->U_ID);

Iimport('template');
$tpl = new Template();
$tpl -> LanguageAssign('title','jifen.view');
$tpl -> DisPlay('jifen');
?>