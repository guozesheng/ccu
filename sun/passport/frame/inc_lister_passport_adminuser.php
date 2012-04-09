<?php
Iimport('Lister');
class Lister_Passport_AdminUser extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_admin',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`uid` asc',
		    'els' => array(),
		    'safearray' => array ('uid',
				'userid',
		    	'name',
				'privkey',
				'loginban',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Passport_AdminUser() {
		$this->__construct();
	}
}