<?php
/*
@
@	����ʱ���� RunTime Environment 
@	λ�� /
*/
//����ʱ��Ŀ¼
define('ROOT',dirname(__FILE__)=="" ? "/" : dirname(__FILE__)."/");
//����ʱframe��Ŀ¼
define('FRAME_ROOT',ROOT."frame/");
$isregGlobals = @ini_get('register_globals');
$isUrlOpen = @ini_get('allow_url_fopen');
$isMagic = get_magic_quotes_gpc();
$isSafeMode = @ini_get('safe_mode');
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}
//��������ʱ��������
if(!include(ROOT.'config/rtc.php')) {
	//�ݴ�!
	include(ROOT.'rtc.php');
}
if ($rtc['debug_open'] == 1) {
	//�������д�����ʾ
	error_reporting(E_ALL ^ E_NOTICE);
}else if ($rtc['debug_open'] == 0) {
	//�������д�����Ϣ
	error_reporting(0);
}

if ($rtc['ip_ban_allsite'] && ! CheckIpBan()) {
	exit('Your ipaddress not allow to visit our website');
}

Iimport('Seo');
$__seo = new Seo();
$__seo -> Start();
$__seo -> ParseValue();


$DocumentIdentify = $__seo -> CreateUrlIdentify();

//û������ magic_quotes_gpcʱ,addslashes!
if(!$isMagic){
	Add_S($_POST);
	Add_S($_GET);
	Add_S($_COOKIE);
}
Add_S($_FILES);
//����ʱ��������
if(!$isregGlobals){
	@extract($_GET,EXTR_SKIP);@extract($_POST,EXTR_SKIP);
	if(!empty($_SESSION)) @extract($_SESSION);
}

//�ű�����ʱʱ���, 
$timestamp = time() + intval($rtc['timeadd']);

$_time_st = GetMicrotime();
$_db_q = 0;
//�ű�����ʱ����·��
$RT_URI = GetRtUri();
//����ʱUrlȫ·��
$RT_URL = GetRtFullUrl();
//����ʱHttp��

$FormToSelf = $_SERVER['PHP_SELF'].'?verify=';

//�������ݿ������ļ�
require_once(ROOT.'config/config_db.php');
//�����û���������

//�������԰�
Iimport('Lang');
$Lang = new Lang();
$Lang -> LoadLangFromFile('i18n');
##�޸Ĵ˴�hhack ����ϵͳ����
#���ֱ� ������Ҫ�����洢���ֵ��ֶκ��û�ID
$_money_table = '##__passport';
#��������ֵ��ֶ�
$money_types = array(
	0 => 'money0',		//�������԰���������� money0 Ϊ��Ļ�����
	1 => 'money1',
	2 => 'money2',
	3 => 'money3',
	4 => 'money4',
	5 => 'money5',
	6 => 'money6',
	7 => 'money7',
	8 => 'money8',
	9 => 'money9',
);
#�����û���
@include(ROOT.'config/usergroup_config.php');
$_usergroup[0] = array(
	'id' => 0,
	'name' => 'nogroup',
);

$ckecknum_ckckey = '_CkCkey_';
$checknum_key = GetCookie($ckecknum_ckckey);

if (!$checknum_key) {
	mt_srand((double)microtime() * 1000000);
	$ck = "";
	$len = mt_rand(5,8);
	for ($i=0;$i<$len;$i++){
		$ck .= chr(mt_rand(ord('a'),ord('z')));
	}
	$ck = substr(md5($ck.time().$_SERVER["HTTP_USER_AGENT"]),mt_rand(0,32-$len),$len);
	$checknum_key = $ck;
	unset($ck);
}
$checknum_no_cookie = "?{$ckecknum_ckckey}={$checknum_key}";

$module_fields = array(		#ģ��Ŀ¼���ձ�,ͨ������Ҫ���޸���Ŀ¼֮����Ҫ�ı�!
	'passport' => 'passport',
	'exam' => 'exam',
);

function GetLoginurl($return=''){
	global $rtc;
	!$return && $return = GetRtFullUrlAndPost();
	$var_forward = $rtc['passport_uc_return'] ? $rtc['passport_uc_return'] : 'forward';
	$login_url = strpos($rtc['passport_login'],'?') ? ($rtc['passport_login'] . '&' . $var_forward . '=' . urlencode($return)) : ($rtc['passport_login'] . '?' . $var_forward . '=' . urlencode($return));
	return $login_url;
}

function GetLogouturl($return='') {
	global $rtc;
	!$return && $return = GetRtFullUrlAndPost();
	$var_forward = $rtc['passport_uc_return'] ? $rtc['passport_uc_return'] : 'forward';
	$logout_url = strpos($rtc['passport_logout'],'?') ? ($rtc['passport_logout'] . '&' . $var_forward . '=' . urlencode($return)) : ($rtc['passport_logout'] . '?' . $var_forward . '=' . urlencode($return));
	return $logout_url;
}

