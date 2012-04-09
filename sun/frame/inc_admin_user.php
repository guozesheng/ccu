<?php
Iimport('user');
class Admin_User extends User {
	
	function __construct() {
		$_adm_config_user = array(
			'uniqueid' => 'userid',
			'passkey' => 'password',
			'uchash' => md5($GLOBALS['rtc']['passport_hash']),
			'prikey' => 'uid',
			'table' => '##__frame_admin',
			'priv' => 'privkey',
			'ucpp' => 'UC_P_A__',	
			'psmethod'=>'md5',
			'usecookie' => true,
			'mainserv' => true,
			'easycheck' => false,
			'ctused' => true,
			'uctime' => $GLOBALS['rtc']['passport_uctime'] ? $GLOBALS['rtc']['passport_uctime'] :30,
			'psckmethod' => 'ppframe',
			'ucpre' => '',
			'domain' => $GLOBALS['rtc']['domain'],
		);
		parent::__construct($_adm_config_user);
	}
	
	function Admin_User() {
		$this->__construct();
	}
}