<?php
Iimport('Element');
class Element_Exam_Class extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_class',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array ('id',
				'name',
				'cktimes',
				'oknum',
				'okban',
				'descrip',
				'istop',
				'upid',
				'ids',
				'orderkey',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_Class() {
		$this->__construct();
	}
}