function GetRegurl($return='') {
	global $rtc;
	!$return && $return = GetRtFullUrlAndPost();
	$var_forward = $rtc['passport_uc_return'] ? $rtc['passport_uc_return'] : 'forward';
	$logout_url = strpos($rtc['passport_reg'],'?') ? ($rtc['passport_reg'] . '&' . $var_forward . '=' . urlencode($return)) : ($rtc['passport_reg'] . '?' . $var_forward . '=' . urlencode($return));
	return $logout_url;
}
##
//У��ʱ�����ʱ���ʽ��ú���
/**
 * ���ʱ��У����ĸ�ʽ��ʱ��
 *
 * @param string $format
 * @param timestamp $time
 * @return string
 */
function GetMyDate($format,$time='now'){
	global $timestamp;
	$timeadd = $GLOBALS['rtc']['timezone'];
	if($time=='now') $time = $timestamp;
	return gmdate($format,$time+$timeadd*3600);
}

/**
 * ��һ������ʱ����GMTʱ���ʱ���
 *
 * @param number $hour
 * @param number $min
 * @param number $sec
 * @param number $mon
 * @param number $day
 * @param number $year
 * @return number
 */
function GetMyTimestamp($hour=0,$min=0,$sec=0,$mon=false,$day=false,$year=false) {
	list($m,$d,$y) = explode(',',GetMyDate('Y,m,d'));
	!$mon && $mon = intval($m);
	!$day && $day = intval($d);
	!$year && $year = intval($y);
	$timeadd = $GLOBALS['rtc']['timezone'];	
	
	return gmmktime($hour,$min,$sec,$mon,$day,$year) - $timeadd * 3600;
}

function GetIP() {
	if(!empty($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
	else return 'Unknow';
}
//�������ʱ�ű�ȫ·��.
function GetRtUri(){
	if(!empty($_SERVER["REQUEST_URI"])){
		return $_SERVER['REQUEST_URI'];
	}else{
		$scriptName = $_SERVER["PHP_SELF"];
		if(empty($_SERVER["QUERY_STRING"])) return $scriptName;
		else return $scriptName . '?' . $_SERVER["QUERY_STRING"];
	}
}
//�������ʱȫ·��
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

function GetRtFullUrlAndPost($full=true) {
	$r = GetRtFullUrl($full);
	if (empty($_POST)) {
		return $r;
	}else {
		$ra = CreateQueryString($_POST);
		if (ereg('\?',$r)) {
			if (ereg('&$',$r)) {
				return $r.$ra;
			}else {
				return $r.'&'.$ra;
			}
		}else {
			return $r . '?' . $ra;
		}
	}
}
/**
 * ��һ������,ͨ����_GET ����_POST �ؽ�query��
 *
 * @param array $query
 * @param string $key
 * @return string
 */
function CreateQueryString($query,$key='') {
	$rtq = '';
	if(is_array($query)) {
		foreach ($query as $k => $v) {
			if(!is_array($v)) {
				if(empty($key)) $rtq .= "{$k}=".urlencode($v)."&";
				else $rtq .= $key."[$k]=".urlencode($v)."&";
			}else {
				if($key) $kk = $key . "[$k]";
				else $kk = $k;
				$rtq .= CreateQueryString($v,$kk);
			}
		}
	}
	return $rtq;
}
//�������ʱ΢ʱ��
function GetRtMicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec);
} 
//�����������ת��
function Add_S(&$array){
	if (is_array($array)) {
		foreach($array as $k=>$v){
			if(!is_array($v)){
				$array[$k]=addslashes($v);
			}else{
				Add_S($array[$k]);
			}
		}
	}
}
function Trip_S(&$array) {
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			if (!is_array($v)) {
				$array[$k] = stripslashes($v);
			}else {
				Trip_S($array[$k]);
			}
		}
	}
}

function GetCookie($key){
	return $_COOKIE[$key];
}

