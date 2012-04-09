<?php
Iimport('Lister');
class Lister_Passport_ClassSh extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__passport_classsh',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '',
		    'els' => array(),
		    'safearray' => 
		    	array (
		    		'id',
		    		'uid',
		    		'classid',
		    		'time',
		    		'sh',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Passport_ClassSh() {
		$this->__construct();
	}
}