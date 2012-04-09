<?php
require('../../rte.php');
error_reporting(0);
$rtc['passport_api_time'] = $rtc['passport_api_time'] ? $rtc['passport_api_time'] : 300;
if ($_GET['time'] < $timestamp - $rtc['passport_api_time']) {	//5·ÖÖÓ¹ýÆÚ
	echo 0;
	exit;
}
if (md5($_GET['db'].$_GET['time'].$rtc['passport_api_hash']) == $_GET['sign']) {
	Iimport('azEncoder');
	$azencoder = new azEncoder();
	$azencoder -> SetKey($rtc['passport_api_hash']);
	
	$db = $azencoder -> Decode($_GET['db']);
	$rarray = array();
	parse_str($db,$rarray);
	
	if ($rarray['t'] == md5($_GET['time']) && $rarray['uid'] >0) {
		Iimport('Admin_User');
		$passport = new Admin_User();
		$passport -> LoadFromDBUsePriID($rarray['uid']);
		if ($passport -> U['uid']> 0) {
			if ($passport -> PassCheckEnCode($passport -> U) == $rarray['passcheck']) {
				//success!
				$rarray = array('uid' => $rarray['uid'] ,'time' => $timestamp);
				$db = $azencoder -> Encode(serialize($rarray));
				$str = "db=$db&time=$timestamp&sign=".md5($db.$timestamp.$rtc['passport_api_hash']);
				echo $azencoder -> Encode($str);
				exit;
			}
		}
	}
}
echo 0;
?>