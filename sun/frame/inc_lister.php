<?php
/**
 * 通用列表类
 * 
 * 此类配合 page 类 可解决列表和翻页问题,单独使用可解决列表问题
 *
 * @author  蜻蜓@@ <hubin999421@gmail.com>
 * @copyright http://www.ppframe.com
 * @version $id
 */

Iimport('dbsql');
class Lister{
	/**
	 * 返回的列表的数组
	 *
	 * @var array $Larray
	 */
	var $Larray;
	/**
	 * 要列表的数据库表
	 *
	 * @var string $Table
	 */
	var $Table;
	/**
	 * 要列数据表的条件,留空将使用 条件 true
	 *
	 * @var string $Where
	 */
	var $Where;
	/**
	 * 列表MySQL Limit现在
	 *
	 * @var string $Limit
	 */
	var $Limit;
	/**
	 * 列表排序字段
	 *
	 * @var string $Orderby
	 */
	var $Orderby;
	/**
	 * 分组字段
	 *
	 * @var string $Groupby
	 */
	var $Groupby;
	/**
	 * GroupBy Having 条件
	 *
	 * @var string $Having
	 */
	var $Having;
	/**
	 * 满足$where条件的总共的记录条数
	 *
	 * @var number $Total
	 */
	var $Total;
	/**
	 * 需列表的字段列举
	 * 用于 select field,field 留空使用 *
	 * 
	 * @var array $Els
	 */
	var $Els;
	/**
	 * 安全现在数组,现在 $Els 防止出现SQL 语句错误
	 *
	 * @var array $SafeArray
	 */
	var $SafeArray;
	/**
	 * 主键,列表索引用
	 *
	 * @var string 主键标志
	 */
	var $PriKey='id';
	
	var $UseCache=true;
	
	var $CacheFile;
	
	var $CacheArray = 'Larray';
	
	var $CacheNumString ='Lnum';
	
	var $CacheDir;
	
	var $CacheTime=600;
	
	var $ArrWhere = array();
	/**
	 * PHP5构造函数,传入一个配置数组
	 *
	 * @param array $config
	 * @example $config = array(
					'table'=>$talbe,
					'where'=>$where,
					'limit'=>$limit,
					'orderby'=>$orderby,
					'els'=>array(),
					'safearray'=>array(),
				)
	 */
	function __construct($config=array()) {
		$this->Larray = null;
		$this->Total = 0;
		$this->CacheDir = WORK_DIR.'temp/frame_temp_dir/';
		if($this->UseCache && !$this->CacheDir){
			PPMakeDir($this->CacheDir);
		}
		$this->SetConfig($config);
		if (defined('Admin_Safe') && Admin_Safe) {
			$this->UseCache = false;
		}
	}
	/**
	 * PHP4构造函数
	 *
	 * @see __construct()
	 */
	function Lister($config = array()) {
		$this->__construct($config);
	}
	/**
	 * 设置配置数组
	 * $overwrite 为true 是覆盖原有配置,否则保留
	 *
	 * @param array $array
	 * @param bool $overwrite
	 */
	function SetConfig($array,$overwrite=true) {
		if($overwrite) {
			isset($array['table']) && $this->Table = $array['table'];
			isset($array['where']) && $this->Where = $array['where'];
			isset($array['limit']) && $this->Limit = $array['limit'];
			isset($array['orderby']) && $this->Orderby = $array['orderby'];
//			isset($array['safearray']) && is_array($array['safearray']) && $this->SafeArray = $array['safearray'];
//			dbsql::SafeArrayPre($array['els'],$this->SafeArray);
			isset($array['els']) && is_array($array['els']) && $this->Els = $array['els'];
			isset($array['prikey']) && $this->PriKey = $array['prikey'];
			isset($array['groupby']) && $this->Groupby = $array['groupby'];
			isset($array['having']) && $this->Having = $array['having'];
		}else {
			$array['table'] &&!$this->Table && $this->Table = $array['table'];
			$array['where'] &&!$this->Where && $this->Where = $array['where'];
			$array['limit'] &&!$this->Limit && $this->Limit = $array['limit'];
			$array['orderby'] &&!$this->Orderby && $this->Orderby = $array['orderby'];
//			$array['safearray'] && is_array($array['safearray']) && !$this->SafeArray && $this->SafeArray = $array['safearray'];
//			dbsql::SafeArrayPre($array['els'],$this->SafeArray);
			$array['els'] && is_array($array['els']) && !$this->Els && $this->Els = $array['els'];
			$array['prikey'] && !$this->PriKey && $this->PriKey = $array['prikey'];
			$array['groupby'] && !$this->Groupby && $this->Groupby = $array['groupby'];
			$array['having'] && !$this->Having && $this->Having = $array['having'];
		}
		!$this->Where && $this->Where = 1;
		$this->ExecCacheFile();
	}
	/**
	 * 获得总记录,供 可供Page 类使用.这样两个类相互依赖
	 *
	 * @return number
	 * @access private
	 */
	function ExecTotalRecord() {
		$this -> ExecCacheFile('count');
		if($this->UseCache && file_exists($this->CacheFile) && time()-filemtime($this->CacheFile) <$this->CacheTime) {
			require($this->CacheFile);
			$this->Total = ${$this->CacheNumString};
			return $this->Total; 
		}else {
			return $this->HardExecTotalRecord();
		}
	}
	
