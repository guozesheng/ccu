<?php
Iimport('Lister');
class Lister_Passport_User extends Lister {
	function __construct() {
		$configs = array (
		    'table' => $GLOBALS['rtc']['passport_table'],
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`uid` asc',
		    'els' => array(),
		    'safearray' => array ('uid',
				'username',
		    	'password',
				'safekey',
				'email',
				'regtime',
				'regip',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Passport_User() {
		$this->__construct();
	}
}