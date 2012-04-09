<?php
Iimport('Lister');
class Lister_Passport_College extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_college',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`code` desc',
		    'els' => array(),
		    'safearray' => 
		    array (
		    	'id',
				'name',
				'code',
				'discript',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Passport_College() {
		$this->__construct();
	}
}