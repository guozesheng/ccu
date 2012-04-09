<?php
Iimport('Element');
class Element_Passport_Professional extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_professional',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array (
		    	'id',
				'pname',
				'cid',
				'pcode',
				'xuezhi',
				'discript',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Passport_Professional() {
		$this->__construct();
	}
}