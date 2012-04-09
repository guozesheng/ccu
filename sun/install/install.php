<?php
if (!defined('install_safe')) {
	exit('Forbidden');
}

//$install_mode_version to write
$install_config_file = ROOT.$mode.'/config/install_config.php';
@include($install_config_file);

#for config
//any mode only need to reconfig this two var.

#for config

if ($installmode == 'install' && !$install_mode_version) {	//模块安装
	#1 create db
	Iimport('dbsql');
	$ppsql = new dbsql();
	$sql = ReadOverFile(ROOT.$mode.'/install/install-sql.txt');
	$sqls = explode(';',$sql);
	foreach ($sqls as $k => $v) {
//		if ($v && !eregi('drop',$v)) {
		if ($v) {
			if (eregi('create table',$v)) {
				$s = substr(strrchr($v,')'),1);
				$v = str_replace($s,'',$v);
				if ($ppsql -> dbVersion > '4.1' && $ppsql -> dbCharset) {
					$v .= " ENGINE=MyISAM DEFAULT CHARSET={$ppsql->dbCharset}";
				}else {
					$v .= " ENGINE=MyISAM ";
				}
			}
			$ppsql -> ExecNoReturnSQL($v);
		}
	}
	#2 write config
	Iimport('Config');
	$Config_obj = new Config('base_config',ROOT.$mode.'/config/baseconfig.php');
	@include(ROOT.$mode.'/config/baseconfig-for-install.php');
	$Config_obj -> LoadConfigNoCheck($base_config);
	$Config_obj -> LoadConfig($config);
	$Config_obj -> EnableDB(false);
	if($Config_obj -> ReConfig() && file_exists($Config_obj -> Cfullfile)) {
		#3 Add module (非独立运行)
		$sysmodule = $mode == 'passport' ? 1 : 0;
		$mode_author = $mode_author ? $mode_author : 'PPFrame';
		$ppsql -> ExecNoReturnSQL("Replace Into ##__frame_module(`key`,`name`,`run`,`author`,`lastmodify`,`adminroot`,`adminmenu`,`adminmenuajax`,`sysmodule`) 
								Values('$mode','$mode',1,'$mode_author','$create_date','{$host}{$mode}/admin/','index_menu.php','ajax_index_menu.php','$sysmodule')");
		#4 write $install_mode_version
		$str = "<?php\r\n\$install_mode_version = '$mode_version';\r\n?>";
		WriteFile($str,$install_config_file);
		#5 echo success
		echo '<font color=green>INSTALL SUCCESS</font><br />';
		echo "<a target=\"_blank\" href=\"{$host}{$mode}/\">Index</a>";
		exit;
	}else {
		echo 'Try to Write ' . $Config_obj->Cfullfile . 'Error <br />';
		echo 'Please Create it And Chmod to 777 First';
		exit;
	}
}else if($installmode == 'update' && $install_mode_version < $mode_version) {	//升级模块
	#1 update db
	Iimport('dbsql');
	$ppsql = new dbsql();
	@include(ROOT.$mode.'/install/update-sql.php');
	if(is_array($_update_sql)) {
		foreach ($_update_sql as $k => $v) {	//rewrite
			if ($k > $install_mode_version && is_array($v)) {
				foreach ($v as $kk => $vv) {
					$vv = trim($vv);
					if ($vv) {
						if (eregi('create table',$vv)) {
							$s = substr(strrchr($vv,')'),1);
							$vv = str_replace($s,'',$vv);
							if ($ppsql -> dbVersion > '4.1' && $ppsql -> dbCharset) {
								$vv .= " ENGINE=MyISAM DEFAULT CHARSET={$ppsql->dbCharset}";
							}else {
								$vv .= " ENGINE=MyISAM ";
							}
						}
						$ppsql -> ExecNoReturnSQL($vv);
						
						
						#可能的分表存储的表
						$trans_tables = array('##__exam_shiti','##__cms_doc_article','##__cms_doc_soft');
						#处理分表的升级
						foreach ($trans_tables as $tbkey => $tbvalue) {
							$tbvalue = $ppsql -> ChangeQuery($tbvalue);
							$vv = $ppsql -> ChangeQuery($vv);
							if (eregi('ALTER TABLE',$vv) && eregi($tbvalue,$vv)) {	//升级可能的题库表
								$tblist = $ppsql -> GetTableList($tbvalue.'_%');
								if ($tblist) {
									foreach ($tblist as $kkk => $vvv) {
										$ppsql -> ExecNoReturnSQL(str_replace($tbvalue,$vvv,$vv));
									}
								}
							}
						}
						#
					}
				}
			}
		}
	}
	#2 write $install_mode_version
	$str = "<?php\r\n\$install_mode_version = '$mode_version';\r\n?>";
	WriteFile($str,$install_config_file);
	#3 echo success
	echo '<font color=green>UPDATE SUCCESS</font><br/>';
	echo "<a target=\"_blank\" href=\"{$host}{$mode}/\">Index</a>";
	exit;
}else {	//安装、升级向导
	$writecheck = array(
		'config','config/baseconfig.php','temp','temp/cache','temp/config','temp/template_cache_dir',
	);
	foreach ($writecheck as $k => $v) {
		if (file_exists(ROOT.$mode.'/'.$v)) {
			$wcd[str_replace('\\','/',ROOT.$mode.'/'.$v)] = is_writeable(ROOT.$mode.'/'.$v) ? 1 : 0;
		}
	}

	$random_str = md5($_SERVER['HTTP_HOST'].$_SERVER['HTTP_USER_AGENT'].time());
	$include = @include(ROOT."/install/mode-install-{$language}.htm");
	!$include && @include(ROOT.'/install/mode-install.htm');
	exit;
}
?>