<?php
Iimport('Lister');
class Lister_Exam_Xt extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_xt',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`orderkey` desc',
		    'els' => array(),
		    'safearray' => array ('id',
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
	
	function Lister_Exam_Xt() {
		$this->__construct();
	}
}