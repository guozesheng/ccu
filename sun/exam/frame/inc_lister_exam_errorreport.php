<?php
Iimport('Lister');
class Lister_Exam_ErrorReport extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_errorreport',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`time` desc',
		    'els' => array(),
		    'safearray' => array (
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
	
	function Lister_Exam_ErrorReport() {
		$this->__construct();
	}
}