<?php
Iimport('user');
class PassPort_User extends User {
	
	function __construct() {
		$_user_config_user = array(
			'uniqueid' => $GLOBALS['rtc']['passport_uniqueid'] ? $GLOBALS['rtc']['passport_uniqueid'] : 'username',
			'passkey' =>  $GLOBALS['rtc']['passport_passkey'] ? $GLOBALS['rtc']['passport_passkey'] : 'password',
			'uchash' =>  $GLOBALS['rtc']['passport_hash'],
			'prikey' => $GLOBALS['rtc']['passport_prikey'] ? $GLOBALS['rtc']['passport_prikey'] : 'uid',
			'table' => $GLOBALS['rtc']['passport_table'] ? $GLOBALS['rtc']['passport_table'] : '##__passport',
			'priv' => 'privkey',
			'safekey' => $GLOBALS['rtc']['passport_safekey'] ? $GLOBALS['rtc']['passport_safekey'] : 'safekey',
			'ucpp' => $GLOBALS['rtc']['passport_ucpp'] ? $GLOBALS['rtc']['passport_ucpp'] :'UC_P__',
			'psmethod'=>$GLOBALS['rtc']['passport_psmethod'] ? $GLOBALS['rtc']['passport_psmethod'] :'md5',
			'usecookie'=>true,
			'mainserv' => true,
			'easycheck' => false,
			'uctime' => $GLOBALS['rtc']['passport_uctime'] ? $GLOBALS['rtc']['passport_uctime'] :10,
			'psckmethod' => $GLOBALS['rtc']['passport_psckmethod'] ? $GLOBALS['rtc']['passport_psckmethod'] :'',
			'ucpre' => $GLOBALS['rtc']['passport_ucpre'] ? $GLOBALS['rtc']['passport_ucpre'] :'',
			'domain' => $GLOBALS['rtc']['domain'],
		);
		parent::__construct($_user_config_user);
	}
	
	function PassPort_User() {
		$this->__construct();
	}
}