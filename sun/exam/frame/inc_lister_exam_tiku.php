<?php
Iimport('Lister');
class Lister_Exam_Tiku extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_tiku',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`orderkey` desc',
		    'els' => array(),
		    'safearray' => array ('id',
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
	
	function Lister_Exam_Tiku() {
		$this->__construct();
	}
}