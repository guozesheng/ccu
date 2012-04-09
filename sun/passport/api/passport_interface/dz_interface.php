<?php
!defined('passport_safe') && exit('Forbidden');

$action = $inter_action == 'login' ? 'login' : 'logout';

if (is_array($inter_db)) {
	foreach ($inter_db as $k => $v) {
		$tmp[] = "$k=$v";
	}
	$userdb = implode('&',$tmp);
	$userdb = str_replace('=','',passport_encrypt($userdb,$base_config['passport_api_hash']));
}

$forward = $inter_forward;
$verify = md5($action.$userdb.$forward.$base_config['passport_api_hash']);
$forward = urlencode($forward);
$userdb = urlencode($userdb);
$inter_url = trim($base_config['passport_api_address'])."?action={$action}&auth={$userdb}&forward={$forward}&verify={$verify}";

//header("Location: $inter_url");
//echo "<meta http-equiv='refresh' content='0;url=$inter_url'>";
//exit;

function passport_encrypt($txt, $key) {
	srand((double)microtime() * 1000000);
	$encrypt_key = md5(rand(0, 32000));
	$ctr = 0;
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
	}
	return base64_encode(passport_key($tmp, $key));
}

function passport_decrypt($txt, $key) {
	$txt = passport_key(base64_decode($txt), $key);
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$md5 = $txt[$i];
		$tmp .= $txt[++$i] ^ $md5;
	}
	return $tmp;
}

function passport_key($txt, $encrypt_key) {
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
	}
	return $tmp;
}

?>