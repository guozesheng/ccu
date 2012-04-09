<?php
Iimport('Lister');
class Lister_Exam_Class extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_class',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`orderkey` desc',
		    'els' => array(),
		    'safearray' => array ('id',
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
	
	function Lister_Exam_Class() {
		$this->__construct();
	}
}