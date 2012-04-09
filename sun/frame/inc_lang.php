<?php
/**
 * ���԰�������
 * 
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
define('LANGDIR',FRAME_ROOT.'lang/');
class Lang{
	/**
	 * ���԰�����
	 *
	 * @var array
	 */
	var $Lang=array();
	var $Group=null;
	
	function __construct() {
		$this->Lang = array();
	}
	
	function Lang() {
		$this->__construct();
	}
	/**
	 * ���һ�����Ա�־��ʵ������
	 *
	 * @param string $key ���Ա�־Key
	 * @param string $group ���԰������־
	 * @return string ��־��ʵ������,���û��,�򷵻ظñ�־
	 */
	function GetLang($key) {
		if(!$key) return null;
		//һ����������
		$a = @func_get_arg(1);
		if($a){
			if(isset($this->Lang[$a][$key])) return $this->Lang[$a][$key];
			else return $key;
		}else if($this->Group) {
			if(isset($this->Lang[$this->Group][$key])) return $this->Lang[$this->Group][$key];
			else return $key;
		}else if(isset($this->Lang[$key])) return $this->Lang[$key];
		else return $key;
	}
	
	/**
	 * ���һ�����Ա�־��ʵ������,�����������
	 *
	 * @param string $key ���Ա�־Key
	 * @param string $group ���԰������־
	 * @return string ��־��ʵ������,���û��,�򷵻ظñ�־
	 */
	function GetFullLang($key) {
		if (!$key) {
			return null;
		}
		$a = @func_get_arg(1);
		if(ereg('\.',$key)) {
			$lang = $this->GetLang($key,$a);
			if ($lang!=$key) {
				return $lang;
			}else {
				$msg = explode('.',$key);
				foreach ($msg as $k => $v) {
					if($v) $message .= $this->GetLang($v,$a);
				}
			}
		}else {
			$message = $this->GetLang($key,$a);
		}
		return $message;
	}
	/**
	 * ֱ�ӿ�������һ�����԰�,ֻ�����ڵ�һ������.�����ǵ���������������.
	 * ����Ѿ���������԰�,��ʹ�� LoadLangFromArray()
	 *
	 * @param array $array Ҫ��������԰�����(����ά)
	 * @param string $group �������԰�����,���ղ�����
	 * @return bool
	 */
	function LoadLang($array=array()) {
		//һ����������
		if(!is_array($array)) return false;
		$a = @func_get_arg(1);
		if($a) $this->Lang[$a] = $array;
		else if($this->Group) $this->Lang[$this->Group]=$array;
		else $this->Lang = $array;
		return true;
	}
	/**
	 * ��һ���ļ�������������,�����ļ���ʮһ���Ϸ���PHP�ļ�,��$langarray Ϊ�����������鶨��
	 * �����ǰ���԰��ǿյ�,ֱ��ʹ��LoadLang()��������,����ʹ��LoadLangFromArray()��ȫ����.
	 *
	 * @param string $file ȥ����׺���ļ���
	 */
	function LoadLangFromFile($file,$ext='php') {
		if(!ereg('\.',$file)) $file = $file . '.' . $ext;
		if (!file_exists($file)) {
			$file = GetTemplate($file,$ext,true);
		}
		if (file_exists($file)) {
			@include(GetTemplate($file));
			if($this->Lang) $this->LoadLangFromArray($__lang_array);
			else $this->LoadLang($__lang_array);
		}
	}
	/**
	 * δ����չ
	 *
	 */
	function LoadLangFromDb() {
		
	}
	/**
	 * ��һ������ά�����鰲ȫ�������԰�.���ڶ�ά�Ĳ��ֽ���ʡ��.
	 *
	 * @param array $array
	 * @return bool
	 */
	function LoadLangFromArray($array=array()) {
		//һ������ �� ����
		if(!is_array($array)) return false;
		$a = @func_get_arg(1);
		if($a) {
			foreach ($array as $key => $value) {
				if(!is_array($value)) $this->Lang[$a][$key] = $value;
			}
		}else if($this->Group) {
			foreach ($array as $key => $value) {
				if(!is_array($value)) $this->Lang[$this->Group][$key] = $value;
			}
		}else {
			foreach ($array as $key => $value) {
				if(is_array($value)) $this->LoadLangFromArray($value,$key);
				else $this->Lang[$key] = $value;
			}
		}
		return true;
	}
	
	/**
	 * ���õ�ǰ���԰�������
	 *
	 * @param string $group
	 */
	function SetLangGroup($group=null) {
		$this->Group = $group;
	}
	/**
	 * �����ǰ���԰�������Ϊ��
	 *
	 */
	function ClearLangGroup() {
		$this->SetLangGroup(null);
	}
}