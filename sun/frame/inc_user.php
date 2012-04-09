<?php
/**
* 通用可扩展用户类 包括普通用户和 管理员用户。
*
* 对用户行为进行的封装,包括了用户的登录,退出,权限控制等功能
* 
* @author 蜻蜓@@ <webmaster@ppframe.com>
* @copyright http://www.ppframe.com
* @version $id
* @todo 设计对Passport 功能(现在能直接操作其他系统数据)
* @todo 设计不同的会话保持和会话断开功能(使用配置参数)
*/
Iimport('azEncoder');
Iimport('dbsql');
class User{
	/**
	 * 用户信息数组,对应用户表的字段
	 *
	 * @var array $U
	 */
	var $U;
	/**
	 * Base信息
	 *
	 * @var array $BU
	 */
	var $BU;
	/**
	 * 用户主键ID
	 *
	 * @var number $U_ID
	 */
	var $U_ID;
	/**
	 * 用户唯一标记
	 *
	 * @var string
	 */
	var $U_Uname;
	
	/**
	 * 用户密码验证串,存于Cookie 或Session
	 *
	 * @var string $PassCheck
	 */
	var $PassCheck;
	/**
	 * 用户的唯一键值字段
	 *
	 * @var string $U_UniqueID
	 */
	var $U_UniqueID;
	/**
	 * 用户密码字段
	 *
	 * @var string $U_PasswordKey
	 */
	var $U_PasswordKey;
	/**
	 * 用户主键字段
	 *
	 * @var number $PriKey
	 */
	var $PriKey;
	/**
	 * 用户权限字段
	 *
	 * @var text $PrivKey
	 */
	var $PrivKey;
	/**
	 * 安全问题字段
	 *
	 * @var string $SafeKey
	 */
	var $SafeKey;
	/**
	 * 用户组键
	 *
	 * @var $GpKey
	 */
	var $GpKey='group';
	/**
	 * 用户附加组键
	 *
	 * @var $GpsKey
	 */
	var $GpsKey='groups';
	/**
	 * 用来操作用户更新的缓存数组
	 *
	 * @var array $U_Update
	 */
	var $U_Update=array();
	/**
	 * 用来操作添加用户的缓存数组
	 *
	 * @var array $U_Insert
	 */
	var $U_Insert=array();
	/**
	 * 用来存储用户信息的数据库表
	 *
	 * @var string $U_Table
	 */
	var $U_Table;
	/**
	 * 操作数据库的类
	 *
	 * @var object $Usql
	 */
	var $Usql;
	/**
	 * 是否使用Cookie 验证用户的标志
	 *
	 * @var bool $UseCookie
	 */
	var $UseCookie = true;
	/**
	 * 用户密码验证串存储 Cookie(Session) 标志
	 *
	 * @var string 
	 * @see $PassCheck $Uc_PP
	 */
	var $Uc_PP;
	/**
	 * 会话保持时间(分钟)
	 *
	 * @var number $Uc_time
	 */
	var $Uc_time=20;
	/**
	 * 安全Hash 码,随机字串
	 *
	 * @var string $Uc_hash
	 */
	var $Uc_hash='lasdjf032maldksf0e3';
	/**
	 * cookie 前缀
	 *
	 * @var string $Uc_pre
	 */
	var $Uc_pre ='pp_';
	/**
	 * 密码加密方式标志
	 * 暂时支持(md5,md5-m-n)
	 * 
	 * @var enum $PsMethod
	 */
	var $PsMethod='md5';
	/**
	 * 会话保持密码加密方式
	 * 暂时支持(default,pw)
	 * 
	 * @var enum $PsMethod
	 */
	var $PsCkMethod='ppframe';
	/**
	 * 是否需要编码密码,如果你已经事先编码过了,先将此值设为false;
	 *
	 * @var bool $PsNeedEncode
	 */
	var $PsNeedEncode = true;
	/**
	 * 编码工具
	 *
	 * @var object
	 */
	var $AuthEncoder;
	/**
	 * 会话保持函数名称
	 *
	 * @var string
	 */
	var $KeepFun = "";
	/**
	 * 会话断开函数名称
	 *
	 * @var string
	 */
	var $DeKeepFun = "";
	/**
	 * 判断会话是否保持函数名称
	 *
	 * @var string
	 */
	var $IsKeepFun = "";
	/**
	 * 用户会话domain
	 *
	 * @var string
	 */
	var $Domain = "";
	/**
	 * 是否简单验证用户登录信息,虽然是简单验证但是是安全的.
	 *
	 * @var bool
	 */
	var $EasyCheck = false;
	/**
	 * 是否是认证服务器
	 *
	 * @var bool
	 */
	var $MainServ = true;
	/**
	 * 是否与主服务器运行在一台服务器。
	 *
	 * @var bool
	 */
	var $SameServer = true;
	/**
	 * 认证服务器用户类
	 *
	 * @var string
	 */
	var $UpperClass = '';
	/**
	 * 在非认证服务器是否有自己的用户表.
	 *
	 * @var bool
	 */
	var $ClientTableUsed = false;
	/**
	 * 在非认证服务器是否请求主服务器信息
	 *
	 * @var bool
	 */
	var $MainUse = true;
	
