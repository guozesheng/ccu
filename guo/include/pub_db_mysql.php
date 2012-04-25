<?php
//���������ǰ,�����趨��Щ�ⲿ����
//$cfg_dbhost="";
//$cfg_dbname="";
//$cfg_dbuser="";
//$cfg_dbpwd="";
//ǰ׺����
//$cfg_dbprefix="";
$dsql = new DedeSql(false);
class DedeSql
{
	var $linkID;
	var $dbHost;
	var $dbUser;
	var $dbPwd;
	var $dbName;
	var $dbPrefix;
	var $result;
	var $queryString;
	var $parameters;
	var $isClose;
	//
	//���ⲿ����ı�����ʼ�࣬���������ݿ�
	//
	function __construct($pconnect=false,$nconnect=true)
 	{
 		$this->isClose = false;
 		if($nconnect) $this->Init($pconnect);
  }

	function DedeSql($pconnect=false,$nconnect=true)
	{
		$this->__construct($pconnect,$nconnect);
	}

	function Init($pconnect=false)
	{
		$this->linkID = 0;
		$this->queryString = "";
		$this->parameters = Array();
		$this->dbHost = $GLOBALS["cfg_dbhost"];
		$this->dbUser = $GLOBALS["cfg_dbuser"];
		$this->dbPwd = $GLOBALS["cfg_dbpwd"];
		$this->dbName = $GLOBALS["cfg_dbname"];
		$this->dbPrefix = $GLOBALS["cfg_dbprefix"];
		$this->result["me"] = 0;
		$this->Open($pconnect);
	}
	//
	//��ָ��������ʼ���ݿ���Ϣ
	//
	function SetSource($host,$username,$pwd,$dbname,$dbprefix="dede_")
	{
		$this->dbHost = $host;
		$this->dbUser = $username;
		$this->dbPwd = $pwd;
		$this->dbName = $dbname;
		$this->dbPrefix = $dbprefix;
		$this->result["me"] = 0;
	}
	function SelectDB($dbname)
	{
		mysql_select_db($dbname);
	}
	//
	//����SQL��Ĳ���
	//
	function SetParameter($key,$value){
		$this->parameters[$key]=$value;
	}
	//
	//�������ݿ�
	//
	function Open($pconnect=false)
	{
		global $dsql;
		//�������ݿ�
		if($dsql && !$dsql->isClose) $this->linkID = $dsql->linkID;
		else
		{
		  if(!$pconnect){ $this->linkID  = @mysql_connect($this->dbHost,$this->dbUser,$this->dbPwd); }
		  else{ $this->linkID = @mysql_pconnect($this->dbHost,$this->dbUser,$this->dbPwd); }
		  //����һ�����󸱱�
		  CopySQLPoint($this);
    }
		//������󣬳ɹ�������ѡ�����ݿ�
		if(!$this->linkID){
			//echo $this->GetError();
			$this->DisplayError("���󾯸棺<font color='red'>�������ݿ�ʧ�ܣ��������ݿ����벻�Ի����ݿ������������δ��װ��ϵͳ���������а�װ��������Ѿ���װ������MySQL������޸�include/config_base.php�����ã�</font>");
			exit();
		}
		@mysql_select_db($this->dbName);
		$mysqlver = explode('.',$this->GetVersion());
		$mysqlver = $mysqlver[0].'.'.$mysqlver[1];
		if($mysqlver>4.0) @mysql_query("SET NAMES '".$GLOBALS['cfg_db_language']."';",$this->linkID);
		if($mysqlver>5.0) @mysql_query("SET sql_mode='' ;", $this->linkID);
		return true;
	}
	//
	//��ô�������
	//
	function GetError()
	{
		$str = ereg_replace("'|\"","`",mysql_error());
		return $str;
	}


