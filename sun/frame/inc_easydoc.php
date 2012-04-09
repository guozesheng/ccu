<?php
class EasyDoc {
	
	var $Data;
	
	function SetData($data) {
		$this -> Data = 
'<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">' 
. $data . 
'</html>';
	}
	
	function DownLoad($filename) {
		header("Content-type: application/msword");
		header("Content-disposition: attachment; filename=$filename");
		echo $this->Data;
	}
	
}