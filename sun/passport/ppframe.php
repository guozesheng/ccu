<?php
/*
Runtime Envirment file
λ��: /passport
*/
require('../rte.php');

#����Ŀ¼�Զ��ж�MODULE
define('MODULE',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
#�̶�MODUL
//define('MODULE','passport');
define('WORK_DIR',ROOT.MODULE."/");

require(WORK_DIR.'config/baseconfig.php');
if ($base_config['ip_ban'] && !CheckIpBan()) {
	ShowMessage('your.ip.not.allow');
}

require(WORK_DIR.'rte_functions.php');
Iimport('PassPort_User');
$passport = new PassPort_User();
$Lang -> LoadLangFromFile('i18n_passport');
if(!$passport->IsLogined()) {
	PassportLogin(GetRtFullUrl(),'no.login');
}

if (empty($base_config['passport_money'])) {
	$base_config['passport_money'] = array(0);
}

if (!isset($base_config['passport_onlinemoney'])) {
	$base_config['passport_onlinemoney'] = 0;
}