<?php
/**
 *通用元素描述类
 * 
 * 封装了通常的元素操作
 * 
 * @author  蜻蜓@@ <hubin999421@gmail.com>
 * @copyright http://www.ppframe.com
 * @version $id
 * @todo 添加对元素附加属性的访问.(包括单个,和列表)
 */
Iimport('dbsql');
class Element {
	/**
	 * 元素主键
	 *
	 * @var string $PriKey
	 */
	var $PriKey;							//主键
	/**
	 * 元素唯一键
	 *
	 * @var string $UniKey
	 */
	var $UniKey;							//唯一键
	/**
	 * 元素属性数据数组
	 *
	 * @var array $Elements
	 */
	var $Elements=array();					//元素属性数组
	/**
	 * @see $Elements
	 * 
	 */
	var $E;
	/**
	 * 元素存储表名称
	 *
	 * @var string $Table
	 */
	var $Table;								//元素存储表名称
	/**
	 * 元素安全数组
	 *
	 * @var array $SafeArray
	 */
	var $SafeArray;							//安全数组
	/**
	 * 元素主键是否自增
	 *
	 * @var bool $pri_auto_increment
	 */
	var $pri_auto_increment = false;		//主键是否自增
	/**
	 * 元素字段列表
	 *
	 * @var array $Els
	 */
	var $Els;
	/**
	 * 增加元素用缓存数组
	 *
	 * @var array $E_Insert
	 */
	var $E_Insert;							//增加元素用数组
	/**
	 * 修改元素用数组
	 *
	 * @var array $E_Update
	 */
	var $E_Update;							//修改元素用数组(包含主键,或唯一键)
	/**
	 * 数据库操作类
	 *
	 * @var object $Esql
	 */
	var $Esql;								//数据库操作用类
	/**
	 * 最后插入元素的ID
	 *
	 * @var number
	 */
	var $E_InsertId;
	/**
	 * 是否使用缓存开关
	 *
	 * @var bool $UseCache
	 */
	var $UseCache=false;					//是否使用缓存
	/**
	 * 缓存有效时间
	 *
	 * @var number $CacheTime
	 */
	var $CacheTime=100;						//缓存有效期
	/**
	 * 临时文件存放夹
	 *
	 * @var string $TempDir
	 */
	var $TempDir = "";
	/**
	 * 缓存文件
	 *
	 * @var string $CacheFile
	 */
	var $CacheFile = "";
	/**
	 * 缓存文件扩展名
	 *
	 * @var string $CacheFileExt
	 */
	var $CacheFileExt = '.php';
	//php5 构造函数
	/**
	 * PHP5构造函数
	 *
	 * @param array $configs 配置信息数组
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
	 * php4 构造函数
	 *
	 * @see __construct()
	 */
	function Element($configs=array()) {
		return $this->__construct($configs);
	}
	
