<?php
error_reporting(E_ALL ^ E_NOTICE);
define('ROOT',dirname(__FILE__)=="" ? "/" : dirname(__FILE__)."/");
//运行时frame根目录
define('FRAME_ROOT',ROOT."frame/");
$isregGlobals = @ini_get('register_globals');
$isUrlOpen = @ini_get('allow_url_fopen');
$isMagic = get_magic_quotes_gpc();
$isSafeMode = @ini_get('safe_mode');
unset($_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS);

if(!$isMagic){
	Add_S($_POST);
	Add_S($_GET);
	Add_S($_COOKIE);
}

//运行时变量处理
if(!$isregGlobals){
	@extract($_GET,EXTR_SKIP);@extract($_POST,EXTR_SKIP);@extract($_COOKIE,EXTR_SKIP);
	if(!empty($_SESSION)) @extract($_SESSION);
}

if (!in_array($language,array('gbk','utf-8','big5'))) {
	include('install/language_select.htm');
	exit;
}else {
	setcookie('language',$language);
}

//$install_frame_version
@include(ROOT.'config/install_config.php');
@include(ROOT.'config/config_db.php');

define('install_safe',1);

$timestamp = time();
//框架版本号,升级框架用
$frame_version = '1.2.9.090101';

//允许的模块
$alreadymode = array('passport','pplog','exam','cms','paper');

if($_SERVER['SERVER_PORT'] == 443) {
	$host2 = 'https://' . ereg_replace('/{1,}','/',$_SERVER['HTTP_HOST'] . str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) . '/' : '/'));
}else {
	$host2 = 'http://' . ereg_replace('/{1,}','/',$_SERVER['HTTP_HOST'] . str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) . '/' : '/'));
}
#new
$host = ereg_replace('/{1,}','/',str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) . '/' : '/'));

$random_str = md5($_SERVER['HTTP_HOST'].$_SERVER['HTTP_USER_AGENT'].time());

if($mode && $install_frame_version >= $frame_version){	//模块安装、升级
	if (in_array($mode,$alreadymode)) {
		
		//引入安装模块信息
		$include = @include(ROOT.$mode.'/install/install_info.php');
		if (!$include) {
			exit('Lack of Install Info');
		}
		//交给模块安装程序
		$include = @include(ROOT.'/install/install.php');
		if (!$include) {
			exit('Lack of Install Program');
		}
	}else {
		exit('Forbidden');
	}
}else {	//主框架安装、升级
	if (!$install_frame_version && $install == 'install') {	//主框架安装
		
		if (empty($admin_user)) {
			echo 'Admin User Empty';
			exit;
		}
		
		if (empty($admin_pass)) {
			echo 'Password Empty';
			exit;
		}
		
		if ($admin_pass != $admin_pass2) {
			echo '2 Password Not Eque';
			exit;
		}
		
		//install
		#1 write db_config
		$dbpconnect = $dbpconnect ? 1 : 0;
		$str = "<?php \r\n\$_db_config= array(
			'dbhost' => '$dbhost',			//数据库主机\r\n
			'dbuser' => '$dbuser',			//数据库用户名\r\n
			'dbpwd' => '$dbpwd',			//数据库密码\r\n
			'dbname' => '$dbname',			//数据库名\r\n
			'dbcharset' => '$dbcharset',	//数据库字符集\r\n
			'dbpre' => '$dbpre',			//数据库表前缀\r\n
			'dbpconnect' => $dbpconnect,	//是否持久连接\r\n
		);\r\n?>";
		$file = ROOT.'config/config_db.php';
		if(!WriteFile($str,$file)) {
			echo 'Write File : Error: <font color=red>'.$file.'</font>';
			exit;
		}
		#2 create db
		@include($file);
		Iimport('dbsql');
		$ppsql = new dbsql();
		//创建数据库,如果不存在的话.
		if (!$ppsql -> DbExist($dbname)) {
			if(!$ppsql -> ExecNoReturnSQL("CREATE DATABASE `$dbname`")){
				echo 'No Access To Create DataBase';
				exit;
			}else {
				$ppsql -> SelectDB($dbname);
			}
		}
		
		$sql = ReadOverFile(ROOT.'install/install-sql.txt');
		$sqls = explode(';',$sql);
		
		foreach ($sqls as $k => $v) {
			if ($v) {
				if (eregi('create table',$v)) {
					$s = substr(strrchr($v,')'),1);
					$v = str_replace($s,'',$v);
					if ($ppsql -> dbVersion > '4.1' && $ppsql -> dbCharset) {
						$v .= " ENGINE=MyISAM DEFAULT CHARSET={$ppsql->dbCharset}";
					}else {
						$v .= " ENGINE=MyISAM ";
					}
				}
				$ppsql -> ExecNoReturnSQL($v);
			}
		}
		//插入管理员
		$ppsql -> ExecNoReturnSQL("Insert Into ##__frame_admin(`userid`,`password`,`privkey`) Values('$admin_user','".md5($admin_pass)."','')");
		#3 setconfig;
		Iimport('Config');
		$Config_obj = new Config('rtc',ROOT.'config/rtc.php');
		@include(ROOT.'rtc-for-install.php');
		$Config_obj -> LoadConfigNoCheck($rtc);
		
		$config = array(
			'passport_hash' => $random_str,
		);
		
		$Config_obj -> LoadConfig($config);
		$Config_obj -> EnableDB(false);
		if($Config_obj -> ReConfig() && file_exists($Config_obj ->Cfullfile)){
			#4 write $install_frame_version
			$str = "<?php\r\n\$install_frame_version = '$frame_version';\r\n?>";
			$file = ROOT.'config/install_config.php';
			if(!WriteFile($str,$file)){
				echo 'Write File : Error: <font color=red>'.$file.'</font>';
				exit;
			}
			#5 refush to passport install;
			$gourl = 'install.php?do=1&installmode=passport';
			echo "<meta http-equiv=\"refresh\" content=\"5;url={$gourl}\" />";
			echo 'Install PPFrame SUCCESS<br />';
			echo "<a href=$gourl>Quick Refresh</a>";
		}else {
			//Echo rtc error
			echo 'Try to Write ' . $Config_obj->Cfullfile . 'Error <br />';
			echo 'Please Create it And Chmod to 777 First';
			exit;
		}
	}else if($install == 'update' && $install_frame_version < $frame_version) {	//主框架升级
		#1 update db
		Iimport('dbsql');
		$ppsql = new dbsql();
		@require(ROOT.'install/update-sql.php');
		if(is_array($_update_sql)) {
			foreach ($_update_sql as $k => $v) {
				if ($k > $install_frame_version && is_array($v)) {
					foreach ($v as $kk => $vv) {
						$vv = trim($vv);
						if ($vv) {
							if (eregi('create table',$vv)) {
								$s = substr(strrchr($vv,')'),1);
								$vv = str_replace($s,'',$vv);
								if ($ppsql -> dbVersion > '4.1' && $ppsql -> dbCharset) {
									$vv .= " ENGINE=MyISAM DEFAULT CHARSET={$ppsql->dbCharset}";
								}else {
									$vv .= " ENGINE=MyISAM ";
								}
							}
							$ppsql -> ExecNoReturnSQL($vv);
						}
					}
				}
			}
		}
		if (!file_exists(ROOT.'config/rtc.php') && file_exists(ROOT.'rtc.php')) {
			copy(ROOT.'rtc.php',ROOT.'config/rtc.php');
			$oldumask = umask(0) ;
			chmod( ROOT.'config/rtc.php', 0777 ) ;
			umask( $oldumask ) ;
		}
		#2 write $install_frame_version
		$str = "<?php\r\n\$install_frame_version = '$frame_version';\r\n?>";
		WriteFile($str,ROOT.'config/install_config.php');
		#3 refush to install index;
		$gourl = 'install.php?do=1';
		echo "<meta http-equiv=\"refresh\" content=\"5;url={$gourl}\" />";
		echo 'Update PPFrame SUCCESS<br />';
		echo "<a href=$gourl>Quick Refresh</a>";
	}else {	//安装向导
		$writecheck = array(
			'config','config/rtc.php','temp','temp/cache','temp/config','temp/template_cache_dir','admin/temp','config/config_db.php'
		);
		
		foreach ($writecheck as $k => $v) {
			if (file_exists(ROOT.$v)) {
				$wcd[str_replace('\\','/',ROOT.$v)] = is_writeable(ROOT.$v) ? 1 : 0;
			}
		}
		
		$include = @include(ROOT."install/install-{$language}.htm");
		!$include && @include(ROOT."install/install.htm");
		exit;
	}
}

