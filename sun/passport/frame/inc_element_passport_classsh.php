<?php
Iimport('Element');
class Element_Passport_ClassSh extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_classsh',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => array(
		    		'id',
		    		'uid',
		    		'classid',
		    		'time',
		    		'sh',
		    	),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Passport_ClassSh() {
		$this->__construct();
	}
}