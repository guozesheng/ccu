<?php
//#########################################################################
/*------------------------
��ʼ��ϵͳ����
------------------------*/
$step=$_GET['step'];
define('VIOOMAROOT',substr(dirname(__FILE__),0,-8));
require_once(VIOOMAROOT."/include/config_rglobals.php");

if(empty($step)) $step = 1;
require_once("./inc_install.php");
/*------------------------
��ʾЭ���ļ�
------------------------*/
if($step==1){
	if(file_exists('install.finish')) {
		echo '���Ѿ���װ����ϵͳ����������°�װ������ɾ��installĿ¼�� install.finish �ļ���Ȼ���ٴ����иó���';
		exit;
	}
	include_once("./templets/step1.html");
	exit();
}
/*------------------------
���Ի���Ҫ��
------------------------*/
else if($step==2)
{
	$phpv = @phpversion();
	$sp_os = $_ENV["OS"];
	$sp_gd = @gdversion();
	$sp_server = $_SERVER["SERVER_SOFTWARE"];
	$sp_host = (empty($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_HOST"] : $_SERVER["REMOTE_ADDR"]);
	$sp_name = $_SERVER["SERVER_NAME"];
	$sp_max_execution_time = ini_get('max_execution_time');
	$sp_allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');
  $sp_allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');
  $sp_safe_mode = (ini_get('safe_mode') ? '<font color=red>[��]On</font>' : '<font color=green>[��]Off</font>');
  $sp_gd = ($sp_gd>0 ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');
  $sp_mysql = (function_exists('mysql_connect') ? '<font color=green>[��]On</font>' : '<font color=red>[��]Off</font>');

  if($sp_mysql=='<font color=red>[��]Off</font>') $sp_mysql_err = true;
  else $sp_mysql_err = false;

  $sp_testdirs = array(
        '/',
        '/include',
		'/data/sessions'
  );
	include_once("./templets/step2.html");
	exit();
}
/*------------------------
��д����
------------------------*/
else if($step==3)
{
	if(!empty($_SERVER["REQUEST_URI"])){$scriptName = $_SERVER["REQUEST_URI"]; }
  else{ $scriptName = $_SERVER["PHP_SELF"]; }
  $path = ereg_replace("/install/index\.php(.*)$","",$scriptName);
  if(empty($_SERVER['HTTP_HOST'])) $baseurl = "http://".$_SERVER['HTTP_HOST'];
  else $baseurl = "http://".$_SERVER['SERVER_NAME'];
  $rnd_cookieEncode = chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('a'),ord('z'))).chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('a'),ord('z'))).mt_rand(1000,9999).chr(mt_rand(ord('A'),ord('Z')));
	include_once("./templets/step3.html");
	exit();
}
/*------------------------
��ʼ��װ����
------------------------*/
else if($step==5)
{
  if(empty($setupsta)) $setupsta = 0;
  //��ʼ��������װ���ݡ�������
  //----------------------------------
  if($setupsta==0)
  {
  	$setupinfo = '';
  	$gotourl = '';
  	$gototime = 2000;
  	if(eregi("[^\.0-9a-z@!_-]",$adminuser) || eregi("[^\.0-9a-z@!_-]",$adminpwd)){
  		echo GetBackAlert("����Ա�û��������뺬�зǷ��ַ���");
  		exit();
  	}

  	//������ݿ�Ȩ��

  	$conn = @mysql_connect($dbhost,$dbuser,$dbpwd);
  	if(!$conn){
  		echo GetBackAlert("���ݿ���������¼������Ч��\\n\\n�޷��������ݿ⣬�������趨��");
  		exit();
  	}
  	$rs = mysql_select_db($dbname,$conn);
  	if(!$rs){
  		$rs = mysql_query(" CREATE DATABASE `$dbname`; ",$conn);
  		if(!$rs){
  		  $errstr = GetBackAlert("���ݿ� {$dbname} �����ڣ�ҲûȨ�޴����µ����ݿ⣡");
  		  echo $errstr;
  		  exit();
  		}else{
  			$rs = mysql_select_db($dbname,$conn);
  			if(!$rs){
  		    $errstr = GetBackAlert("������ݿ� {$dbname} ûȨ�ޣ�");
  		    echo $errstr;
  		    exit();
  		  }
  		}
  	}

  	//��ȡ����ģ�壬���滻��ʵ����
  	$errstr = GetBackAlert("��ȡ���� config_base.php ʧ�ܣ�����install/config_base.php�Ƿ�ɶ�ȡ��");
  	$fp = fopen(VIOOMAROOT."/install/config_base.php","r") or die($errstr);
    $configstr1 = fread($fp,filesize(VIOOMAROOT."/install/config_base.php"));
    fclose($fp);
    $errstr = GetBackAlert("��ȡ���� config_hand.php ʧ�ܣ�����install/config_hand.php�Ƿ�ɶ�ȡ��");
    $fp = fopen(VIOOMAROOT."/install/config_hand.php","r") or die($errstr);
    $configstr2 = fread($fp,filesize(VIOOMAROOT."/install/config_hand.php"));
    fclose($fp);

    $configstr1 = str_replace('~dbhost~',$dbhost,$configstr1);
    $configstr1 = str_replace('~dbname~',$dbname,$configstr1);
    $configstr1 = str_replace('~dbuser~',$dbuser,$configstr1);
    $configstr1 = str_replace('~dbpwd~',$dbpwd,$configstr1);
    $configstr1 = str_replace('~dbprefix~',$dbprefix,$configstr1);
    $configstr1 = str_replace('~db_language~',$db_language,$configstr1);

    $errstr = GetBackAlert("д������ include/config_base.php ʧ�ܣ����� include�ļ��� �Ƿ�ɶ�д��");
  	$fp = fopen(VIOOMAROOT."/include/config_base.php","w") or die($errstr);
  	fwrite($fp,$configstr1);
  	fclose($fp);

  	$indexurl = (empty($cmspath) ? '/' : $cmspath);
  	$configstr2 = str_replace('~cmspath~',$cmspath,$configstr2);
  	$configstr2 = str_replace('~cookiepwd~',$cookiepwd,$configstr2);
  	$configstr2 = str_replace('~webname~',$webname,$configstr2);
  	$configstr2 = str_replace('~weburl~',$weburl,$configstr2);
  	$configstr2 = str_replace('~indexurl~',$indexurl,$configstr2);
  	$configstr2 = str_replace('~adminmail~',$adminmail,$configstr2);

  	$fp = fopen(VIOOMAROOT."/include/config_hand.php","w") or die($errstr);
  	fwrite($fp,$configstr2);
  	fclose($fp);
  	$fp = fopen(VIOOMAROOT."/include/config_hand_bak.php","w");
  	fwrite($fp,$configstr2);
  	fclose($fp);

  	 //������ݿ���Ϣ�������������ݱ�
  	 mysql_query("SET NAMES '{$db_language}';",$conn);
     $rs = mysql_query("SELECT VERSION();",$conn);
     $row = mysql_fetch_array($rs);
     $mysql_version = $row[0];
     $mysql_versions = explode(".",trim($mysql_version));
     $mysql_version = $mysql_versions[0].".".$mysql_versions[1];
	 $adminpwd=md5($adminpwd);
     $admindatas = "
          INSERT INTO `#@__boss` VALUES (null, '{$adminuser}', '{$adminpwd}', '{$adminuser}', '��', '0',  '2012-05-06 20:35:28', '127.0.0.1','0','1');
          INSERT INTO `#@__config` VALUES (1, 'cfg_webname', '��˾����', '{$webname}', 'string', 8);
          INSERT INTO `#@__config` VALUES (2, 'cfg_basehost', 'վ�����ַ', '{$weburl}', 'string', 8);
          INSERT INTO `#@__config` VALUES (3, 'cfg_cmspath', '��װĿ¼', '{$path}', 'string', 8);
          INSERT INTO `#@__config` VALUES (5, 'cfg_cookie_encode', 'cookie������', '{$cookiepwd}', 'string', 8);
          INSERT INTO `#@__config` VALUES (4, 'cfg_indexurl', '��ҳ��ҳ����', '{$indexurl}', 'string',8);
          INSERT INTO `#@__config` VALUES (6, 'cfg_adminemail', 'վ��EMAIL', '{$adminmail}', 'string', 8);
		  INSERT INTO `#@__config` VALUES (7, 'cfg_backup_dir', '���ݱ���Ŀ¼', 'backup_data', 'string', 30);
		  INSERT INTO `#@__config` VALUES (8, 'cfg_keeptime', 'Cookie����ʱ��', '2', 'smallint', 6);
		  INSERT INTO `#@__config` VALUES (10, 'cfg_conact', '��ϵ��', '', 'string', 10);
		  INSERT INTO `#@__config` VALUES (11, 'cfg_phone', '��ϵ�绰', '', 'string', 15);
		  INSERT INTO `#@__config` VALUES (16, 'cfg_record', '��ʾ��¼��', '10', 'smallint', 6);
		  INSERT INTO #@__usertype VALUES (1,'��������Ա','1','admin_AllowAll');
		  INSERT INTO #@__usertype VALUES (2,'��ͨ��ʦ','1','c_ACClab');
     ";
     if($mysql_version < 4.1) $fp = fopen(VIOOMAROOT."/install/setup40.sql","r");
     else $fp = fopen(VIOOMAROOT."/install/setup41.sql","r");
    //�������ݱ��д������
    $query = "";
    while(!feof($fp))
	  {
		   $line = trim(fgets($fp,1024));
		   if(ereg(";$",$line)){
			   $query .= $line;
			   $query = str_replace('#@__',$dbprefix,$query);
			   if($mysql_version < 4.1) mysql_query($query,$conn);
			   else mysql_query(str_replace('#~lang~#',$db_language,$query),$conn);
			   $query='';
		   }else if(!ereg("^(//|--)",$line)){
			   $query .= $line;
		   }
	  }
	  fclose($fp);

	  $sysquerys = explode(';',$admindatas);
	  foreach($sysquerys as $query){
	  	if(trim($query)!='') mysql_query(str_replace('#@__',$dbprefix,$query),$conn);
	  }

	  mysql_close($conn);
	  $gotourl = 'index.php?step=5&setupsta=1';
	  $setupinfo = "
	    �ɹ���װϵͳ�������ݣ����ڿ�ʼ��װ��Ҫ�Ļ�������<br />
	    ���Ե�...<br />
	    ���ϵͳ̫��ʱ��û��Ӧ��������<a href='{$gotourl}'>����&gt;&gt;</a>
	  ";
  	include_once("./templets/step4.html");
	  exit();
  }
  //��װ��������
  else if($setupsta==1)
  {
  	 	  $gototime = 2000;
  	 	  $gotourl = '../login.php';
  	 	  $setupinfo = "
	        �����������Ŀ�İ�װ<br />
	        ����ת�����Ա��¼ҳ�棬���Ե�...<br />
	        ���ϵͳ̫��ʱ��û��Ӧ��������<a href='{$gotourl}'>����&gt;&gt;</a>
	      ";
  	 include_once(VIOOMAROOT."/include/config_base.php");
  	 $dsql = new DedeSql(false);
  	 $fp = fopen(VIOOMAROOT."/install/setup_data1.sql","r");
     //�������ݱ��д������
     $query = "";
     
     while(!feof($fp))
	   {
		   $line = trim(fgets($fp,1024));
		   if(ereg(";$",$line)){
			   $query .= $line;
//if(trim($query)!='') mysql_query(str_replace('#@__',$dbprefix,$query),$conn);
			   $dsql->ExecuteNoneQuery($query);
			   $query='';
		   }else if(!ereg("^(//|--)",$line)){
			   $query .= $line;
		   }
	   }
	   fclose($fp);
  	 $dsql->Close();
  	 include_once("./templets/step4.html");
	   exit();
  }
}

 
/*------------------------
������ݿ��Ƿ���Ч
function _10_TestDbPwd()
------------------------*/
else if($step==10)
{
  header("Pragma:no-cache\r\n");
  header("Cache-Control:no-cache\r\n");
  header("Expires:0\r\n");
	header("Content-Type: text/html; charset=gb2312");
	$conn = @mysql_connect($dbhost,$dbuser,$dbpwd);
	if($conn)
	{
	  $rs = mysql_select_db($dbname,$conn);
	  if(!$rs)
	  {
		   $rs = mysql_query(" CREATE DATABASE `$dbname`; ",$conn);
		   if($rs){
		  	  mysql_query(" DROP DATABASE `$dbname`; ",$conn);
		  	  echo "<font color='green'>��Ϣ��ȷ</font>";
		   }else{
		      echo "<font color='red'>���ݿⲻ���ڣ�ҲûȨ�޴����µ����ݿ⣡</font>";
		   }
	  }else{
		    echo "<font color='green'>��Ϣ��ȷ</font>";
	  }
	}else{
		echo "<font color='red'>���ݿ�����ʧ�ܣ�</font>";
	}
	@mysql_close($conn);
	exit();
}
?>