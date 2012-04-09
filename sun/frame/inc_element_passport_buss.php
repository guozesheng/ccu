<?php
Iimport('Element');
class Element_Passport_Buss extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__passport_buss',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => array(
		    		'id',
		    		'name',
		    		'descrip',
		    		'istop',
		    		'upid',
		    		'ids',
		    		'orderkey',
		    	),
		 	);
		 	$configs['els'] = $configs['safearray'];
		parent::__construct($configs);
	}
	
	function Element_Passport_Buss() {
		$this->__construct();
	}
}