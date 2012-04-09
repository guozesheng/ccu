<?php
Iimport('Element');
class Element_Exam_Filehash  extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_filehash',
		    'prikey' => 'hash',
		    'unikey' => 'hash',
		    'auto' => false,
		    'usecache' => true,
		    'safearray' => 
		    array ('hash',
				'time',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_Filehash() {
		$this->__construct();
	}
}