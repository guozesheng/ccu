<?php
/**
 *ͨ��Ԫ��������
 * 
 * ��װ��ͨ����Ԫ�ز���
 * 
 * @author  ����@@ <hubin999421@gmail.com>
 * @copyright http://www.ppframe.com
 * @version $id
 * @todo ��Ӷ�Ԫ�ظ������Եķ���.(��������,���б�)
 */
Iimport('dbsql');
class Element {
	/**
	 * Ԫ������
	 *
	 * @var string $PriKey
	 */
	var $PriKey;							//����
	/**
	 * Ԫ��Ψһ��
	 *
	 * @var string $UniKey
	 */
	var $UniKey;							//Ψһ��
	/**
	 * Ԫ��������������
	 *
	 * @var array $Elements
	 */
	var $Elements=array();					//Ԫ����������
	/**
	 * @see $Elements
	 * 
	 */
	var $E;
	/**
	 * Ԫ�ش洢������
	 *
	 * @var string $Table
	 */
	var $Table;								//Ԫ�ش洢������
	/**
	 * Ԫ�ذ�ȫ����
	 *
	 * @var array $SafeArray
	 */
	var $SafeArray;							//��ȫ����
	/**
	 * Ԫ�������Ƿ�����
	 *
	 * @var bool $pri_auto_increment
	 */
	var $pri_auto_increment = false;		//�����Ƿ�����
	/**
	 * Ԫ���ֶ��б�
	 *
	 * @var array $Els
	 */
	var $Els;
	/**
	 * ����Ԫ���û�������
	 *
	 * @var array $E_Insert
	 */
	var $E_Insert;							//����Ԫ��������
	/**
	 * �޸�Ԫ��������
	 *
	 * @var array $E_Update
	 */
	var $E_Update;							//�޸�Ԫ��������(��������,��Ψһ��)
	/**
	 * ���ݿ������
	 *
	 * @var object $Esql
	 */
	var $Esql;								//���ݿ��������
	/**
	 * ������Ԫ�ص�ID
	 *
	 * @var number
	 */
	var $E_InsertId;
	/**
	 * �Ƿ�ʹ�û��濪��
	 *
	 * @var bool $UseCache
	 */
	var $UseCache=false;					//�Ƿ�ʹ�û���
	/**
	 * ������Чʱ��
	 *
	 * @var number $CacheTime
	 */
	var $CacheTime=100;						//������Ч��
	/**
	 * ��ʱ�ļ���ż�
	 *
	 * @var string $TempDir
	 */
	var $TempDir = "";
	/**
	 * �����ļ�
	 *
	 * @var string $CacheFile
	 */
	var $CacheFile = "";
	/**
	 * �����ļ���չ��
	 *
	 * @var string $CacheFileExt
	 */
	var $CacheFileExt = '.php';
	//php5 ���캯��
	/**
	 * PHP5���캯��
	 *
	 * @param array $configs ������Ϣ����
	 * @see SetAllConfig()
	 */
	function __construct($configs=array()) {
		$this->SetAllConfig($configs);
		$this->TempDir = WORK_DIR.'temp/frame_temp_dir/';
		if($this->UseCache && !file_exists($this->TempDir)){
			PPMakeDir($this->TempDir);
		}
		$this->Esql = new dbsql();
		$this->E = &$this->Elements;
		if (defined('Admin_Safe') && Admin_Safe) {
			$this->UseCache = false;
		}else {
			$this->ExecCacheFile($this->E[$this->PriKey],$this->E[$this->UniKey]);
		}
	}
	/**
	 * php4 ���캯��
	 *
	 * @see __construct()
	 */
	function Element($configs=array()) {
		return $this->__construct($configs);
	}
	
