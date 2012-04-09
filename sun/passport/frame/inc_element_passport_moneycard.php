<?php
Iimport('Element');
class Element_Passport_Moneycard extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_moneycard',
		    'prikey' => 'cardno',
		    'unikey' => 'password',
		    'auto' => false,
		    'usecache' => false,
		    'safearray' => 
		    array (
		    	'cardno',
		    	'password',
		    	'createtime',
		    	'date',
		    	'timeout',
		    	'mnum',
		    	'mtype',
		    	'state',
		    	'useruse',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Passport_Moneycard() {
		$this->__construct();
	}
}