function PutCookie($key,$value,$kptime,$pa="/",$domain=''){
	return setcookie($key,$value,$GLOBALS['timestamp'] + $kptime,$pa,$domain,$_SERVER['SERVER_PORT'] == 443 ?1:0);
}
function DropCookie($key,$domain=''){
	setcookie($key,"",$GLOBALS['timestamp'] - 365 * 86400,"/",$domain,$_SERVER['SERVER_PORT'] == 443 ?1:0);
}
//��array ת��Ϊ�Ϸ���php �����ַ���
function var_export_bl_4_2($array){
	return Array_Export($array,false,1);
}
function PP_var_export($array) {
	if (function_exists('var_export')) {
		return var_export($array,true);
	}else {
		//
	}
}
function ShowMsg($msg,$gourl=-1,$only=0,$time=false,$exit=1){
	ShowMessage($msg,$gourl,$only,$time,$exit);
}
function ShowMessage($msg,$gourl=-1,$only=0,$time=false,$msg2='') {
//	<meta http-equiv='refresh' content='$time;url=index.php'>
	global $Lang;
	if($time === false) $time = $GLOBALS['rtc']['showtime'];
	$time>1000 && $time = intval($time/1000);
	if($gourl==-1){
		$buttongo = $gourl = 'javascript:history.go(-1);';
	}else if($gourl===0){
		if ($_SERVER['HTTP_REFERER']) {
			$buttongo = "javascript:location='{$_SERVER['HTTP_REFERER']}'";
			$gourl = $_SERVER['HTTP_REFERER'];
		}else {
			$buttongo = $gourl = 'javascript:history.go(-1);';
		}
	}else {
		$buttongo = "javascript:location='{$gourl}'";
	}
	
	if($time == 0) {
		$meta = "<meta http-equiv=\"refresh\" content=\"{$time};url={$gourl}\" />";
		echo $meta;
	}else {
		Iimport('template');
		$tmp = new Template();
		!$only && $tmp ->Assign('meta',$meta);
//		$gourlold==-1 && $gourlold = 'history.go(-1)';
		$tmp -> Assign('gourl',$gourl);
		$tmp -> Assign('buttongo',$buttongo);
		$msg = trim($msg);
		$message = $Lang->GetFullLang($msg);
		$tmp -> Assign('msg',$message);
		$tmp -> Assign('msg2',$msg2);
		$tmp -> Assign('title',$message);
		$tmp -> Assign('time',$time * 1000);
		if(!$only) {
			$tmp -> DisPlay('msg_show');
		}else {
			$tmp -> DisPlay('msg_button');
		}
	}
	exit;
}

function EchoMsg($msg) {
	echo GetMsg($msg);
}

function GetMsg($msg) {
	global $Lang;
	return $Lang->GetFullLang($msg);
}

function WriteFile($str,$file) {
	if($file){
		@touch($file);
		$oldumask = umask(0);
		@chmod( $file, 0777 );
		@umask( $oldumask );
		$hand = @fopen($file,'wb');
		if(!is_resource($hand)) return false;
		if(is_writable($file)) {
			@flock($hand,LOCK_EX);
			$rt = fwrite($hand,$str);
			@flock($hand,LOCK_UN);
		}//end ife
		else {
			return false;
		}
		return !($rt === false);
	}//endif
	return false;
}//end writefile()
/**
 * ����һ��Ŀ¼
 *
 * @param string $dir
 */
function PPMakeDir($dir) {
	$oldumask = umask(0);
	mkdir($dir , 0777 );
	umask( $oldumask );
}

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

function SafeHtml($str) {
	$farr = array( 
        "/\s+/",//���˶���Ŀհ� 
        "/<(\/?)(script|i?frame|style|html|body|title|link|meta|input|form|select|textarea\?|\%)([^>]*?)>/isU",//���� <script �ȿ�������������ݻ����ı���ʾ���ֵĴ���,�������Ҫ����flash��,�����Լ���<object�Ĺ��� 
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",//����javascript��on�¼� 
   ); 
   $tarr = array( 
        ' ', 
        '&lt;\\1\\2\\3&rt;',//���Ҫֱ���������ȫ�ı�ǩ������������� 
        '\\1\\2',
   ); 
  $str = preg_replace( $farr,$tarr,$str); 
   return $str; 
}
//�ͷ�����ɺϷ��ַ���,when phpversion<4.2
function Array_Export($array,$ads=false,$c=1,$t='',$var=''){
	$c && $var="array(\r\n";
	$t.="  ";
	if(is_array($array)){
		foreach($array as $key => $value){
			if($ads)
			$var.="$t'".addslashes($key)."'=>";
			else 
			$var.="$t'".$key."'=>";
			
			if(is_array($value)){
				$var.="array(\r\n";
				$var=vvar_export($value,0,$t,$var);
				$var.="$t),\r\n";
			} else{
				if($ads)
				$var.="'".addslashes($value)."',\r\n";
				else
				$var.="'".$value."',\r\n";
			}
		}
	}
	if($c){
		$var.=")";
	}
	return $var;
}

function StartDB($pconnect=false){
	if(!defined('DBOPENED') || !DBOPENED){
		$GLOBALS['ppsql'] = new dbsql($pconnect);
		define('DBOPENED',true);
	}
}

function PrePagesize($size,$default=10) {
	if ($GLOBALS['pagesize'] > 0) {
		return $GLOBALS['pagesize'];
	}
	$size = intval($size);
	if ($size > 0) {
		return $size;
	} else {
		return $default;
	}
}