	//��ʼ��
	function Init() {
		
	}
	/**
	 * ����Ԫ��
	 *
	 * @param array $element Ҫ�����Ԫ������
	 */
	function LoadElements($element=array()) {
		if(!is_array($element)) return ;
		foreach ($element as $k => $v) {
			if(!is_array($v)) {
				$this->Elements[$k] = $v;
			}
		}
	}
	/**
	 * ��¼һ�����߶��Ԫ��,ʹ��E_Insert �������������Ψһ���ظ����
	 * @param string $type
	 * @param number $w ά��(һ�β���һ����һά,��������Ƕ�ά)
	 * @return bool
	 */
	function DoRecord($type='insert',$w=1) {
		//safe $this->E_Insert first
		dbsql::safeArrayPre($this->E_Insert,$this->SafeArray,$w);
		//CheckOutkeys $this->E_Insert first if autoincrement prikey
		if($this->pri_auto_increment) {
			$this->CheckOutKeys($this->E_Insert,array($this->PriKey));
		}
		//do insert
		$do = $this->Esql->DoInsert($this->E_Insert,$this->Table,$type);
		$this -> E_InsertId = $this->Esql->dbLastId;
		return $do;
	}
	/**
	 * �����жϵ�����������¼��Ԫ������,����ʹ�ö�ά����һ�����ö��Ԫ��
	 *
	 * @param array $element
	 * @todo �ж������ʽ�ų�����
	 */
	function SetRecord($element=array()) {
//		�˴���$elements ������ϸ�ʽ,�������.�������ж�
//		$this->E_Insert = $element;
		if(!is_array($element)) return ;
		$i = $type = 0;
		foreach ($element as $k => $v){
			//��������
			if(!is_array($v) && ($i==0||$type==1)){
				$type = 1;
				//��¼һ����Ч����
				$this->E_Insert[$k] = addslashes(stripslashes(str_replace('##__','@@__',$v)));
			}else if(is_array($v) && ($i==0||$type==2)){
				$type = 2;
				foreach ($v as $kk => $vv) {
					$this->E_Insert[$k][$kk] = addslashes(stripslashes(str_replace('##__','@@__',$vv)));
				}
			}else {
				continue;
			}
			$i = 1;
		}
	}
	/**
	 * @see SetRecord()
	 */
	function SetInsert($element=array()) {
		$this->SetRecord($element);
	}
	
	/**
	 * ����һ��Ԫ�� ʹ�� E_Update (���������������Ψһ��)
	 * �ɹ�����true��ʧ�ܷ���false
	 *
	 * @return bool
	 */
	function DoUpdate($unset=array()) {
		//safe	$this->E_Update first
		dbsql::safeArrayPre($this->E_Update,$this->SafeArray,1);
		//CheckOutKeys $this->E_Update first if autoincrement prikey
		/* ����ı����,�������ط��ų�.�����޷�����
		if($this->pri_auto_increment) {
			$this->CheckOutKeys($this->E_Update,array($this->PriKey));
		}
		*/
		//���ñ����ֶ�
		if($this->pri_auto_increment) {
			$pt = array($this->PriKey);
		}
		if($this->PriKey && !empty($this->E_Update[$this->PriKey])) {
			$where = "{$this->PriKey}='{$this->E_Update[$this->PriKey]}'";
		}else if($this->UniKey && !empty($this->E_Update[$this->UniKey])) {
			$where = "`{$this->UniKey}`='{$this->E_Update[$this->UniKey]}'";
			$pt[] = $this->UniKey;
		}else {
			return false;
		}
		if($this->E_Update) {
			return $this->Esql->DoUpdate($this->E_Update,$this->Table,$where,$unset,$pt);
		}else {
			return true;
		}
	}
	
	function DoCommonUpdate($where) {
		if($this->E_Update) {
			return $this->Esql->DoUpdate($this->E_Update,$this->Table,$where);
		}else {
			return true;
		}
	}
	/**
	 * �����������µ�Ԫ������
	 *
	 * @param array $element
	 */
	function SetUpdate($element=array()) {
		if(!is_array($element)) return ;
		foreach ($element as $k => $v) {
			if(!is_array($v)) {
				$this->E_Update[$k] = addslashes(stripslashes(str_replace('##__','@@__',$v)));
			}
		}
	}
	/**
	 * ʹ����������Ψһ���Ƴ�һ��Ԫ��
	 *
	 * @param mix $pri �����ļ�ֵ
	 * @param mix $unikey Ψһ����ֵ
	 * @return bool
	 */
	function DoRemove($pri="",$unikey="") {
		//to do set $where
		if($pri && $this->PriKey) {
			$where = "{$this->PriKey}='{$pri}'";
		}else if($unikey && $this->UniKey) {
			$where = "{$this->UniKey}='{$unikey}'";
		}else {
			return false;
		}
		return $this->Esql->DoDelete($this->Table,$where);
	}
	
