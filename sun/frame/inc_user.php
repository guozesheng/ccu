<?php
/**
* ͨ�ÿ���չ�û��� ������ͨ�û��� ����Ա�û���
*
* ���û���Ϊ���еķ�װ,�������û��ĵ�¼,�˳�,Ȩ�޿��Ƶȹ���
* 
* @author ����@@ <webmaster@ppframe.com>
* @copyright http://www.ppframe.com
* @version $id
* @todo ��ƶ�Passport ����(������ֱ�Ӳ�������ϵͳ����)
* @todo ��Ʋ�ͬ�ĻỰ���ֺͻỰ�Ͽ�����(ʹ�����ò���)
*/
Iimport('azEncoder');
Iimport('dbsql');
class User{
	/**
	 * �û���Ϣ����,��Ӧ�û�����ֶ�
	 *
	 * @var array $U
	 */
	var $U;
	/**
	 * Base��Ϣ
	 *
	 * @var array $BU
	 */
	var $BU;
	/**
	 * �û�����ID
	 *
	 * @var number $U_ID
	 */
	var $U_ID;
	/**
	 * �û�Ψһ���
	 *
	 * @var string
	 */
	var $U_Uname;
	
	/**
	 * �û�������֤��,����Cookie ��Session
	 *
	 * @var string $PassCheck
	 */
	var $PassCheck;
	/**
	 * �û���Ψһ��ֵ�ֶ�
	 *
	 * @var string $U_UniqueID
	 */
	var $U_UniqueID;
	/**
	 * �û������ֶ�
	 *
	 * @var string $U_PasswordKey
	 */
	var $U_PasswordKey;
	/**
	 * �û������ֶ�
	 *
	 * @var number $PriKey
	 */
	var $PriKey;
	/**
	 * �û�Ȩ���ֶ�
	 *
	 * @var text $PrivKey
	 */
	var $PrivKey;
	/**
	 * ��ȫ�����ֶ�
	 *
	 * @var string $SafeKey
	 */
	var $SafeKey;
	/**
	 * �û����
	 *
	 * @var $GpKey
	 */
	var $GpKey='group';
	/**
	 * �û��������
	 *
	 * @var $GpsKey
	 */
	var $GpsKey='groups';
	/**
	 * ���������û����µĻ�������
	 *
	 * @var array $U_Update
	 */
	var $U_Update=array();
	/**
	 * ������������û��Ļ�������
	 *
	 * @var array $U_Insert
	 */
	var $U_Insert=array();
	/**
	 * �����洢�û���Ϣ�����ݿ��
	 *
	 * @var string $U_Table
	 */
	var $U_Table;
	/**
	 * �������ݿ����
	 *
	 * @var object $Usql
	 */
	var $Usql;
	/**
	 * �Ƿ�ʹ��Cookie ��֤�û��ı�־
	 *
	 * @var bool $UseCookie
	 */
	var $UseCookie = true;
	/**
	 * �û�������֤���洢 Cookie(Session) ��־
	 *
	 * @var string 
	 * @see $PassCheck $Uc_PP
	 */
	var $Uc_PP;
	/**
	 * �Ự����ʱ��(����)
	 *
	 * @var number $Uc_time
	 */
	var $Uc_time=20;
	/**
	 * ��ȫHash ��,����ִ�
	 *
	 * @var string $Uc_hash
	 */
	var $Uc_hash='lasdjf032maldksf0e3';
	/**
	 * cookie ǰ׺
	 *
	 * @var string $Uc_pre
	 */
	var $Uc_pre ='pp_';
	/**
	 * ������ܷ�ʽ��־
	 * ��ʱ֧��(md5,md5-m-n)
	 * 
	 * @var enum $PsMethod
	 */
	var $PsMethod='md5';
	/**
	 * �Ự����������ܷ�ʽ
	 * ��ʱ֧��(default,pw)
	 * 
	 * @var enum $PsMethod
	 */
	var $PsCkMethod='ppframe';
	/**
	 * �Ƿ���Ҫ��������,������Ѿ����ȱ������,�Ƚ���ֵ��Ϊfalse;
	 *
	 * @var bool $PsNeedEncode
	 */
	var $PsNeedEncode = true;
	/**
	 * ���빤��
	 *
	 * @var object
	 */
	var $AuthEncoder;
	/**
	 * �Ự���ֺ�������
	 *
	 * @var string
	 */
	var $KeepFun = "";
	/**
	 * �Ự�Ͽ���������
	 *
	 * @var string
	 */
	var $DeKeepFun = "";
	/**
	 * �жϻỰ�Ƿ񱣳ֺ�������
	 *
	 * @var string
	 */
	var $IsKeepFun = "";
	/**
	 * �û��Ựdomain
	 *
	 * @var string
	 */
	var $Domain = "";
	/**
	 * �Ƿ����֤�û���¼��Ϣ,��Ȼ�Ǽ���֤�����ǰ�ȫ��.
	 *
	 * @var bool
	 */
	var $EasyCheck = false;
	/**
	 * �Ƿ�����֤������
	 *
	 * @var bool
	 */
	var $MainServ = true;
	/**
	 * �Ƿ�����������������һ̨��������
	 *
	 * @var bool
	 */
	var $SameServer = true;
	/**
	 * ��֤�������û���
	 *
	 * @var string
	 */
	var $UpperClass = '';
	/**
	 * �ڷ���֤�������Ƿ����Լ����û���.
	 *
	 * @var bool
	 */
	var $ClientTableUsed = false;
	/**
	 * �ڷ���֤�������Ƿ���������������Ϣ
	 *
	 * @var bool
	 */
	var $MainUse = true;
	
