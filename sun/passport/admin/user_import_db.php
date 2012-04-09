<?php
require('ppframe.php');

$db = $db ? $db : $_db_config['dbname'];
if (!$tb ) {
	EchoMsg('please.give.table');
	exit;
}
$ppsql = new dbsql();
if (!$ppsql -> TableExist($tb,$db)) {
	EchoMsg('table.not.exist');
	exit;
}
if ($db == $ppsql ->dbName && $tb == $ppsql -> ChangeQuery($rtc['passport_table'])) {
	EchoMsg('table.same');
	exit;
}
$userfields = $ppsql -> GetFieldList($rtc['passport_table']);
$fromfields = $ppsql -> GetFieldList($tb,$db);
$sql = '';
$ignore = $ignore ? 'IGNORE' : '';
if ($ignore) {
	$replace = false;
}
if ($replace) {
	$sql = "Replace Into `{$_db_config['dbname']}`.`{$rtc['passport_table']}` ";
}else {
	$sql = "Insert $ignore Into `{$_db_config['dbname']}`.`{$rtc['passport_table']}` ";
}
$fs = array();
$vs = array();
if (is_array($fields)) {
	foreach ($fields as $k => $v) {
		if (key_exists($k,$userfields) && $v) {
			$fs[] = "`$k`";
			if (key_exists($v,$fromfields)) {
				if ($v == $k) {
					$vs[] = "`$v`";
				}else {
					$vs[] = "`$v` as `$k`";
				}
			}else {
				EchoMsg('from.table.field.error');
				exit;
			}
		}else {
			//continue;
		}
	}
	if ($fs && $vs && count($fs) == count($vs)) {	//yes
		$fs = '('.implode(',',$fs).')';
		$vs = implode(',',$vs);
		
		$sql  =  $sql . $fs . '  Select ' .$vs .' From ' . " `$db`.`$tb` ";
		
		if ($check_first) {
			EchoMsg('test.success');
			echo '<br />';
			echo 'SQL:'.$ppsql -> ChangeQuery($sql);
		}else {
			if($ppsql -> ExecNoReturnSQL($sql)) {
				EchoMsg('import.success');
				echo '<br />';
				echo 'SQL:';
				echo $ppsql -> ChangeQuery($sql);
			}else {
				EchoMsg('do.fail');
				echo '<br />';
				echo 'SQL:';
				echo $ppsql -> ChangeQuery($sql);
			}
		}
	}else {
		EchoMsg('unexpect.error');
		exit;
	}
}else {
	EchoMsg('please.give.your.request');
}
?>