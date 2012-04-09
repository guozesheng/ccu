<?php
/**
 * 简易缓存，缓存整个文件输出。按$Identify 做标志
 * 
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
Iimport('Seo');
class EasyCache {
	
	var $Ctime = 3600;
	
	var $Identify = '';
	
	var $CacheDir = '';
	
	var $CacheFile = '';
	
	var $Timestamp;
	
	function __construct($time=3600,$identify='') {
		if ($time) {
			$this->Ctime = $time;
		}
		$this->Timestamp = $GLOBALS['timestamp'] ? $GLOBALS['timestamp'] : time();
		$this->Identify = $identify ? $identify : Seo::CreateUrlIdentify();
		$this->CacheDir = defined('WORK_DIR') ? WORK_DIR . 'temp/' : (defined('ROOT') ? ROOT.'temp/' : './temp/');
		if (!file_exists($this->CacheDir)) {
			PPMakeDir($this->CacheDir);
		}
		$this->CacheDir .= 'easy_cache_dir/';
		if (!file_exists($this->CacheDir)) {
			PPMakeDir($this->CacheDir);
		}
		$this->CacheFile = $this->CacheDir . $this->Identify . '.php';
	}
	
	function EasyCache($time=3600,$identify=''){
		$this->__construct($time,$identify);
	}
	
	function UseCache($client=0,$server=0) {
		$etag = $_SERVER['HTTP_IF_NONE_MATCH'];
		!$client & $client = $this->Ctime;
		list($identify,$settime) = explode(',',$etag);
		if($identify == $this->Identify && $settime + $client > $this->Timestamp) {	//缓存有效
			header('Last-Modified: '. GetHttpdate($settime));
			header('Expires: '.GetHttpdate($settime+$client));
			header('Etag:'.$etag,true,304);
			exit;
		}else {	//缓存无效
			header('Last-Modified: '. GetHttpdate($this->Timestamp));
			header('Etag:'.$this->Identify.','.$this->Timestamp,true);
			//使用服务器缓存
			$stime = $server ? $server : $this->Ctime;
			if (file_exists($this->CacheFile) && filemtime($this->CacheFile) + $this->Ctime > $this->Timestamp) {
				@include($this->CacheFile);
				echo $easycache['c'];
				exit;
			}else {
				//go
			}
		}
	}
	
	function StoreCache() {
		$output = ob_get_flush();
		$output = array('c' => $output);
		$str = "<?php\r\n \$easycache = ". PP_var_export($output).";\r\n?>";
		WriteFile($str,$this->CacheFile);
	}
	
	function RemoveCahce() {
		@unlink($this->CacheFile);
	}
}