function PassportLogin($return='',$msg='') {
	#����UC����ת��UC������վ��½
	global $rtc;
	$var_forward = $rtc['passport_uc_return'] ? $rtc['passport_uc_return'] : 'forward';
	if ($rtc['uc_use']) {
		$login_url = strpos($rtc['passport_login'],'?') ? $rtc['passport_login'] . '&' . $var_forward . '=' . urlencode($return) : $rtc['passport_login'] . '?' . $var_forward . '=' . urlencode($return);
		ShowMessage('',$login_url,0,0);
		exit;
	}
	!$msg && $msg = 'no.login';
	Iimport('template');
	$tpl = new Template();
	$tpl -> LanguageAssign('msg',$msg);
	$tpl -> Assign('return',$return);;
	$tpl -> LanguageAssign('title','passport.login');
	$tpl -> DisPlay('login');
	exit;
}
//�������,�ڶ���������Ч������ԭ��ֻҪ������ __autoload ����ڶ��������������ˣ�PHP4����Ч��ʵ�ڷѽ⣡
//�Ѳ�����class_exists �ἤ�� __autoload ��̫��ŭ�ˡ�
function Iimport($class,$module='') {
	//sql  ���뵥������,Ҫ���Ƕ����ݿ�.���� mysqli ��.
	$class = strtolower($class);
	if($class=='dbsql') {
		require_once(FRAME_ROOT.'inc_db_mysql.php');
		return 1;
	}else {
		$files = array(
			FRAME_ROOT."inc_{$class}.php",
		);
		if ($GLOBALS['_INMODULE_']) {
			$files[] = ROOT.$GLOBALS['_INMODULE_'].'/frame/'."inc_{$class}.php";
		}
		if ($module && $GLOBALS['module_fields'][$module]) {
			$module = $GLOBALS['module_fields'][$module];
		}
		if ($module && $module != $GLOBALS['_INMODULE_']) {
			$files[] = ROOT.$module.'/frame/'."inc_{$class}.php";
		}
		if (defined('WORK_DIR')) {
			$files[] = WORK_DIR.'frame/'."inc_{$class}.php";
		}
		foreach ($files as $k => $v) {
			if (file_exists($v)) {
				require_once($v);
				return 1;
			}else if ( $GLOBALS['rtc']['debug_open']){
				$failedb[] = $v;
			}
		}
	}
	if ( $GLOBALS['rtc']['debug_open']) {
		exit("Class $class not exist<br/>---Serach Class File Via---<br/>".implode('<br />',$failedb));
	}else {
		exit('Import Class Error');
	}
}


function SeoOver() {
	global $__seo;
	$__seo = new Seo();
	$__seo -> Over();
}

function UseClientCache($time=60,$identify='') {
	global $timestamp;
	$identify = $identify ? $identify : Seo::CreateUrlIdentify();
	$etag = $_SERVER['HTTP_IF_NONE_MATCH'];
	list($identify,$settime) = explode('|',$etag);
	if($identify == $identify && $settime + $time > $timestamp) {	//������Ч
		header('Last-Modified: '. GetHttpdate($settime));
		header('Expires: '.GetHttpdate($settime+$time));
		header('Etag:'.$etag,true,304);
		exit;
	}else {	//������Ч go
		header('Last-Modified: '. GetHttpdate($timestamp));
		header('Etag:'. $identify .'|'.$timestamp,true);
	}
}
/**
 * ���HTTP-DATE ��ʽʱ��	rfc2616
 * 1��rfc1123-date	2��rfc850-date	3��asctime-date
 *
 * @param number $timestamp
 * @param int $type
 * @return string
 */
function GetHttpdate($timestamp,$type=1) {
	switch ($type) {
		case 1:
		return gmdate('D, d M Y H:i:s',$timestamp) . ' GMT';
		case 2:
		return gmdate('l, d-M-y H:i:s',$timestamp) . ' GMT';
		case 3:
		return gmdate('D M d H:i:s Y',$timestamp) . ' GMT';
		default:
		return gmdate('D, d M Y H:i:s',$timestamp) . ' GMT';
	}
}

//������������ģ���ļ�
function GetTemplate($file,$ext='htm',$base=false) {
	return NewGetTemplate($file,$ext,$base);
}
function NewGetTemplate($file,$ext='htm',$base=false) {
	global $rtc,$base_config;
	$style = $GLOBALS['_INSTYLE_'] ? $GLOBALS['_INSTYLE_'] : ($base_config['style'] ? $base_config['style'] : ($rtc['style'] ? $rtc['style'] : 'ppframe')) ;
	$language = $GLOBALS['_INLANG_'] ? $GLOBALS['_INLANG_'] : ($base_config['language'] ? $base_config['language'] : ($rtc['language'] ? $rtc['language'] : 'gbk'));
	if(!ereg('\.',$file)) $file = $file . '.' . $ext;
	if (file_exists($file)) {
		return $file;
	}
	$dirdb = array(
		1 => defined('TEMPLATE_DIR') ? ( !$base ? TEMPLATE_DIR.'template/' : WORK_DIR.'template/' ) : (defined('WORK_DIR') ? WORK_DIR.'template/' : ROOT.'template/'),
	);
	if ($GLOBALS['_INMODULE_']) {
		$dirdb[] = ROOT.$GLOBALS['_INMODULE_'].'/template/';
	}
	if ($dirdb[1] != ROOT.'template/') {
		$dirdb[] = ROOT.'template/';
	}
	$filedb = array(
		$style . '/' . $language .'/'. $file,
		$style . '/' . $file,
	);
	if ($style != 'ppframe') {
		$filedb[] = 'ppframe/' . $language . '/' . $file;
		$filedb[] = 'ppframe/' . $file;
	}
	
	foreach ($dirdb as $k => $v) {
		foreach ($filedb as $kk => $vv) {
			$realfile = $v.$vv;
			if(file_exists($realfile)) {
				return $realfile;
				break;	//means nothing
			}else if ( $GLOBALS['rtc']['debug_open']){
				$failedb[] = $realfile;
			}
		}
	}
	if ( $GLOBALS['rtc']['debug_open']) {
		exit("File $file not exist<br/>---Serach Via---<br/>".implode('<br />',$failedb));
	}else {
		exit('Get Template File Error');
	}
}

