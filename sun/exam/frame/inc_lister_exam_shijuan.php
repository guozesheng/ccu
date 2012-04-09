<?php
Iimport('Lister');
class Lister_Exam_Shijuan extends Lister {
	function __construct() {
		$configs = array (
		    'table' => '##__exam_shijuan',
		    'where' => '',	//need to set
		    'limit' => '',
		    'orderby' => '`time` desc',
		    'els' => array(),
		    'safearray' => array ('id',
				'title',
				'descrip',
				'dotime',
				'limitime',
				'start',
				'end',
				'shitimix',
				'fenmix',
				'timemix',
				'kfmix',
				'fensize',
				'lvlmix',
				'money',
				'mtype',
				'tiku',
				'class',
				'fix',
				'xinli',
				'random',
				'selfpf',
				'open',
				'protect',
				'cjbm',
				'kjdata',
				'protecttime',
				'ktimes',
				'ltime',
				'jrate',
				'jnum',
				'jmtype',
				'adminid',
				'okrate',
				'okban',
				'helplink',
				'dotimes',
				'dtuptime',
				'totals',
				'showadmin',
				'dostyle',
				'allowcolleges',
				'allowpros',
				'allowgrades',
				'allowgroups',
				'allowclass',
				'allowclasses',
				'andor',
				'del',
				'time',
				),
		  );
		parent::__construct($configs);
	}
	
	function Lister_Exam_Shijuan() {
		$this->__construct();
	}
}