<?php
Iimport('user');
class Exam_AdminUser extends User {
	function __construct() {
		$_adm_config_user = array(
			'uniqueid' => 'userid',
			'uchash' => md5($GLOBALS['rtc']['passport_hash']),
			'prikey' => 'uid',
			'table' => '##__exam_admin',
			'priv' => 'privkey',
			'ucpp' => 'UC_P_A__',	
			'psmethod'=>'md5',
			'usecookie' => true,
			'mainserv' => false,
			'easycheck' => false,
			'ctused' => true,
			'uctime' => $GLOBALS['rtc']['passport_uctime'] ? $GLOBALS['rtc']['passport_uctime'] :30,
			'psckmethod' => 'ppframe',
			'ucpre' => '',
			'domain' => $GLOBALS['rtc']['domain'],
			'ctused' => true,
		);
		
		$this->UpperClass = 'Admin_User';
		$this->SameServer = $GLOBALS['base_config']['server_independ'] ? 0 : 1;
		
		parent::__construct($_adm_config_user);
		
	}
	
	function Exam_AdminUser() {
		$this->__construct();
	}
}