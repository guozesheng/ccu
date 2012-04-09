<?php
Iimport('Element');
class Element_Frame_Syslogs extends Element {
	
	function __construct() {
		$configs = array (
		    'table' => '##__frame_syslogs',
		    'prikey' => 'id',
		    'unikey' => '',
		    'auto' => true,
		    'usecache' => true,
		    'safearray' => 
		    array ('id',
				'adminid',
				'adminname',
				'module',
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
	
	function Element_Frame_Syslogs() {
		$this->__construct();
	}
}