function GetStyle(){ 
	if ($GLOBALS['base_config']['style']) {
		return $GLOBALS['base_config']['style'];
	}else if($GLOBALS['rtc']['style']){
		return $GLOBALS['rtc']['style'];
	}else {
		return 'ppframe';
	}
}

function if_uploaded_file($tmp_name){
	if (!$tmp_name || $tmp_name=='none') {
		return false;
	} elseif (function_exists('is_uploaded_file') && !is_uploaded_file($tmp_name) && !is_uploaded_file(str_replace('\\\\', '\\', $tmp_name))) {
		return false;
	} else{
		return true;
	}
}
//�����Ƿ���һ������ $k �� �� ,�ѷ���
function CheckNumeric($num,$k=0) {
	if(is_numeric($num) && $num>$k) return true;
	return false;
}

function PlusPlus($num) {
	if (is_numeric($num)) {
		return ++$num;
	}else {
		return $num . '1';
	}
}

/**
 * �����ļ�����Ȩ�޼��
 * 
 * @param array $privs Ȩ�޼���
 * @param string[optional] $file �ܼ����ļ���
 * @param bool[optional] $self �Ƿ��Ǽ���Լ���Ȩ��
 * @return bool
 */
function CheckPriv($privs='',$file='',$self=true) {
	global $Admin;
	if ($self) {	//����Լ���Ȩ��	
		if ($Admin -> U_ID == 1) {	// 1��ID�ǳ�������Ա
			return 1;
		}
	}
	if ($privs == '') {
		$privs = $Admin -> U[$Admin->PrivKey];
	}
	!$file && $file = basename($_SERVER['SCRIPT_NAME']);
	#ȥ��Ajax_ ͷ ajax_��Ӱ��Ȩ��
	if (eregi('^ajax_',$file)) {
		$file = substr($file,5);
	}
	//���� _ ������ index_ ��ͷ�Ŀ���Ȩ��
	if (!ereg('\_',$file) || eregi('^index_',$file)) {
		return 1;
	}
	
	//�����Ȩ������һ�����ļ�ͷƥ�䣬��ͨ��
	if (is_array($privs)) {
		foreach ($privs as $k => $v) {
			if ($v && ereg("^$v",$file)) {
				return 1;
			}else {	//none
				continue;
			}
		}
	}
	return 0;
}

/**
*����Ȩ�޿�ݺ���
*/
function CheckAdminAuth($auth='') {
	if (!CheckPriv('',$auth)) {
		ShowMessage('no.auth.'.$auth);
	}
}

function GetMicrotime() {
	list($usec, $sec) = explode(" ", microtime());
   	return ((float)$usec + (float)$sec);
}

/**
 * ɨ��һ��Ŀ¼,����Ȩ�ޱ�
 *
 * @param bool $hard
 * @return array
 */
function PrivsScan($dir='',$hard=false) {
	$cachedir = WORK_DIR.'temp/rte_config_temp_dir/';
	if(!file_exists($cachedir)) {
		@mkdir($cachedir);
	}
	$cachefile = $cachedir.'priv_scan_lister.php';
	
	if (!$hard && file_exists($cachefile) && time() - filemtime($cachefile) <  1200) {
		require($cachefile);
	}else {
		Iimport('FileManager');
		$dir = $dir ? $dir : WORK_DIR;
		$filemanager = new FileManager($dir);
		$filemanager -> Scan($dir);
		$rarray = array();
		foreach ($filemanager -> FileData as $k => $v) {
			$v = $v['name'];
			if (!eregi('\.php$',$v) && !eregi('\.ppfauth$',$v)) {		//ָ����׺��Ȩ���ļ�
				continue;
			}
			if (eregi('^ajax_',$v)) {
				$v = substr($v,5);
			}
			$v = substr($v,0,strrpos($v,'.'));
			if (!ereg('\_',$v) || eregi('^index_',$v)) {
				continue;
			}
			$array = explode('_',$v);
			if (count($array) >= 3) {
				$rarray[$array[0]][$array[0] . '_' . $array[1]][implode('_',$array)] = implode('_',$array);
			}else if (count($array) == 2) {
				$rarray[$array[0]][$array[0] . '_' . $array[1]] = implode('_',$array);
			}
		}
		$str = "<?php\r\n \$rarray = ". PP_var_export($rarray). "\r\n?>";
		WriteFile($str,$cachefile);
	}
	return $rarray;
}

