<?php
/**
 * UCenter Ӧ�ó��򿪷� API For PPFrame
 *
 * ���ļ�Ϊ api/uc.php �ļ��Ŀ����������û����� UCenter ֪ͨ��Ӧ�ó��������
 */
error_reporting(0);
define('UC_VERSION', '1.5.0');		//UCenter �汾��ʶ

define('API_DELETEUSER', 1);		//�û�ɾ�� API �ӿڿ���
define('API_RENAMEUSER', 1);		//�û����� API �ӿڿ���
define('API_UPDATEPW', 1);		//�û������� API �ӿڿ���
define('API_GETTAG', 0);		//��ȡ��ǩ API �ӿڿ���
define('API_SYNLOGIN', 1);		//ͬ����¼ API �ӿڿ���
define('API_SYNLOGOUT', 1);		//ͬ���ǳ� API �ӿڿ���
define('API_UPDATEBADWORDS', 0);	//���¹ؼ����б� ����
define('API_UPDATEHOSTS', 1);		//���������������� ����
define('API_UPDATEAPPS', 1);		//����Ӧ���б� ����
define('API_UPDATECLIENT', 1);		//���¿ͻ��˻��� ����
define('API_UPDATECREDIT', 1);		//�����û����� ����
define('API_GETCREDITSETTINGS', 1);	//�� UCenter �ṩ�������� ����
define('API_UPDATECREDITSETTINGS', 1);	//����Ӧ�û������� ����

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

#
require(dirname(__FILE__).'/../../rte.php');
#����Ŀ¼�Զ��ж�MODULE
define('MODULE',str_replace(str_replace('\\','/',ROOT),'',str_replace('\\','/',dirname(dirname(__FILE__)))));
define('WORK_DIR',ROOT.MODULE."/");
#

#UC_CLIENT_ROOT ����ַ����
define('UC_CLIENT_ROOT', FRAME_ROOT.'uc_client.1.5/');

#��������
require_once ROOT.'config/config.uc.php';	//UC����
#

$code = $_GET['code'];
parse_str(authcode($code, 'DECODE', UC_KEY), $get);
//if(MAGIC_QUOTES_GPC) {
	$get = dstripslashes($get);
//}
if($timestamp - $get['time'] > 3600) {
	exit('Authracation has expiried');
}
if(empty($get)) {
	exit('Invalid Request');
}
$action = $get['action'];

