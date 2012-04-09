<?php
/**
 * ͨ��MySQL���Ӵ�����.
 * 
 * ���ݿ������,
 * �Դ����ݱ����ִ�����.
 * �Դ�ͨ��Insert����,�Զ������鴴��SQL ���,���ٳ������
 * �Դ�ͨ��Update����,�Զ������鴴��SQL ���,���ٳ������
 * �Դ�ͨ��Delete����,�Զ�����SQL ���,���ٳ������
 * 
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id
 * @todo ���ʹ��mysqli �ľ���ͬ��API��dbsql ��.Ӧ�ó����������
 */
class dbsql{
	/**
	 * ���ݿ�������
	 *
	 * @var string $dbHost
	 */
	var $dbHost;
	/**
	 * ���ݿ���
	 *
	 * @var string $dbName
	 */
	var $dbName;
	/**
	 * ���ݿ��û���
	 *
	 * @var string $dbUser
	 */
	var $dbUser;
	/**
	 * ���ݿ�����
	 *
	 * @var string $dbPwd
	 */
	var $dbPwd;
	/**
	 * ���ݿ��ַ��� �������ݿ��ַ�У��. Set Names $dbCharset
	 *
	 * @var string $dbCharset
	 */
	var $dbCharset;
	/**
	 * ���ݿ�����ID
	 *
	 * @var source $dbLinkID
	 */
	var $dbLinkID;
	/**
	 * ���ݿ��ǰ׺,����һ�����ݿ����м�����ͬ��ϵͳ������Ҫ
	 *
	 * @var string $dbPre
	 */
	var $dbPre;
	/**
	 * SQL��ѯ�ִ�
	 *
	 * @var string $dbQuery
	 */
	var $dbQuery="";
	/**
	 * �������������
	 *
	 * @var array $dbResultSet
	 */
	var $dbResultSet=array();
	/**
	 * �Ƿ񱣳ֳ�������
	 *
	 * @var bool $dbPconnect
	 */
	var $dbPconnect=false;
	/**
	 * ���ݿ�汾��,���ڰ汾������
	 *
	 * @var string $dbVersion
	 */
	var $dbVersion;
	/**
	 * �������Ӱ������
	 *
	 * @var number $dbAffectRow
	 */
	var $dbAffectRow=0;
	/**
	 * ������ID
	 * 
	 * @see mysql_insert_id()
	 * @var unknown_type
	 */
	var $dbLastId=0;
	var $dbUnSet=array();
	
	/**
	 * PHP5���캯��
	 *
	 * @param bool $pconnect �Ƿ��������
	 * @todo �������ɵ������ò���
	 */
	function __construct($config='config'){
		$config = $GLOBALS['_db_'.$config];
		if(empty($config) || !is_array($config)) $config = $GLOBALS["_db_config"];
		$this->dbHost = $config['dbhost'];
		$this->dbUser = $config['dbuser'];
		$this->dbPwd = $config['dbpwd'];
		$this->dbName = $config['dbname'];
		$this->dbCharset = $GLOBALS['base_config']['language'] ? str_replace('-','',$GLOBALS['base_config']['language']) : $config['dbcharset'];
		$this->dbPre = $config['dbpre'];
		$this->dbPconnect = $config['dbpconnect'];
		$this->dbLinkID = 0;
		$this->Start();
		$this->dbVersion = $this->GetServerInfo();
	}
	/**
	 * PHP4���캯��
	 *
	 * @see __construct()
	 */
	function dbsql($config=array()){
		$this->__construct($config);
	}
	/**
	 * ��ʼ���ݿ�����,��ִ������У��
	 *
	 * @return bool
	 */
	function Start(){
		if($this->dbLinkID){
			return ;
		}
		if($this->dbPconnect){
			$this->dbLinkID = @mysql_pconnect($this->dbHost,$this->dbUser,$this->dbPwd);
		}else{
			$this->dbLinkID = @mysql_connect($this->dbHost,$this->dbUser,$this->dbPwd);
		}
		if(!$this->dbLinkID){
			$this->ShowConnError("Connect Database Server Failed!");
		}else{
			$this->dbName && @mysql_select_db($this->dbName);
			if($this->GetServerInfo()>'4.1'&&$this->dbCharset){
				@mysql_query("SET NAMES {$this->dbCharset}",$this->dbLinkID);
				#ʹ��Ĭ�� SQL_MODE , ��Ҫ�ǽ����� �ϸ�ģʽ��
				@mysql_query("SET SQL_MODE=''");
			}
		}
	}//end of start;

