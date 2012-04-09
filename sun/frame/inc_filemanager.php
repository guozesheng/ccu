<?php
/**
* �ļ�Ŀ¼������
*
* ����һ��Ŀ¼���ļ��������������б����飬һ�����ļ��б�һ����Ŀ¼�б�
* ����ʹ�ò��� Lock ������������ BaseDir Ŀ¼
* 
* @author  ����@@ <webmaster@ppframe.com>
* @copyright http://www.ppframe.com
* @version $id
*/

class FileManager {
	/**
	 * �ļ���������Ŀ¼
	 *
	 * @var string $BaseDir
	 */
	var $BaseDir;
	/**
	 * ��ǰҪ����/������ļ�
	 *
	 * @var string $File
	 */
	var $File;
	/**
	 * �����Ŀ¼����Ŀ¼Ϊ����Ŀ¼��
	 *
	 * @var string $Dir
	 */
	var $Dir;
	/**
	 * �����Ŀ¼����Ŀ¼Ϊ���Ŀ¼������ڸ�Ŀ¼ $BaseDir
	 *
	 * @var unknown_type
	 */
	var $XDir;
	/**
	 * Ŀ¼�б�����
	 *
	 * @var array $DirData
	 * @access  private
	 */
	var $DirData=array();
	/**
	 * �ļ��б�����
	 *
	 * @var array $FileData
	 * @access  private
	 */
	var $FileData=array();
	/**
	 * �����ĵ�����Ŀ¼��׼
	 *
	 * @var bool $Lock �Ƿ�������Ŀ¼��־
	 * @access  private
	 */
	var $Lock = true;
	/**
	 * ����Ŀ¼��С�ļ���,0������,1���ظ�Ŀ¼�������ļ��Ĵ�С��.
	 *
	 * @var bool
	 */
	var $DirSizeLvel = 0;
	/**
	 * PHP5���캯����ָ��һ�������趨��Ŀ¼
	 *
	 * @param string $dir �ò�����ʹ�� realpath ��������
	 */
	function __construct($dir="") {
		if($dir) {
			$this->SetBaseDir($dir);
		}else {
			$this->SetBaseDir(ROOT);
		}
	}
	/**
	 * PHP4���캯����ֱ��ʹ������PHP5���캯��
	 *
	 * @param string $dir �ò�����ʹ�� realpath ��������
	 */
	function FileManager($dir="") {
		$this->__construct($dir);
	}
	/**
	 * �����ļ������Ŀ¼
	 *
	 * @param string $basedir ��Ŀ¼ʹ��realpath����
	 * @access private
	 */
	function SetBaseDir($basedir) {
		$this->BaseDir = realpath($basedir);
	}
	/**
	 * �����ļ�����ǰĿ¼
	 *
	 * @param string $dir ��Ŀ¼ʹ��realpath������������˸�Ŀ¼������Ŀ¼�ֲ��ڸ�Ŀ¼�½����óɿ�Ŀ¼
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
	 * ���ò�����ǰ�ļ���������Ŀ¼�ļ����ұȶԵȡ����߶Ե�ǰ�ļ����в���
	 *
	 * @param string $file ���ļ������������ļ�ȫ·��
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
	 * �����Ƿ��������ļ���Ŀ¼���ú���������Scan ֮ǰ����
	 *
	 * @param bool $lock
	 * @access private
	 */
	function EnableLock($lock=true) {
		if($lock) $this->Lock = true;
		else $this->Lock = false;
	}
	/**
	 * ɨ��һ��Ŀ¼������ø�Ŀ¼�µ��ļ��б��Ŀ¼�б�
	 * 
	 * 
	 * @see SetDir()
	 * @param string $dir @see SetDir() ���Ŀ¼!
	 * @param string $file @see SetFile()
	 * @access private
	 * @todo �ļ�����,���˵�ϵͳ�����ļ���
	 */
	function Scan($dir='',$file=null) {
		$this->SetDir($dir);
		$this->SetFile($file);
		if(!is_dir($this->Dir)) return ;
		$dh = opendir($this->Dir);
		while (false !== ($filename = readdir($dh))) {
			//�ļ���Ŀ¼����
			if(ereg('^\.',$filename) && $filename!='..') continue;
			if(ereg('^_',$filename)) continue;
			//�ļ���Ŀ¼����
			if(is_dir($this->Dir.'/'.$filename)){
				if($this->Dir == $this->BaseDir && $filename=='..') continue;
				$this->DirData[] = array(
					'name' => $filename,
					'usesize' => $this->DirSize($filename),
				);
			}else {
				//�ļ�����
				
				//�ļ�����
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
	 * �����㼶�����Ŀ¼���ļ�ռ�ÿռ��С ��λ KB
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
	 * �ݹ�ļ���һ��Ŀ¼�µ��ļ����ܴ�С KB.�ѷ���
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
		//Scan ���ݹ�����µ��øú���
		if(is_array($fm->DirData)) {
			foreach ($fm->DirData as $v) {
				$rval += $v['size'];
			}
		}
		return $rval;
	}
	/**
	 * �ݹ�ķ���һ��Ŀ¼�µ��ļ���С�ܺ�,KB
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
		//Scan ���ݹ�����µ��øú���
		if(is_array($fm->DirData)) {
			foreach ($fm->DirData as $v) {
				$rval += $v['size'];
			}
		}
		return $rval;
	}
	/**
	 * ������һ���ļ������ļ���
	 *
	 * @param string $oldname �������ļ�����Ŀ¼��
	 * @param string $newname �������ļ�����Ŀ¼��
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
	 * ɾ��һ���ļ�����Ŀ¼�������Զ��ж����ļ�����Ŀ¼
	 *
	 * @param string $file �������ļ�����Ŀ¼��
	 * @return bool
	 * @access public
	 */
	function Delete($file="") {
		if(!$file) return false;
		if(is_file($file) && file_exists($file)) return @unlink($file);
		if(is_dir($file)) return @rmdir($file);
	}
	/**
	 * ��ȡһ���ļ����������Ϊ�ս�Ĭ�϶�ȡ��ǰ�����ļ� ::File
	 * ����ļ������ڣ����ؿ��ִ�
	 *
	 * @param string $file �������ļ���
	 * @return string
	 * @access public
	 */
	function View($file="") {
		empty($file) && $file = $this->File;
		if(!file_exists($file)) return "";
		return readfile($file);
	}
	/**
	 * �༭һ���ļ�������ɹ����򷵻�true�����򷵻�false
	 * ����ļ������ڽ����Դ���֮
	 *
	 * @param string $str �����ĵ��ַ���
	 * @param string $file �������ļ���������Ĭ�ϵ�ǰ�����ļ�::File @see SetFile()
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
	 * ��һ��Ŀ¼·������URLENCODE������׼������ǰ��.
	 * ����Ա�����ĳЩ��������֧������URL��������500����.
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