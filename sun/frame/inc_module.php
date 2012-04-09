<?php
/**
 * 通用模型处理类，已废弃
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
require_once(FRAME_ROOT."inc_element.php");
class Module extends Element {
	
	//一个模型可能有多个属性,注意外键的处理
	var $ModuleElements; 						//模型元素(属性)数组	array (二维)
	var $ModuleElementsConfigs;					//模型元素配置信息		array (二维)
	
	var $EClass;								//元素操作类
	
	function __construct($configs=array()) {
		parent::__construct($configs);
		//创建一个空 Element 对象操作元素
		$this->EClass = new Element(array());
	}
	
	function Module($configs=array()) {
		return $this->__construct($configs);
	}
	
//	设置一个元素的属性,不在考虑批处理
	function PushElement($tag="",$array=array()) {
		if(!$tag) return ;
		if(empty($array)) return ;
		$this->ModuleElements[$tag] = $array;
	}
//	设置一个元素的配置
	function SetElementConfig($tag="",$array=array()) {
		if(!$tag) return ;
		if(empty($array)) return ;
		$this->ModuleElementsConfigs[$tag] = $array;
	}
//	得到一个元素,返回一个数组
	function GetModuleElement($tag="") {
		if(empty($tag)) return array();
		else {
			return $this->ModuleElements[$tag];
		}
	}
//	得到一个元素的某个属性
	function GetModuleElementValue($tag="",$key="") {
		if(empty($tag)) return null;
		else {
			if(isset($this->ModuleElements[$tag][$key])) {
				return $this->ModuleElements[$tag][$key];
			}else {
				return null;
			}
		}
	}
//	更新一个元素,$array 将赋值给E_Update
	function UpdateElement($tag,$array=array()) {
		
	}
//	插入一个元素,$array将赋值给E_Insert,不再考虑批量处理能力
	function RecordElement($tag,$array=array()) {
		
	}
//	移除一个元素
	function RemoveElement($tag,$pri="",$unikey="") {
		
	}
//	从数据库载入一个元素
	function LoadElementFromDb($tag,$prikey="",$unikey="") {
		
	}
//	从数据库一次载入所有附加元素
	function LoadAllElementFromDb($prikeys=array(),$unikeys=array()) {
		
	}
	//其他函数需重载来设置附加属性
}