	/**
	 * PHP5���캯��
	 * ��$GLOBALS["_config_$TYPE"] ���һ����������
	 * 
	 * @example $_user_config_user = array(
					'uniqueid' => "studentid",			//Ψһ������
					'passkey' => "password",			//�����
					'uchash' => "of3jdfsfjdlfj",		//��ȫhash��
					'prikey' => "id",					//����
					'table' => "##__frame_user",		//�洢��
					'priv' => "privkey",				//Ȩ�޼�
					'ucid' => "_U_ID_",					//cookie��־�����û�ID(��session)
					'ucpp' => "_U_P_",					//cookie��־����������֤��(��session)
					'psmethod'=>"md5",					//������ܷ�ʽ
					'usecookie'=>true					//�Ƿ�ʹ��cookie��֤
				);
	 * 
	 * 
	 * @param string $type
	 */
	function __construct($config=array()){
		$config && is_array($config) && $this->SetAllConfig($config);
		switch (strtolower($this->PsCkMethod)) {
			case 'pw':
			case 'pw5':
			case 'pw6':
			$this->Uc_pre = substr(md5($GLOBALS['rtc']['passport_ucpre_pw']),0,5) . '_';
			break;
			
			case 'ppframe':
			default:
			break;
		}
		(empty($this->Domain) || !eregi(str_replace('.','\.',$this->Domain),'.'.$_SERVER['HTTP_HOST']) || !ereg('\.',$this->Domain) || $this->Domain == 'localhost') && $this->Domain = '';
		!$this->MainServ && $this->UseCookie = true;
		$this->AuthEncoder = new azEncoder();
		$this->AuthEncoder->SetKey($this->Uc_hash);
		$this->GetIDFromCookie();
		$this->Usql = new dbsql();
		if($this->IsLogined()){
			if ($this->MainServ) {
				$this->BU = &$this->U;
				if (isset($this->U['u_lastlogin']) && isset($this->U['u_lastlogip'])) {
					if ($GLOBALS['timestamp'] - $this -> U['u_lastlogin'] > $this -> Uc_time * 60 || $this->GetIP() != $this->U['u_lastlogip']) {
						$this -> UpdateLastLogin();
					}
				}
			}else if(!$this->BU && $this -> MainUse){
				if ($this->SameServer && $this->UpperClass) {
					Iimport($this->UpperClass);
					$upobj = new $this->UpperClass;
					$upobj -> LoadFromDBUsePriID($this->U_ID);
					$this->BU = $upobj -> U;
				}else {	//����������е�
					$this->BU = GetUserInfo($this->U_ID);
				}
			}
			if (!$this->MainServ && !$this -> ClientTableUsed) {
				$this -> U = & $this->BU;
			}
			if(!$this->U_Uname && $this->BU[$this->U_UniqueID]) $this -> U_Uname = $this->BU[$this->U_UniqueID];
			$this->PutLoginedInfo();
			$this->SetID($this->U[$this->PriKey]);
		}else {
			$this->ReSet();
		}
	}
	/**
	 * PHP4���캯��
	 *
	 * @see __construct()
	 */
	function User($config=array()){
		return $this->__construct($config);
	}
	//ʹ�� ����$array �����û���Ϣ $Key => $Value ����������Ϣ
	/**
	 * �����û���Ϣ,ʹ��������ʾ key=>value �Ե� $array  $Key => $Value ����������Ϣ
	 * $rewrite true ʱ���ԭ����Ϣ
	 * $overwrite true �Ǹ���ԭ����Ϣ
	 *
	 * @param array $array
	 * @param bool $rewrite
	 * @param bool $overwrite
	 */
	function SetUElement($array,$rewrite=true,$overwrite=true){
		if(!is_array($array) || !$array) return ;
		if($rewrite){
			$this->U = array(
				$this->PriKey => $this->U[$this->PriKey]
			);
		}
		if($overwrite){
			//����ԭ����Ϣ
			foreach ($array as $k => $v){
				$this->U[$k] = $v;
			}
		}else {
			//������ԭ����Ϣ
			foreach ($array as $k => $v){
				if(empty($this->U[$k])){
					$this->U[$k] = $v;
				}
			}
		}
		//����ID;
		$this->U[$this->PriKey]>0 && $this->SetID($this->U[$this->PriKey]);
		$this->U_UniqueID && $this->U[$this->U_UniqueID] && $this->U_Uname = $this->U[$this->U_UniqueID];
		//��ɢȨ������
		$this->ExportPrivs();
	}
	//�����û�,���Բ���һ��,���߶���û�
	/**
	 * �����û�,���Բ���һ��,���߶���û�
	 * ʹ�� $U_Insert ����
	 *
	 * @param array $safearray �������˷Ƿ��ֶε�����
	 * @param number $w 1��һά����,������һ��.2�ǲ���������ά����
	 * @return bool
	 */
	function DoRecordUser($safearray=array(),$w=1,$type='insert',$ignore=0){
		//safe u_insert first
		if(is_array($safearray) && !empty($safearray)) {
			dbsql::SafeArrayPre($this->U_Insert,$safearray,$w);
		}
		
		return $this->Usql->DoInsert($this->U_Insert,$this->U_Table,$type,$ignore);
	}
	/**
	 * ����һ���û�
	 * ʹ��safearray �����ð�ȫ����.�ⲿ����,�ǳ���Ҫ.�������������ʻ��ʽ���Ҫ�ֶ�
	 *
	 * @param array $safearray �������˷Ƿ��ֶε�����
	 * @return bool
	 */
	//�����ȵ��� SetUpdateInfo ����Update ����
	function DoReRecord($safearray=array(),$unset=array()){
		//safe u_update first
		if(is_array($safearray) && !empty($safearray)) {
			dbsql::SafeArrayPre($this->U_Update,$safearray,1);
		}
		if(empty($this->U_Update)) return true;
		if($this->U_ID<=0) return false;
		$where = "`$this->PriKey`='$this->U_ID'";
		return $this->Usql->DoUpdate($this->U_Update,$this->U_Table,$where,$unset);
	}
	/**
	 * �������ڸ����û��Ļ�������
	 * �� DoReRecord ǰ����
	 *
	 * @param array $array ���ڸ����û�����������
	 * @param bool $rewrite �Ƿ���д��־,Ĭ��true,Ҳû�б�Ҫʹ��false
	 */
	function SetUpdateInfo($array,$rewrite=true){
		if($rewrite) $this->U_Update = array();
		foreach ($array as $k => $v){
			//��������
			if($k == $this->PriKey) continue;
			//����δ����ֵ
			if($v == $this->U[$k]) continue;
			//��¼һ����Ч����
			$this->U_Update[$k] = addslashes(stripslashes($v));
		}
		//�������ֶμ���
		if($this->PsNeedEncode && isset($this->U_Update[$this->U_PasswordKey])) {
			$this->U_Update[$this->U_PasswordKey] = $this->PassWordEnCode($this->U_Update[$this->U_PasswordKey]);
		}
	}
	/**
	 * �������ڲ����û�����������
	 *
	 * @param array $array ���ڲ����û�����������
	 * @param bool $rewrite �Ƿ���д��־,Ĭ��true,Ҳû�б�Ҫʹ��false
	 */
	function SetInsertInfo($array,$rewrite=true){
		if($rewrite) $this->U_Insert = array();
		$i = $type = 0;
		foreach ($array as $k => $v){
			if(!is_array($v) && ($i==0||$type==1)){
				$type = 1;
				//if($this->MainServ && $k == $this->PriKey) continue;
				//�������ֶμ���
				if($this->PsNeedEncode && $k == $this->U_PasswordKey) $v = $this->PassWordEnCode($v);
				//��¼һ����Ч����
				$this->U_Insert[$k] = addslashes(stripslashes(str_replace('##__','@@__',$v)));
			}else if(is_array($v) && ($i==0||$type==2)){
				$type = 2;
				foreach ($v as $kk => $vv) {
					//if($this->MainServ && $kk == $this->PriKey) continue;
					if($this->PsNeedEncode && $kk == $this->U_PasswordKey) $vv = $this->PassWordEnCode($vv);
					$this->U_Insert[$k][$kk] = addslashes(stripslashes(str_replace('##__','@@__',$vv)));
				}
			}else {
				continue;
			}
			$i = 1;
		}
	}
	/**
	 * �����û��洢��
	 *
	 * @param string $tb ������
	 */
	function SetUserTable($tb){
		$this->U_Table = $tb;
	}
	/**
	 * �����û���Ψһ��
	 *
	 * @param string $uniKey Ψһ������
	 */
	function SetUniKey($uniKey){
		$this->U_UniqueID = $uniKey;
	}
	/**
	 * ���������ֶ�
	 *
	 * @param string $passKey �����ֶε�����
	 */
	function SetPassKey($passKey){
		$this->U_PasswordKey = $passKey;
	}
	/**
	 * һ��������������,ʹ������.
	 *
	 * @param array $config
	 */
	function SetAllConfig($config=array()) {
		if(empty($config) || !is_array($config)) return ;
		isset($config['uniqueid']) && $config['uniqueid'] && $this->SetUniKey($config['uniqueid']);
		isset($config['passkey']) && $config['passkey'] && $this->SetPassKey($config['passkey']);
		isset($config['uchash']) && $config['uchash'] && $this->Uc_hash = $config['uchash'];
		isset($config['prikey']) && $config['prikey'] && $this->PriKey = $config['prikey'];
		isset($config['priv']) && $config['priv'] && $this->PrivKey = $config['priv'];
		isset($config['safekey']) && $config['safekey'] && $this->SafeKey = $config['safekey'];
		isset($config['table']) && $config['table'] && $this->U_Table = $config['table'];
		isset($config['ucpp']) && $config['ucpp'] && $this->Uc_PP = $config['ucpp'];
		isset($config['usecookie']) && is_bool($config['usecookie']) && $this->UseCookie = $config['usecookie'];
		isset($config['psmethod']) && $config['psmethod'] && $this->PsMethod = $config['psmethod'];
		isset($config['mainserv']) && is_bool($config['mainserv']) && $this->MainServ = $config['mainserv'];
		isset($config['mainuse']) && $this -> MainUse = $config['mainuse'];
		isset($config['easycheck']) && is_bool($config['easycheck']) && $this->EasyCheck = $config['easycheck'];
		isset($config['uctime']) && $config['uctime'] && $this->Uc_time = $config['uctime'];
		isset($config['domain']) && $config['domain'] && $this->Domain = $config['domain'];
		isset($config['ctused']) && $config['ctused'] && $this->ClientTableUsed = $config['ctused'];
		isset($config['psckmethod']) && $config['psckmethod'] && $this->PsCkMethod = $config['psckmethod'];
		isset($config['ucpre']) && $config['ucpre'] && $this->Uc_pre = $config['ucpre'];
		isset($config['gpkey']) && $config['gpkey'] && $this->GpKey = $config['gpkey'];
		isset($config['gpskey']) && $config['gpskey'] && $this->GpsKey = $config['gpskey'];
	}
	//����һ���û���Ȩ��
	/**
	 * ����һ���û���Ȩ��
	 *
	 * @param array $priv Ȩ������
	 * @param bool $rewrite �Ƿ���д
	 * @param bool $overwrite �Ƿ񸲸�
	 */
	function SetPrivs($priv=array(),$rewrite=true,$overwrite=true){
		if(!is_array($priv)) return ;
		if($rewrite){
			$this->U[$this->PrivKey] = array();
		}
		if($overwrite){
			foreach ($priv as $k => $v){
				$this->U[$this->PrivKey][$k] = $v;
			}
		}else {
			foreach ($priv as $k => $v){
				if(empty($this->U[$this->PrivKey][$k])){
					$this->U[$this->PrivKey][$k] = $v;
				}
			}
		}
	}
	/**
	 * ���ĳ���û���һ��,�����Ȩ��
	 * �����������ּ���
	 * �������д����������Ȩ�޵�����·��أ���������һ��Ȩ�޼��ɡ�
	 *
	 * @param array $priv ���������
	 * @param char $type + or -
	 * @return bool
	 * @access private
	 */
	function CheckPrivs($priv=array(),$type="+"){
		if(!in_array($type,array("+","-"))) $type = "+";
		if(!is_array($priv)) $priv = explode(',',$priv);
		if($type=="+"){
			$rt = true;
			foreach ($priv as $k => $v){
				if(!in_array($v,$this->U[$this->PrivKey])){
					$rt = false;
					break;
				}
			}
			return $rt;
		}else {
			$rt = false;
			foreach ($priv as $k => $v){
				if(in_array($v,$this->U[$this->PrivKey])){
					$rt = true;
					break;
				}
			}
			return $rt;
		}
	}
	/**
	 * �ͷ��û�Ȩ��Ϊ������ʽ
	 * rewrite
	 */
	function ExportPrivs(){
		$this->U[$this->PrivKey] = explode(' ',trim($this->U[$this->PrivKey]));
	}
	/**
	 * ����û��Ƿ���ĳһ�û�����
	 *
	 * @param number $g
	 * @param bool $b
	 * @return bool
	 */
	function CheckGroupExist($g,$b=true) {
		if ($b) {	//���û�����
			if ($this->BU[$this->GpKey] == $g) {
				return true;
			}
			if ($this->BU[$this->GpsKey]) {
//				#����DZ,PWд��
//				$array = explode(' ',explode(' ',trim(str_replace(array(',',"\t"),' ',$this->BU[$this->GpsKey]))));
//				if (in_array($g,$array)) {
//					return true;
//				}
				if (ereg(" $g ",$this->BU[$this->GpsKey])) {
					return true;
				}
			}
			return false;
		}else {	//���û�����
			return false;
		}
		return false;
	}
	/**
	 * �ж��û��Ƿ���һЩ�û�����
	 *
	 * @param string $gs
	 * @param bool $b
	 * @return bool
	 */
	function CheckAllGroupExist($gs,$b=true) {
		$gs = explode(' ',trim($gs));
		if (is_array($gs)) {
			foreach ($gs as $k => $v) {
				if ($this->CheckGroupExist($v,$b)) {
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * ���½��û�Ȩ��д�����ݿ�
	 * rewrite
	 * @return bool
	 */
	function ReRecordPriv(){
		$this->SetUpdateInfo(array($this->PrivKey => ' ' . implode(' ',trim($this->U[$this->PrivKey])) . ' ' ));
		return $this->DoReRecord();
	}
	/**
	 * �ж��Ƿ��¼,�ѵ�¼����true,���򷵻�false
	 *
	 * @return bool
	 */
	function IsLogined(){
		$return = false;
		if(defined('ISLOGIN')) { 
			return 	ISLOGIN;
		}
		if(!$this->EasyCheck) {
			if ($this->MainServ) {	//���������ϸ���
				if($this->U_ID <=0) return false;
				$this -> LoadFromDBUsePriID($this->U_ID);
				if($this->PassCheck == $this->PassCheckEnCode($this->U)){
					$return = true;
				}else {
					$return = false;
				}
			}else {	//�����������ϸ���
				if ($this->U_ID > 0 && $this->PassCheck && md5($this -> PassCheck.$this->Uc_hash) == $this->GetCookie($this->Uc_PP.'_CK')){	//��֤����֤
						if ($this->ClientTableUsed) {
							$this  -> LoadFromDBUsePriID($this->U_ID);
						}
						$return = true;
				}else {		//���ݿ���֤
					if ($this->SameServer && $this-> UpperClass) {	//����֤��������ͬһ��������,ʹ����֤�������ϸ���
						Iimport($this->UpperClass);
						$upobj = new $this->UpperClass;
						if($upobj -> IsLogined()) {
							$this -> U_Uname = $upobj -> U_Uname;
							$this -> BU = $upobj -> U;
							$this->PutCookie($this->Uc_PP.'_CK',md5($upobj->PassCheck.$upobj->Uc_hash),$this->Uc_time);
							unset($upobj);
							if ($this->ClientTableUsed) {
								$this  -> LoadFromDBUsePriID($this->U_ID);
							}
							$return = true;
						}
					}else {	//��������������ͬһ����������,it's safe now!
						if ($this->U_ID>0) {
							if (CheckUserPass($this->U_ID,$this->PassCheck)) {
								$this->PutCookie($this->Uc_PP.'_CK',md5($this->PassCheck.$this->Uc_hash),$this->Uc_time);
								if ($this->ClientTableUsed) {
									$this  -> LoadFromDBUsePriID($this->U_ID);
								}
								$return = true;
							}
						}
					}
				}
			}
		}else {	// �򵥼��	to rewrite! no safe
			if($this->U_ID > 0 ) {
				$return = true;
			}else {
				$return = false;
			}
		}
		define('ISLOGIN',$return);
		!$return && $this -> ExitLogin();
		return $return;
	}
	/**
	 * �����û�����,ʹ�� uniquekey �� password ��֤,password (����)
	 *
	 * @param string $uniqID �û���Ψһ�ֶε�ֵ
	 * @param string $password �û�������
	 * @return bool
	 * @access private
	 */
	function CheckUserUniqID($uniqID,$password){
		$this->Usql->SetQueryString("Select `{$this->U_UniqueID}`,`{$this->U_PasswordKey}` From {$this->U_Table} where {$this->U_UniqueID} like '{$uniqID}'");
		$row = $this->Usql->GetOneArray();
		if(empty($row)){
			return false;
		}
		else {
			if($this->PassWordEnCode($password) != $row[$this->U_PasswordKey]){
				return false;
			}else {
				$this->LoadFromDBuseUniqID($uniqID);
				return true;
			}
		}
	}
	/**
	 * �����û�����,ʹ�� ���� �� ���� ��֤,password (����)
	 *
	 * @param number $ID �û�id
	 * @param string $password �û�����
	 * @return bool
	 */
	function CheckUser($ID,$password){
		if(empty($ID) || !is_numeric($ID)) return false;
		if(empty($password)) return false;
		$this->Usql->SetQueryString("Select `{$this->PriKey}`,`{$this->U_PasswordKey}` From {$this->U_Table} where {$this->PriKey} like '{$ID}'");
		$row = $this->Usql->GetOneArray();
		if(empty($row)) return false;
		else {
			if($this->PassWordEnCode($password) != $row[$this->U_PasswordKey]){
				return false;
			}else {
				$this->LoadFromDBUsePriID($ID);
				return true;
			}
		}
	}
	/**
	 * �����û��ѵ�¼״̬
	 *
	 */
	function PutLoginedInfo(){
		//��վ��������cookie
		if($this->UseCookie || $this->MainServ){
			$this->PutCookie($this->Uc_PP,$this->PassCheck,$this->Uc_time*60);
		}else if(!$this->UseCookie){
			$_SESSION[$this->Uc_PP] = $this->PassCheck;
		}
	}
	/**
	 * ȡ���û���¼״̬
	 *
	 */
	function ExitLogin(){
		if($this->UseCookie){
			if ($GLOBALS['rtc']['uc_use']) {	# �޸�UC�޷�ͬ���˳��� IE BUG
				$this->PutCookie($this->Uc_PP,'1',$this->Uc_time*60);
			}else {
				$this->DropCookie($this->Uc_PP);
				//����dz
				$this->DropCookie('sid');
			}
		}else {
			unset($_SESSION[$this->Uc_PP]);
			@session_destroy();
		}
	}
	/**
	 * ʹ�����������ݿ�����û���Ϣ
	 *
	 * @param number $id �û�������ֵ
	 */
	function LoadFromDBUsePriID($id){
		if(!$this->MainServ && !$this->ClientTableUsed) {
			return ;
		}
		$this->Usql->SetQueryString("Select * From {$this->U_Table} where `{$this->PriKey}`='{$id}'");
		$row = $this->Usql->GetOneArray();
		$this->SetUElement($row);
	}
	/**
	 * ʹ��Ψһ�������ݿ�����û���Ϣ
	 *
	 * @param string $uniq
	 */
	function LoadFromDBuseUniqID($uniq){
		if(!$this->MainServ && !$this->ClientTableUsed) {
			return ;
		}
		$this->Usql->SetQueryString("Select * From {$this->U_Table} where `{$this->U_UniqueID}` like '{$uniq}'");
		$row = $this->Usql->GetOneArray();
		$this->SetUElement($row);
	}
	/**
	 * ����û���Ϣ
	 *
	 */
	function ReSet(){
		$this->U_ID = -1;
		$this->U = array();
	}
	/**
	 * ��Cookie(Session) ����û�ID��Ϣ
	 *
	 */
	function GetIDFromCookie(){
		if($this->UseCookie){
			$this->PassCheck = $this->GetCookie($this->Uc_PP);
			$this->PassCheckDeCode();
		}else {
			session_cache_expire($this->Uc_time);
			session_save_path(FRAME_ROOT.'session');
			@session_start();
			$this->PassCheck = $_SESSION[$this->Uc_PP];
			$this->PassCheckDeCode();
		}
//		$this->ParseSecretUserInfo();
	}
	/**
	 * �����û�ID
	 *
	 * @param number $id
	 */
	function SetID($id){
		is_numeric($id) && $id>0 && $this->U_ID = $id;
		//���ÿ�λ
		if(empty($this->U_ID)||$this->U_ID<=0) $this->U_ID = -1;
		$this->U[$this->PriKey] = $this->U_ID;
	}
	/**
	 * ������ܺ���.�ɸ��ݲ��������û����ܷ�ʽ
	 *
	 * @param string $p �������ִ�
	 * @return string ���ܺ��ִ�
	 */
	function PassWordEnCode($p){
		switch (strtolower($this->PsMethod)) {
			case 'md5':
			return md5($p);
			break;
			case 'md5-16':
			return substr(md5($p),0,16);
			break;
			case 'md5-24':
			return substr(md5($p),0,24);
			case 'no':
			return $p;
			break;
			default:		//Ĭ��md5
			return md5($p);
			break;
		}
	}
	
	function PassCheckEnCode($p=array()) {
		empty($p) && $p = $this->U;
		switch (strtolower($this->PsCkMethod)) {
			case 'pw5':
			case 'pw6':
			case 'pw':
			$string = $p[$this->PriKey]."\t".md5($_SERVER["HTTP_USER_AGENT"].$p[$this->U_PasswordKey].$this->Uc_hash)."\t".$p[$this->SafeKey];
			$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$this->Uc_hash),8,18);
			$len	= strlen($key);
			$code	= '';
			for($i=0; $i<strlen($string); $i++){
				$k		= $i % $len;
				$code  .= $string[$i] ^ $key[$k];
			}
			return base64_encode($code);
			break;
			
			case 'dz':
			case 'dz6':
			return  $this -> DZ_Authcode("{$p[$this->U_PasswordKey]}\t{$p[$this->SafeKey]}\t{$p[$this->PriKey]}", 'ENCODE');
			break;
			
			case 'ppframe':
			default:
			$string = $p[$this->PriKey]."\t".md5($_SERVER["HTTP_USER_AGENT"].$p[$this->U_PasswordKey].$this->Uc_hash)."\t".urlencode($this->U_Uname);
			return $this -> AuthEncoder -> Encode($string);
		}
	}
	
	function PassCheckDeCode() {
		switch (strtolower($this->PsCkMethod)) {
			case 'pw5':
			case 'pw6':
			case 'pw':
			$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$this->Uc_hash),8,18);
			$string	= base64_decode($this->PassCheck);
			$len	= strlen($key);
			$code	= '';
			for($i=0; $i<strlen($string); $i++){
				$k		= $i % $len;
				$code  .= $string[$i] ^ $key[$k];
			}
			@list($uid,$pwd,) = explode("\t",$code);
			$this->SetID($uid);
			break;
			
			case 'dz' :
			@list($pwd,$safekey,$uid) = explode("\t",$this->DZ_Authcode($this->PassCheck,'DECODE'));
			$this->SetID($uid);
			break;
			
			case 'ppframe':
			default:
			@list($uid,,$this->U_Uname) = explode("\t",$this->AuthEncoder->Decode($this->PassCheck));
			$this->SetID($uid);
			$this->U_Uname = urldecode($this->U_Uname);
			break;
		}
	}
	/**
	 * �ؽ�PassCheck
	 * ����������Ч
	 */
	function PassCheckRebuild() {
		$this -> PassCheck = $this -> PassCheckEnCode($this->U);
		//����DZ
		$this->DropCookie('sid');
	}
	
