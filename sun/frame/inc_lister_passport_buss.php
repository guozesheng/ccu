<?php
Iimport('Lister');
class Lister_Passport_Buss extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_buss',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => 'orderkey desc',
		    'els' => array(),
		    'safearray' => 
		    	array (
		    		'id',
		    		'name',
		    		'descrip',
		    		'istop',
		    		'upid',
		    		'ids',
		    		'orderkey',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Passport_Buss() {
		$this->__construct();
	}
}