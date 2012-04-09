<?php
/*
Runtime Envirment file
位置: /passport
*/
require('../rte.php');

#根据目录自动判断MODULE
define('MODULE',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
#固定MODUL
//define('MODULE','exam');

define('WORK_DIR',ROOT.MODULE."/");

require(WORK_DIR.'config/baseconfig.php');

if ($base_config['ip_ban'] && !CheckIpBan()) {
	ShowMessage('your.ip.not.allow');
}

require(WORK_DIR.'config/config_txdb.php');
require(WORK_DIR.'rte_functions.php');
if ($base_config['exam_close']) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

Iimport('PassPort_User');
$exam_user = new PassPort_User();

function Login_check() {
	global $exam_user;
	if (!$exam_user->IsLogined()) {
		PassportLogin(GetRtFullUrlAndPost(),'no.login');
	}
}
$Lang -> LoadLangFromFile('i18n_exam');
if(empty($base_config['kaojuan_autotime']))  $base_config['kaojuan_autotime'] = 300;
if(empty($base_config['exam_online_timelimit'])) $base_config['exam_online_timelimit'] = 600;
if (empty($base_config['kaojuan_before_select']) || $base_config['kaojuan_before_select'] > $base_config['kaojuan_livetime'] * 60) {
	$base_config['kaojuan_before_select'] = $base_config['kaojuan_livetime'] * 30;
}
?>