	/**
	 * ����SQL���,�ú������Զ��� ##__ ���� ���ݿ��ǰ׺!
	 *
	 * @param string $string SQL���
	 */
	function SetQueryString($string){
		$string = trim($string);
		$this->dbQuery = trim($this->ChangeQuery($string));
	}
	
	function ChangeQuery($string) {
		return str_replace(array('##__','@@__'),array($this->dbPre,'##__'),$string);
	}
	/**
	 * �����з��ؽ������SQL���
	 *
	 * @param string $sourelink �������־
	 * @param string $sql SQL���,�����Ϊ����ִ��SetQueryString($sql)
	 */
	function ExecReturnSQL($sourelink='pp',$sql=""){
		if($sql!="") $this->SetQueryString($sql);
		$this->dbResultSet[$sourelink] = mysql_query($this->dbQuery,$this->dbLinkID);
		if(!$this->dbResultSet[$sourelink]){
			$this->ShowExecError();
		}
		$GLOBALS['_db_q'] ++;
	}
	/**
	 * ����û�з��ؽ������SQL ���,��Update,Insert��
	 *
	 * @param string $sql SQL���,�����Ϊ����ִ��SetQueryString($sql)
	 * @return bool
	 */
	function ExecNoReturnSQL($sql=''){
		if($sql!='') $this->SetQueryString($sql);
		if(empty($this->dbQuery)) return false;
		if(!($do = mysql_query($this->dbQuery,$this->dbLinkID))){
			$this->ShowExecError();
		}
		$this->dbAffectRow = mysql_affected_rows($this->dbLinkID);
		$GLOBALS['_db_q'] ++;
		return $do;
	}
	/**
	 * ��һ��������л��һ����¼��һ������,���ƫ��
	 *
	 * @param string $sourelink �������־
	 * @param defined $atype ��һ�����������Խ�������ֵ��MYSQL_ASSOC��MYSQL_NUM �� MYSQL_BOTH
	 * @return array ����һ����������
	 */
	function GetArray($sourelink='pp',$atype=MYSQL_ASSOC){
		if (empty($sourelink)) $sourelink = 'pp';
		if(!$this->IsActiveSource($sourelink)) return array();
		else {
			return mysql_fetch_array($this->dbResultSet[$sourelink],$atype);
		}
	}
	/**
	 * ��һ��������л��һ����¼��һ������,���ƫ��
	 *
	 * @param string $sourcelink
	 * @return object
	 */
	function GetObject($sourcelink='pp'){
		if (empty($sourelink)) $sourelink = 'pp';
		if(!$this->IsActiveSource($sourcelink)) return false;
		else {
			return mysql_fetch_object($this->dbResultSet[$sourcelink]);
		}
	}
	
