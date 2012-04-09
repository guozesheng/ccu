<?php
Iimport('Lister');
class Lister_Passport_Moneylog extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_moneylog',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`time` asc',
		    'els' => array(),
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
	
	function Lister_Passport_Moneylog() {
		$this->__construct();
	}
}