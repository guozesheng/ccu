<?php
/**
 * Seo 处理类
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
Iimport('urlrewrite');
Iimport('pathinfo');
class Seo {
	
	var $SeoMethod = 'pathinfo';		//rewrite URL重写 、pathinfo 、none 不使用
	
	var $SeoLevel = 0;				//0、基本，1、加强的。
	
	var $Ob_Start= 1;				//是否启用 ob_gzhandler 压缩
	
	var $UseKey = true;				//总数true
	
	var $Params = array();
	
	function __construct() {
		isset($GLOBALS['rtc']['seo_method']) && $this->SeoMethod = $GLOBALS['rtc']['seo_method'];
		isset($GLOBALS['rtc']['seo_level']) && $this->SeoLevel = $GLOBALS['rtc']['seo_level'];
		isset($GLOBALS['rtc']['seo_obstart']) && $this->SeoLevel = $GLOBALS['rtc']['seo_obstart'];
	}
	
	function Seo() {
		$this->__construct();
	}
	
	//Seo 监听开始
	function Start($gz = true){
		if($gz && $this->Ob_Start && function_exists('ob_gzhandler')) {
			ob_start('ob_gzhandler');
		}else {
			ob_start();
		}
	}
	
	//解析url
	function ParseValue($v='') {
		if(strtolower($this->SeoMethod) == 'rewrite' || strtolower($this->SeoMethod) == 'pathinfo') {
			if($this->UseKey) {
				$this->ParseValueUseKey($v);
			}else {
				$this->ParseValueWithoutKey();
			}
		}
	}
	/**
	 * 带键值对的解析变量!
	 *
	 * @param string $v
	 */
	function ParseValueUseKey($v='') {
		if($v) {
			isset($_SERVER['QUERY_STRING']) && UrlRewrite::RewriteQuery2Value($_SERVER['QUERY_STRING'],$v);
			isset($_SERVER['PATH_INFO']) && PathInfo::PathInfo2Value($_SERVER['PATH_INFO'],$v);
		}else {
			isset($_SERVER['QUERY_STRING']) && UrlRewrite::RewriteQuery2Value($_SERVER['QUERY_STRING']);
			isset($_SERVER['PATH_INFO']) && PathInfo::PathInfo2Value($_SERVER['PATH_INFO']);
		}
	}
	/**
	 * 无键值对的解析变量到Params!
	 * 需要自己定义每个变量的意义
	 *
	 */
	function ParseValueWithoutKey() {
		if($_SERVER['QUERY_STRING']) {
			UrlRewrite::RewriteQueryParams($_SERVER['QUERY_STRING'],$this->Params);
		}

		if($_SERVER['PATH_INFO']) {
			PathInfo::PathInfoParams($_SERVER['PATH_INFO'],$this->Params);
		}
	}
	/**
	 * 返回无键值对解析的变量
	 * 按编号索引
	 *
	 * @param number $i
	 * @return string
	 */
	function GetParams($i=0) {
		return $this->Params[$i];
	}
	//处理结束,输出缓存
	function Over() {
		$output = ob_get_clean();
		if(strtolower($this->SeoMethod) == 'rewrite' || strtolower($this->SeoMethod) == 'pathinfo') {
			$output = preg_replace(
			array("/\<a(\s*[^\>]+\s*)href\=([^\"\'>\s]+[\.php]?[^\"\'>\s]*)/ies","/\<form(\s*[^\>]+\s*)action\=([^\"\'>\s]+[\.php]?[^\"\'>\s]*)/ies"),
			array("\$this->UrlChange('\\2','<a\\1href=\"')","\$this->UrlChange('\\2','<form\\1action=\"')"),
			$output
			);
		}
		$this->Start();
		echo $output;
	}
	
	//美化Url
	function UrlChange($url,$tag,$ext='.html') {
		return stripslashes($tag).$this->BaseUrlChange($url,$ext).'"';
	}
	
	function BaseUrlChange($url,$seomethod='',$seolevel='',$ext='.html'){
		$ua = parse_url($url);
		if(empty($ua['query'])) $ext = '';
		$seomethod = in_array(strtolower($seomethod),array('rewrite','pathinfo')) ? $seomethod : $this->SeoMethod;
		$seolevel = $seolevel ? 1 : 0;
		
		if(ereg("^http|ftp|telnet|mms|rtsp",$url)===false || $ua['host'] == $_SERVER['HTTP_HOST']) {
			$url = $ua['path']. ($ua['query'] ? '?'.$ua['query'] : '') . ($ua['fragment'] ? '#'.$ua['fragment'] : '');
			if(strpos($url,'#')!==false){
				$add = substr($url,strpos($url,'#'));
				$url = str_replace($add,'',$url);
			}
			if(strtolower($seomethod) == 'rewrite') {
				$url = UrlRewrite::Url2RewriteUrl($url,$seolevel);
				$url .= ereg('\.[a-zA-Z]+$',$url)? $add : ($ext.$add);
			}else if(strtolower($seomethod) == 'pathinfo') {
				$url = PathInfo::Url2PathInfo($url,$seolevel);
				$url .= ereg('\.[a-zA-Z]+$',$url)? $add : ($ext.$add);
			}
		}
		return $url;
	}
	//为一个URL获得一个唯一的标志符. $ident 用于区分不同用户,可能你希望不同用户看到不同的结果呢.
	//$hash ,maybe 也许你需要更加多的差别控制呢.再加个参数吧.这个参数可以用来让你的缓存文件更加隐蔽,防止别人运算出你的缓存地址,看到了他本身看不到的内容.
	function CreateUrlIdentify($ident='',$hash='') {
		$get = $_GET;
		ksort($get);
		$identify = md5(serialize($get));
		return md5($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_FILENAME'].$identify.$ident.$hash);
	}
}