if($action == 'test') {
	exit(API_RETURN_SUCCEED);
} elseif($action == 'deleteuser') {

	!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);

	//�û�ɾ�� API �ӿ�
	$uids = $get['ids'];
	$uids = explode(' ',trim(str_replace(',',' ',$uids)));
	Iimport('PassPort_User');
	$passport = new PassPort_User();
	$passport -> RemoveUsersUseId($uids);
	exit(API_RETURN_SUCCEED);

} elseif($action == 'renameuser') {

	!API_RENAMEUSER && exit(API_RETURN_FORBIDDEN);

	//�û����� API �ӿ�
	
	$uid = $get['uid'];
	$usernamenew = $get['newusername'];
	Iimport('PassPort_User');
	$passport = new PassPort_User();
	$passport -> LoadFromDBUsePriID($uid);
	$passport -> SetUpdateInfo(array($passport -> U_UniqueID => $usernamenew));
	$passport -> DoReRecord();
	
	exit(API_RETURN_SUCCEED);

} elseif($action == 'updatepw') {

	!API_UPDATEPW && exit(API_RETURN_FORBIDDEN);

	//�����û�����
	$username = $get['username'];
	$newpw = $get['password'];
	Iimport('PassPort_User');
	$passport = new PassPort_User();
	$passport -> LoadFromDBuseUniqID($username);
	if ($passport -> U_ID > 0) {
		$passport -> SetUpdateInfo(array($passport -> U_UniqueID => $username,$passport -> U_PasswordKey =>$username));
		$passport -> DoReRecord();
	}else {
		//׷�����û� no need ��½һ�ξͻ�׷�ӣ���������ڵĻ���
	}
	exit(API_RETURN_SUCCEED);

} elseif($action == 'gettag') {

	!API_GETTAG && exit(API_RETURN_FORBIDDEN);

	//��ȡ��ǩ API �ӿ�
	$return = array($name, array());
	echo uc_serialize($return, 1);

} elseif($action == 'synlogin' && $_GET['time'] == $get['time']) {

	!API_SYNLOGIN && exit(API_RETURN_FORBIDDEN);

	//ͬ����¼ API �ӿ�
	$uid = intval($get['uid']);
	$username = $get['username'];
	$password = $get['password'];
	Iimport('PassPort_User');
	$passport = new PassPort_User();
	$passport -> ReSet();
	$passport -> LoadFromDBuseUniqID($username);
	if ($passport -> U_ID > 0) {
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$passport -> PassCheckRebuild();
		$passport -> PutLoginedInfo();
	}else {
		//׷�����û�
		#׷�ӵ��û�������Ч���޷���PPF Passport ��½���ⲻ��Ҫ������ͨ��UC��½��һ���޸ĺ󼴿�ͬ�����룡
		$passport -> SetInsertInfo(array($passport -> PriKey => $uid,$passport -> U_UniqueID => $username,$passport -> U_PasswordKey => $password));
		$passport -> DoRecordUser();
		$passport -> LoadFromDBuseUniqID($username);
		$passport -> PassCheckRebuild();
		$passport -> PutLoginedInfo();
	}
} elseif($action == 'synlogout') {

	!API_SYNLOGOUT && exit(API_RETURN_FORBIDDEN);
	//ͬ���ǳ� API �ӿ�
	header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	Iimport('PassPort_User');
	$passport = new PassPort_User();
	$passport -> ExitLogin();
} elseif($action == 'updatebadwords') {

	!API_UPDATEBADWORDS && exit(API_RETURN_FORBIDDEN);

	//���¹ؼ����б�
	exit(API_RETURN_SUCCEED);

} elseif($action == 'updatehosts') {

	!API_UPDATEHOSTS && exit(API_RETURN_FORBIDDEN);

	//����HOST�ļ�
	exit(API_RETURN_SUCCEED);

} elseif($action == 'updateapps') {

	!API_UPDATEAPPS && exit(API_RETURN_FORBIDDEN);

	//����Ӧ���б�
	exit(API_RETURN_SUCCEED);

} elseif($action == 'updateclient') {

	!API_UPDATECLIENT && exit(API_RETURN_FORBIDDEN);
	$cacheDir='./data/cache';
	if(is_dir($cacheDir)){
		if ($handle = opendir($cacheDir)) {
		    while (false !== ($file = readdir($handle))) {
		        if(is_file($file)){
		        		unlink($cacheDir."/".$file);
		        }
		    }
		    closedir($handle);
		}
	}
	//���¿ͻ��˻���
	exit(API_RETURN_SUCCEED);

} elseif($action == 'updatecredit') {

	!UPDATECREDIT && exit(API_RETURN_FORBIDDEN);
	$credit = intval($get['credit']);
	$amount = intval($get['amount']);
	$uid = intval($get['uid']);
	AddMoney($amount,$uid,$credit,'uc_syn_update');
	//�����û�����
	exit(API_RETURN_SUCCEED);
} elseif($action == 'getcreditsettings') {

	!GETCREDITSETTINGS && exit(API_RETURN_FORBIDDEN);
	foreach ($rtc['passport_money'] as $k => $v) {
		$credits[$v] = 	array(GetMsg($money_types[$v]),'');
	}
	//�� UCenter �ṩ��������
	echo uc_serialize($credits);

} elseif($action == 'updatecreditsettings') {

	!API_UPDATECREDITSETTINGS && exit(API_RETURN_FORBIDDEN);
	$outextcredits = array();
	if(isset($get['credit'])&&is_array($get['credit'])){
		foreach($get['credit'] as $appid => $credititems) {
			if($appid == UC_APPID) {
				foreach($credititems as $value) {
					$outextcredits[] = array(
						'appiddesc' => $value['appiddesc'],
						'creditdesc' => $value['creditdesc'],
						'creditsrc' => $value['creditsrc'],
						'title' => $value['title'],
						'unit' => $value['unit'],
						'ratio' => $value['ratio']
					);
				}
			}
		}
	}
	
	$config_file = ROOT.'config/config.uc.creditsettings.php';
	
	WriteFile("<?php\r\n\$creditsettings = " . PP_var_export($outextcredits) . ";\r\n?>",$config_file);
	
	//����Ӧ�û�������
	exit(API_RETURN_SUCCEED);

} else {

	exit(API_RETURN_FAILED);

}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function dstripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

function uc_serialize($arr, $htmlon = 0) {
	include_once UC_CLIENT_ROOT.'./lib/xml.class.php';
	return xml_serialize($arr, $htmlon);
}

function uc_unserialize($s) {
	include_once UC_CLIENT_ROOT.'./lib/xml.class.php';
	return xml_unserialize($s);
}