	/**
	 * ��һ��ֻ����һ�е�SQL ���ֱ�ӻ��һ�������¼
	 *
	 * @param string $sql
	 * @return array
	 */
	function GetOneArray($sql=""){
		if($sql != ""){
			$this->SetQueryString($sql);
		}
		if(!eregi("limit",$this->dbQuery)) $this->dbQuery .= " limit 0,1";
		$this->ExecReturnSQL('onearray');
		$arr = $this->GetArray('onearray');
		if(!is_array($arr)) return "";
		else {
			$this->FreeOneResult('onearray');
			return $arr;
		}
	}
	/**
	 * ͨ��Inset ����,�ύһ������,�Զ�����SQL ��䲢Insert ,����SQL����
	 * �÷����������Բ���һ������,Ҳ��һ�β����������,һά�����ύ,����һ������;��ά�����ύ�����������
	 *
	 * @param array $array
	 * @param string $table 
	 * @param enmu $type ����Insert (inseret into)���� replace (replace into)��Сд������
	 * @return bool
	 * @access public
	 */
	function DoInsert($array,$table,$type="Insert",$ignore=0){	
		if(!is_array($array)) return false;
		$tbcols = "(";
		$tbvalues = "(";
		$i = 0;
		foreach ($array as $k => $v){
			if(!is_array($v)){				//����һ�м�¼	array Ϊһά����
				if($tbcols=="(") $tbcols .= "`{$k}`";
				else $tbcols .= ",`{$k}`";
				if($tbvalues=="(") $tbvalues .= "'{$v}'";
				else $tbvalues .= ",'{$v}'";
				$inone = true;
			}else {							//������м�¼ $array Ϊ��ά����
				$inone = false;
				if($tbvalues!="(") $tb = "(";
				else $tb = "";
				foreach ($v as $kk => $vv){
					if($i==0){
						if($tbcols=="(") $tbcols .="`{$kk}`";
						else $tbcols .=",`{$kk}`";
					}
					if($tb=="("||$tb=="") $tb .="'{$vv}'";
					else $tb .= ",'{$vv}'";
				}
				if($tbvalues!="(") $tbvalues .= ",".$tb . ")";
				else $tbvalues .= $tb . ")";
				$i ++;
			}
		}
		$tbcols .=")";
		if($inone) $tbvalues .= ")";
		$ignore = $ignore ? 'IGNORE' : '';
		if(strtolower($type)=="replace") $sql = "Replace Into {$table} {$tbcols} Values {$tbvalues}";
		else $sql = "Insert $ignore Into {$table} {$tbcols} Values {$tbvalues}";
		$this->SetQueryString($sql);
		$do = $this -> ExecNoReturnSQL();
		if($do){
			$this->dbLastId = $this -> GetLastInsertId();
			return 1;
		}else {
			return 0;
		}
	}
	
