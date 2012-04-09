<?php
Iimport('Element');
class Element_Passport_Moneylog extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_moneylog',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => false,
		    'safearray' => 
		    array ('id',
				'uid',
				'num',
				'yue',
				'mtype',
				'time',
				'descrip',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Passport_Moneylog() {
		$this->__construct();
	}
}