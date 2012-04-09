<?php
Iimport('Element');
class Element_Passport_Money extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => $GLOBALS['_money_table'],
		    'prikey' => 'uid',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => false,
		    'safearray' => array(),
		 	);
		 	$configs['safearray'] = array_merge(array($configs['prikey']),$GLOBALS['money_types']);
		 	$configs['els'] = $configs['safearray'];
		parent::__construct($configs);
	}
	
	function Element_Passport_Money() {
		$this->__construct();
	}
}