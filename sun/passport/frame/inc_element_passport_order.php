<?php
Iimport('Element');
class Element_Passport_Order extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_order',
		    'prikey' => 'id',
		    'unikey' => 'orderno',
		    'auto' => true,
		    'usecache' => false,
		    'safearray' => 
		    array ('id',
				'orderno',
				'uid',
				'mtype',
				'num',
				'fee',
				'time',
				'paytime',
				'state',
				'descrip',
				'title',
				'payer',
				'tool',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Passport_Order() {
		$this->__construct();
	}
}