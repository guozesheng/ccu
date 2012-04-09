<?php
Iimport('Lister');
class Lister_Passport_Moneycard extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_moneycard',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`cardno` asc',
		    'els' => array(),
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
	
	function Lister_Passport_Moneycard() {
		$this->__construct();
	}
}