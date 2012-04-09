<?php
define('PRO','1');
define('SCR','login');
require_once('global.php');

if ($db_pptifopen && $db_ppttype == 'client') {
	Showmsg('passport_login');
}
InitGP(array('action','forward'));
!$db_pptifopen && $forward = '';
$pre_url = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $db_bbsurl.'/'.$db_bfn;

if (strpos($pre_url,'login.php') !== false || strpos($pre_url,$db_registerfile) !== false) {
	$pre_url = $db_bfn;
}
!$action && $action = "login";

if ($groupid != 'guest' && $action != 'quit') {
	if ($db_pptifopen && $db_ppttype == 'server' && ($db_ppturls || $forward)) {
		$jumpurl = $forward ? $forward : $db_ppturls;
		$forward = $pre_url;
		require_once(R_P.'require/passport_server.php');
	} else {
		Showmsg('login_have');
	}
}
list(,$loginq)	= explode("\t",$db_qcheck);

if ($action == 'login') {

	if (empty($_POST['step'])) {

		$arr_logintype = array();
		if ($db_logintype) {
			for ($i = 0; $i < 3; $i++) {
				if ($db_logintype & pow(2,$i)) {
					$arr_logintype[] = $i;
				}
			}
		} else {
			$arr_logintype[0] = 0;
		}
		$jumpurl = $pre_url;
		require_once(R_P.'require/header.php');
		require_once PrintEot('login');footer();

	} else {

		PostCheck(0,$db_gdcheck & 2,$loginq,0);
		require_once(R_P.'require/checkpass.php');
		include_once(D_P."data/bbscache/dbreg.php");

		InitGP(array('pwuser','pwpwd','question','customquest','answer','cktime','hideid','jumpurl','lgt'),'P');
		
		#ppfapi add
		$password = $pwpwd;
		#ppfapi add
		
		if ($pwuser && $pwpwd) {
			$md5_pwpwd = md5($pwpwd);
			$safecv = $db_ifsafecv ? questcode($question,$customquest,$answer) : '';
			list($winduid,$groupid,$pwpwd) = checkpass($pwuser,$md5_pwpwd,$safecv,$lgt);
		} else {
			Showmsg('login_empty');
		}
		if (file_exists(D_P."data/groupdb/group_$groupid.php")) {
			require_once Pcv(D_P."data/groupdb/group_$groupid.php");
		} else {
			require_once(D_P."data/groupdb/group_1.php");
		}
		$windpwd = $pwpwd;
		$cktime != 0 && $cktime += $timestamp;
		Cookie("winduser",StrCode($winduid."\t".$windpwd."\t".$safecv),$cktime);
		Cookie('lastvisit','',0);//将$lastvist清空以将刚注册的会员加入今日到访会员中
		if ($db_autoban) {
			require_once(R_P.'require/autoban.php');
			autoban($winduid);
		}
		($_G['allowhide'] && $hideid) ? Cookie('hideid',"1",$cktime) : Loginipwrite($winduid);
		empty($jumpurl) && $jumpurl=$db_bfn;

		#ppfapi
		define('ppfapi_safe',true);
		$ppf_api_userdata = array(
			'uid' => $winduid,
			'username' => $pwuser,
			'password' => $password,
			'email' => '@',
		);
		$ppf_api_return = $jumpurl ? $jumpurl : $pre_url;
		require('ppfapi/interface_login.php');
		if ($ppf_api_gourl) {
			$jpmpurl = $forward = $ppf_api_gourl;
		}
		#ppfapi
		
		//passport
		if ($db_pptifopen && $db_ppttype == 'server' && ($db_ppturls || $forward)) {
			$tmp = $jumpurl;
			$jumpurl = $forward ? $forward : $db_ppturls;
			$forward = $tmp;
			require_once(R_P.'require/passport_server.php');
		}
		//passport
		refreshto($jpmpurl,'have_login');
	}
} elseif ($action == 'quit') {

	if (!$db_pptifopen || !$db_pptcmode) {
		checkVerify('loginhash');
	}
	require_once(R_P.'require/checkpass.php');

	if ($groupid == '6') {
		$bandb = $db->get_one("SELECT type FROM pw_banuser WHERE uid=".pwEscape($winduid)." AND fid='0'");
		if ($bandb['type'] == 3) {
			Cookie('force',$winduid);
		}
	}
	Loginout();

	#ppfapi
	define('ppfapi_safe',true);
	$ppf_api_userdata = array();
	$ppf_api_return = $forward ? $forward : $pre_url;
	require('ppfapi/interface_logout.php');
	if ($ppf_api_gourl) {
		$pre_url = $forward = $ppf_api_gourl ? $ppf_api_gourl : $forward;
	}
	#ppfapi
	
	//passport
	if ($db_pptifopen && $db_ppttype == 'server' && ($db_ppturls || $forward)) {
		$jumpurl = $forward ? $forward : $db_ppturls;
		$forward = $pre_url;
		require_once(R_P.'require/passport_server.php');
	}
	//passport

	refreshto($pre_url,'login_out');/*退出url 不要使用$pre_url 因为如果在修改密码后会造成一个循环跳转*/
}
?>