<?php
!defined('passport_safe') && exit('Forbidden');

$action = $inter_action == 'login' ? 'login' : 'quit';

if (is_array($inter_db)) {
	foreach ($inter_db as $k => $v) {
		$tmp[] = "$k=$v";
	}
	$userdb = implode('&',$tmp);
	$userdb = str_replace('=','',PW_StrCode($userdb));
}

$forward = $inter_forward;
$verify = md5($action.$userdb.$forward.$base_config['passport_api_hash']);
$forward = urlencode($forward);
$inter_url = trim($base_config['passport_api_address'])."?action={$action}&userdb={$userdb}&forward={$forward}&verify={$verify}";

//header("Location: $inter_url");
//echo "<meta http-equiv='refresh' content='0;url=$inter_url'>";
//exit;

function PW_StrCode($string,$action='ENCODE'){
	$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['base_config']['passport_api_hash']),8,18);
	$string	= $action == 'ENCODE' ? $string : base64_decode($string);
	$len	= strlen($key);
	$code	= '';
	for($i=0; $i<strlen($string); $i++){
		$k		= $i % $len;
		$code  .= $string[$i] ^ $key[$k];
	}
	$code = $action == 'DECODE' ? $code : base64_encode($code);
	return $code;
}
?>