/**
 * ����û�������Ϣ
 *
 * @param number $uid
 * @return array
 */
function GetUserMoneys($uid) {
	Iimport('Element_Passport_Money');
	$element_passport_money = new Element_Passport_Money();
	$element_passport_money -> SetCacheState(false);
	$element_passport_money -> Load($uid);
	return $element_passport_money ->E;
}
/**
 * �����û��ȼ�
 * floor(sqrt(($x + 50) / 5) - 2)
 * ���15��
 * 
 * @param number $x
 * @return number
 */
function ExecExamLelvel($x) {
	if ($x<=0) {
		return 0;
	}
	$x = sqrt(($x+50)/5) -2;
	$x = floor($x);
	$x = $x > 15 ? 15 : $x;
	return $x;
}

/**
 * ���ѻ���
 *
 * @param number $num ����
 * @param num $uid �û�ID
 * @param number $mtype	����
 * @param bool	$ckunique �Ƿ����ظ�
 * @param string $why ԭ��
 */
function UseMoney($num,$uid,$mtype=0,$why='',$ckunique=false) {
	global $money_types;
	if (!in_array($mtype,$GLOBALS['rtc']['passport_money'])) {
		return -1;	//��֧�ֵĻ�������
	}
	if ($uid<1) {
		return -2;	//�û����쳣
	}

	if (!$GLOBALS['base_config']['server_independ']) {	//ͬ������
		#����ظ�����,�����ظ�
		if ($ckunique) {
			//todo
			Iimport('Lister_Passport_Moneylog');
			$lister_passport_moneylog = new Lister_Passport_Moneylog();
			$lister_passport_moneylog -> EnableCache(false);
			$lister_passport_moneylog -> SetWhere("`uid`=$uid");
			$lister_passport_moneylog -> SetWhere("`descrip`='$why'");
			$lister_passport_moneylog -> CreateWhere();
			if ($lister_passport_moneylog -> ExecTotalRecord() > 0) {
				return 1;
			}
		}
		
		Iimport('Element_Passport_Money');
		$element_passport_money = new Element_Passport_Money();
		$element_passport_money -> Load($uid);
		if ($num > 0) {	//����
			if ($element_passport_money -> E[$money_types[$mtype]] < $num) {
				return -3;	//���Ҳ���
			}
			$element_passport_money -> SetUpdate(array($element_passport_money->PriKey => $uid , $money_types[$mtype]=>"`$money_types[$mtype]`-$num"));
		}else if ($num < 0) {	//��ֵ
			$element_passport_money -> SetUpdate(array($element_passport_money->PriKey=>$uid , $money_types[$mtype]=>"`$money_types[$mtype]`+".abs($num)));
		}
		if($element_passport_money -> DoUpdate(array($money_types[$mtype]))) {
			if (!$GLOBALS['rtc']['pass_moneylog_ban']) {
				WriteMoneyLog($num,$uid,$mtype,$why);
			}
			return 1;	//�ɹ�
		}else {
			return 0;	//ʧ��
		}
	}else {	//�������
		//http://passport/api/usemoney.php?db=....&t=&sign=md5_sign
		$api_root = $GLOBALS['rtc']['passport_root_api'] ? $GLOBALS['rtc']['passport_root_api'] : $GLOBALS['rtc']['passport_root'];
		$api_root .= 'api/';
		$api_file = 'usemoney.php';
		
		$db = 't='.strtolower(md5($GLOBALS['timestamp'])).'&uid='.$uid.'&num='.$num.'&mtype='.$mtype.'&why='.$why;
		
		Iimport('azEncoder');
		$azencoder = new azEncoder();
		$azencoder ->SetKey($GLOBALS['rtc']['passport_api_hash']);
		$db = $azencoder ->Encode($db);
		
		$api_file = $api_file . '?db=' . $db . '&time=' . $GLOBALS['timestamp'] . '&sign=' . strtolower(md5($db.$GLOBALS['timestamp'].$GLOBALS['rtc']['passport_api_hash']));
		
		Iimport('Http');
		$http = new Http();
		$http -> OpenUrl($api_root.$api_file);
		$rtext = $http -> Send();
		
		if ($rtext) {
			#return azEncode(code=code&time={timestamp}&sign=md5_sign);
			$azencoder = new azEncoder();
			$azencoder ->SetKey($GLOBALS['rtc']['passport_api_hash']);
			$rtext = $azencoder -> Decode($rtext);
			$rarray = array();
			parse_str($rtext,$rarray);
			if ($rarray['sign'] == strtolower(md5($rarray['code'].$rarray['time'].$GLOBALS['rtc']['passport_api_hash']))) {
				return $rarray['code'];
			}else {
				return 0;
			}
		}else {
			return 0;
		}
	}
}

