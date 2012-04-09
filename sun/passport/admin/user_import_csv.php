<?php
require('ppframe.php');


$uploadfile = $_FILES['file']['tmp_name'];

if (!if_uploaded_file($uploadfile)) {
	EchoMsg('please.give.uploade.file');
//	EchoMsg('NO');
	exit;
}

#set locale 尝试修正区域设置, todo 考虑操作系统
$oldlocale = setlocale(LC_ALL,null);
if (eregi('Win',PHP_OS)) {	//windows
	$locale = '';
	$codepage = array(
		'gbk' => 936,
		'big5' => 950,
		'utf8' => 65001,
	);
	if ($charset && $codepage[$charset]) {
		$locale .= '.' . $codepage[$charset];
	}
	$locale && setlocale(LC_ALL,$locale);
}else if (eregi('Lin',PHP_OS)) {	//linux
	$locale = null;
	if ($country) {
		$locale = $country;
		if ($charset) {
			$locale .= '.' . $charset;
		}
	}
	$locale && setlocale(LC_ALL,$locale);
}
#set locale 尝试修正区域设置

$ppsql = new dbsql();
$table_fields = $ppsql -> GetFieldList($rtc['passport_table']);

$insert_fields = array();
//parse csv
$handle = fopen($uploadfile,'rb');
$i = 0;
$failed_num = $success_num = 0;
$length = 1024*1024;
if (phpversion() > 5.0) {
	$length = 0;
}
while (is_array($data = fgetcsv($handle,$length))) {
	$i ++;
	$continue = 0;
	$array = array();
	if ($i == 1) {
		//跳过说明行
		continue;
	}else if ($i == 2) {
		//记录字段列表
		foreach ($data as $k => $v) {
			$v = trim($v);
			if (array_key_exists($v,$table_fields)) {
				$insert_fields[$k] = $v;
				$insert_fields[$v] = & $insert_fields[$k];
			}
		}
		if (!array_key_exists($rtc['passport_uniqueid'],$insert_fields)) {
			EchoMsg('username.field.not.exist');
			exit;
		}
		if (!array_key_exists($rtc['passport_passkey'],$insert_fields)) {
			EchoMsg('password.field.not.exist');
			exit;
		}
		continue;
	}
	//one record
	foreach ($data as $k => $v) {
//		$v = trim($v);
		if ($insert_fields[$k]) {
			$array[$insert_fields[$k]] = $v ? $v : $memory[$v];
		}
		if (eregi('int',$table_fields[$v][1])) {
			$array[$insert_fields[$k]] = intval($array[$insert_fields[$k]]);
		}
	}
	$array['regtime'] = $timestamp;
	$array['regip'] = 'Unknow';
	if (!is_array($array) || !array_key_exists($rtc['passport_uniqueid'],$array) || !$array[$rtc['passport_uniqueid']] || !array_key_exists($rtc['passport_passkey'],$array) || !$array[$rtc['passport_passkey']]) {
		$continue = 1;
	}
	if ($continue) {
		$failed[] = $array;
		$failed_num ++;
		continue;
	}else {
		//do
		$successed[] = $array;
		$success_num ++;
		$memory = $array;
//		Add_S($array);
		$insert[] = $array;
	}
}

setlocale(LC_ALL,$oldlocale);

$total_num = $failed_num + $success_num;
//导入
if (!$check_first && $success_num > 0) {
	Iimport('PassPort_User');
	$passport_user = new PassPort_User();
	if($pass_encode) {
		$passport_user -> PsNeedEncode = true;
		in_array($pass_method,array('md5','md5-16')) && $passport_user -> PsMethod = $pass_method;
	}else {
		$passport_user -> PsNeedEncode = false;
	}
	$passport_user -> SetInsertInfo($insert);
	$safearray = array_keys($table_fields);
	$inserttype = $replace ? 'replace' : 'insert';
	if($passport_user -> DoRecordUser($safearray,2,$inserttype,$ignore)){
		
	}else {
		$failed_num = $failed_num + $success_num;
		$success_num = 0;
	}
}

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_import_csv');

?>