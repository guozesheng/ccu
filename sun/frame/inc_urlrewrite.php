<?php
/**
 * 对URL重写的几个函数的封装.该类无需实例化.
 * 为了兼容PHP4 ,未定义成员变量.否则在php4下不兼容
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class UrlRewrite {
	/**
	 * RewriteUrl 格式 将一个Url 转化成RewriteUrl格式串
	 *
	 * @param string $str 需要解析的Url
	 * @param bool $level 更加美化的UrlRewrite
	 * @return string RewriteUrl格式的Url
	 */
	function Url2RewriteUrl($str,$level=0) {
		if(!$str) return false;
		$str = str_replace('&amp;','&',$str);
		$str = str_replace('://','%3a%2f%2f',$str);
		$stra = parse_url($str);
		if($level && eregi('.php',$stra['path'])) {
			$stra['path'] = substr($stra['path'],0,strrpos($stra['path'],'.php'));
			$str = substr($str,0,strrpos($str,'.php'));
		}
		if($stra['query']){
			if($level) return $stra['path'] . UrlRewrite::Query2RewriteUrl($stra['query']);
			else return $stra['path'] .'?'. substr(UrlRewrite::Query2RewriteUrl($stra['query']),1);
		}else {
			return $str;
		}
	}
	/**
	 * RewriteUrl 格式串 用于将通常的查询串转化成RewriteUrl格式.
	 *
	 * @param string $query 需要解析的查询串
	 * @return string RewriteUrl格式的query串
	 */
	function Query2RewriteUrl($query) {
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
				$rt .= UrlRewrite::Array2RewriteUrl($k,$v);
			}
		}
		return $rt.$queryadd;
	}
	/**
	 * 将一个数组的键=》值提交给该函数，返回RewriteUrl格式串
	 *
	 * @param string $k 键
	 * @param mix $v 值
	 * @return string 返回的RewriteUrl串
	 */
	function Array2RewriteUrl($k,$v,$tk='-') {
		if(!is_array($v)) {
			if($v) {
				return $tk.$k.$tk.htmlentities(urlencode(urldecode($v)));
			}
			else {
				return $tk.$k.$tk;
			}
		}else {
			$ar = each($v);
			return UrlRewrite::Array2RewriteUrl($k.'['.$ar['key'].']',$ar['value']);
		}
	}
	/**
	 * 将RewriteUrl格式的串解析到一个数组。默认解析到全局域
	 *
	 * @param string $query RewriteUrl格式的串
	 * @param string $value 解析到以此变量为名称的全局值中.
	 * @param string $tk 使用的分隔符默认为-
	 * @param define $extract_type 是否覆盖已有变量,同extract的参数
	 */
	function RewriteQuery2Value($query,$value='',$tk='-',$extract_type=EXTR_SKIP) {
		if(!$value) {
			$v = &$_GET;
		}else {
			$v = &$GLOBALS[$value];
		}
		if(!$tk) $tk = '-';
		$int = strrpos($query,'.');
		if($int!==false) {
			$query = substr($query,0,$int);
		}
		$arr = explode($tk,$query);
		if(is_array($arr)) {
			for ($i = 0;$i < count($arr);) {
				if($arr[$i] && isset($arr[$i+1]) && $arr[$i+1]) {
					$str = "{$arr[$i]}={$arr[$i+1]}";
					parse_str($str,$str);
					$str = each($str);
					if($extract_type == EXTR_SKIP && isset($v[$str[0]])) {
						//none
					}else {
						if($str[1]){
							$v[$str[0]] = get_magic_quotes_gpc() ? addslashes($str[1]) : $str[1];
							ini_get('register_globals') && $v !== $GLOBALS && $GLOBALS[$str[0]] = $v[$str[0]];
						}
					}
//					$params[$i] = $arr[$i];
//					$params[$i+1] = $arr[$i+1];
					$i += 2; continue;
				}else {
//					if($arr[$i]) {
//						$params[$i] = $arr[$i];
//					}
					$i ++; continue;
				}
			}
		}
	}
	
	function RewriteQueryParams($query,&$params,$tk='-') {
		$params = array();
		if(!$tk) $tk = '-';
		$int = strrpos($query,'.');
		if($int!==false) {
			$query = substr($query,0,$int);
		}
		$arr = explode($tk,$query);
		if(is_array($arr)) {
			foreach ($arr as $k => $v) {
				if($v) $params[] = addslashes($v);
			}
		}
	}
}