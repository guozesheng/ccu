<?php
/**
 *	通用配置处理类
 * 
 *	功能:自动配置处理,自动保存为数组到文件,如果选择Userdb,也将保存一份副本到数据库
 *	所有这些都是自动的.
 *	用户无需担心处理方式
 * 
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id
 *
*/

Iimport('dbsql');
class Config{
	/**
	 * 配置保持的数据表
	 *
	 * @var string $Ctable
	 */
	var $Ctable= "##__frame_config";
	/**
	 * 是否用数据库保存配置副本标志
	 *
	 * @var bool $CUseDb
	 */
	var $CUseDb= true;
	/**
	 * 保存配置的变量名
	 *
	 * @var string $CArrayName
	 */
	var $CArrayName;
	/**
	 * 保存配置的数组
	 *
	 * @var array
	 */
	var $CArray=array();
	/**
	 * 保存配置文件的目录
	 *
	 * @var string $Cdir
	 */
	var $Cdir;
	/**
	 * 数据库访问操作类
	 *
	 * @var object
	 */
	var $Csql;
	/**
	 * 配置文件存放的文件名
	 *
	 * @var string $Cfile
	 */
	var $Cfile;
	/**
	 * 配置文件的扩展名，使用.PHP可以防止配置文件被下载
	 *
	 * @var string $CfileExt
	 */
	var $CfileExt='.php';
	/**
	 * 完整的配置文件路径
	 *
	 * @var string $Cfullfile
	 */
	var $Cfullfile;
	/**
	 * 附加变量，用来解决冲突，暂不使用
	 *
	 * @var string $Cplus
	 */
	var $Cplus="";
	
	/**
	 * PHP5构造函数
	 * 
	 *
	 * @param string $carrayname 用于存储配置变量的变量名
	 * @param string $fullfile 设置配置文件存放的完整路径，（高级用户）。
	 */
	function __construct($carrayname,$file='',$table=''){
//		$vnum = func_get_args();				//$vnum[1]=$file,$vnum[2]=$table;
		$this->CArrayName = $carrayname;
		$this -> Cdir = ROOT.'temp/';
		if(!is_dir($this->Cdir)){
			PPMakeDir($this->Cdir);
		}
		$this->Cdir = ROOT.'temp/config/';
		if(!is_dir($this->Cdir)){
			PPMakeDir($this->Cdir);
		}
		$this->SetFullFile($file);
		$this->SetTable();
	}
	/**
	 * PHP4构造函数
	 *
	 * @see __construct()
	 */
	function Config($carrayname,$file='',$table=''){
		$this->__construct($carrayname,$file,$table);
	}
	/**
	 * 设置配置文件保存的完整路径,
	 * 如果给定了参数$file 则直接将此参数设置成完整路径，这用于生成一个数组配置到一个给定的文件
	 * 默认情况下，成会按一定规则生成一个唯一的文件，只用记住 $CarrayName 其他的交给程序就行了。
	 * 
	 * @param string $file 指定给程序的配置文件存放完整路径
	 * @access private
	 */
	function SetFullFile($file="") {
		$this->Cfile = substr(md5($this->CArrayName.$this->Cplus),0,16).$this->CArrayName;
		if($file) {
			$this->Cfullfile = $file;
		}else {
			$this->Cfullfile = realpath($this->Cdir.$this->Cfile.$this->CfileExt) 
							? realpath($this->Cdir.$this->Cfile.$this->CfileExt)
							: $this->Cdir.$this->Cfile.$this->CfileExt;
		}
	}
	/**
	 * 载入配置数据，并将数据保存与 $CArray
	 * 程序先从配置文件找起，如果找不到而有存有数据库配置副本，则从数据库载入配置
	 *
	 */
	function Load(){
		if(file_exists($this->Cfullfile)){
			require_once($this->Cfullfile);
			$this->CArray = ${$this->CArrayName};
		}else{
			if($this->CUseDb){
				$this->LoadFromDb();
			}else {
				$this->CArray = array();
			}
		}
	}
	//载入已有配置
	//$overwrite=true 覆盖所有配置,默认修改
	/**
	 * 从一个数组载入配置数据到配置数组
	 *
	 * @param array $array 将被载入的配置数据（一维数组）
	 * @param string $name 载入的配置的标志。如果留空，将产生一个一维数组配置，否则产生二维数组配置
	 * @param bool $overwrite 是否覆盖原有配置开关,默认覆盖原有配置
	 * @param bool $rewrite 是否重写原有配置开关，默认不重写。这意味着，你没有指定的配置项将使用原有配置
	 * @param bool $allrewrite 重写所有配置开关（危险），默认不，也没有必要开。这将擦除所有配置数据。
	 */
	function LoadConfig($array=array(),$name='',$overwrite=true,$rewrite=false,$allrewrite=false){
		if(!is_array($array)) return ;
		
		
		if($rewrite && $name) {
			$this->CArray[$name] = array();
		}
		if($allrewrite) $this->CArray = array();//全擦除
		if($overwrite){//覆盖
			foreach ($array as $k => $v){
				if ($k) {
					if($name) $this->CArray[$name][$k] = $v;
					else $this->CArray[$k] = $v;
				}
			}
		}else {
			foreach ($array as $k => $v){
				if ($k) {
					if($name){
						if(empty($this->CArray[$name][$k])){
							$this->CArray[$name][$k] = $v;
						}
					}else {
						if(empty($this->CArray[$k])) {
							$this->CArray[$k] =$v;
						}
					}
				}
			}
		}
	}
	/**
	 * 不做检查直接载入一个配置到配置数据数组
	 *
	 * @param array $array 要载入的数组
	 * @param string $name 配置名称，留空载入成最低一维数组，否则最低是二维数组
	 * @param bool $overwrite 暂时无用
	 */
	function LoadConfigNoCheck($array=array(),$name="",$overwrite=true) {		
		if($name) $this->CArray[$name] = $array;
		else  $this->CArray = $array;
	}
	/**
	 * 重写配置信息。
	 * 如果打开数据库存储备份，则同时也写入一份到数据库
	 *
	 */
	function ReConfig(){
		//Update DB;
		if($this->CUseDb){
			$array = array(
				'truename' => $this->CArrayName,
				'name' => $this->Cfile,
				'body' => str_replace("##__","@@__",serialize($this->CArray))
			);
			$this->StartSQL();
			$this->Csql->DoInsert($array,$this->Ctable,"Replace");
		}
		//Update File
		$string = '';
		Trip_S($this->CArray);
		$string = PP_var_export($this->CArray);
		
		$string = "<?php\r\n \${$this->CArrayName} = ".$string."\r\n?>";
		if(!$this->Cfullfile){
			$this->SetFullFile();
		}
		
		return WriteFile($string,$this->Cfullfile);
	}
	/**
	 * 强制从数据库载入配置备份
	 *
	 */
	function LoadFromDb(){
		if(!$this->CUseDb) return ;
//		$magic_quotes = get_magic_quotes_runtime();
		$this->StartSQL();
		$this->Csql->SetQueryString("Select * From {$this->Ctable} where `truename` like '$this->CArrayName' limit 1");
		$row = $this->Csql->GetOneArray();
		$this->CArray = unserialize($row['body']);
//		set_magic_quotes_runtime($magic_quotes);
	}
	
