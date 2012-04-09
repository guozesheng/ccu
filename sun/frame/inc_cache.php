<?php
require_once(ROOT."frame/inc_db_mysql.php");
/**
 *	对来自SQL的数据缓存自动处理类
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @version $id
*/

class Cache{
	/**
	 * 缓存变量名,可接受数组缓存多个变量
	 *
	 * @var string $CvarName
	 */
	var $CvarName;
	/**
	 * 缓存目录
	 *
	 * @var string $CacheDir
	 */
	var $CacheDir;
	/**
	 * 缓存文件名
	 *
	 * @var string $CacheFile
	 */
	var $CacheFile;
	/**
	 * 缓存文件完整路径
	 *
	 * @var string $CacheFullFile
	 */
	var $CacheFullFile;
	/**
	 * 数据库操作类
	 *
	 * @var object $Csql
	 */
	var $Csql;
	/**
	 * 数据库查询串
	 *
	 * @var mix $CsqlString
	 */
	var $CsqlString="";
	/**
	 * 缓存文件后缀
	 *
	 * @var string $Ext
	 */
	var $Ext=".php";
	/**
	 * 缓存数组变量
	 *
	 * @var array $CValue
	 */
	var $CValue=array();
	/**
	 * 缓存有效期(秒)
	 *
	 * @var number $Ctime
	 */
	var $Ctime=300;
	/**
	 * 是否使用缓存开关
	 *
	 * @var bool $UseCache
	 */
	var $UseCache=true;
	/**
	 * 使用文件缓存开关
	 *
	 * @var bool $UseFile
	 */
	var $UseFile=true;
	/**
	 * 使用数据库缓存开关
	 *
	 * @var bool $UseDb
	 */
	var $UseDb=true;
	/**
	 * 缓存数据库表名
	 *
	 * @var string $CacheDB
	 */
	var $CacheDB="##__frame_cache";
	
	/**
	 * PHP5构造函数
	 *
	 * @param string $cvarname 变量名
	 * @param string $sql SQL串
	 */
	function __construct($cvarname,$sql=""){
		$this->CvarName = $cvarname;
		$this->CsqlString = $sql;
		$this -> Csql = $GLOBALS['ppsql'];
		if(!is_object($this->Csql))
		$this->Csql = new dbsql();
		$this->CacheDir = ROOT."temp/cache/";
//		自动Load,使用默认值时可以打开,如果要自己设置参数,关闭这里!
//		$this->Load();
	}
	/**
	 * php4构造函数
	 *
	 * @see __construct()
	 */
	function Cache($cvarname,$sql=""){
		$this->__construct($cvarname,$sql);
	}
	/**
	 * 载入缓存变量
	 *
	 * @param mix $sqls
	 */
	function Load($sqls=""){
		$this->CacheFile=substr(md5($this->CvarName),0,16).substr($this->CvarName,0,16);
		$this->CacheFullFile = realpath($this->CacheDir.$this->CacheFile.$this->Ext)
							? realpath($this->CacheDir.$this->CacheFile.$this->Ext)
							: $this->CacheDir.$this->CacheFile.$this->Ext;
		if(!is_dir($this->CacheDir)){
			PPMakeDir($this->CacheDir);
		}
		if($this->UseCache){
			//使用缓存
			if($this->UseFile && file_exists($this->CacheFullFile) && ($GLOBALS['timestamp']-filemtime($this->CacheFullFile) < $this->Ctime)){
				//文件缓存有效
				require_once($this->CacheFullFile);
				$this->CValue = ${$this->CvarName};
			}else if($this->UseDb){
				//数据库缓存
				$this->CValue = $this->GetDBCache();
				if(empty($this->CValue)){
					$this->GetDBValue($sqls);
					$this->WriteCache();
				}
			}else{
				//缓存过期
				$this->GetDBValue();
				$this->WriteCache();
			}
		}else {
			//不使用缓存
			$this->GetDBValue();
		}
	}
	
	/**
	 * 从数据库获得缓存变量,参数 $sqls ,选择要更新的sqls
	 *
	 * @param mix $sqls
	 * @return bool
	 */
	function GetDBValue($sqls=""){
		if(empty($this->CsqlString)) return false;
		if(!is_array($this->CsqlString)){
			$this->Csql->SetQueryString($this->CsqlString);
			$this->Csql->ExecReturnSQL();
			while ($row = $this->Csql->GetArray()) {
				$ar[] = $row;
			}
			$this->CValue = $ar;
		}else{
			//缓存多个数组变量
			foreach ($this->CsqlString as $k => $v){
				if(!empty($sqls) && is_array($sqls) && !in_array($k,$sqls)){
					//跳过不更新的sql,默认全部更新.
					continue;
				}
				$this->Csql->SetQueryString($v);
				$this->Csql->ExecReturnSQL();
				while ($row = $this->Csql->GetArray()) {
					$ar[$k][] = $row;
				}
			}
			$this->CValue = $ar;
		}
	}
	
