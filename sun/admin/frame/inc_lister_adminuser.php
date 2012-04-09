<?php
Iimport('Lister');
class Lister_AdminUser extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__frame_admin',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`uid` asc',
		    'els' => array(),
		    'safearray' => array ('uid',
				'userid',
				'name',
				'password',
				'email',
				'telphone',
				'ceilphone',
				'privkey',
				'loginban',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_AdminUser() {
		$this->__construct();
	}
}
?>