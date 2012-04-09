<?php
Iimport('Element');
class Element_Exam_Kaojuan extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_kaojuan',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array ('id',
				'name',
				'shijuan',
				'author',
				'start',
				'end',
				'content',
				'damix',
				'totalsorce',
				'sorce',
				'plusorce',
				'close',
				'ip',
				'time',
				'lastupdate',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_Kaojuan() {
		$this->__construct();
	}
}