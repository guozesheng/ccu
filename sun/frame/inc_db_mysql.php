<?php
/**
 * 通用MySQL链接处理类.
 * 
 * 数据库操作类,
 * 自带数据表备份字串功能.
 * 自带通用Insert功能,自动从数组创建SQL 语句,减少程序风险
 * 自带通用Update功能,自动从数组创建SQL 语句,减少程序风险
 * 自带通用Delete功能,自动创建SQL 语句,减少程序风险
 * 
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id
 * @todo 完成使用mysqli 的具有同样API的dbsql 类.应用程序无需更改
 */
class dbsql{
	/**
	 * 数据库主机名
	 *
	 * @var string $dbHost
	 */
	var $dbHost;
	/**
	 * 数据库名
	 *
	 * @var string $dbName
	 */
	var $dbName;
	/**
	 * 数据库用户名
	 *
	 * @var string $dbUser
	 */
	var $dbUser;
	/**
	 * 数据库密码
	 *
	 * @var string $dbPwd
	 */
	var $dbPwd;
	/**
	 * 数据库字符集 用于数据库字符校对. Set Names $dbCharset
	 *
	 * @var string $dbCharset
	 */
	var $dbCharset;
	/**
	 * 数据库链接ID
	 *
	 * @var source $dbLinkID
	 */
	var $dbLinkID;
	/**
	 * 数据库表前缀,对于一个数据库运行几个相同的系统可能需要
	 *
	 * @var string $dbPre
	 */
	var $dbPre;
	/**
	 * SQL查询字串
	 *
	 * @var string $dbQuery
	 */
	var $dbQuery="";
	/**
	 * 结果集保存数组
	 *
	 * @var array $dbResultSet
	 */
	var $dbResultSet=array();
	/**
	 * 是否保持持续链接
	 *
	 * @var bool $dbPconnect
	 */
	var $dbPconnect=false;
	/**
	 * 数据库版本号,用于版本差别控制
	 *
	 * @var string $dbVersion
	 */
	var $dbVersion;
	/**
	 * 结果集受影响行数
	 *
	 * @var number $dbAffectRow
	 */
	var $dbAffectRow=0;
	/**
	 * 最后插入ID
	 * 
	 * @see mysql_insert_id()
	 * @var unknown_type
	 */
	var $dbLastId=0;
	var $dbUnSet=array();
	
	/**
	 * PHP5构造函数
	 *
	 * @param bool $pconnect 是否持续链接
	 * @todo 设置自由导入配置参数
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
	 * PHP4构造函数
	 *
	 * @see __construct()
	 */
	function dbsql($config=array()){
		$this->__construct($config);
	}
	/**
	 * 开始数据库链接,并执行语言校对
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
				#使用默认 SQL_MODE , 主要是禁用了 严格模式。
				@mysql_query("SET SQL_MODE=''");
			}
		}
	}//end of start;

	/**
	 * 设置SQL语句,该函数将自动将 ##__ 换成 数据库表前缀!
	 *
	 * @param string $string SQL语句
	 */
	function SetQueryString($string){
		$string = trim($string);
		$this->dbQuery = trim($this->ChangeQuery($string));
	}
	
