<?php 
/*************************************************
���ļ�����Ϣ�������û����и��ģ��������������и���
**************************************************/
session_start();
error_reporting(E_ALL || ~E_NOTICE);

define('VIOOMAINC',dirname(__FILE__));

$ckvs = Array('_GET','_POST','_COOKIE','_FILES');
$ckvs4 = Array('HTTP_GET_VARS','HTTP_POST_VARS','HTTP_COOKIE_VARS','HTTP_POST_FILES');

//PHPС��4.1�汾�ļ����Դ���
$phpold = 0;
foreach($ckvs4 as $_k=>$_v){ 
	if(!@is_array(${$_v})) continue;
	if(!@is_array(${$ckvs[$_k]})){ 
		${$ckvs[$_k]} = ${$_v}; unset(${$_v}); $phpold=1;
	}
}
//ȫ�ְ�ȫ���
foreach($ckvs as $ckv){
   foreach($$ckv AS $_k => $_v){ 
      if(eregi("^(_|globals|cfg_)",$_k)) unset(${$ckv}[$_k]);
   }
}

//�����û����õ�ϵͳ����
require_once(VIOOMAINC."/config_hand.php");

//php5.1�汾����ʱ������
if(PHP_VERSION > '5.1') {
	$time51 = 'Etc/GMT'.($cfg_cli_time > 0 ? '-' : '+').abs($cfg_cli_time);
	function_exists('date_default_timezone_set') ? @date_default_timezone_set($time51) : '';
}


//Session����·��
//$sessSavePath = VIOOMAINC."/../data/sessions/";
//if(is_writeable($sessSavePath) && is_readable($sessSavePath)){ session_save_path($sessSavePath); }

//���ݿ�������Ϣ
$cfg_dbhost = 'localhost';
$cfg_dbname = 'viooma2008';
$cfg_dbuser = 'root';
$cfg_dbpwd = 'vertrigo';
$cfg_dbprefix = 'viooma_';
$cfg_db_language = 'gbk';

//���ժҪ��Ϣ��****�벻Ҫɾ������**** ����ϵͳ�޷���ȷ����ϵͳ©����������Ϣ
//-----------------------------
$cfg_softname = "Viooma������ϵͳ";
$cfg_soft_enname = "Viooma 2008��";
$cfg_soft_devteam = "viooma�Ŷ�";
$cfg_version = 'v2008';
$cfg_ver_lang = 'gb2312'; //�Ͻ��ֹ��޸Ĵ���

//�������ݿ���ͳ��ú���
require_once(VIOOMAINC.'/config_passport.php');
require_once(VIOOMAINC.'/config.php');
if(!$__ONLYCONFIG) include_once(VIOOMAINC.'/pub_db_mysql.php');
if(!$__ONLYDB) include_once(VIOOMAINC.'/inc_functions.php');
?>