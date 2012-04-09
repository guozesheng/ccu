<?php
Iimport('Lister');
class Lister_Passport_Order extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_order',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`time` desc',
		    'els' => array(),
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
	
	function Lister_Passport_Order() {
		$this->__construct();
	}
}