	/**
	 * 得到一个配置的值
	 *
	 * @param string $string 配置项的键值
	 * @param string $name 留空返回一维数组，否则返回二维数组值
	 * @return mix 返回该项配置的值
	 */
	function GetValue($string,$name=""){
		if($string) {
			if($name) return $this->CArray[$name][$string];
			else return $this->CArray[$string];
		}else {
			if($name) return $this->CArray[$name];
		}
	}
	
	/**
	 * 获得整个配置项
	 *
	 * @param string $string 配置标志
	 * @return array 返回 $string 标志的配置项
	 */
	function GetConfig($string="") {
		if($string) return $this->CArray[$string];
		else return $this->CArray;
	}
	
	/**
	 * 设置文件扩展名
	 *
	 * @param string $ext 文件扩展名可加点也可不加
	 */
	function SetExt($ext){
		if(ereg('[.]',$ext)) $this-> CfileExt = $ext;
		else $this->Ext = ".{$ext}"; 
	}
	/**
	 * 设置配置文件相对于根目录的存放目录
	 * 该方法尚未启用
	 *
	 * @param string $dir
	 */
	function SetConDir($dir){
		$this -> Cdir = ROOT.$cdir."/";
	}
	
	/**
	 * 开关数据库备份存储
	 *
	 * @param bool $true true 打开数据库存储，false关闭
	 */
	function EnableDB($true=true){
		if($true) $this -> CUseDb = true;
		else $this -> CUseDb = false;
	}
	
	/**
	 * 开始数据库访问。如果已经链接不再链接。此处检查比较粗糙
	 *
	 */
	function StartSQL() {
		if(!is_object($this->Csql))
		$this->Csql = new dbsql();
	}
	/**
	 * 设置数据库备份表
	 *
	 * @param string $table
	 */
	function SetTable($table="") {
		if($table) $this->Ctable = $table;
	}
}