	function DoComonRemove($where) {
		return $this->Esql->DoDelete($this->Table,$where);
	}
	/**
	 * ���㻺���ļ��Ĵ��λ��
	 *
	 * @param mix $prikey ����ֵ
	 * @param mix $unikey Ψһ��ֵ
	 */
	function ExecCacheFile($prikey='',$unikey='') {
		if($prikey > 0) {
			$this->CacheFile = $this->TempDir . md5($this->Table."_".$prikey.serialize($this->Els)).$this->CacheFileExt;
		}else if($unikey) {
			$this->CacheFile = $this->TempDir . md5($this->Table."_".$unikey.serialize($this->Els)).$this->CacheFileExt;
		}else {
			$this->CacheFile = '';
		}
	}
	/**
	 * ����Ԫ����Ϣ����������ݻ��棬��ʹ�û���
	 * ʹ����������Ψһ��������ʹ������
	 * 
	 * @param mix $prikey ����ֵ
	 * @param mix $unikey Ψһ��ֵ
	 */
	function Load($prikey='',$unikey='') {
		$this->ExecCacheFile($prikey,$unikey);
		if($this->UseCache && $this->CacheFile && file_exists($this->CacheFile)) {
			if(time() - filemtime($this->CacheFile) < $this->CacheTime) {
				@include($this->CacheFile);
				$this -> Elements = $Cache_Element;
			}else {
				$this->LoadFromDb($prikey,$unikey);
			}
		}else {
			$this->LoadFromDb($prikey,$unikey);
		}
	}
	/**
	 * ǿ�ƴ����ݿ�����Ԫ����Ϣ,������Ϣ�����绺���ļ�
	 * ����Ԫ�غ�ʹ�ø÷�����������һ�Σ����ɸ��»���
	 *
	 * @param mix $prikey ����ֵ
	 * @param mix $unikey Ψһ��ֵ
	 * @return bool
	 */
	function LoadFromDb($prikey="",$unikey="") {
		if(is_array($this->Els)) {
			$el = implode(',',$this->Els);
		}
		!$el && $el = "*";
		$sql = "Select {$el} From {$this->Table} where ";
		if($this->PriKey && $prikey) {
			if (ereg('`',$this->PriKey)) {
				$sql .= "{$this->PriKey}='{$prikey}'";
			}else {
				$sql .= "`{$this->PriKey}`='{$prikey}'";
			}
		}else if($this->UniKey && $unikey) {
			if (ereg('`',$this->UniKey)) {
				$sql .= "{$this->UniKey}='{$unikey}'";
			}else {
				$sql .= "`{$this->UniKey}`='{$unikey}'";
			}
		}else {
			return false;
		}
		//to do: add cache process
		$this->Elements = $this->Esql->GetOneArray($sql);
		//write cache
		if($this->UseCache && is_writable($this->TempDir) && $this->CacheFile) {
			$string = PP_var_export($this->Elements);
			$string = "<?php\r\n \$Cache_Element=".$string."\r\n?>";
			WriteFile($string,$this->CacheFile);
		}
	}
    /**
     * ��ø�Ԫ�ص���������
     *
     * @return array
     */
	function GetElement() {
		return $this->Elements;
	}
	/**
	 * ��ø�Ԫ�����Ե�һ��ֵ
	 *
	 * @param string $key ���Ա�ǣ����ݿ��ֶΣ�
	 * @return mix
	 */
	function GetElementValue($key="") {
		if(isset($this->Elements[$key])) return $this->Elements[$key];
		else return null;
	}
	/**
	 * ����Ԫ�ش洢��
	 *
	 * @param string $table ���ݿ����
	 */
	function SetTable($table="table") {
		$this->Table = trim($table);
	}
	/**
	 * ����Ԫ������
	 *
	 * @param string $prikey ���ݿ������ֶ���
	 */
	function SetPriKey($prikey="prikey") {
		$this->PriKey = trim($prikey);
	}
	/**
	 * ����Ԫ��Ψһ��
	 *
	 * @param string $unikey ���ݿ�Ψһ�ֶ���
	 */
	function SetUniKey($unikey="unikey") {
		$this->UniKey = trim($unikey);
	}
	/**
	 * ���������Ƿ�����
	 *
	 * @param bool $type true,�� false,��
	 */
	function SetPriAutoIncrement($type=false) {
		if($type) $this->pri_auto_increment = true;
		else $this->pri_auto_increment = false;
	}
	/**
	 * ���û���״̬
	 *
	 * @param boo $type �Ƿ�ʹ�û����־��true���� false����
	 * @param number $ct ����ʱ��
	 */
	function SetCacheState($type=true,$ct=100) {
		if($type){
			$this->UseCache = $type;
			$this->CacheTime = $ct;
		}
		else $this-> UseCache = false;
	}
	/**
	 * CACHE ���أ�
	 *
	 * @param bool $bool
	 */
	function EnableCache($bool=true) {
		$this->UseCache = $bool;
	}
	/**
	 * ���ð�ȫ��������
	 *
	 * @param array $safearray
	 */
	function SetSafeArray($safearray=array()) {
		if(is_array($safearray) && !empty($safearray)) $this->SafeArray = $safearray;
		else $this->SafeArray=array();
	}
	/**
	 * ʹ��һ��������������������Ϣ,��ݺ���
	 * 
	 * @param array $configs ������Ϣ����
	 * @example $configs = array(
							'table' => $table,
							'prikey' => $prikey,
							'unikey' => $unikey,
							'auto' => $autoincrement,
							'usecache' => $usecache,
							'els'=> array(),
							'safearray'=> $safearray(array)
						);
	 */
	function SetAllConfig($configs=array()) {
		if(!empty($configs['table'])) {
			$this->SetTable($configs['table']);
		}
		if(!empty($configs['prikey'])) {
			$this->SetPriKey($configs['prikey']);
		}
		if(!empty($configs['unikey'])) {
			$this->SetUniKey($configs['unikey']);
		}
		if(!empty($configs['safearray'])) {
			$this->SetSafeArray($configs['safearray']);
		}
		if(!empty($configs['usecache'])) {
			$this->UseCache = true;
		}
		if($configs['els'] && is_array($configs['els'])) $this->Els = $configs['els'];
		if(isset($configs['auto']) && $configs['auto']) {
			$this->SetPriAutoIncrement(true);
		}else if(isset($configs['auto']) && !$configs['auto']) {
			$this->SetPriAutoIncrement(false);
		}
	}
	
	/**
	 * ������������Ϣ,��δʹ��
	 *
	 * @return bool
	 */
	function CheckConfig() {
		if(empty($this->Table)) return false;
	}
	/**
	 * ��һ������,������һ������ļ���Χ,������ļ���Χ��������һ�������ֵ��Χ��
	 *
	 * @param array $array ��Ҫ�����Ƶ�����
	 * @param array $keyarray Ҫ���ų��ļ�����
	 */
	function CheckOutKeys(&$array,$keyarray=array()) {
		if(!$keyarray) return ;
		if(!is_array($array)) return ;
		foreach ($array as $k => $v) {
			if(is_array($v)) {
				foreach ($v as $key =>$value) {
					if(in_array($key,$keyarray)) {
						unset($array[$k][$key]);
					}
				}
			}else {
				if(in_array($k,$keyarray)) {
					unset($array[$k]);
				}
			}
		}
	}
	
	function RemoveCache($prikey='',$unikey='') {
		$this -> ExecCacheFile($prikey,$unikey);
		@unlink($this -> CacheFile);
	}
	//��������
	function __destruct() {
		
	}
	
	//ħ������
	function __sleep() {
		//���л� sleep ����
	}
	function __wakeup() {
		//���л� wakeup ����
	}
}