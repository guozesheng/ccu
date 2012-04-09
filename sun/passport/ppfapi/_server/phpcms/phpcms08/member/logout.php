<?php
require './include/common.inc.php';
if(!$forward) $forward = HTTP_REFERER;
if($PHPCMS['enableserverpassport'])
{
	$verify = substr(md5($PHPCMS['passport_serverkey']),0,8);
	$logouturl = $PHPCMS['passport_serverurl'].$PHPCMS['passport_logouturl'];
	$addstr = QUERY_STRING ? QUERY_STRING : '';
	$logouturl .= (strpos($logouturl, '?') ? '&' : '?').$addstr.'&verify='.$verify;
	header('location:'.$logouturl);
	exit;
}

if(!isset($action)) $action = '';

$member->logout();

#ppfapi
	define('ppfapi_safe',true);
	$ppf_api_userdata = array();
	$ppf_api_return = $forward;
	require('ppfapi/interface_logout.php');
	if ($ppf_api_gourl) {
		$forward = $ppf_api_gourl ? $ppf_api_gourl : $forward;
	}
#ppfapi
	
if($PHPCMS['enablepassport'])
{
	if($action == 'logout_ajax')
	{
		$forward = url($M['url'], 1).'logout.php?action=ajax_message';
	}
	#ppfapi del
//	else
//	{
//		$forward = isset($forward) ? url($forward, 1) : SITE_URL;
//	}
	#ppfapi del
	$action = 'logout';
	require MOD_ROOT.'api/passport_server_'.$PHPCMS['passport_file'].'.php';
	header('location:'.$url);
	exit;
}
if($PHPCMS['uc'])
{
    $action = 'logout';
    require MOD_ROOT.'api/passport_server_ucenter.php';
}
if($action == 'ajax')
{
	echo 1;
	exit;
}

if(!$forward) $forward = SITE_URL;
showmessage($LANG['logout_success'].$ucsynlogout, $forward);
?>