	/**
	 * 写缓存入缓存文件
	 * @access private
	 */
	function WriteCache(){
		if(!$this -> UseFile && $this -> UseDb){
			$this -> WriteDBCache();
			return ;
		}
		$string = "";
		if(phpversion()>'4.2.0'){
			$string = var_export($this->CValue,true);
		}else{
			//todo var_export 改写
		}
		$string = "<?php\r\n \${$this->CvarName} = {$string}; \r\n?>";
		if(file_exists($this->CacheFullFile)){
			if(is_writable($this->CacheFullFile)){
				WriteFile($string,$this->CacheFullFile);
			}else {
				$this -> WriteDBCache();
			}
		}else {
			WriteFile($string,$this->CacheFullFile);
			fclose($handle);
		}
	}
	
	/**
	 * 写入数据库缓存
	 *
	 */
	function WriteDBCache(){
		//缓存到数据库
		$array = array(
			'cacheid' => $this->CacheFile,
			'cachevalue' => serialize($this->CValue),
			'time' => time()
		);
		$this->Csql->DoInsert($array,$this->CacheDB,'Replace');
	}
	
	/**
	 * 或得数据库缓存
	 *
	 * @return array
	 */
	function GetDBCache(){
		$this -> Csql -> SetQueryString("Select cachevalue,time From {$this->CacheDB} where cacheid='{$this->CacheFile}' Limit 0,1");
		$row = $this -> Csql -> GetOneArray();
		if(!empty($row) && time()-$row['time'] < $this->Ctime){
			return unserialize($row['cachevalue']);
		}else {
			return null;
		}
	}
	
	/**
	 * 返回缓存数组数据
	 *
	 * @param string $key
	 * @param bool $all
	 * @return array
	 */
	function GetValue($key="",$all=false){
		if(empty($key)){
			//获得单数组缓存变量,仅返回一维数组
			if($all) return $this->CValue;
			$rta = array();
			foreach ($this->CValue as $k => $v){
				if(!is_array($v)) $rta[$k] = $v;
			}
			return $rta;
		}else {
			//获得多数组缓存变量
			if(!empty($this->CValue[$key])){
				return $this->CValue[$key];
			}else {
				return array();
			}
		}
	}
	
	/**
	 * 设置缓存时间
	 * $ismin 为true时是分钟，否则是秒
	 *
	 * @param number $min
	 * @param bool $ismin
	 */
	function SetCtime($min,$ismin=true){
		if($ismin) $this->Ctime = $min * 60;
		else $this->Ctime = $min;
	}
	/**
	 * 设置缓存文件存放目录
	 *
	 * @param string $dir
	 */
	function SetCacheDir($dir){
		$this->CacheDir = $dir;
	}
	/**
	 * 设置缓存文件扩展名
	 *
	 * @param string $ext
	 */
	function SetExt($ext){
		if(ereg('[.]',$ext)) $this->Ext = $ext;
		else $this->Ext = ".{$ext}"; 
	}
	/**
	 * 是否打开数据库缓存
	 *
	 * @param bool $true
	 */
	function EnableDB($true){
		if($true) $this -> UseDb = true;
		else $this -> UseDb = false;
	}
	/**
	 * 设置缓存数据
	 *
	 * @param array $array
	 * @param string $key
	 * @param bool $rewrite
	 * @param bool $overwrite
	 */
	function SetCache($array,$key="",$rewrite=false,$overwrite=false){
		//重写
		if($rewrite) $this->CValue=array();
		//覆盖
		if($overwrite){
			foreach ($array as $k => $v){
				if(empty($key)){
					$this->CValue[$k] = $v;
				}else{
					$this->CValue[$key][$k] = $v;
				}
			}
		}else {	//不覆盖
			foreach ($array as $k => $v){
				if(empty($key)){
					if(empty($this->CValue[$k]))
					$this->CValue[$k] = $v;
				}else {
					if(empty($this->CValue[$key][$k]))
					$this->CValue[$key][$k]=$v;
				}
			}
		}
	}
}