function CheckIpBan() {
	global $rtc;
	if ($rtc['ip_ban'] == 0) {
		return 1;
	}else {
		if (defined('IPBAN_CHECKED')) {
			return IPBAN_CHECKED;
		}
		!class_exists('IpCheck') && Iimport('IpCheck');
		$ipcheck = new IpCheck();
		if ($rtc['ip_ban'] == 1) {	//��ֹ���г��������
			$allow = $rtc['ip_ban_allow'];
			if($allow) {
				$allows = explode("\n",str_replace(array("\r\n","\r"),"\n",$allow));
				if (is_array($allows)) {
					foreach ($allows as $k => $v) {
						if ($v) {
							$ipcheck -> ReSet();
							$ipcheck -> SetIprule($v);
							if ($ipcheck -> CheckIPIn(GetIP())) {
								define('IPBAN_CHECKED',1);
								return 1;
							}
						}
					}
				}
			}
			define('IPBAN_CHECKED',0);
			return 0;
		}
		if ($rtc['ip_ban'] == 2) {	//�������г��˽�ֹ��
			$r = 1;
			$ban = $rtc['ip_ban_ban'];
			$bans = explode("\n",str_replace(array("\r\n","\r"),"\n",$ban));
			if (is_array($bans)) {
				foreach ($bans as $k => $v) {
					if ($v) {
						$ipcheck -> ReSet();
						$ipcheck -> SetIprule($v);
						if ($ipcheck -> CheckIPIn(GetIP())) {
							define('IPBAN_CHECKED',0);
							return 0;
						}
					}
				}
			}
			define('IPBAN_CHECKED',1);
			return 1;
		}
	}
}

/**
 * ���ѻ��ҵķ�����
 *
 * @param number $num ����
 * @param num $uid �û�ID
 * @param number $mtype	����
 * @param string $why ԭ��
 * @param bool	$ckunique �Ƿ����ظ�
 * @return number
 */
function AddMoney($num,$uid,$mtype=0,$why='',$ckunique=false){
	return UseMoney(-$num,$uid,$mtype,$why,$ckunique);
}
/**
 * ������־
 *
 * @param number $num
 * @param number $mtype
 * @param string $why
 */
function WriteMoneyLog($num,$uid,$mtype=0,$why='') {
	global $money_types;
	if (!in_array($mtype,$GLOBALS['rtc']['passport_money'])) {
		return -1;	//��֧�ֵĻ�������
	}
	if ($uid < 1) {	//����ʶ����û�
		return -2;
	}
	Iimport('Element_Passport_Moneylog');
	if ($num) {
		$element_passport_moneylog = new Element_Passport_Moneylog();
		Iimport('Element_Passport_Money');
		$element_passport_money = new Element_Passport_Money();
		$element_passport_money -> Load($uid);
		
		$element_passport_moneylog -> SetInsert(array(
			'uid'=>$uid,
			'num'=>-$num,
			'mtype'=>$mtype,
			'yue' => $element_passport_money -> E[$money_types[$mtype]] ? $element_passport_money -> E[$money_types[$mtype]] : 0,
			'time' => $GLOBALS['timestamp'],
			'descrip' => $why,	
			)
		);
		if ($element_passport_moneylog -> DoRecord()) {
			return 1;	//success
		}else {
			return 0;	//fail!
		}
	}else {
		return 0;
	}
}

function WriteMessage($uid,$subject,$body,$fromid=0,$store=0,$sys=0) {	//���Ͷ���Ϣ
	if (!$GLOBALS['base_config']['server_independ']) {
		Iimport('Message');
		$message = new Message($fromid);
		return $message -> Write($uid,$subject,$body,$store,$sys);
	}else {
		//
		$api_root = $GLOBALS['rtc']['passport_root_api'] ? $GLOBALS['rtc']['passport_root_api'] : $GLOBALS['rtc']['passport_root'];
		$api_root .= 'api/';
		$api_file = 'sendmessage.php';
		
		$db = 't='.strtolower(md5($GLOBALS['timestamp'])).'&uid='.$uid.'&subject='.$subject.'&body='.$body.'&fromid='.$fromid.'&stroe='.$store.'&sys='.$sys;
		
		Iimport('azEncoder');
		$azencoder = new azEncoder();
		$azencoder ->SetKey($GLOBALS['rtc']['passport_api_hash']);
		$db = $azencoder ->Encode($db);
		
		$api_file = $api_file . '?db=' . $db . '&time=' . $GLOBALS['timestamp'] . '&sign=' . strtolower(md5($db.$GLOBALS['timestamp'].$GLOBALS['rtc']['passport_api_hash']));
		
		Iimport('Http');
		$http = new Http();
		$http -> OpenUrl($api_root.$api_file);
		$rtext = $http -> Send();
		
		if ($rtext) {
			$azencoder = new azEncoder();
			$azencoder ->SetKey($GLOBALS['rtc']['passport_api_hash']);
			$rtext = $azencoder -> Decode($rtext);
			$rarray = array();
			parse_str($rtext,$rarray);
			if ($rarray['sign'] == strtolower(md5($rarray['code'].$rarray['time'].$GLOBALS['rtc']['passport_api_hash']))) {
				return $rarray['code'];
			}else {
				return 0;
			}
		}else {
			return 0;
		}
	}
}

