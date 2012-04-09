<?php
Iimport('Element');
class Element_Exam_Shiti extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_shiti',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array ('id',
				'title',
				'type',
				'upid',
				'xuanxiang',
				'daan',
				'jieda',
				'fen',
				'kf',
				'unuse',
				'author',
				'adminid',
				'tikus',
				'level',
				'tag',
				'time',
				'orderkey',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_Shiti() {
		$this->__construct();
	}
}