<?php
require('ppframe.php');
$configfile = ROOT.'config/config.uc.php';
$lockfile = ROOT.'config/uc_install.lock';

if (isset($Submit)) {
	if(file_exists($lockfile)) {
		ShowMessage('PPF already install for UCenter <br /> You Can Remove File:'.$lockfile.' To install again',-1,1);
	}
	$ucapi = preg_replace("/\/$/", '', trim($input['uc_api']));
	$ucip = trim($input['uc_ip']);
	
	if(empty($ucapi) || !preg_match("/^(http:\/\/)/i", $ucapi)) {
		ShowMessage('UCenter URL Error',-1,1);
	} else {
		//检查服务器 dns 解析是否正常, dns 解析不正常则要求用户输入ucenter的ip地址
		if(!$ucip) {
			$temp = @parse_url($ucapi);
			$ucip = gethostbyname($temp['host']);
			if(ip2long($ucip) == -1 || ip2long($ucip) === FALSE) {
				$ucip = '';
			}
		}
	}
	
	define('UC_API',$ucapi);

	//验证UCEnter Client是否安装
	$include = false;
	
	if ($input['version'] == '10') {
		$include = @include_once(FRAME_ROOT.'uc_client.1.0/client.php');
	}else {
		$include = @include_once(FRAME_ROOT.'uc_client.1.5/client.php');
	}
	if (!$include) {
		ShowMessage('uc_client dir not exist',-1,1);
	}
	
	#检查UCenter state
	$ucinfo = sfopen($ucapi.'/index.php?m=app&a=ucinfo', 500, '', '', 1, $ucip);
	list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
	$dbcharset = strtolower(trim($_db_config['dbcharset'] ? str_replace('-', '', $_db_config['dbcharset']) : $_db_config['dbcharset']));
	$ucdbcharset = strtolower(trim($ucdbcharset ? str_replace('-', '', $ucdbcharset) : $ucdbcharset));
	$apptypes = strtolower(trim($apptypes));
	if($status != 'UC_STATUS_OK') {
		ShowMessage("UCenter Connect Error<br /> Info: ({$status})",-1,1);
	}
//	elseif(UC_VERSION > $ucversion) {
//		show_msg('您的 UCenter 服务端版本 ('.$ucversion.') 过低，请升级 UCenter 服务端到最新版本，并且升级，下载地址：http://download.comsenz.com/');
//	} 
	elseif($dbcharset && $ucdbcharset && $ucdbcharset != $dbcharset) {
		ShowMessage('UCenter dbcharset != PPF dbcharset',-1,1);
	} elseif(strpos($apptypes, 'ppframe')) {
		ShowMessage('Already Installed A PPFrame program',-1,1);
	}
	
	$app_url = $rtc['host'] . 'exam';
	$postdata = "m=app&a=add&ucfounder=&ucfounderpw=".urlencode($input['ucfounderpw'])."&apptype=".urlencode('OTHER')."&appname=".urlencode(GetMsg('online.exam'))."&appurl=".urlencode($app_url)."&appip=&appcharset=".$rtc['language'].'&appdbcharset='.$_db_config['dbcharset'].'&apifilename='.urlencode('uc15.php');
	$s = sfopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);
	if(empty($s)) {
		ShowMessage('UCenter Con\'t Connect!',-1,1);
	} elseif($s == '-1') {
		ShowMessage('UCenter Administrator Password Error',-1,1);
	} else {
		$ucs = explode('|', $s);
		if(empty($ucs[0]) || empty($ucs[1])) {
			ShowMessage('UCenter Return Data Error,Please See : <br />'.shtmlspecialchars($s),-1,1);
		} else {
			//处理成功
			//验证是否可以直接联接MySQL
			$link = mysql_connect($ucs[2], $ucs[4], $ucs[5], 1);
			$connect = $link && mysql_select_db($ucs[3], $link) ? 'mysql' : '';
			
			$_Config['connect'] = $connect;
			//返回
			foreach (array('key', 'appid', 'dbhost', 'dbname', 'dbuser', 'dbpw', 'dbcharset', 'dbtablepre', 'charset') as $key => $value) {
				if($value == 'dbtablepre') {
					$ucs[$key] = '`'.$ucs[3].'`.'.$ucs[$key];
				}
				$_Config[$value] = $ucs[$key];
			}
			
			//写入config文件 rewrite
			$configcontent = "<?php
define('UC_CONNECT', 'mysql');	
define('UC_DBHOST', 'localhost');
define('UC_DBUSER', 'root');
define('UC_DBPW', 'hust');
define('UC_DBNAME', 'uct');
define('UC_DBCHARSET', 'gbk');
define('UC_DBTABLEPRE', '`uct`.uc_');
define('UC_DBCONNECT', '0');

define('UC_KEY', 'key');
define('UC_API', 'http://www.uct.test');
define('UC_CHARSET', 'gbk');
define('UC_IP', '');
define('UC_APPID', '5');
?>";
			
			$_Config['api'] = $input['uc_api'];
			$_Config['ip'] = $input['uc_ip'];
			$_Config['dbconnect'] = 0;
			
			$keys = array_keys($_Config);
			foreach ($keys as $value) {
				$upkey = strtoupper($value);
				$lowkey = strtolower($value);
				$configcontent = preg_replace("/define\('UC_".$upkey."'\s*,\s*'.*?'\)/i", "define('UC_".$upkey."', '".$_Config[$lowkey]."')", $configcontent);
			}
			if(!$fp = fopen($configfile, 'w')) {
				ShowMessage("File: $configfile Is Not Writeable ,Please Change Mod to 777",-1,1);
			}
			fwrite($fp, trim($configcontent));
			fclose($fp);
			#copy file
			chdir(ROOT.'exam/api');
			if ($input['version'] == '10') {
				//uc 1.0
				$fn = 'uc10.php';
			}else {
				$fn = 'uc15.php';
			}
			if (!@copy($fn,'uc.php')) {
				//
				$e = "Please copy $fn to uc.php in dir " . getcwd();
			}
			//show success
			WriteFile('PPFrame',$lockfile);
			
			#rtc write!
			Iimport('Config');
			$Config_obj = new Config('rtc',ROOT.'config/rtc.php');
			$Config_obj -> LoadConfigNoCheck($rtc);
			$Config_obj -> LoadConfig(array('uc_use'=>1,'uc_version'=>$input['version'] == '10' ? '10' : '15'));
			$Config_obj -> EnableDB(false);
			$Config_obj -> ReConfig();
			#
			
			ShowMessage("UCenter Config Success <br /> APPID: $_Config[appid]<br /><font color=red>$e</font>",-1,1);
		}
	}
}else {
	@include(ROOT.'config/config.uc.php');
	Iimport('Template');
	$tpl = new Template();
	$tpl -> display('ucenter_combine');
}

//打开远程地址
//$ucinfo = sfopen($ucapi.'/index.php?m=app&a=ucinfo', 500, '', '', 1, $ucip);
function sfopen($url, $limit = 500000, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].'?'.$matches['query'].(empty($matches['fragment'])?'':'#'.$matches['fragment']) : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';//note $errstr : $errno \r\n
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if($status['timed_out']) {
			return '';
		}
		$return = fread($fp, 524);
		$limit -= strlen($return);
		while(!feof($fp) && $limit > -1) {
			$limit -= 100524;
			$return .= @fread($fp, 100524);
		}
		@fclose($fp);
		$return = preg_replace("/\r\n\r\n/", "\n\n", $return, 1);
		$strpos = strpos($return, "\n\n");
		$strpos = $strpos !== FALSE ? $strpos + 2 : 0;
		$return = substr($return, $strpos);
		return $return;
	}
}
?>