//对数组变量加转义
function Add_S(&$array){
	foreach($array as $k=>$v){
		if(!is_array($v)){
			$array[$k]=addslashes($v);
		}else{
			Add_S($array[$k]);
		}
	}
}

function Trip_S(&$array) {
	foreach ($array as $k => $v) {
		if (!is_array($v)) {
			$array[$k] = stripslashes($v);
		}else {
			Trip_S($array[$k]);
		}
	}
}

function WriteFile($str,$file) {
	if($file){
		@touch($file);
		$hand = @fopen($file,'wb');
		if(!is_resource($hand)) return false;
		if(is_writable($file)) {
			flock($hand,LOCK_EX);
			$rt = fwrite($hand,$str);
			flock($hand,LOCK_UN);
		}//end if
		else {
			return false;
		}
		return !($rt === false);
	}//endif
	return false;
}//end writefile()

function ReadOverFile($file) {
	$handle = @fopen($file,'rb');
	if (is_resource($handle)) {
		while ($r = fgets($handle,1024)) {
			$rt .= $r;
		}
		return $rt;
	}else {
		return '';
	}
}

function Iimport($class,$module='') {
	//sql  必须单独考虑,要考虑多数据库.还有 mysqli 等.
	$class = strtolower($class);
	if($class=='dbsql') {
		require_once(FRAME_ROOT.'inc_db_mysql.php');
	}else if(file_exists(FRAME_ROOT."inc_{$class}.php")) {
		require_once(FRAME_ROOT."inc_{$class}.php");
	}else if($module && file_exists(ROOT.$module.'/frame/'."inc_{$class}.php")){
		require_once(ROOT.$module.'/frame/'."inc_{$class}.php");
	}else if(defined('WORK_DIR') && file_exists(WORK_DIR.'frame/'."inc_{$class}.php")) {
		require_once(WORK_DIR.'frame/'."inc_{$class}.php");
	}else {
		exit('class_not_found-----<br />'.$class);
	}
}

function PP_var_export($array) {
	if (function_exists('var_export')) {
		return var_export($array,true);
	}else {
		//
	}
}

function PPMakeDir($dir) {
	mkdir($dir);
	$oldumask = umask(0);
	chmod($dir,777);
	umask( $oldumask );
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