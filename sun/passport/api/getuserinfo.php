<?php
require('../../rte.php');
error_reporting(0);
$rtc['passport_api_time'] = $rtc['passport_api_time'] ? $rtc['passport_api_time'] : 1800;
if ($_GET['time'] < $timestamp - $rtc['passport_api_time']) {	//30·ÖÖÓ¹ýÆÚ
	echo 0;
	exit;
}

if (strtolower(md5($_GET['db'].$_GET['time'].$rtc['passport_api_hash'])) == $_GET['sign']) {
	Iimport('azEncoder');
	$azencoder = new azEncoder();
	$azencoder -> SetKey($rtc['passport_api_hash']);
	
	$db = $azencoder -> Decode($_GET['db']);
	$rarray = array();
	parse_str($db,$rarray);
	
	if ($rarray['t'] == strtolower(md5($_GET['time']))) {
		Iimport('PassPort_User');
		$passport = new PassPort_User();
		if ($rarray['uid'] >0) {
			$passport -> LoadFromDBUsePriID($rarray['uid']);
		}else if ($rarray['un']) {
			$passport -> LoadFromDBuseUniqID($rarray['un']);
		}else {
			echo '0';
			exit;
		}
		
		if ($passport -> U['uid']> 0) {
			unset($passport -> U[$passport ->U_PasswordKey]);
			$infodb = $azencoder -> Encode(serialize($passport ->U));
			$sign = strtolower(md5($infodb.$timestamp.$rtc['passport_api_hash']));
			$str = $azencoder -> Encode("infodb=$infodb&time=$timestamp&sign=$sign");
			echo $str;
			exit;
		}
	}
}

echo 0;
?>