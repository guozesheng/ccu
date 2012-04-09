<?php
require('../../rte.php');
error_reporting(0);
$rtc['passport_api_time'] = $rtc['passport_api_time'] ? $rtc['passport_api_time'] : 300;
if ($_GET['time'] < $timestamp - $rtc['passport_api_time']) {	//5·ÖÖÓ¹ýÆÚ
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
		$code = WriteMessage($rarray['uid'],$rarray['subject'],$rarray['body'],$rarray['fromid'],$rarray['store'],$rarray['sys']);
		$rtext = "code=$code&time={$timestamp}&sign=".strtolower(md5($code.$timestamp.$GLOBALS['rtc']['passport_api_hash']));
		echo $azencoder -> Encode($rtext);
		exit;
	}
}

echo 0;
?>