	/**
	 * 强行从数据库获得记录总数!
	 *
	 * @return number
	 * @access private
	 */
	function HardExecTotalRecord() {
		if($this->Table && $this->Where) {
			$ppsql = new dbsql();
			$ppsql -> SetQueryString("Select count(*) as c From {$this->Table} where {$this->Where}");
			$row = $ppsql -> GetOneArray();
			$this -> Total = $row['c'];
		}
		if($this->UseCache && is_writable($this->CacheDir)) {
			$str = "<?php\r\n\${$this->CacheNumString}={$this->Total};\r\n?>";
			WriteFile($str,$this->CacheFile);
		}
		return $this->Total;
	}
	/**
	 * 获得列表
	 *
	 * @param callback $pf 用于预处理的函数
	 * @example function pf_lister(&$row=array()) {
					if(isset($row['run'])){ 
						if($row['run']) $row['run'] = '是';
						else $row['run'] = '否';
					}
					if(isset($row['business'])) {
						if($row['business']) $row['business'] = '是';
						else $row['business'] = '否';
					}
					return $row;
				}
	 * @return array 返回2维数组
	 */
	function GetList($pf='',$pv='') {
		$this->ExecCacheFile($pf,$pv);
		if($this->UseCache && file_exists($this->CacheFile) && time()-filemtime($this->CacheFile) < ($this->CacheTime + mt_rand(0,100))) {
			require($this->CacheFile);
			$this->Larray = ${$this->CacheArray};
			return $this->Larray; 
		}else {
			return $this -> HardGetLister($pf,$pv);
		}
	}
	/**
	 * GetList() 的别名,但是推荐使用这样函数
	 * @param callback $pf 用于预处理的函数
	 * 
	 * @see GetList()
	 * @return array
	 */
	function GetLister($pf='',$pv='') {
		return $this->GetList($pf,$pv);
	}
	/**
	 * 强制从数据库载入列表并根据缓存配置重写缓存
	 *
	 * @param callback $pf
	 * @return array
	 */
	function HardGetLister($pf='',$pv='') {
		if($this->Table) {
			$ppsql = new dbsql();
			if(is_array($this->Els)) {
				$el = implode(',',$this->Els);
			}
			!$el && $el = "*";
			$sql = "Select {$el} From {$this->Table}";
			if($this->Where && $this->Where != 1) $sql .= " where {$this->Where}";
			if($this->Groupby) {
				$sql .= " Group by {$this->Groupby}";
				if($this->Having) $sql .= " Having $this->Having";
			}
			if($this->Orderby) $sql .= " order by {$this->Orderby}";
			if($this->Limit) $sql .= " limit {$this->Limit}";
			$ppsql -> SetQueryString($sql);
			$ppsql -> ExecReturnSQL();
			$this -> Larray = array();
			while ($row = $ppsql-> GetArray()) {
				//预处理 $row
				if($pf && is_callable($pf)) {
					$row2 = array($row);
					if ($pv) {
						if (!is_array($pv)) {
							$pv = array($pv);
						}
						$row2 = array_merge($row2,$pv);
					}
					$row = call_user_func_array($pf,$row2);
				}
				if ($row) {
					if($this->PriKey && $row[$this->PriKey]) $this->Larray[$row[$this->PriKey]] = $row;
					else $this->Larray[] = $row;
				}
			}
			if($this->UseCache && is_writable($this->CacheDir) && $this->CacheFile) {
				$str = "<?php\r\n\${$this->CacheArray}=".PP_var_export($this->Larray)."\r\n?>";
				WriteFile($str,$this->CacheFile);
			}
		}
		return $this->Larray;
	}
	/**
	 * 获得总记录数
	 *
	 * @return number 
	 */
	function GetTotalRecord() {
		$this->ExecTotalRecord();
		return $this->Total;
	}
	
	function ExecCacheFile($pf='',$pv='') {
		!is_array($pv) && $pv = array($pv);
		$pv = serialize($pv);
		$this->CacheFile = md5(serialize($this->Els).$this->Table.$this->Where.$this->Groupby.$this->Having.$this->Orderby.$this->Limit.$pf.$pv).'.php';
		$this->CacheFile = $this->CacheDir.$this->CacheFile;
	}
	
	function EnableCache($true=true) {
		$this->UseCache = $true;
	}
	
	function SetWhere($where,$key='') {
		if(empty($key)) $this->ArrWhere [] = $where;
		else $this->ArrWhere[$key] = $where;
	}
	
	function SetLimit($limit='0,1') {
		$this->Limit = $limit;
	}
	
	function SetGroupby($groupby) {
		$this->Groupby = $groupby;
	}
	
	function SetCacheTime($ctime) {
		$this->CacheTime = intval($ctime);
	}
	
	function ClearWhere() {
		$this -> ArrWhere = array();
	}
	
	function CreatWhere($where=array()) {
		
		if(is_array($where) && $where) {
			$this -> ClearWhere();
			foreach ($where as $v) $this->SetWhere($v);
		}
		if(is_array($this->ArrWhere) && $this->ArrWhere) $this -> Where = implode(' and ',$this->ArrWhere);
		else $this -> Where = '1';
	}
	
	function CreateWhere($where=array()) {
		$this->CreatWhere($where);
	}
	
	function RemoveCache($pf='',$pv='') {
		$this -> ExecCacheFile($pf='',$pv='');
		@unlink($this -> CacheFile);
	}
}