<?php
/**
 * ͨ��ģ�ʹ����࣬�ѷ���
 *
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
require_once(FRAME_ROOT."inc_element.php");
class Module extends Element {
	
	//һ��ģ�Ϳ����ж������,ע������Ĵ���
	var $ModuleElements; 						//ģ��Ԫ��(����)����	array (��ά)
	var $ModuleElementsConfigs;					//ģ��Ԫ��������Ϣ		array (��ά)
	
	var $EClass;								//Ԫ�ز�����
	
	function __construct($configs=array()) {
		parent::__construct($configs);
		//����һ���� Element �������Ԫ��
		$this->EClass = new Element(array());
	}
	
	function Module($configs=array()) {
		return $this->__construct($configs);
	}
	
//	����һ��Ԫ�ص�����,���ڿ���������
	function PushElement($tag="",$array=array()) {
		if(!$tag) return ;
		if(empty($array)) return ;
		$this->ModuleElements[$tag] = $array;
	}
//	����һ��Ԫ�ص�����
	function SetElementConfig($tag="",$array=array()) {
		if(!$tag) return ;
		if(empty($array)) return ;
		$this->ModuleElementsConfigs[$tag] = $array;
	}
//	�õ�һ��Ԫ��,����һ������
	function GetModuleElement($tag="") {
		if(empty($tag)) return array();
		else {
			return $this->ModuleElements[$tag];
		}
	}
//	�õ�һ��Ԫ�ص�ĳ������
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
//	����һ��Ԫ��,$array ����ֵ��E_Update
	function UpdateElement($tag,$array=array()) {
		
	}
//	����һ��Ԫ��,$array����ֵ��E_Insert,���ٿ���������������
	function RecordElement($tag,$array=array()) {
		
	}
//	�Ƴ�һ��Ԫ��
	function RemoveElement($tag,$pri="",$unikey="") {
		
	}
//	�����ݿ�����һ��Ԫ��
	function LoadElementFromDb($tag,$prikey="",$unikey="") {
		
	}
//	�����ݿ�һ���������и���Ԫ��
	function LoadAllElementFromDb($prikeys=array(),$unikeys=array()) {
		
	}
	//�������������������ø�������
}