<?php
define('FCK_ROOT',dirname(__FILE__)=='' ? '/' : dirname(__FILE__).'/');
define('ROOT',realpath(FCK_ROOT.'../').'/');
define('FRAME_ROOT',ROOT.'frame/');
define('WORK_DIR',FCK_ROOT);
#����Ŀ¼�Զ��ж�MODULE
define('MODULE',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(__FILE__))));
#�̶�MODUL
//define('MODULE','exam');

define('Admin_Safe',MODULE);

require(ROOT.'config/rtc.php');
require(FCK_ROOT.'config/baseconfig.php');
require(ROOT.'config/config_db.php');

$timestamp = time() + intval($rtc['timeadd']);

function GetMyDate($format,$time='now'){
	global $timestamp;
	$timeadd = $GLOBALS['rtc']['timezone'];
	if($time=='now') $time = $timestamp;
	return gmdate($format,$time+$timeadd*3600);
}

function Iimport($class,$module='') {
	//sql  ���뵥������,Ҫ���Ƕ����ݿ�.���� mysqli ��.
	$class = strtolower($class);
	if($class=='dbsql') {
		require_once(FRAME_ROOT.'inc_db_mysql.php');
		return ;
	}else {
		$files = array(
			FRAME_ROOT."inc_{$class}.php",
		);
		if ($module) {
			$files[] = ROOT.$module.'/frame/'."inc_{$class}.php";
		}
		if (defined('WORK_DIR')) {
			$files[] = WORK_DIR.'frame/'."inc_{$class}.php";
		}
		
		foreach ($files as $k => $v) {
			if (file_exists($v)) {
				require_once($v);
				return ;
			}else {
				$failedb[] = $v;
			}
		}
	}
	exit("Class $class not exist<br/>---Serach Class File Via---<br/>".implode('<br />',$failedb));
}

function GetIP() {
	if(!empty($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
	else return 'Unknow';
}
?>