	function DZ_Authcode($string, $operation, $key = '') {
		$key = md5($key ? $key : md5($GLOBALS['rtc']['passport_hash'].$_SERVER['HTTP_USER_AGENT']));
		$key_length = strlen($key);
	
		$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
		$string_length = strlen($string);
	
		$rndkey = $box = array();
		$result = '';
	
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($key[$i % $key_length]);
			$box[$i] = $i;
		}
	
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
	
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if($operation == 'DECODE') {
			if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
				return substr($result, 8);
			} else {
				return '';
			}
		} else {
			return str_replace('=', '', base64_encode($result));
		}
	}
	/**
	 * ���ٸ����û�����
	 *
	 * @param string $p ������
	 * @return bool
	 */
	function UpdatePass($p) {
		$this -> SetUpdateInfo(array($this->U_PasswordKey=>$p));
		return $this->DoReRecord();
	}
	/**
	 * �����ϴε�½ʱ��IP
	 *
	 */
	function UpdateLastLogin() {
		$this -> SetUpdateInfo(array('u_lastlogin' => $GLOBALS['timestamp'],'u_lastlogip'=>$this->GetIP()));
		$this -> DoReRecord(array('u_lastlogin','u_lastlogip'));
	}
	
	/**
	 * ���ø��ӵĻ����û���Ϣ
	 *
	 */
	function SetSecretUserInfo() {
		$this->PutCookie($this->Uc_PP.'_data',$this->CreateSecretUserInfo(),100 * 24 * 3600);
	}
	
	function ClearSecretUserInfo() {
		$this->DropCookie($this->Uc_PP.'_data');
	}
	/**
	 * �����û�������Ϣ
	 *
	 * @return string
	 */
	function CreateSecretUserInfo(){
		//�û���\tȨ�ޱ�\tothers
		$string = $this->U_Uname . "\t" . '';
		return $this->AuthEncoder->Encode($string);
	}
	/**
	 * �������ܵ��û���Ϣ
	 * 
	 */
	function ParseSecretUserInfo() {
		//�ӻ�����Ϣ���
		if ( ($this->UseCookie || $this->MainServ) ) {
			$string = $this->GetCookie($this->Uc_PP.'_data');
		}else {
//			$string = $_SERVER[$this->Uc_PP.'_data'];
		}
		@list($this->U_Uname,) = @explode("\t",$this -> AuthEncoder -> Decode($string));
	}
	/**
	 * ����һ��������֤��Ϣ��cookie
	 *
	 * @param string $key
	 * @param string $value
	 * @param int $kptime
	 * @param string $pa
	 */
	function PutCookie($key,$value,$kptime,$pa="/"){
		$timestamp = $GLOBALS['timestamp'];
		setcookie($this->Uc_pre.$key,$value,$timestamp+$kptime,$pa,$this->Domain,$_SERVER['SERVER_PORT'] == 443 ?1:0);
	}
	/**
	 * ��֤�Ϸ��Ժ���һ��cookie
	 *
	 * @param string $key
	 * @return string
	 */
	function GetCookie($key) {
		if (isset($_COOKIE[$this->Uc_pre.$key])) {
			return $_COOKIE[$this->Uc_pre.$key];
		}else {
			return '';
		}
	}
	/**
	 * ����һ��Cookie
	 *
	 * @param string $key
	 */
	function DropCookie($key){
		setcookie($this->Uc_pre.$key,'',$GLOBALS['timestamp']- 365 * 86400,'/',$this->Domain,$_SERVER['SERVER_PORT'] == 443 ?1:0);
//		setcookie($this->Uc_pre.$key.'__CK_MD5','',time() - 365 * 86400,'/',$this->Domain);
	}
	
	function RemoveUser($uid) {
		if ($uid) {
			return $this -> CommonRemove("$this->PriKey='$uid'");
		}else {
			return false;
		}
	}
	
	function RemoveUsersUseId($ids=array()) {
		if (is_array($ids) && $ids) {
			//todo
			foreach ($ids as $k => $id) {
				if(!$id > 0) {
					unset($ids[$k]);
				}
			}
			$ids = implode(',',$ids);
			return $this -> CommonRemove("$this->PriKey in ( $ids )");
		}
		return false;
	}
	
	function RemoveUsersUseNames($names = array()) {
		if (is_array($names) && $names) {
			//todo
			foreach ($names as $k => $n) {
				if ($n) {
					$names[$k] = "'".addslashes($n)."'";
				}else {
					unset($names[$k]);
				}
			}
			$names = implode(',',$names);
			return $this -> CommonRemove("$this->U_UniqueID in ( $names )");
		}
	}
	function CommonRemove($where) {
		return $this->Usql -> Dodelete($this->U_Table,$where);
	}
	function GetIp() {
		if(!empty($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
		else return 'Unknow';
	}
}


function CheckUserPass($uid,$passcheck) {	//ֻ�����ڿ�������ļ���
	if ($uid < 1) {
		return array();
	}
	if (defined('Admin_Safe')) {	//������Ա
		$api_root = $GLOBALS['rtc']['admin_host_api'] ? $GLOBALS['rtc']['admin_host_api'] : $GLOBALS['rtc']['admin_host'];
	}else {	//���passport user
		$api_root = $GLOBALS['rtc']['passport_root_api'] ? $GLOBALS['rtc']['passport_root_api'] : $GLOBALS['rtc']['passport_root'];
	}
	$api_root .= 'api/';
	$api_file = 'checkuserpass.php';
	$passcheck = urlencode($passcheck);
	$db = 't='.strtolower(md5($GLOBALS['timestamp'])).'&uid='.$uid.'&passcheck='.$passcheck;
	Iimport('azEncoder');
	
	$azencoder = new azEncoder();
	$azencoder ->SetKey($GLOBALS['rtc']['passport_api_hash']);
	$db = $azencoder -> Encode($db);
	
	$api_file = $api_file . '?db=' . $db . '&time=' . $GLOBALS['timestamp'] . '&sign=' . strtolower(md5($db.$GLOBALS['timestamp'].$GLOBALS['rtc']['passport_api_hash']));
	#get checkuserpass.php?db=azEncode(db)&time={timestamp}&sign={md5_sign}
	Iimport('Http');
	$http = new Http();
	$http -> OpenUrl($api_root.$api_file);
	$rtext = $http -> Send();
	
	if ($rtext) {
		#return azEncode(db=azencode(serialize(array('uid'=>{uid},'time'=>{timestamp})))&time={timestamp}&sign=md5_sign);
		$rtext = $azencoder -> Decode($rtext);
		$rarray = array();
		parse_str($rtext,$rarray);
		if ($rarray['sign'] == strtolower(md5($rarray['db'].$rarray['time'].$GLOBALS['rtc']['passport_api_hash']))) {
			$rtext = $azencoder -> Decode($rarray['db']);
			$rarray2 = unserialize($rtext);
			if ($rarray2['uid'] == $uid && $rarray2['time'] == $rarray['time']) {
				return 1;
			}
		}
	}
	return 0;
}

function GetUserInfo($uid,$un='') {
	if ($uid < 1) {
		return array();
	}
	if (defined('Admin_Safe')) {	//������Ա
		$api_root = $GLOBALS['rtc']['admin_host_api'] ? $GLOBALS['rtc']['admin_host_api'] : $GLOBALS['rtc']['admin_host'];
	}else {	//���passport user
		$api_root = $GLOBALS['rtc']['passport_root_api'] ? $GLOBALS['rtc']['passport_root_api'] : $GLOBALS['rtc']['passport_root'];
	}
	$api_root .= 'api/';
	$api_file = 'getuserinfo.php';
	
	$db = 't='.strtolower(md5($GLOBALS['timestamp'])).'&uid='.$uid.'&un='.$un;
	Iimport('azEncoder');
	
	$azencoder = new azEncoder();
	$azencoder ->SetKey($GLOBALS['rtc']['passport_api_hash']);
	$db = $azencoder ->Encode($db);
	
	$api_file = $api_file . '?db=' . $db . '&time=' . $GLOBALS['timestamp'] . '&sign=' . strtolower(md5($db.$GLOBALS['timestamp'].$GLOBALS['rtc']['passport_api_hash']));
	#get getuserinfo.php?db=azEncode(db)&time={timestamp}&sign={md5_sign}
	Iimport('Http');
	$http = new Http();
	$http -> OpenUrl($api_root.$api_file);
	$rtext = $http -> Send();
	
	if ($rtext) {
		#return azEncode(infodb=azencode(serialize(infoarray()))&time={timestamp}&sign=md5_sign);
		$rtext = $azencoder -> Decode($rtext);
		$rarray = array();
		parse_str($rtext,$rarray);
		
		if ($rarray['sign'] == strtolower(md5($rarray['infodb'].$rarray['time'].$GLOBALS['rtc']['passport_api_hash']))) {
			$rtext = $azencoder -> Decode($rarray['infodb']);
			$rarray = unserialize($rtext);
			if ($rarray['uid'] == $uid) {
				return $rarray;
			}
		}
	}
	return array();
}