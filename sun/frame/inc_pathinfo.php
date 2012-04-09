<?php
/**
 * 对PathInfo的几个函数的封装.该类无需实例化.
 * 为了兼容PHP4 ,未定义成员变量.否则在php4下不兼容
 * 要在php4下使用该功能,请确保 cgi.fix_pathinfo=1 ,否则php4没有提供对 path_info的支持.
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class PathInfo {

	/**
	 * Url2PathInfo 格式 将一个Url 转化成PathInfo格式串
	 *
	 * @param string $str 需要解析的Url
	 * @param bool $level 更加美化的PathInfo
	 * @return string PathInfo格式的Url
	 */
	function Url2PathInfo($str,$level=0) {
		if(!$str) return false;
		$str = str_replace('&amp;','&',$str);
		$str = str_replace('://','%3a%2f%2f',$str);
		$stra = parse_url($str);
		if($level && eregi('.php',$stra['path'])){
			$stra['path'] = substr($stra['path'],0,strrpos($stra['path'],'.php'));
			$str = substr($str,0,strrpos($str,'.php'));
		}
		if($stra['query']){
			return $stra['path'] . PathInfo::Query2PathInfo($stra['query']);
		}else {
			return $str;
		}
	}
	
	/**
	 * Query2PathInfo 格式串 用于将正常的查询串转化成PathInfo格式.
	 *
	 * @param string $query 需要解析的查询串
	 * @return string PathInfo格式的query串
	 */
	function Query2PathInfo($query) {
		if(!$query) return '';
		$output = '';
		$rt = '' ;
		
		if(ereg('\.[a-zA-Z]+$',$query)) {
			$query2 = $query;
			$query = substr($query,0,strrpos($query,'.'));
			$queryadd = strrchr($query2,'.');
		}
		
		parse_str($query,$output);
		if(is_array($output)) {
			foreach ($output as $k => $v) {
				$rt .= PathInfo::Array2PathInfo($k,$v);
			}
		}
		return $rt.$queryadd;
	}
	/**
	 * 将一个数组的键=》值提交给该函数，返回PathInfo格式串
	 *
	 * @param string $k 键
	 * @param mix $v 值
	 * @return string 返回的PathInfo串
	 */
	function Array2PathInfo($k,$v) {
		if(!is_array($v)) {
			if($v){
				//转义
				if(ereg('//',$v)) {
					$v = str_replace('//','||||',$v);
					$rt = '/'.$k . '/' .htmlentities(urlencode(urldecode($v)));
					return $rt;
				}
				return '/'.$k . '/' .htmlentities(urlencode(urldecode($v)));
			}
			else {
				return '/'.$k;
			}
		}else {
			$ar = each($v);
			return PathInfo::Array2PathInfo($k.'['.$ar['key'].']',$ar['value']);
		}
	}
	
	/**
	 * 将PathInfo格式的串解析到一个数组。默认解析到全局域
	 *
	 * @param string $pathinfo 需要解析的串
	 * @param defined $extract_type 是否覆盖已有变量，同 extract
	 * @param string $value 解析到以此变量为名称的全局值中.
	 */
	function PathInfo2Value($pathinfo,$value=null,$extract_type=EXTR_SKIP) {
		if(!$value) {
			$v = &$_GET;
		}else {
			$v = &$GLOBALS[$value];
		}
		$int = strrpos($pathinfo,'.');
		if($int!==false) {
			$pathinfo = substr($pathinfo,0,$int);
		}
		$arr = explode('/',$pathinfo);
//		echo '<pre>';print_r($arr);exit;
		if(is_array($arr)) {
			for ($i = 0;$i < count($arr);) {
				if($arr[$i] && isset($arr[$i+1])) {
					$str = "{$arr[$i]}={$arr[$i+1]}";
					parse_str($str,$str);
					$str = each($str);
					if($extract_type == EXTR_SKIP && isset($v[$str[0]])) {
						//none
					}else {
						//反转义
						$v[$str[0]] = get_magic_quotes_gpc() ? addslashes(str_replace('||||','//',$str[1])) : str_replace('||||','//',$str[1]);
						ini_get('register_globals') && $v !== $GLOBALS && $GLOBALS[$str[0]] = $v[$str[0]];
					}
					$i += 2; continue;
				}else {
					$i ++; continue;
				}
			}
		}
	}
	
	function PathInfoParams($pathinfo,&$params) {
		$params = array();
		$int = strrpos($pathinfo,'.');
		if($int!==false) {
			$pathinfo = substr($pathinfo,0,$int);
		}
		$arr = explode('/',$pathinfo);
		if(is_array($arr)) {
			foreach ($arr as $k => $v) {
				if($v) $params[] = addslashes($v);
			}
		}
	}
}