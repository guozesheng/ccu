<?php

define('ROOT',realpath(dirname(__FILE__).'/../../')=='' ? '/' : realpath(dirname(__FILE__).'/../../').'/');
//运行时frame根目录
define('FRAME_ROOT',ROOT.'frame/');
define('MODULE','passport');

define('WORK_DIR',ROOT.MODULE."/");
require(ROOT.'config/rtc.php');
require_once(ROOT.'config/config_db.php');
$timestamp = time() + intval($rtc['timeadd']);

require('./frame/inc_ppf_api.php');
if(PHP_VERSION < '4.1.0') {
	$_POST = &$HTTP_POST_VARS;
}

if (!get_magic_quotes_gpc()) {
	PPF_API_Add_S($_POST);
}

//对数组变量加转义
function PPF_API_Add_S(&$array){
	if (is_array($array)) {
		foreach($array as $k=>$v){
			if(!is_array($v)){
				$array[$k]=addslashes($v);
			}else{
				PPF_API_Add_S($array[$k]);
			}
		}
	}
}

function PPF_API_GetUserData($fkey) {
	global $userdata,$ppf_api_config_client;
	return $userdata[$ppf_api_config_client['fields'][$fkey]];
}

function Iimport($class,$module='') {
	//sql  必须单独考虑,要考虑多数据库.还有 mysqli 等.
	$class = strtolower($class);
	if($class=='dbsql') {
		require_once(FRAME_ROOT.'inc_db_mysql.php');
		return 1;
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
				return 1;
			}else {
				$failedb[] = $v;
			}
		}
	}
	exit("Class $class not exist<br/>---Serach Class File Via---<br/>".implode('<br />',$failedb));
}

//获得运行时脚本全路径.
function GetRtUri(){
	if(!empty($_SERVER["REQUEST_URI"])){
		return $_SERVER['REQUEST_URI'];
	}else{
		$scriptName = $_SERVER["PHP_SELF"];
		if(empty($_SERVER["QUERY_STRING"])) return $scriptName;
		else return $scriptName . '?' . $_SERVER["QUERY_STRING"];
	}
}
//获得运行时全路径
function GetRtFullUrl($full=true) {
	if (!$full) {
		return GetRtUri();
	}
	if(!empty($_SERVER['SCRIPT_URI'])) {
		if(empty($_SERVER["QUERY_STRING"])) return $_SERVER['SCRIPT_URI'];
		else return $_SERVER['SCRIPT_URI'] . '?' . $_SERVER["QUERY_STRING"];
	}
	$url = GetRtUri();
	if($_SERVER['SERVER_PORT'] == 443) {
		return 'https://'.$_SERVER['HTTP_HOST'].$url;
	}else {
		return 'http://'.$_SERVER['HTTP_HOST'].$url;
	}
}
?>