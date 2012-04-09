<?php
Iimport('Element');
class Element_Passport_College  extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_college',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
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
	
	function Element_Passport_College() {
		$this->__construct();
	}
}