	/**
	 * PHP5构造函数
	 * 从$GLOBALS["_config_$TYPE"] 获得一个配置数组
	 * 
	 * @example $_user_config_user = array(
					'uniqueid' => "studentid",			//唯一键键名
					'passkey' => "password",			//密码键
					'uchash' => "of3jdfsfjdlfj",		//安全hash码
					'prikey' => "id",					//主键
					'table' => "##__frame_user",		//存储表
					'priv' => "privkey",				//权限键
					'ucid' => "_U_ID_",					//cookie标志——用户ID(含session)
					'ucpp' => "_U_P_",					//cookie标志——密码验证串(含session)
					'psmethod'=>"md5",					//密码加密方式
					'usecookie'=>true					//是否使用cookie验证
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
				}else {	//跨服务器运行的
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
	 * PHP4构造函数
	 *
	 * @see __construct()
	 */
	function User($config=array()){
		return $this->__construct($config);
	}
	//使用 数组$array 设置用户信息 $Key => $Value 都是有用信息
	/**
	 * 设置用户信息,使用数组显示 key=>value 对的 $array  $Key => $Value 都是有用信息
	 * $rewrite true 时清空原有信息
	 * $overwrite true 是覆盖原有信息
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
			//覆盖原有信息
			foreach ($array as $k => $v){
				$this->U[$k] = $v;
			}
		}else {
			//不覆盖原有信息
			foreach ($array as $k => $v){
				if(empty($this->U[$k])){
					$this->U[$k] = $v;
				}
			}
		}
		//设置ID;
		$this->U[$this->PriKey]>0 && $this->SetID($this->U[$this->PriKey]);
		$this->U_UniqueID && $this->U[$this->U_UniqueID] && $this->U_Uname = $this->U[$this->U_UniqueID];
		//打散权限数组
		$this->ExportPrivs();
	}
	//插入用户,可以插入一个,或者多个用户
	/**
	 * 插入用户,可以插入一个,或者多个用户
	 * 使用 $U_Insert 插入
	 *
	 * @param array $safearray 用来过滤非法字段的数组
	 * @param number $w 1是一维插入,即插入一个.2是插入多个即二维插入
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
	 * 更新一个用户
	 * 使用safearray 来设置安全数组.外部传入,非常重要.用来保护诸如帐户资金都重要字段
	 *
	 * @param array $safearray 用来过滤非法字段的数组
	 * @return bool
	 */
	//必须先调用 SetUpdateInfo 设置Update 数组
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
	 * 设置用于更新用户的缓存数组
	 * 在 DoReRecord 前调用
	 *
	 * @param array $array 用于更新用户的数据数组
	 * @param bool $rewrite 是否重写标志,默认true,也没有必要使用false
	 */
	function SetUpdateInfo($array,$rewrite=true){
		if($rewrite) $this->U_Update = array();
		foreach ($array as $k => $v){
			//跳过主键
			if($k == $this->PriKey) continue;
			//跳过未更改值
			if($v == $this->U[$k]) continue;
			//记录一个有效更改
			$this->U_Update[$k] = addslashes(stripslashes($v));
		}
		//对密码字段加密
		if($this->PsNeedEncode && isset($this->U_Update[$this->U_PasswordKey])) {
			$this->U_Update[$this->U_PasswordKey] = $this->PassWordEnCode($this->U_Update[$this->U_PasswordKey]);
		}
	}
	/**
	 * 设置用于插入用户的数据数组
	 *
	 * @param array $array 用于插入用户的数据数组
	 * @param bool $rewrite 是否重写标志,默认true,也没有必要使用false
	 */
	function SetInsertInfo($array,$rewrite=true){
		if($rewrite) $this->U_Insert = array();
		$i = $type = 0;
		foreach ($array as $k => $v){
			if(!is_array($v) && ($i==0||$type==1)){
				$type = 1;
				//if($this->MainServ && $k == $this->PriKey) continue;
				//对密码字段加密
				if($this->PsNeedEncode && $k == $this->U_PasswordKey) $v = $this->PassWordEnCode($v);
				//记录一个有效属性
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
	 * 设置用户存储表
	 *
	 * @param string $tb 表名字
	 */
	function SetUserTable($tb){
		$this->U_Table = $tb;
	}
	/**
	 * 设置用户的唯一键
	 *
	 * @param string $uniKey 唯一键名字
	 */
	function SetUniKey($uniKey){
		$this->U_UniqueID = $uniKey;
	}
	/**
	 * 设置密码字段
	 *
	 * @param string $passKey 密码字段的名字
	 */
	function SetPassKey($passKey){
		$this->U_PasswordKey = $passKey;
	}
	/**
	 * 一次设置所有配置,使用数组.
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
	//设置一个用户的权限
	/**
	 * 设置一个用户的权限
	 *
	 * @param array $priv 权限数组
	 * @param bool $rewrite 是否重写
	 * @param bool $overwrite 是否覆盖
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
	 * 检查某个用户的一项,或多项权限
	 * 有正、负两种检验
	 * 正：具有待检验的所有权限的情况下返回，负：具有一种权限即可。
	 *
	 * @param array $priv 待检查数组
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
	 * 释放用户权限为数组形式
	 * rewrite
	 */
	function ExportPrivs(){
		$this->U[$this->PrivKey] = explode(' ',trim($this->U[$this->PrivKey]));
	}
	/**
	 * 检查用户是否在某一用户组里
	 *
	 * @param number $g
	 * @param bool $b
	 * @return bool
	 */
	function CheckGroupExist($g,$b=true) {
		if ($b) {	//主用户组检查
			if ($this->BU[$this->GpKey] == $g) {
				return true;
			}
			if ($this->BU[$this->GpsKey]) {
//				#兼容DZ,PW写法
//				$array = explode(' ',explode(' ',trim(str_replace(array(',',"\t"),' ',$this->BU[$this->GpsKey]))));
//				if (in_array($g,$array)) {
//					return true;
//				}
				if (ereg(" $g ",$this->BU[$this->GpsKey])) {
					return true;
				}
			}
			return false;
		}else {	//从用户组检查
			return false;
		}
		return false;
	}
	/**
	 * 判断用户是否在一些用户组中
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
	 * 重新将用户权限写入数据库
	 * rewrite
	 * @return bool
	 */
	function ReRecordPriv(){
		$this->SetUpdateInfo(array($this->PrivKey => ' ' . implode(' ',trim($this->U[$this->PrivKey])) . ' ' ));
		return $this->DoReRecord();
	}
	/**
	 * 判断是否登录,已登录返回true,否则返回false
	 *
	 * @return bool
	 */
	function IsLogined(){
		$return = false;
		if(defined('ISLOGIN')) { 
			return 	ISLOGIN;
		}
		if(!$this->EasyCheck) {
			if ($this->MainServ) {	//主服务器严格检测
				if($this->U_ID <=0) return false;
				$this -> LoadFromDBUsePriID($this->U_ID);
				if($this->PassCheck == $this->PassCheckEnCode($this->U)){
					$return = true;
				}else {
					$return = false;
				}
			}else {	//非主服务器严格检测
				if ($this->U_ID > 0 && $this->PassCheck && md5($this -> PassCheck.$this->Uc_hash) == $this->GetCookie($this->Uc_PP.'_CK')){	//验证串验证
						if ($this->ClientTableUsed) {
							$this  -> LoadFromDBUsePriID($this->U_ID);
						}
						$return = true;
				}else {		//数据库验证
					if ($this->SameServer && $this-> UpperClass) {	//与认证服务器在同一服务器上,使用认证服务器严格检测
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
					}else {	//与主服务器不在同一个服务器上,it's safe now!
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
		}else {	// 简单检测	to rewrite! no safe
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
	 * 检验用户密码,使用 uniquekey 和 password 验证,password (明码)
	 *
	 * @param string $uniqID 用户的唯一字段的值
	 * @param string $password 用户的密码
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
	 * 检验用户密码,使用 主键 和 密码 验证,password (明码)
	 *
	 * @param number $ID 用户id
	 * @param string $password 用户密码
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
	 * 设置用户已登录状态
	 *
	 */
	function PutLoginedInfo(){
		//主站必须设置cookie
		if($this->UseCookie || $this->MainServ){
			$this->PutCookie($this->Uc_PP,$this->PassCheck,$this->Uc_time*60);
		}else if(!$this->UseCookie){
			$_SESSION[$this->Uc_PP] = $this->PassCheck;
		}
	}
	/**
	 * 取消用户登录状态
	 *
	 */
	function ExitLogin(){
		if($this->UseCookie){
			if ($GLOBALS['rtc']['uc_use']) {	# 修复UC无法同步退出的 IE BUG
				$this->PutCookie($this->Uc_PP,'1',$this->Uc_time*60);
			}else {
				$this->DropCookie($this->Uc_PP);
				//兼容dz
				$this->DropCookie('sid');
			}
		}else {
			unset($_SESSION[$this->Uc_PP]);
			@session_destroy();
		}
	}
	/**
	 * 使用主键从数据库更新用户信息
	 *
	 * @param number $id 用户主键键值
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
	 * 使用唯一键从数据库更新用户信息
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
	 * 清空用户信息
	 *
	 */
	function ReSet(){
		$this->U_ID = -1;
		$this->U = array();
	}
	/**
	 * 从Cookie(Session) 获得用户ID信息
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
	 * 设置用户ID
	 *
	 * @param number $id
	 */
	function SetID($id){
		is_numeric($id) && $id>0 && $this->U_ID = $id;
		//设置空位
		if(empty($this->U_ID)||$this->U_ID<=0) $this->U_ID = -1;
		$this->U[$this->PriKey] = $this->U_ID;
	}
	/**
	 * 密码加密函数.可根据参数设置用户加密方式
	 *
	 * @param string $p 待加密字串
	 * @return string 加密后字窜
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
			default:		//默认md5
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
	 * 重建PassCheck
	 * 主服务器有效
	 */
	function PassCheckRebuild() {
		$this -> PassCheck = $this -> PassCheckEnCode($this->U);
		//兼容DZ
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
	 * 快速更新用户密码
	 *
	 * @param string $p 新密码
	 * @return bool
	 */
	function UpdatePass($p) {
		$this -> SetUpdateInfo(array($this->U_PasswordKey=>$p));
		return $this->DoReRecord();
	}
	/**
	 * 更新上次登陆时间IP
	 *
	 */
	function UpdateLastLogin() {
		$this -> SetUpdateInfo(array('u_lastlogin' => $GLOBALS['timestamp'],'u_lastlogip'=>$this->GetIP()));
		$this -> DoReRecord(array('u_lastlogin','u_lastlogip'));
	}
	
	/**
	 * 设置附加的机密用户信息
	 *
	 */
	function SetSecretUserInfo() {
		$this->PutCookie($this->Uc_PP.'_data',$this->CreateSecretUserInfo(),100 * 24 * 3600);
	}
	
	function ClearSecretUserInfo() {
		$this->DropCookie($this->Uc_PP.'_data');
	}
	/**
	 * 创建用户机密信息
	 *
	 * @return string
	 */
	function CreateSecretUserInfo(){
		//用户名\t权限表\tothers
		$string = $this->U_Uname . "\t" . '';
		return $this->AuthEncoder->Encode($string);
	}
	/**
	 * 解析机密的用户信息
	 * 
	 */
	function ParseSecretUserInfo() {
		//从机密信息获得
		if ( ($this->UseCookie || $this->MainServ) ) {
			$string = $this->GetCookie($this->Uc_PP.'_data');
		}else {
//			$string = $_SERVER[$this->Uc_PP.'_data'];
		}
		@list($this->U_Uname,) = @explode("\t",$this -> AuthEncoder -> Decode($string));
	}
	/**
	 * 设置一个带有验证信息的cookie
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
	 * 验证合法性后获得一个cookie
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
	 * 销毁一个Cookie
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


function CheckUserPass($uid,$passcheck) {	//只适用于跨服务器的检验
	if ($uid < 1) {
		return array();
	}
	if (defined('Admin_Safe')) {	//检测管理员
		$api_root = $GLOBALS['rtc']['admin_host_api'] ? $GLOBALS['rtc']['admin_host_api'] : $GLOBALS['rtc']['admin_host'];
	}else {	//检测passport user
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
	if (defined('Admin_Safe')) {	//检测管理员
		$api_root = $GLOBALS['rtc']['admin_host_api'] ? $GLOBALS['rtc']['admin_host_api'] : $GLOBALS['rtc']['admin_host'];
	}else {	//检测passport user
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