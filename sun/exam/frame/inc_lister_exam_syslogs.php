<?php
Iimport('Lister');
class Lister_Exam_Syslogs extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_shijuan',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`dotime` desc',
		    'els' => array(),
		    'safearray' => array ('id',
				'adminid',
				'adminname',
				'dotype',
				'doaction',
				'identifyid',
				'dourl',
				'dotime',
				'doip',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Exam_Syslogs() {
		$this->__construct();
	}
}