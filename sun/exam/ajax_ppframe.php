<?php
/*
Runtime Envirment file
λ��: /passport
*/
require('../rte.php');

#����Ŀ¼�Զ��ж�MODULE
define('MODULE',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
#�̶�MODUL
//define('MODULE','exam');

define('WORK_DIR',ROOT.MODULE."/");

require(WORK_DIR.'config/baseconfig.php');
require(WORK_DIR.'config/config_txdb.php');
require(WORK_DIR.'rte_functions.php');
if ($base_config['exam_close']) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//http 1.0
header('Pragma: no-cache') ;
header("Content-Type: text/html; charset=$rtc[language]");

Iimport('PassPort_User');
$exam_user = new PassPort_User();

function Login_check() {
	global $exam_user;
	if (!$exam_user->IsLogined()) {
		echo '<font color=red>' . GetMsg('no.login') .'</font>';
		exit;
	}
}
//�������԰�
$Lang -> LoadLangFromFile('i18n_exam');