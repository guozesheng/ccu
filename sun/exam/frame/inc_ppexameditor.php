<?php
Iimport('FCKeditor');
class PPExamEditor extends FCKeditor {
	function __construct($instanceName,$value='',$action='') {
		parent::__construct($instanceName,$value,$action);
		$this->BasePath = $GLOBALS['base_config']['exam_root'].'fckeditor/';
	}
	
	function PPExamEditor($instanceName,$value='',$action='') {
		$this->__construct($instanceName,$value,$action);
	}
}