	//
	//�ر����ݿ�
	//
	function Close()
	{
		@mysql_close($this->linkID);
		$this->isClose = true;
		if(is_object($GLOBALS['dsql'])){ $GLOBALS['dsql']->isClose = true; }
		$this->FreeResultAll();
	}


	//-----------------
	//��������������
	//-----------------
	function ClearErrLink()
	{
		global $cfg_dbkill_time;
		if(empty($cfg_dbkill_time)) $cfg_dbkill_time = 30;
		@$result=mysql_query("SHOW PROCESSLIST",$this->linkID);
    if($result)
    {
       while($proc=mysql_fetch_assoc($result))
       {
          if($proc['Command']=='Sleep'
             && $proc['Time']>$cfg_dbkill_time) @mysql_query("KILL ".$proc["Id"],$this->linkID);
       }
    }
	}

	//
	//�ر�ָ�������ݿ�����
	//
	function CloseLink($dblink)
	{
		@mysql_close($dblink);
	}
	//
	//ִ��һ�������ؽ����SQL��䣬��update,delete,insert��
	//
	function ExecuteNoneQuery($sql="")
	{
		global $dsql;
		if($dsql->isClose){
			$this->Open(false);
			$dsql->isClose = false;
		}
		if($sql!="") $this->SetQuery($sql);
		if(is_array($this->parameters)){
			foreach($this->parameters as $key=>$value){
				$this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
			}
		}
		return mysql_query($this->queryString,$this->linkID);
	}
	//
	//ִ��һ������Ӱ���¼������SQL��䣬��update,delete,insert��
	//
	function ExecuteNoneQuery2($sql="")
	{
		global $dsql;
		if($dsql->isClose){
			$this->Open(false);
			$dsql->isClose = false;
		}
		if($sql!="") $this->SetQuery($sql);
		if(is_array($this->parameters)){
			foreach($this->parameters as $key=>$value){
				$this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
			}
		}
		mysql_query($this->queryString,$this->linkID);
		return mysql_affected_rows($this->linkID);
	}
	function ExecNoneQuery($sql="")
	{
		return $this->ExecuteNoneQuery($sql);
	}
	//
	//ִ��һ�������ؽ����SQL��䣬��SELECT��SHOW��
	//
	function Execute($id="me",$sql="")
	{
		global $dsql;
		if($dsql->isClose){
			$this->Open(false);
			$dsql->isClose = false;
		}
		if($sql!="") $this->SetQuery($sql);
		$this->result[$id] = @mysql_query($this->queryString,$this->linkID);
		if(!$this->result[$id]){
			$this->DisplayError(mysql_error()." - Execute Query False! <font color='red'>".$this->queryString."</font>");
		}
	}
	function Query($id="me",$sql="")
	{
		$this->Execute($id,$sql);
	}
	//
	//ִ��һ��SQL���,����ǰһ����¼�������һ����¼
	//
	function GetOne($sql="",$acctype=MYSQL_BOTH)
	{
		global $dsql;
		if($dsql->isClose){
			$this->Open(false);
			$dsql->isClose = false;
		}
		if($sql!=""){
		  if(!eregi("limit",$sql)) $this->SetQuery(eregi_replace("[,;]$","",trim($sql))." limit 0,1;");
		  else $this->SetQuery($sql);
		}
		$this->Execute("one");
		$arr = $this->GetArray("one",$acctype);
		if(!is_array($arr)) return("");
		else { @mysql_free_result($this->result["one"]); return($arr);}

	}
	//
	//ִ��һ�������κα����йص�SQL���,Create��
	//
	function ExecuteSafeQuery($sql,$id="me")
	{
		global $dsql;
		if($dsql->isClose){
			$this->Open(false);
			$dsql->isClose = false;
		}
		$this->result[$id] = @mysql_query($sql,$this->linkID);
	}
	//
	//���ص�ǰ��һ����¼�����α�������һ��¼
	// MYSQL_ASSOC��MYSQL_NUM��MYSQL_BOTH
	//
	function GetArray($id="me",$acctype=MYSQL_BOTH)
	{
		if($this->result[$id]==0) return false;
		else return mysql_fetch_array($this->result[$id],$acctype);
	}
	function GetObject($id="me")
	{
		if($this->result[$id]==0) return false;
		else return mysql_fetch_object($this->result[$id]);
	}
	//
	//����Ƿ����ĳ���ݱ�
	//
	function IsTable($tbname)
	{
		$this->result[0] = mysql_list_tables($this->dbName,$this->linkID);
		while ($row = mysql_fetch_array($this->result[0]))
		{
			if(strtolower($row[0])==strtolower($tbname))
			{
				mysql_freeresult($this->result[0]);
				return true;
			}
		}
		mysql_freeresult($this->result[0]);
		return false;
	}
	//
	//���MySql�İ汾��
	//
	function GetVersion()
	{
		global $dsql;
		if($dsql->isClose){
			$this->Open(false);
			$dsql->isClose = false;
		}
		$rs = mysql_query("SELECT VERSION();",$this->linkID);
		$row = mysql_fetch_array($rs);
		$mysql_version = $row[0];
		mysql_free_result($rs);
		return $mysql_version;
	}
	//
	//��ȡ�ض������Ϣ
	//
	function GetTableFields($tbname,$id="me")
	{
		$this->result[$id] = mysql_list_fields($this->dbName,$tbname,$this->linkID);
	}
	//
	//��ȡ�ֶ���ϸ��Ϣ
	//
	function GetFieldObject($id="me")
	{
		return mysql_fetch_field($this->result[$id]);
	}
	//
	//��ò�ѯ���ܼ�¼��
	//
	function GetTotalRow($id="me")
	{
		if($this->result[$id]==0) return -1;
		else return mysql_num_rows($this->result[$id]);
	}
	//
	//��ȡ��һ��INSERT����������ID
	//
	function GetLastID()
	{
		//��� AUTO_INCREMENT ���е������� BIGINT���� mysql_insert_id() ���ص�ֵ������ȷ��
		//������ SQL ��ѯ���� MySQL �ڲ��� SQL ���� LAST_INSERT_ID() �������
		//$rs = mysql_query("Select LAST_INSERT_ID() as lid",$this->linkID);
		//$row = mysql_fetch_array($rs);
		//return $row["lid"];
		return mysql_insert_id($this->linkID);
	}
	//
	//�ͷż�¼��ռ�õ���Դ
	//
	function FreeResult($id="me")
	{
		@mysql_free_result($this->result[$id]);
	}
	function FreeResultAll()
	{
		if(!is_array($this->result)) return "";
		foreach($this->result as $kk => $vv){
			if($vv) @mysql_free_result($vv);
		}
	}
	//
	//����SQL��䣬���Զ���SQL������#@__�滻Ϊ$this->dbPrefix(�������ļ���Ϊ$cfg_dbprefix)
	//
	function SetQuery($sql)
	{
		$prefix="#@__";
		$sql = str_replace($prefix,$this->dbPrefix,$sql);
		$this->queryString = $sql;
	}
	function SetSql($sql)
	{
		$this->SetQuery($sql);
	}
	//
	//��ʾ�������Ӵ�����Ϣ
	//
	function DisplayError($msg)
	{
		echo "<html>\r\n";
		echo "<head>\r\n";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>\r\n";
		echo "<title>Viooma������ Error Track</title>\r\n";
		echo "</head>\r\n";
		echo "<body>\r\n<p style='line-helght:150%;font-size:10pt'>\r\n";
		echo $msg;
		echo "<br/><br/>";
		echo "</p>\r\n</body>\r\n";
		echo "</html>";
		//$this->Close();
		//exit();
	}
}

//����һ�����󸱱�
function CopySQLPoint(&$ndsql)
{
	$GLOBALS['dsql'] = $ndsql;
}


?>