	/**
	 * ͨ��Update ����.ֻ���ύһ�������table ������!�Զ�����SQL���,����SQL����
	 *
	 * @param array $array ��Ҫ���µ�����
	 * @param string $table ���ݱ�
	 * @param string $where where����
	 * @param array $arunset ���� ' ���ֶΡ�����ѧ������
	 * @param array $protect �ܱ����������ֶ�
	 * @param bool $check �Ƿ������ݱ��ֶ��Ƿ�ȫ
	 * @return bool
	 * @access public
	 */
	function DoUpdate($array,$table,$where,$arunset=array(),$protect=array(),$check=false){
		if(empty($where)) exit('Σ�ղ���.Forbidden');
		if(!is_array($array)) return false;
		$sql = "Update {$table} Set ";
		if(!is_array($arunset)) $arunset = array();		//���� `` �ֶ�
		if(!is_array($protect)) $protect = array();		//�����Ʋ��������ֶ�,��ȫ����
		$up = '';
		foreach ($array as $k => $v){
			//��ȫ���,��������û�е��ֶ�
			if(in_array($k,$protect)) continue;
			if($check){
				$ch = false;
				$ar = $this->GetFieldList($table);
				foreach ($ar as $kk => $vv){
					if(in_array($v,$vv['Field'])){
						$ch = true;break;
					}
				}
				if(!$ch) continue;
			}
			if($up == ''){
				if(in_array($k,$arunset)) $up .= "`{$k}`={$v}";
				else $up .="`{$k}`='$v'";
			}else {
				if(in_array($k,$arunset)) $up .= ",`{$k}`={$v}";
				else $up .=",`{$k}`='{$v}'";
			}
		}
		if($up) {
			$sql = "{$sql} {$up} where {$where}";
			$this->SetQueryString($sql);
			if($this->ExecNoReturnSQL()){
				return true;
			}else {
				$this->dbAffectRow = 0;
				return false;
			}
		}else {
			return true;
		}
	}
	/**
	 * ͨ��ɾ�����¼����,������ʹ��,����Ǳ������
	 *
	 * @param string $table
	 * @param string $where
	 * @return bool
	 * @access public
	 */
	function Dodelete($table,$where){
		if(empty($where)) exit('Σ�ղ���.Forbidden');
		$sql = "Delete From {$table} where {$where}";
		$this->SetQueryString($sql);
		$do = $this->ExecNoReturnSQL();
		if($do){
			$this->dbAffectRow = $this->GetAffectRow();
			return true;
		}else{
			$this->dbAffectRow = 0;
			return false;
		}
	}
	/**
	 * �����Ӱ������
	 *
	 * @return number
	 */
	function GetAffectRow(){
		return mysql_affected_rows($this->dbLinkID);
	}
	/**
	 * �����������ID
	 * bigint �������ֶ�ʹ��mysql �ڲ����� LAST_INSERT_ID() , 
	 * LAST_INSERT_ID() ���������ڲ���null,����0���Զ����ɵ�ֵ,�����Լ�ָ�����ض���ֵ�������ϴβ�����ֵ,���û��,��Ȼ����0.
	 * 
	 * @return number
	 */
	function GetLastInsertId(){
//		return $this->dbLastId = mysql_insert_id($this->dbLinkID);
		//����!�ú�����auto_increament �ֶ���bigint ʱ��ʧЧ. ���,�β��ı���һ״����.ʹ������ķ���,���ܵõ���ȷ�Ľ��.
		/**
		bigint �������ֶ�ʹ��mysql �ڲ����� LAST_INSERT_ID() ,  LAST_INSERT_ID() ���������ڲ���null,����0���Զ����ɵ�ֵ,�����Լ�ָ�����ض���ֵ�������ϴβ�����ֵ,���û��,��Ȼ����0.
		*/
		$row = $this -> GetOneArray('Select LAST_INSERT_ID() as li');
		return $row['li'];
	}
	/**
	 * ��÷��صļ�¼����
	 *
	 * @param string $sourelink �������־
	 * @return num
	 */
	function GetReturnNum($sourelink='pp'){
		if(empty($this->dbResultSet[$sourelink])) return -1;
		else return mysql_num_rows($this->dbResultSet[$sourelink]);
	}
	/**
	 * ������ݿ�������汾��Ϣ
	 *
	 * @return string ���ݿ�汾�ִ�
	 */
	function GetServerInfo(){
		return mysql_get_server_info($this->dbLinkID);
	}
	/**
	 * ������ݿ��б�
	 *
	 * @return array ��ѯʧ�ܷ���false
	 */
	function GetDbList(){
		$dbs = array();
		$this->dbResultSet['dbs'] = @mysql_query("SHOW DATABASES",$this->dbLinkID);
		if(!$this->IsActiveSource('dbs')){
			return array();
		}
		while ($row = $this->GetArray('dbs',MYSQL_NUM)) {
			$dbs[$row[0]] = $row[0];
		}
		$this->FreeOneResult('dbs');
		return $dbs;
	}
	/**
	 * ������ݿ��Ƿ����
	 *
	 * @param string $db
	 * @return bool
	 */
	function DbExist($db) {
		$this->dbResultSet['db'] = @mysql_query("SHOW DATABASES LIKE '$db'",$this->dbLinkID);
		if(!$this->IsActiveSource('db')){
			return false;
		}
		while ($row = $this->GetArray('db',MYSQL_NUM)) {
			$return = $row[0] == $db ? true : false;
			break;
		}
		$this->FreeOneResult('db');
		return $return;
	}
	/**
	 * ���ĳ�����ݿ�ı��б�
	 *
	 * @param string $dbname ���ݿ���
	 * @return array ��ѯʧ�ܷ���false
	 */
	function GetTableList($like='',$db=''){
		$tbs = array();
		if (!$db) $db = $this-> dbName;
		if ($like) {
			$like = $this->ChangeQuery($like);
			$like = str_replace(array('_','.',),array('\_','\.'),str_replace(array('\_','\.'),array('_','.'),$like));
			$sql = "SHOW TABLES FROM {$db} like '$like'";
		}else {
			$sql = "SHOW TABLES FROM {$db}";
		}
		$this -> dbResultSet['tbs'] = @mysql_query($this->ChangeQuery($sql),$this->dbLinkID);
		if(!$this->IsActiveSource('tbs')){
			return array();
		}
		while ($row = $this->GetArray('tbs',MYSQL_NUM)) {
			$tbs[$row[0]] = $row[0];
		}
		$this->FreeOneResult('tbs');
		return $tbs;
	}
	/**
	 * �ж�һ�����ݱ��Ƿ����
	 *
	 * @param string $table
	 * @param string $db
	 * @return bool
	 */
	function TableExist($table,$db='') {
		!$db && $db = $this->dbName;
		
		$table = $this->ChangeQuery($table);
		$table = str_replace(array('_','.',),array('\_','\.'),str_replace(array('\_','\.'),array('_','.'),$table));
		
		$this->dbResultSet['tbs'] = @mysql_query("SHOW TABLES FROM {$db} LIKE '$table'",$this->dbLinkID);
		if(!$this->IsActiveSource('tbs')){
			return false;
		}
		while ($row = $this->GetArray('tbs',MYSQL_NUM)) {
			$return = $row[0] ? true : false;
			break;
		}
		$this->FreeOneResult('tbs');
		return $return;
	}
	/**
	 * ���ĳ������ֶ��б�
	 *
	 * @param string $table ���ݱ���
	 * @param string $dbname ���ݿ���
	 * @return array ��ѯʧ�ܷ��� �� array()
	 */
	function GetFieldList($table,$dbname='',$like='',$w=2){
		$fds = array();
		if ($dbname=="") $dbname = $this->dbName;
		$sql = "Show columns From {$dbname}.{$table}";
		if ($like) {
			$like = str_replace(array('_','.',),array('\_','\.'),str_replace(array('\_','\.'),array('_','.'),$like));
			$sql .= " like '$like'";
		}
		$this -> dbResultSet['cols'] = @mysql_query($this->ChangeQuery($sql));
		if(!$this->IsActiveSource('cols')) return array();
		while ($row = $this->GetArray('cols',MYSQL_NUM)) {
			if ($w == 2) {
				$fds[$row[0]]= $row;
			}else {
				$fds[$row[0]]= $row[0];
			}
		}
		$this->FreeOneResult('cols');
		return $fds;
	}
	/**
	 * ���һ�������Ƿ����һ���ֶ�
	 *
	 * @param string $field
	 * @param string $table
	 * @param string $db
	 * @return bool
	 */
	function FieldExist($field,$table,$db='') {
		!$db && $db = $this->dbName;
		
		$field = str_replace(array('_','.',),array('\_','\.'),str_replace(array('\_','\.'),array('_','.'),$field));
		
		$this->dbResultSet['field'] = @mysql_query("Show columns From {$db}.{$table} LIKE '$field'",$this->dbLinkID);
		if(!$this->IsActiveSource('field')){
			return false;
		}
		while ($row = $this->GetArray('field',MYSQL_NUM)) {
			$return = $row[0] == $field ? true : false;
			break;
		}
		$this->FreeOneResult('field');
		return $return;
	}
	//���һ�����ݿ��ı���
	//�Ѳ�4.1���ϰ�, toversion<4.1����
	/**
	 * ���һ�����ݿ��ı���
	 *
	 * @param string $table ���ݿ��
	 * @param bool $droptable �Ƿ����droptable
	 * @param string $toversion ���ݳ�ʲô�汾�����ݿ�SQL���
	 * @return string
	 * @todo test where toversion <4.1
	 */
	function GetBackUpTableStructString($table,$droptable=true,$toversion='4.1'){
		$str = "";
		if($droptable) $str = "DROP TABLE IF EXISTS `$table`;\r\n";
		$this->SetQueryString($str);
		$str = $this->dbQuery."\r\n";
		$this->SetQueryString("Show Create Table `$table`");
		$this->ExecReturnSQL();
		$row = $this->GetArray('',MYSQL_NUM);
		$engine = "ENGINE=MyISAM AUTO_INCREMENT=(.*) DEFAULT CHARSET={$this->dbCharset}";
		if($toversion=='4.1' && $this->dbVersion<'4.1'){
			$str .= preg_replace("/TYPE=MyISAM  AUTO_INCREMENT=(.*)/isU","ENGINE=MyISAM AUTO_INCREMENT=\\1 DEFAULT CHARSET={$this->dbCharset}",$row[1]);
		}else if($toversion<'4.1'&&$this->dbVersion>'4.1'){
			$str .= preg_replace("/ENGINE=MyISAM AUTO_INCREMENT=(.*) DEFAULT CHARSET={$this->dbCharset}/isU","TYPE=MyISAM AUTO_INCREMENT=\\1",$row[1]);
		}else {
			//�Ѳ�
			$str .= $row[1];
		}
		$str .= ";\r\n";
		return $str;
	}
	
