<?php
!defined('MODULE') && exit('Forbidden');
function GetMoneyName($m) {
	global $money_types;
	return GetMsg($money_types[$m]);
}