	//初始化
	function Init() {
		
	}
	/**
	 * 载入元素
	 *
	 * @param array $element 要载入的元素数组
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
	 * 记录一个或者多个元素,使用E_Insert 不检查主键或者唯一键重复情况
	 * @param string $type
	 * @param number $w 维数(一次插入一条是一维,插入多条是二维)
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
	 * 不做判断的设置用来记录的元素数组,可以使用二维数组一次设置多个元素
	 *
	 * @param array $element
	 * @todo 判断数组格式排除错误
	 */
	function SetRecord($element=array()) {
//		此处的$elements 必须符合格式,否则出错.程序不做判断
//		$this->E_Insert = $element;
		if(!is_array($element)) return ;
		$i = $type = 0;
		foreach ($element as $k => $v){
			//跳过主键
			if(!is_array($v) && ($i==0||$type==1)){
				$type = 1;
				//记录一个有效属性
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
	 * 更新一个元素 使用 E_Update (必须包含主键或者唯一键)
	 * 成功返回true，失败返回false
	 *
	 * @return bool
	 */
	function DoUpdate($unset=array()) {
		//safe	$this->E_Update first
		dbsql::safeArrayPre($this->E_Update,$this->SafeArray,1);
		//CheckOutKeys $this->E_Update first if autoincrement prikey
		/* 必须改变策略,在其他地方排除.否则无法更新
		if($this->pri_auto_increment) {
			$this->CheckOutKeys($this->E_Update,array($this->PriKey));
		}
		*/
		//设置保护字段
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
	 * 设置用来更新的元素数组
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
	 * 使用主键或者唯一键移除一个元素
	 *
	 * @param mix $pri 主键的键值
	 * @param mix $unikey 唯一键键值
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
	 * 计算缓存文件的存放位置
	 *
	 * @param mix $prikey 主键值
	 * @param mix $unikey 唯一键值
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
	 * 载入元素信息，如果有数据缓存，则使用缓存
	 * 使用主键或者唯一键，优先使用主键
	 * 
	 * @param mix $prikey 主键值
	 * @param mix $unikey 唯一键值
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
	 * 强制从数据库载入元素信息,并将信息记载如缓存文件
	 * 更新元素后使用该方法重新载入一次，即可更新缓存
	 *
	 * @param mix $prikey 主键值
	 * @param mix $unikey 唯一键值
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
     * 获得该元素的属性数组
     *
     * @return array
     */
	function GetElement() {
		return $this->Elements;
	}
	/**
	 * 获得该元素属性的一个值
	 *
	 * @param string $key 属性标记（数据库字段）
	 * @return mix
	 */
	function GetElementValue($key="") {
		if(isset($this->Elements[$key])) return $this->Elements[$key];
		else return null;
	}
	/**
	 * 设置元素存储表
	 *
	 * @param string $table 数据库表名
	 */
	function SetTable($table="table") {
		$this->Table = trim($table);
	}
	/**
	 * 设置元素主键
	 *
	 * @param string $prikey 数据库主键字段名
	 */
	function SetPriKey($prikey="prikey") {
		$this->PriKey = trim($prikey);
	}
	/**
	 * 设置元素唯一键
	 *
	 * @param string $unikey 数据库唯一字段名
	 */
	function SetUniKey($unikey="unikey") {
		$this->UniKey = trim($unikey);
	}
	/**
	 * 设置主键是否自增
	 *
	 * @param bool $type true,是 false,否
	 */
	function SetPriAutoIncrement($type=false) {
		if($type) $this->pri_auto_increment = true;
		else $this->pri_auto_increment = false;
	}
	/**
	 * 设置缓存状态
	 *
	 * @param boo $type 是否使用缓存标志，true，是 false，否
	 * @param number $ct 缓存时间
	 */
	function SetCacheState($type=true,$ct=100) {
		if($type){
			$this->UseCache = $type;
			$this->CacheTime = $ct;
		}
		else $this-> UseCache = false;
	}
	/**
	 * CACHE 开关！
	 *
	 * @param bool $bool
	 */
	function EnableCache($bool=true) {
		$this->UseCache = $bool;
	}
	/**
	 * 设置安全控制数组
	 *
	 * @param array $safearray
	 */
	function SetSafeArray($safearray=array()) {
		if(is_array($safearray) && !empty($safearray)) $this->SafeArray = $safearray;
		else $this->SafeArray=array();
	}
	/**
	 * 使用一个数组设置所有配置信息,快捷函数
	 * 
	 * @param array $configs 配置信息数组
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
	 * 检查必须配置信息,暂未使用
	 *
	 * @return bool
	 */
	function CheckConfig() {
		if(empty($this->Table)) return false;
	}
	/**
	 * 用一个数组,限制另一个数组的键范围,该数组的键范围不能在另一个数组的值范围内
	 *
	 * @param array $array 需要被限制的数组
	 * @param array $keyarray 要被排除的键数组
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
	//析构函数
	function __destruct() {
		
	}
	
	//魔术函数
	function __sleep() {
		//序列化 sleep 函数
	}
	function __wakeup() {
		//序列化 wakeup 函数
	}
}