<?php
/**
 * ��PathInfo�ļ��������ķ�װ.��������ʵ����.
 * Ϊ�˼���PHP4 ,δ�����Ա����.������php4�²�����
 * Ҫ��php4��ʹ�øù���,��ȷ�� cgi.fix_pathinfo=1 ,����php4û���ṩ�� path_info��֧��.
 *
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class PathInfo {

	/**
	 * Url2PathInfo ��ʽ ��һ��Url ת����PathInfo��ʽ��
	 *
	 * @param string $str ��Ҫ������Url
	 * @param bool $level ����������PathInfo
	 * @return string PathInfo��ʽ��Url
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
	 * Query2PathInfo ��ʽ�� ���ڽ������Ĳ�ѯ��ת����PathInfo��ʽ.
	 *
	 * @param string $query ��Ҫ�����Ĳ�ѯ��
	 * @return string PathInfo��ʽ��query��
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
	 * ��һ������ļ�=��ֵ�ύ���ú���������PathInfo��ʽ��
	 *
	 * @param string $k ��
	 * @param mix $v ֵ
	 * @return string ���ص�PathInfo��
	 */
	function Array2PathInfo($k,$v) {
		if(!is_array($v)) {
			if($v){
				//ת��
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
	 * ��PathInfo��ʽ�Ĵ�������һ�����顣Ĭ�Ͻ�����ȫ����
	 *
	 * @param string $pathinfo ��Ҫ�����Ĵ�
	 * @param defined $extract_type �Ƿ񸲸����б�����ͬ extract
	 * @param string $value �������Դ˱���Ϊ���Ƶ�ȫ��ֵ��.
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
						//��ת��
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