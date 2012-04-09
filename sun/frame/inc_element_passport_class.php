<?php
Iimport('Element');
class Element_Passport_Class extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_class',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => array(
		    		'id',
		    		'classname',
		    		'grade',
		    		'cid',
		    		'ctype',
		    		'allowapply',
		    		'apmtype',
		    		'apmoney',
		    		'applysh',
		    	),
		 	);
		 	$configs['els'] = $configs['safearray'];
		parent::__construct($configs);
	}
	
	function Element_Passport_Class() {
		$this->__construct();
	}
}