	function ChangeQuery($string) {
		return str_replace(array('##__','@@__'),array($this->dbPre,'##__'),$string);
	}
	/**
	 * 运行有返回结果集的SQL语句
	 *
	 * @param string $sourelink 结果集标志
	 * @param string $sql SQL语句,如果不为空则执行SetQueryString($sql)
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
	 * 运行没有返回结果集的SQL 语句,如Update,Insert等
	 *
	 * @param string $sql SQL语句,如果不为空则执行SetQueryString($sql)
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
	 * 从一个结果集中或得一条记录到一个数组,逐次偏移
	 *
	 * @param string $sourelink 结果集标志
	 * @param defined $atype 是一个常量，可以接受以下值：MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH
	 * @return array 返回一个数组结果集
	 */
	function GetArray($sourelink='pp',$atype=MYSQL_ASSOC){
		if (empty($sourelink)) $sourelink = 'pp';
		if(!$this->IsActiveSource($sourelink)) return array();
		else {
			return mysql_fetch_array($this->dbResultSet[$sourelink],$atype);
		}
	}
	/**
	 * 从一个结果集中获得一条记录到一个对象,逐次偏移
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
	 * 从一条只返回一行的SQL 语句直接或得一个数组记录
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
	 * 通用Inset 方法,提交一个数组,自动创建SQL 语句并Insert ,避免SQL错误
	 * 该方法不仅可以插入一条数据,也可一次插入多条数据,一维数组提交,插入一条数据;二维数组提交插入多条数据
	 *
	 * @param array $array
	 * @param string $table 
	 * @param enmu $type 接受Insert (inseret into)或者 replace (replace into)大小写不敏感
	 * @return bool
	 * @access public
	 */
	function DoInsert($array,$table,$type="Insert",$ignore=0){	
		if(!is_array($array)) return false;
		$tbcols = "(";
		$tbvalues = "(";
		$i = 0;
		foreach ($array as $k => $v){
			if(!is_array($v)){				//插入一行记录	array 为一维数组
				if($tbcols=="(") $tbcols .= "`{$k}`";
				else $tbcols .= ",`{$k}`";
				if($tbvalues=="(") $tbvalues .= "'{$v}'";
				else $tbvalues .= ",'{$v}'";
				$inone = true;
			}else {							//插入多行记录 $array 为二维数组
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
	 * 通用Update 方法.只需提交一个数组和table 名即可!自动创建SQL语句,避免SQL错误
	 *
	 * @param array $array 需要更新的数组
	 * @param string $table 数据表
	 * @param string $where where条件
	 * @param array $arunset 不加 ' 的字段。作数学计算用
	 * @param array $protect 受保护不更新字段
	 * @param bool $check 是否检查数据表字段是否安全
	 * @return bool
	 * @access public
	 */
	function DoUpdate($array,$table,$where,$arunset=array(),$protect=array(),$check=false){
		if(empty($where)) exit('危险操作.Forbidden');
		if(!is_array($array)) return false;
		$sql = "Update {$table} Set ";
		if(!is_array($arunset)) $arunset = array();		//不加 `` 字段
		if(!is_array($protect)) $protect = array();		//受限制不被更新字段,安全限制
		$up = '';
		foreach ($array as $k => $v){
			//安全检查,跳过表中没有的字段
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
	 * 通用删除表记录方法,不建议使用,存在潜在隐患
	 *
	 * @param string $table
	 * @param string $where
	 * @return bool
	 * @access public
	 */
	function Dodelete($table,$where){
		if(empty($where)) exit('危险操作.Forbidden');
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
	 * 获得受影响行数
	 *
	 * @return number
	 */
	function GetAffectRow(){
		return mysql_affected_rows($this->dbLinkID);
	}
	/**
	 * 获得最后插入行ID
	 * bigint 的自增字段使用mysql 内部函数 LAST_INSERT_ID() , 
	 * LAST_INSERT_ID() 仅返回由于插入null,或者0而自动生成的值,对于自己指定的特定的值将返回上次产生的值,如果没有,当然返回0.
	 * 
	 * @return number
	 */
	function GetLastInsertId(){
//		return $this->dbLastId = mysql_insert_id($this->dbLinkID);
		//警告!该函数在auto_increament 字段是bigint 时将失效. 因此,何不改变这一状况呢.使用下面的方法,总能得到正确的结果.
		/**
		bigint 的自增字段使用mysql 内部函数 LAST_INSERT_ID() ,  LAST_INSERT_ID() 仅返回由于插入null,或者0而自动生成的值,对于自己指定的特定的值将返回上次产生的值,如果没有,当然返回0.
		*/
		$row = $this -> GetOneArray('Select LAST_INSERT_ID() as li');
		return $row['li'];
	}
	/**
	 * 获得返回的记录行数
	 *
	 * @param string $sourelink 结果集标志
	 * @return num
	 */
	function GetReturnNum($sourelink='pp'){
		if(empty($this->dbResultSet[$sourelink])) return -1;
		else return mysql_num_rows($this->dbResultSet[$sourelink]);
	}
	/**
	 * 获得数据库服务器版本信息
	 *
	 * @return string 数据库版本字串
	 */
	function GetServerInfo(){
		return mysql_get_server_info($this->dbLinkID);
	}
	/**
	 * 获得数据库列表
	 *
	 * @return array 查询失败返回false
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
	 * 检测数据库是否存在
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
	 * 获得某个数据库的表列表
	 *
	 * @param string $dbname 数据库名
	 * @return array 查询失败返回false
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
	 * 判断一个数据表是否存在
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
	 * 获得某个表的字段列表
	 *
	 * @param string $table 数据表名
	 * @param string $dbname 数据库名
	 * @return array 查询失败返回 空 array()
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
	 * 获得一个表里是否存在一个字段
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
	//获得一个数据库表的备份
	//已测4.1以上版, toversion<4.1待测
	/**
	 * 获得一个数据库表的备份
	 *
	 * @param string $table 数据库表
	 * @param bool $droptable 是否添加droptable
	 * @param string $toversion 备份成什么版本的数据库SQL语句
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
			//已测
			$str .= $row[1];
		}
		$str .= ";\r\n";
		return $str;
	}
	
	/**
	 * 获得表定义串
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
	 * 关闭指定的链接
	 *
	 * @param source $link
	 */
	function CloseOneLink($link){
		if(!is_resource($link)) return ;
		mysql_close($link);
		$this->dbLinkID = !is_resource($this->dbLinkID)?"":$this->dbLinkID;
	}
	/**
	 * 关闭数据库链接
	 *
	 */
	function Close(){
		$this->FreeAllResult();
		$this->CloseOneLink($this->dbLinkID);
	}
	/**
	 * 返回某次查询的字段列表
	 *
	 * @param string $source 结果集标志
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
	 * 选择数据库
	 *
	 * @param string $dbname 数据库名
	 * @param bool $change 是否改变对象当前数据库
	 */
	function SelectDB($dbname,$change=false){
		mysql_select_db($dbname);
		if($change) $this->dbName = $dbname;
	}
	/**
	 * 释放一个结果集
	 *
	 * @param string $rs 结果集标志
	 */
	function FreeOneResult($rs='pp'){
		@mysql_free_result($this->dbResultSet[$rs]);
		$this->UpdateResultSet();
	}
	/**
	 * 剔除无效结果集
	 *
	 */
	function UpdateResultSet(){
		foreach ($this->dbResultSet as $k => $v){
			if(!is_resource($v)) unset($this->dbResultSet[$k]);
		}
	}
	/**
	 * 释放所有结果集
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
	 * try 一个符号是否是有效的Resource资源号.
	 *
	 * @param string $source 结果集标志
	 * @return bool
	 */
	function IsActiveSource($source='pp'){
		if(is_resource($this->dbResultSet[$source])) return true;
		else return false;
	}
	/**
	 * 用一个数组,限制另一个数组的键范围,该数组的键范围必须在另一个数组的值范围内
	 *
	 * @param array $safe 需要限制的数组,这是一个传值,将改变该参数的值
	 * @param array $safearray 限制数组的范围
	 * @access public
	 */
	function SafeArrayPre(&$safe,$safearray,$w=1) {
		if( !$safe || !is_array($safe) || !is_array($safearray) || !$safearray) return ;
		foreach ($safe as $k => $v) {
			if ($w == 1) {	//一维安全
				if(!is_array($v)){
				//safe 一维数组
					if(!in_array($k,$safearray)) {
						unset($safe[$k]);
					}
				}else {
					unset($safe[$k]);
				}
			}else {	//二维安全
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
	 * 错误通知接口
	 *
	 * @param string $msg 错误信息
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
	 * 输出错误信息
	 *
	 */
	function ShowExecError(){
		$this->ShowError(mysql_error() ." Errno(" . mysql_errno() . ")<br /> MySQL Execut SQL:<font color=red>{$this->dbQuery}</font>");
	}
}//end of class
?>