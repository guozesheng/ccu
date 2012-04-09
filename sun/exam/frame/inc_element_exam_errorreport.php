<?php
Iimport('Element');
class Element_Exam_ErrorReport extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_errorreport',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array (
		    	'id',
				'tiku',
				'stid',
				'author',
				'title',
				'why',
				'time',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_ErrorReport() {
		$this->__construct();
	}
}