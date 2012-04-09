<?php
Iimport('Lister');
class Lister_Exam_Filehash extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_filehash',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`time` desc',
		    'els' => array(),
		    'safearray' => array ('hash',
				'time',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Exam_Filehash() {
		$this->__construct();
	}
}