/**
 * ��¼��־
 *
 * @param string $action
 * @param string $identify
 * 
 */
function SyslogsWrite($action=0,$identify=0) {
	if(!defined('Admin_Safe')) return ;
	global $Admin;
	Iimport('Element_Frame_Syslogs');
	$element_frame_syslogs = new Element_Frame_Syslogs();
	$element_frame_syslogs -> SetInsert(array(
		'adminid' => $Admin -> U_ID,
		'adminname' => $Admin -> U_Uname,
		'module' => MODULE,
		'dotype' => substr(basename($_SERVER['SCRIPT_NAME']),0,strpos(basename($_SERVER['SCRIPT_NAME']),'.')),
		'doaction' => $action,
		'identifyid' => $identify,
		'dourl' => GetRtFullUrlAndPost(),
		'dotime' => $GLOBALS['timestamp'],
		'doip' => GetIP(),
	));
	$element_frame_syslogs -> DoRecord();
}


/**
 * ���һ�����ŵ������Ӳ��ŵ�ID
 *
 * @param number $id	����id
 * @return string
 */
function GetBussSunIds($id) {
	if ($id<1) {
		return '';
	}
	!class_exists('Lister_Passport_Buss') && Iimport('Lister_Passport_Buss');
	$lister_lister = new Lister_Passport_Buss();
	$lister_lister -> Els = array('id');
	$lister_lister -> SetWhere("`ids` like '%,$id,%'");
	$lister_lister -> CreateWhere();
	$lister = $lister_lister -> GetList();
	foreach ($lister as $k =>$v) {
		$lister[$k] = $v['id'];
	}
	return implode(',',$lister);
}

function GetTableFields($tb,$like) {
	Iimport('dbsql');
	$ppsql = new dbsql();
	return $ppsql -> GetFieldList($tb,'',$like);
}
//�÷ָ�������һ�������һ���ַ���
function Array2String($array,$sp=',') {
	if(!is_array($array)) return '';
	return implode($sp,$array);
}

function String2Array($string,$sp=',') {
	$array = preg_split("/$sp/",$string,-1,PREG_SPLIT_DELIM_CAPTURE);
	return $array;
}

//php5 �Զ���������δ֪���
function __autoload($class) {
	Iimport(strtolower($class));
}

function GetGradeS() {
	global $rtc;
	$gradestart = $rtc['passport_grade_start'] ? $rtc['passport_grade_start'] : 2000;
	if ($gradestart < 1900) {
		$gradestart = 1900;
	}
	$grades = array();
	for ($i = $gradestart ; $i <= GetMyDate('Y') + 2; $i ++) {
		$grades[$i] = $i;
	}
	return $grades;
}
/**
 * ��ð༶�б�
 *
 * @param number $ctype 0��Ȼ��,1�γ̰�
 * @param number $grade �꼶
 * @param string $classname �༶��
 * @param bool $jq �Ƿ�ȷƥ��༶��
 * @param number $cid ԺϵID
 * @param bool $allow �Ƿ��������
 * @param string $in �༶ID�б�
 * @return array
 */
function GetStudentClassList($ctype=null,$grade=0,$classname='',$jq=0,$cid=0,$in='',$allow=null) {
	Iimport('Lister_Passport_Class');
	$lister_passport_class = new Lister_Passport_Class();
	if (isset($ctype)) {
		if($ctype) $lister_passport_class -> SetWhere("`ctype`=1");
		else $lister_passport_class -> SetWhere("`ctype`=0");
	}
	if ($grade) {
		$lister_passport_class -> SetWhere("`grade`=$grade");
	}
	if (isset($allow)) {
		if ($allow) {
			$lister_passport_class -> SetWhere("`allowapply`=1");
		}else {
			$lister_passport_class -> SetWhere("`allowapply`=0");
		}
	}
	if ($classname) {
		if ($jq) {
			$lister_passport_class -> SetWhere("`classname` like '$classname'",'classname');
		}else {
			$lister_passport_class -> SetWhere("`classname` like '%$classname%'",'classname');
		}
	}
	if ($cid > 0 ) {
		$lister_passport_class -> SetWhere("`cid`='$cid'");
	}
	if ($in) {
		$in = implode(',',explode(' ',trim(str_replace(',',' ',$in))));
		$lister_passport_class -> SetWhere("`id` in ($in)",'in');
	}
	$lister_passport_class -> CreateWhere();
	return $lister_passport_class -> GetList();
}

function GetCollegesLister() {
	!class_exists('Lister_Passport_College') && Iimport('Lister_Passport_College');
	$lister_lister = new Lister_Passport_College();
	return $lister_lister -> GetList();
}

function GetProfessionalLister($cid=0) {
	!class_exists('Lister_Passport_Professional') && Iimport('Lister_Passport_Professional');
	$lister_lister = new Lister_Passport_Professional();
	$cid = intval($cid);
	if ($cid > 0) {
		$lister_lister -> SetWhere("`cid`=$cid");
	}
	$lister_lister -> CreateWhere();
	return $lister_lister -> GetList();
}