	/**
	 * ��ñ��崮
	 *
	 * @param string $table
	 * @param bool $droptable
	 * @return string
	 */
	function GetTableDefString($table,$cptable='',$droptable=false) {
		$str = '';
		$crlf = "\n";
		$this->SetQueryString($table);
		$table = $this->dbQuery;
		$this->SetQueryString($cptable);
		$cptable = $this->dbQuery;
		
		if($droptable) $str = "DROP TABLE IF EXISTS `$table`;$crlf";
		$this->SetQueryString("Show Create Table `$table`");
		$this->ExecReturnSQL();
		$row = $this->GetArray('',MYSQL_NUM);
		if($row) {
			$create_sql = $row[1];
			unset($row);
			if($cptable) {
				if (strpos($create_sql, "(\r\n ")) {
		            $create_sql = str_replace("\r\n", $crlf, $create_sql);
		        } elseif (strpos($create_sql, "(\n ")) {
		            $create_sql = str_replace("\n", $crlf, $create_sql);
		        } elseif (strpos($create_sql, "(\r ")) {
		            $create_sql = str_replace("\r", $crlf, $create_sql);
		        }
		        if(!$droptable) {
		        	$create_sql     = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_sql);
		        }
		        $sql_lines = explode($crlf,$create_sql);
		        foreach ($sql_lines as $k => $v) {
		        	if (preg_match('/^CREATE TABLE/is',$v)) {
		        		$sql_lines[$k] = str_replace($table,$cptable,$v);
		        		break;
		        	}
		        }
		        $create_sql = implode($crlf,$sql_lines);
			}
			
			if($this->dbVersion < '4.1'){
				$str .= preg_replace("/TYPE=MyISAM (.*)/is","ENGINE=MyISAM",$create_sql);
			}else if($this->dbVersion >= '4.1'){
				$str .= preg_replace("/ENGINE=MyISAM (.*)/is","TYPE=MyISAM DEFAULT CHARSET={$this->dbCharset}",$create_sql);
			}
			$str .= ";$crlf";
			return $str;
		}else {
			return '';
		}
	}
	/**
	 * �ر�ָ��������
	 *
	 * @param source $link
	 */
	function CloseOneLink($link){
		if(!is_resource($link)) return ;
		mysql_close($link);
		$this->dbLinkID = !is_resource($this->dbLinkID)?"":$this->dbLinkID;
	}
	/**
	 * �ر����ݿ�����
	 *
	 */
	function Close(){
		$this->FreeAllResult();
		$this->CloseOneLink($this->dbLinkID);
	}
	/**
	 * ����ĳ�β�ѯ���ֶ��б�
	 *
	 * @param string $source �������־
	 * @return array
	 */
	function GetFieldAfterQuery($source='pp'){
		$ar = array();
		while ($row = mysql_fetch_field($this->dbResultSet[$source])) {
			$ar[] = $row;
		}
		return $ar;
	}
	/**
	 * ѡ�����ݿ�
	 *
	 * @param string $dbname ���ݿ���
	 * @param bool $change �Ƿ�ı����ǰ���ݿ�
	 */
	function SelectDB($dbname,$change=false){
		mysql_select_db($dbname);
		if($change) $this->dbName = $dbname;
	}
	/**
	 * �ͷ�һ�������
	 *
	 * @param string $rs �������־
	 */
	function FreeOneResult($rs='pp'){
		@mysql_free_result($this->dbResultSet[$rs]);
		$this->UpdateResultSet();
	}
	/**
	 * �޳���Ч�����
	 *
	 */
	function UpdateResultSet(){
		foreach ($this->dbResultSet as $k => $v){
			if(!is_resource($v)) unset($this->dbResultSet[$k]);
		}
	}
	/**
	 * �ͷ����н����
	 *
	 */
	function FreeAllResult(){
		if(empty($this->dbResultSet)) return ;
		if(is_array($this->dbResultSet)){
			foreach ($this->dbResultSet as $k => $v){
				mysql_free_result($v);
				unset($this->dbResultSet[$k]);
			}
		}
	}
	/**
	 * try һ�������Ƿ�����Ч��Resource��Դ��.
	 *
	 * @param string $source �������־
	 * @return bool
	 */
	function IsActiveSource($source='pp'){
		if(is_resource($this->dbResultSet[$source])) return true;
		else return false;
	}
	/**
	 * ��һ������,������һ������ļ���Χ,������ļ���Χ��������һ�������ֵ��Χ��
	 *
	 * @param array $safe ��Ҫ���Ƶ�����,����һ����ֵ,���ı�ò�����ֵ
	 * @param array $safearray ��������ķ�Χ
	 * @access public
	 */
	function SafeArrayPre(&$safe,$safearray,$w=1) {
		if( !$safe || !is_array($safe) || !is_array($safearray) || !$safearray) return ;
		foreach ($safe as $k => $v) {
			if ($w == 1) {	//һά��ȫ
				if(!is_array($v)){
				//safe һά����
					if(!in_array($k,$safearray)) {
						unset($safe[$k]);
					}
				}else {
					unset($safe[$k]);
				}
			}else {	//��ά��ȫ
				if(!is_array($v)){
					unset($safe[$k]);
				}else {
					foreach ($v as $key => $value) {
						if(!in_array($key,$safearray)) {
							unset($safe[$k][$key]);
						}
					}
				}
			}
		}
	}
	/**
	 * ����֪ͨ�ӿ�
	 *
	 * @param string $msg ������Ϣ
	 */
	function ShowError($msg){
		@include(WORK_DIR . 'config/install_config.php');
		@include(ROOT . 'config/install_config.php');
		$module = "MODULE: " . MODULE . " Version: " . $install_mode_version .  " Base FRAME Version: " . $install_frame_version;
		$env = "PHPVersion: " . phpversion() . " MySQL Version: " . $this -> dbVersion;
		
		echo "<html>\r\n";
		echo "<head>\r\n";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\r\n";
		echo "<title>MySQL Database Error</title>\r\n";
		echo "<style type='text/css'>P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:16px;}A { TEXT-DECORATION: none;}a:hover{ text-decoration: underline;}TD { BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 16pt; COLOR: #000000;}</style>";
		echo "</head>\r\n";
		echo "<body>\r\n<p style='line-helght:150%'>\r\n";
		echo $msg;
		echo"<br><b>The URL Is</b>:<br>".GetRtFullUrl();
		echo "<br />";
		echo $env;
		echo "<br/>";
		echo $module;
		echo "<br/>";
		echo "<strong>You Can Get Help From <a target=_blank href=http://bbs.ppframe.com/?r=" . GetRtFullUrl(0) . "&mv=$install_mode_version&fv=$install_frame_version>http://bbs.ppframe.com/</a></strong>";
		echo "</p>\r\n</body>\r\n";
		echo "</html>";
		exit;
	}
	
	function ShowConnError() {
		$this->ShowError("Connect Database Server Failed:<font color=red>" . mysql_error() . " Errno(" . mysql_errno() . ")</font>");
	}
	/**
	 * ���������Ϣ
	 *
	 */
	function ShowExecError(){
		$this->ShowError(mysql_error() ." Errno(" . mysql_errno() . ")<br /> MySQL Execut SQL:<font color=red>{$this->dbQuery}</font>");
	}
}//end of class
?>