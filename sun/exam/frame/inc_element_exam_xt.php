<?php
Iimport('Element');
class Element_Exam_Xt extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_xt',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array ('id',
				'sjid',
				'tiku',
				'stid',
				'type',
				'orderkey',
				'fenzhi',
				'koufenzhi',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_Xt() {
		$this->__construct();
	}
}