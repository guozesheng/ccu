<?php
Iimport('Element');
class Element_Exam_Tiku extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_tiku',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array ('id',
				'name',
				'descrip',
				'istop',
				'upid',
				'storetable',
				'allowpush',
				'orderkey',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_Tiku() {
		$this->__construct();
	}
}