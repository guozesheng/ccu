<?php
require('ppframe.php');

!isset($mtype) && $mtype = 0;
$uid = intval($uid);
Iimport('PassPort_User');
$passport_user = new PassPort_User();
$passport_user -> ReSet();
if ($uid) {
	$passport_user -> LoadFromDBUsePriID($uid);
	if (!$passport_user -> U) {
		ShowMessage('user.not.exist');
	}
	$username = $passport_user -> U_Uname;
}else if($username) {
	$passport_user -> LoadFromDBuseUniqID($username);
	$uid = $passport_user -> U_ID;
}

if ($uid && in_array($mtype,$rtc['passport_money'])) {
	//
	Iimport('Lister_Passport_Moneylog');
	$lister_passport_moneylog = new Lister_Passport_Moneylog();
	$lister_passport_moneylog -> SetWhere("`uid`=$uid");
	$lister_passport_moneylog -> SetWhere("`mtype`=$mtype");
	
	if ($datestart && strlen(intval($datestart))==8) {
		$year = substr($datestart,0,4);
		$month = substr($datestart,4,2);
		$day = substr($datestart,6,2);
		$start = GetMyTimestamp(0,0,0,$month,$day,$year);
		$lister_passport_moneylog -> SetWhere("`time`>=$start");
	}
	
	if ($dateend && strlen(intval($dateend)) == 8) {
		$year = substr($dateend,0,4);
		$month = substr($dateend,4,2);
		$day = substr($dateend,6,2);
		$end = GetMyTimestamp(0,0,0,$month,$day,$year);
		$lister_passport_moneylog -> SetWhere("`time`<$end");
	}
	
	if ($num) {
		$lister_passport_moneylog -> SetWhere("`num`=$num");
	}
	
	if ($descrip) {
		$lister_passport_moneylog -> SetWhere("`descrip` like '%$descrip%'");
	}
	
	$lister_passport_moneylog -> CreateWhere();
	
	Iimport('Page');
	$pagesize = 20;
	$p = new Page($lister_passport_moneylog->ExecTotalRecord(),$page,$pagesize);
	$p -> SetDpages();
	$pagelink = $p -> GetPageLink();
	$lister_passport_moneylog -> SetLimit($p -> Limit);
	
	$lister = $lister_passport_moneylog -> GetList();
	
	$do_success = 1;
	
}

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('money_jifen_account');
?>