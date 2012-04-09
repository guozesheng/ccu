<?php
/**
 * 语言包处理类
 * 
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
define('LANGDIR',FRAME_ROOT.'lang/');
class Lang{
	/**
	 * 语言包数组
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
	 * 获得一个语言标志的实际语言
	 *
	 * @param string $key 语言标志Key
	 * @param string $group 语言包分组标志
	 * @return string 标志的实际语言,如果没有,则返回该标志
	 */
	function GetLang($key) {
		if(!$key) return null;
		//一个隐含参数
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
	 * 获得一个语言标志的实际语言,分析语言组合
	 *
	 * @param string $key 语言标志Key
	 * @param string $group 语言包分组标志
	 * @return string 标志的实际语言,如果没有,则返回该标志
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
	 * 直接快速载入一个语言包,只能用于第一次载入.将覆盖掉所有已载入语言.
	 * 如果已经载入过语言包,请使用 LoadLangFromArray()
	 *
	 * @param array $array 要载入的语言包数组(最大二维)
	 * @param string $group 载入语言包分组,留空不分组
	 * @return bool
	 */
	function LoadLang($array=array()) {
		//一个隐含参数
		if(!is_array($array)) return false;
		$a = @func_get_arg(1);
		if($a) $this->Lang[$a] = $array;
		else if($this->Group) $this->Lang[$this->Group]=$array;
		else $this->Lang = $array;
		return true;
	}
	/**
	 * 从一个文件载入语言配置,语言文件包十一个合法的PHP文件,以$langarray 为数组名的数组定义
	 * 如果当前语言包是空的,直接使用LoadLang()快速载入,否则使用LoadLangFromArray()安全载入.
	 *
	 * @param string $file 去除后缀的文件名
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
	 * 未来扩展
	 *
	 */
	function LoadLangFromDb() {
		
	}
	/**
	 * 从一个最大二维的数组安全载入语言包.大于二维的部分将被省略.
	 *
	 * @param array $array
	 * @return bool
	 */
	function LoadLangFromArray($array=array()) {
		//一个隐含 组 参数
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
	 * 设置当前语言包工作组
	 *
	 * @param string $group
	 */
	function SetLangGroup($group=null) {
		$this->Group = $group;
	}
	/**
	 * 清楚当前语言包工作组为空
	 *
	 */
	function ClearLangGroup() {
		$this->SetLangGroup(null);
	}
}