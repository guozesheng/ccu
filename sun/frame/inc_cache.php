<?php
require_once(ROOT."frame/inc_db_mysql.php");
/**
 *	������SQL�����ݻ����Զ�������
 *
 * @author  ����@@ <webmaster@ppframe.com>
 * @version $id
*/

class Cache{
	/**
	 * ���������,�ɽ������黺��������
	 *
	 * @var string $CvarName
	 */
	var $CvarName;
	/**
	 * ����Ŀ¼
	 *
	 * @var string $CacheDir
	 */
	var $CacheDir;
	/**
	 * �����ļ���
	 *
	 * @var string $CacheFile
	 */
	var $CacheFile;
	/**
	 * �����ļ�����·��
	 *
	 * @var string $CacheFullFile
	 */
	var $CacheFullFile;
	/**
	 * ���ݿ������
	 *
	 * @var object $Csql
	 */
	var $Csql;
	/**
	 * ���ݿ��ѯ��
	 *
	 * @var mix $CsqlString
	 */
	var $CsqlString="";
	/**
	 * �����ļ���׺
	 *
	 * @var string $Ext
	 */
	var $Ext=".php";
	/**
	 * �����������
	 *
	 * @var array $CValue
	 */
	var $CValue=array();
	/**
	 * ������Ч��(��)
	 *
	 * @var number $Ctime
	 */
	var $Ctime=300;
	/**
	 * �Ƿ�ʹ�û��濪��
	 *
	 * @var bool $UseCache
	 */
	var $UseCache=true;
	/**
	 * ʹ���ļ����濪��
	 *
	 * @var bool $UseFile
	 */
	var $UseFile=true;
	/**
	 * ʹ�����ݿ⻺�濪��
	 *
	 * @var bool $UseDb
	 */
	var $UseDb=true;
	/**
	 * �������ݿ����
	 *
	 * @var string $CacheDB
	 */
	var $CacheDB="##__frame_cache";
	
	/**
	 * PHP5���캯��
	 *
	 * @param string $cvarname ������
	 * @param string $sql SQL��
	 */
	function __construct($cvarname,$sql=""){
		$this->CvarName = $cvarname;
		$this->CsqlString = $sql;
		$this -> Csql = $GLOBALS['ppsql'];
		if(!is_object($this->Csql))
		$this->Csql = new dbsql();
		$this->CacheDir = ROOT."temp/cache/";
//		�Զ�Load,ʹ��Ĭ��ֵʱ���Դ�,���Ҫ�Լ����ò���,�ر�����!
//		$this->Load();
	}
	/**
	 * php4���캯��
	 *
	 * @see __construct()
	 */
	function Cache($cvarname,$sql=""){
		$this->__construct($cvarname,$sql);
	}
	/**
	 * ���뻺�����
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
			//ʹ�û���
			if($this->UseFile && file_exists($this->CacheFullFile) && ($GLOBALS['timestamp']-filemtime($this->CacheFullFile) < $this->Ctime)){
				//�ļ�������Ч
				require_once($this->CacheFullFile);
				$this->CValue = ${$this->CvarName};
			}else if($this->UseDb){
				//���ݿ⻺��
				$this->CValue = $this->GetDBCache();
				if(empty($this->CValue)){
					$this->GetDBValue($sqls);
					$this->WriteCache();
				}
			}else{
				//�������
				$this->GetDBValue();
				$this->WriteCache();
			}
		}else {
			//��ʹ�û���
			$this->GetDBValue();
		}
	}
	
	/**
	 * �����ݿ��û������,���� $sqls ,ѡ��Ҫ���µ�sqls
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
			//�������������
			foreach ($this->CsqlString as $k => $v){
				if(!empty($sqls) && is_array($sqls) && !in_array($k,$sqls)){
					//���������µ�sql,Ĭ��ȫ������.
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
	 * д�����뻺���ļ�
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
			//todo var_export ��д
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
	 * д�����ݿ⻺��
	 *
	 */
	function WriteDBCache(){
		//���浽���ݿ�
		$array = array(
			'cacheid' => $this->CacheFile,
			'cachevalue' => serialize($this->CValue),
			'time' => time()
		);
		$this->Csql->DoInsert($array,$this->CacheDB,'Replace');
	}
	
	/**
	 * ������ݿ⻺��
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
	 * ���ػ�����������
	 *
	 * @param string $key
	 * @param bool $all
	 * @return array
	 */
	function GetValue($key="",$all=false){
		if(empty($key)){
			//��õ����黺�����,������һά����
			if($all) return $this->CValue;
			$rta = array();
			foreach ($this->CValue as $k => $v){
				if(!is_array($v)) $rta[$k] = $v;
			}
			return $rta;
		}else {
			//��ö����黺�����
			if(!empty($this->CValue[$key])){
				return $this->CValue[$key];
			}else {
				return array();
			}
		}
	}
	
	/**
	 * ���û���ʱ��
	 * $ismin Ϊtrueʱ�Ƿ��ӣ���������
	 *
	 * @param number $min
	 * @param bool $ismin
	 */
	function SetCtime($min,$ismin=true){
		if($ismin) $this->Ctime = $min * 60;
		else $this->Ctime = $min;
	}
	/**
	 * ���û����ļ����Ŀ¼
	 *
	 * @param string $dir
	 */
	function SetCacheDir($dir){
		$this->CacheDir = $dir;
	}
	/**
	 * ���û����ļ���չ��
	 *
	 * @param string $ext
	 */
	function SetExt($ext){
		if(ereg('[.]',$ext)) $this->Ext = $ext;
		else $this->Ext = ".{$ext}"; 
	}
	/**
	 * �Ƿ�����ݿ⻺��
	 *
	 * @param bool $true
	 */
	function EnableDB($true){
		if($true) $this -> UseDb = true;
		else $this -> UseDb = false;
	}
	/**
	 * ���û�������
	 *
	 * @param array $array
	 * @param string $key
	 * @param bool $rewrite
	 * @param bool $overwrite
	 */
	function SetCache($array,$key="",$rewrite=false,$overwrite=false){
		//��д
		if($rewrite) $this->CValue=array();
		//����
		if($overwrite){
			foreach ($array as $k => $v){
				if(empty($key)){
					$this->CValue[$k] = $v;
				}else{
					$this->CValue[$key][$k] = $v;
				}
			}
		}else {	//������
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