<?php
/**
* 文件目录管理类
*
* 管理一个目录的文件，生成两个个列表数组，一个是文件列表，一个是目录列表
* 可以使用参数 Lock 将管理锁定在 BaseDir 目录
* 
* @author  蜻蜓@@ <webmaster@ppframe.com>
* @copyright http://www.ppframe.com
* @version $id
*/

class FileManager {
	/**
	 * 文件管理器根目录
	 *
	 * @var string $BaseDir
	 */
	var $BaseDir;
	/**
	 * 当前要操作/管理的文件
	 *
	 * @var string $File
	 */
	var $File;
	/**
	 * 管理的目录，该目录为绝对目录。
	 *
	 * @var string $Dir
	 */
	var $Dir;
	/**
	 * 管理的目录，该目录为相对目录。相对于根目录 $BaseDir
	 *
	 * @var unknown_type
	 */
	var $XDir;
	/**
	 * 目录列表数组
	 *
	 * @var array $DirData
	 * @access  private
	 */
	var $DirData=array();
	/**
	 * 文件列表数组
	 *
	 * @var array $FileData
	 * @access  private
	 */
	var $FileData=array();
	/**
	 * 锁定文档到根目录标准
	 *
	 * @var bool $Lock 是否锁定根目录标志
	 * @access  private
	 */
	var $Lock = true;
	/**
	 * 返回目录大小的级别,0不返回,1返回该目录下所有文件的大小和.
	 *
	 * @var bool
	 */
	var $DirSizeLvel = 0;
	/**
	 * PHP5构造函数，指定一个参数设定主目录
	 *
	 * @param string $dir 该参数将使用 realpath 函数处理。
	 */
	function __construct($dir="") {
		if($dir) {
			$this->SetBaseDir($dir);
		}else {
			$this->SetBaseDir(ROOT);
		}
	}
	/**
	 * PHP4构造函数，直接使用引用PHP5构造函数
	 *
	 * @param string $dir 该参数将使用 realpath 函数处理。
	 */
	function FileManager($dir="") {
		$this->__construct($dir);
	}
	/**
	 * 设置文件管理根目录
	 *
	 * @param string $basedir 该目录使用realpath处理
	 * @access private
	 */
	function SetBaseDir($basedir) {
		$this->BaseDir = realpath($basedir);
	}
	/**
	 * 设置文件管理当前目录
	 *
	 * @param string $dir 该目录使用realpath处理，如果锁定了根目录，而该目录又不在根目录下将设置成空目录
	 * @access private
	 */
	function SetDir($dir) {
		$this->Dir = realpath($this->BaseDir."/".$dir."/");
		$this->XDir = str_replace($this->BaseDir,'',$this->Dir)."/";
		$this->XDir = str_replace('\\','/',$this->XDir);
		$this->XDir = ereg_replace("\/{2,}","/",$this->XDir);
		if($this->Lock) {
			if(!preg_match("|".addslashes($this->BaseDir)."|i",$this->Dir)) {
				$this->Dir = $this->BaseDir;
				$this->XDir = '';
			}
		}
		$dir = str_replace('\\','/',str_replace($this->BaseDir,'',$this->Dir));
//		$this -> XDir = $this->EncodeDir($this->XDir);
	}
	/**
	 * 设置操作当前文件，可用于目录文件查找比对等。或者对当前文件进行操作
	 *
	 * @param string $file 仅文件名，而不是文件全路径
	 * @access private
	 */
	function SetFile($file) {
		if(!$file) return ;
		if($this->Dir) {
			$this->File = realpath($this->Dir."/".$file);
			!file_exists($this->File) && $this->File = null;
		}
		else $this->File = null;
	}
	/**
	 * 设置是否锁定在文件主目录，该函数必须在Scan 之前调用
	 *
	 * @param bool $lock
	 * @access private
	 */
	function EnableLock($lock=true) {
		if($lock) $this->Lock = true;
		else $this->Lock = false;
	}
	/**
	 * 扫描一个目录，并获得该目录下的文件列表和目录列表。
	 * 
	 * 
	 * @see SetDir()
	 * @param string $dir @see SetDir() 相对目录!
	 * @param string $file @see SetFile()
	 * @access private
	 * @todo 文件过滤,过滤掉系统隐藏文件等
	 */
	function Scan($dir='',$file=null) {
		$this->SetDir($dir);
		$this->SetFile($file);
		if(!is_dir($this->Dir)) return ;
		$dh = opendir($this->Dir);
		while (false !== ($filename = readdir($dh))) {
			//文件、目录过滤
			if(ereg('^\.',$filename) && $filename!='..') continue;
			if(ereg('^_',$filename)) continue;
			//文件、目录过滤
			if(is_dir($this->Dir.'/'.$filename)){
				if($this->Dir == $this->BaseDir && $filename=='..') continue;
				$this->DirData[] = array(
					'name' => $filename,
					'usesize' => $this->DirSize($filename),
				);
			}else {
				//文件过滤
				
				//文件过滤
				$this->FileData[] = array(
					'name' => $filename,
					'size' => sprintf("%.3f",filesize($this->Dir.'/'.$filename)/1024),
					'mtime' => strftime("%y-%m-%d %H:%M:%S",filemtime($this->Dir.'/'.$filename)),
					'text' => ereg('(.php|.txt|.htm|.html|.tpl)$',$filename) && 'text',
				);
			}
		}
		is_resource($dh) && closedir($dh);
	}
	/**
	 * 按计算级别计算目录里文件占用空间大小 单位 KB
	 *
	 * @param string $dir
	 * @return float
	 */
	function DirSize($dir) {
		if($dir=='..') return 0;
		if(!$this->DirSizeLvel) return 0;
		else if($this->DirSizeLvel) return $this->EasyDirSize($dir);
	}
	/**
	 * 递归的计算一个目录下的文件的总大小 KB.已废弃
	 * 
	 * @param string $dir
	 * @return float
	 */
	function HardDirSize($dir) {
		$fm = new FileManager($this->Dir.'/'.$dir);
		$fm -> DirSizeLvel = true;
		$root = '';
		$fm -> Scan($root);
		$rval = 0;
		if(is_array($fm->FileData)){
			foreach ($fm->FileData as $v) {
				$rval += $v['size'];
			}
		}
		//Scan 后会递归的重新调用该函数
		if(is_array($fm->DirData)) {
			foreach ($fm->DirData as $v) {
				$rval += $v['size'];
			}
		}
		return $rval;
	}
	/**
	 * 递归的返回一个目录下的文件大小总和,KB
	 * 
	 * @param string $dir
	 * @return float
	 */
	function EasyDirSize($dir) {
		$fm = new FileManager($this->Dir.'/'.$dir);
		$fm -> DirSizeLvel = false;
		$root = '';
		$fm -> Scan($root);
		$rval = 0;
		if(is_array($fm->FileData)){
			foreach ($fm->FileData as $v) {
				$rval += $v['size'];
			}
		}
		//Scan 后会递归的重新调用该函数
		if(is_array($fm->DirData)) {
			foreach ($fm->DirData as $v) {
				$rval += $v['size'];
			}
		}
		return $rval;
	}
	/**
	 * 重命名一个文件或者文件名
	 *
	 * @param string $oldname 完整的文件名或目录名
	 * @param string $newname 完整的文件名或目录名
	 * @return bool
	 * @access public
	 */
	function Rename($oldname,$newname) {
		if (empty($oldname) || empty($newname) || !file_exists($oldname)) {
			return false;
		}
		return @rename($oldname,$newname);
	}
	/**
	 * 删除一个文件或者目录，程序将自动判断是文件还是目录
	 *
	 * @param string $file 完整的文件或者目录名
	 * @return bool
	 * @access public
	 */
	function Delete($file="") {
		if(!$file) return false;
		if(is_file($file) && file_exists($file)) return @unlink($file);
		if(is_dir($file)) return @rmdir($file);
	}
	/**
	 * 读取一个文件，如果参数为空将默认读取当前操作文件 ::File
	 * 如果文件不存在，返回空字串
	 *
	 * @param string $file 完整的文件名
	 * @return string
	 * @access public
	 */
	function View($file="") {
		empty($file) && $file = $this->File;
		if(!file_exists($file)) return "";
		return readfile($file);
	}
	/**
	 * 编辑一个文件，如果成功，则返回true。否则返回false
	 * 如果文件不存在将尝试创建之
	 *
	 * @param string $str 将更改的字符串
	 * @param string $file 完整的文件名，留空默认当前操作文件::File @see SetFile()
	 * @return bool
	 */
	function Edit($str,$file="") {
		empty($file) && $file = $this->File;
		$hd = fopen($file,'wb');
		@flock($hd,LOCK_EX);
		$rt = fwrite($hd,$str);
		@flock($hd,LOCK_UN);
		fclose($hd);
		return !($rt===false);
	}
	/**
	 * 对一个目录路径进行URLENCODE编码以准备交给前端.
	 * 这可以避免因某些服务器不支持中文URL而产生的500错误.
	 *
	 * @param string $url
	 * @return unknown
	 */
	function EncodeDir($url) {
		if(!$url) return '';
		if($url=='/') return '/';
		$urls = explode('/',$url);
		foreach ($urls as $key => $value) {
			if($value) $urls[$key] = urlencode($value);
			else $urls[$key] = '';
		}
		$url = implode('/',$urls);
		return $url;
	}
}