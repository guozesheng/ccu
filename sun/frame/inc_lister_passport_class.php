<?php
Iimport('Lister');
class Lister_Passport_Class extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_class',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '',
		    'els' => array(),
		    'safearray' => 
		    	array (
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
		parent::__construct($configs);
	}
	
	function Lister_Passport_Class() {
		$this->__construct();
	}
}