<?php
/**
 * Ftp ²Ù×÷Àà
 *
 * @author  òßòÑ@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class Ftp {
	
	var $Stream;
	
	var $Host = '211.103.153.168';
	
	var $Port=21;
	
	var $TimeOut = 90;
	
	var $Username = 'ftp';
	
	var $Password = 'hand2hand';
	
	var $Author = true;
	
	var $Wd = '/';
	
	var $Mode = FTP_BINARY;	//FTP_ASCII OR FTP_BINARY
	
	function Ftp() {
		$this->__construct();
	}
	
	function __construct() {
		$this->Open();
		if($this->Author) {
			$this->Login();
		}
		$this->Wd = ftp_pwd($this->Stream);
	}
	
	function Open() {
		$this->Stream = ftp_connect($this->Host,$this->Port,$this->TimeOut);
	}
	
	function Login() {
		return ftp_login($this->Stream,$this->Username,$this->Password);
	}
	
	function Close() {
		if(is_resource($this->Stream)) {
			ftp_close($this->Stream);
		}
	}
	
	function Mkdir($directory) {
		return @ftp_mkdir($this->Stream,$directory);
	}
	
	function ChDir($directory) {
		$dir = @ftp_chdir($this->Stream,$directory);
		$this->Wd = ftp_pwd($this->Stream);
		return $dir;
	}
	
	function GoDir($directory) {
		$go = $this->ChDir($directory);
		if(!$go) {
			$this->Mkdir($directory);
			$this->ChDir($directory);
		}
	}
	
	function DGoDir($directory) {
		$directory = ereg_replace('/{1,}','/',$directory);
		$dirs = explode('/',$directory);
		$this->GoDir('/');
		foreach ($dirs as $k => $v) {
			$this->GoDir($v);
		}
	}
	
	function Put($remote_file,$local_file) {
		return ftp_put($this->Stream,$remote_file,$local_file,$this->Mode);
	}
	
	function Get($local_file,$remote_file) {
		return ftp_get($this->Stream,$local_file,$remote_file,$this->Mode);
	}
	
	function Raw($command) {
		return ftp_raw($this->Stream,$command);
	}
	
	function Ls() {
		return ftp_nlist($this->Stream,$this->Wd);
	}
}