<?php
Iimport('Lister');
class Lister_Exam_Shiti extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_shiti',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`orderkey` desc,`time` desc',
		    'els' => array(),
		    'safearray' => array ('id',
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
	
	function Lister_Exam_Shiti() {
		$this->__construct();
	}
}