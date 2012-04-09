<?php
Iimport('Lister');
class Lister_Exam_Kaojuan extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_kaojuan',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`time` desc',
		    'els' => array(),
		    'safearray' => array ('id',
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
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Exam_Kaojuan() {
		$this->__construct();
	}
}