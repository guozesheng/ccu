<?php
/**
 * ��URL��д�ļ��������ķ�װ.��������ʵ����.
 * Ϊ�˼���PHP4 ,δ�����Ա����.������php4�²�����
 *
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class UrlRewrite {
	/**
	 * RewriteUrl ��ʽ ��һ��Url ת����RewriteUrl��ʽ��
	 *
	 * @param string $str ��Ҫ������Url
	 * @param bool $level ����������UrlRewrite
	 * @return string RewriteUrl��ʽ��Url
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
	 * RewriteUrl ��ʽ�� ���ڽ�ͨ���Ĳ�ѯ��ת����RewriteUrl��ʽ.
	 *
	 * @param string $query ��Ҫ�����Ĳ�ѯ��
	 * @return string RewriteUrl��ʽ��query��
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
	 * ��һ������ļ�=��ֵ�ύ���ú���������RewriteUrl��ʽ��
	 *
	 * @param string $k ��
	 * @param mix $v ֵ
	 * @return string ���ص�RewriteUrl��
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
	 * ��RewriteUrl��ʽ�Ĵ�������һ�����顣Ĭ�Ͻ�����ȫ����
	 *
	 * @param string $query RewriteUrl��ʽ�Ĵ�
	 * @param string $value �������Դ˱���Ϊ���Ƶ�ȫ��ֵ��.
	 * @param string $tk ʹ�õķָ���Ĭ��Ϊ-
	 * @param define $extract_type �Ƿ񸲸����б���,ͬextract�Ĳ���
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