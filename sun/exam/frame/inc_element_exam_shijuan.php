<?php
Iimport('Element');
class Element_Exam_Shijuan extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__exam_shijuan',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array ('id',
				'title',
				'descrip',
				'overdescrip',
				'dotime',
				'limitime',
				'start',
				'end',
				'shitimix',
				'fenmix',
				'timemix',
				'descripmix',
				'lvlmix',
				'kfmix',
				'fensize',
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
				'allowbids',
				'andor',
				'del',
				'time',
				),
		 	);
		parent::__construct($configs);
	}
	
	function Element_Exam_Shijuan() {
		$this->__construct();
	}
}