<?php
Iimport('Lister');
class Lister_Passport_Professional extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_professional',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`pcode` desc',
		    'els' => array(),
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
	
	function Lister_Passport_Professional() {
		$this->__construct();
	}
}