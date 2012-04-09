<?php
/**
 *	ͨ�����ô�����
 * 
 *	����:�Զ����ô���,�Զ�����Ϊ���鵽�ļ�,���ѡ��Userdb,Ҳ������һ�ݸ��������ݿ�
 *	������Щ�����Զ���.
 *	�û����赣�Ĵ���ʽ
 * 
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id
 *
*/

Iimport('dbsql');
class Config{
	/**
	 * ���ñ��ֵ����ݱ�
	 *
	 * @var string $Ctable
	 */
	var $Ctable= "##__frame_config";
	/**
	 * �Ƿ������ݿⱣ�����ø�����־
	 *
	 * @var bool $CUseDb
	 */
	var $CUseDb= true;
	/**
	 * �������õı�����
	 *
	 * @var string $CArrayName
	 */
	var $CArrayName;
	/**
	 * �������õ�����
	 *
	 * @var array
	 */
	var $CArray=array();
	/**
	 * ���������ļ���Ŀ¼
	 *
	 * @var string $Cdir
	 */
	var $Cdir;
	/**
	 * ���ݿ���ʲ�����
	 *
	 * @var object
	 */
	var $Csql;
	/**
	 * �����ļ���ŵ��ļ���
	 *
	 * @var string $Cfile
	 */
	var $Cfile;
	/**
	 * �����ļ�����չ����ʹ��.PHP���Է�ֹ�����ļ�������
	 *
	 * @var string $CfileExt
	 */
	var $CfileExt='.php';
	/**
	 * �����������ļ�·��
	 *
	 * @var string $Cfullfile
	 */
	var $Cfullfile;
	/**
	 * ���ӱ��������������ͻ���ݲ�ʹ��
	 *
	 * @var string $Cplus
	 */
	var $Cplus="";
	
	/**
	 * PHP5���캯��
	 * 
	 *
	 * @param string $carrayname ���ڴ洢���ñ����ı�����
	 * @param string $fullfile ���������ļ���ŵ�����·�������߼��û�����
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
	 * PHP4���캯��
	 *
	 * @see __construct()
	 */
	function Config($carrayname,$file='',$table=''){
		$this->__construct($carrayname,$file,$table);
	}
	/**
	 * ���������ļ����������·��,
	 * ��������˲���$file ��ֱ�ӽ��˲������ó�����·��������������һ���������õ�һ���������ļ�
	 * Ĭ������£��ɻᰴһ����������һ��Ψһ���ļ���ֻ�ü�ס $CarrayName �����Ľ�����������ˡ�
	 * 
	 * @param string $file ָ��������������ļ��������·��
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
	 * �����������ݣ��������ݱ����� $CArray
	 * �����ȴ������ļ���������Ҳ������д������ݿ����ø�����������ݿ���������
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
	//������������
	//$overwrite=true ������������,Ĭ���޸�
	/**
	 * ��һ�����������������ݵ���������
	 *
	 * @param array $array ����������������ݣ�һά���飩
	 * @param string $name ��������õı�־��������գ�������һ��һά�������ã����������ά��������
	 * @param bool $overwrite �Ƿ񸲸�ԭ�����ÿ���,Ĭ�ϸ���ԭ������
	 * @param bool $rewrite �Ƿ���дԭ�����ÿ��أ�Ĭ�ϲ���д������ζ�ţ���û��ָ���������ʹ��ԭ������
	 * @param bool $allrewrite ��д�������ÿ��أ�Σ�գ���Ĭ�ϲ���Ҳû�б�Ҫ�����⽫���������������ݡ�
	 */
	function LoadConfig($array=array(),$name='',$overwrite=true,$rewrite=false,$allrewrite=false){
		if(!is_array($array)) return ;
		
		
		if($rewrite && $name) {
			$this->CArray[$name] = array();
		}
		if($allrewrite) $this->CArray = array();//ȫ����
		if($overwrite){//����
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
	 * �������ֱ������һ�����õ�������������
	 *
	 * @param array $array Ҫ���������
	 * @param string $name �������ƣ�������������һά���飬��������Ƕ�ά����
	 * @param bool $overwrite ��ʱ����
	 */
	function LoadConfigNoCheck($array=array(),$name="",$overwrite=true) {		
		if($name) $this->CArray[$name] = $array;
		else  $this->CArray = $array;
	}
	/**
	 * ��д������Ϣ��
	 * ��������ݿ�洢���ݣ���ͬʱҲд��һ�ݵ����ݿ�
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
	 * ǿ�ƴ����ݿ��������ñ���
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
	 * �õ�һ�����õ�ֵ
	 *
	 * @param string $string ������ļ�ֵ
	 * @param string $name ���շ���һά���飬���򷵻ض�ά����ֵ
	 * @return mix ���ظ������õ�ֵ
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
	 * �������������
	 *
	 * @param string $string ���ñ�־
	 * @return array ���� $string ��־��������
	 */
	function GetConfig($string="") {
		if($string) return $this->CArray[$string];
		else return $this->CArray;
	}
	
	/**
	 * �����ļ���չ��
	 *
	 * @param string $ext �ļ���չ���ɼӵ�Ҳ�ɲ���
	 */
	function SetExt($ext){
		if(ereg('[.]',$ext)) $this-> CfileExt = $ext;
		else $this->Ext = ".{$ext}"; 
	}
	/**
	 * ���������ļ�����ڸ�Ŀ¼�Ĵ��Ŀ¼
	 * �÷�����δ����
	 *
	 * @param string $dir
	 */
	function SetConDir($dir){
		$this -> Cdir = ROOT.$cdir."/";
	}
	
	/**
	 * �������ݿⱸ�ݴ洢
	 *
	 * @param bool $true true �����ݿ�洢��false�ر�
	 */
	function EnableDB($true=true){
		if($true) $this -> CUseDb = true;
		else $this -> CUseDb = false;
	}
	
	/**
	 * ��ʼ���ݿ���ʡ�����Ѿ����Ӳ������ӡ��˴����Ƚϴֲ�
	 *
	 */
	function StartSQL() {
		if(!is_object($this->Csql))
		$this->Csql = new dbsql();
	}
	/**
	 * �������ݿⱸ�ݱ�
	 *
	 * @param string $table
	 */
	function SetTable($table="") {
		if($table) $this->Ctable = $table;
	}
}