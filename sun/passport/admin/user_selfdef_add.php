<?php
require('ppframe.php');
$ppsql = new dbsql();

$tb = $rtc['passport_table'];

if (empty($input['field'])) {
	ShowMessage('please.give.field.name');
}
if ($input['editonce']) {
	$once = 'no';
}else {
	$once = 'yes';
}
	
if ($input['autocreate']) {
	$input['field'] = 'selfdef_' . $once .'_' . $input['field'];
}else {
	$input['field'] = 'mydef_' . $once .'_'  . $input['field'];
}
#field exist?
if($ppsql -> GetFieldList($tb,'',$input['field'])) {
	ShowMessage('field.exist');
}

if (!in_array($input['type'],array('varchar','char','int','smallint','tinyint','text','mediumtext'))) {
	ShowMessage('field.type.error');
}

if (empty($input['length'])) {
	$input['length'] = 10;
}

if (empty($input['default'])) {
	if (eregi('int$',$input['type'])) {
		$input['default'] = 0;
	}else {
		$input['default'] = '';
	}
}else {
	if (eregi('int$',$input['type'])) {
		$input['default'] = intval($input['default']);
	}
}

if (isset($Submit)) {
	$sql = "ALTER TABLE `$tb` ADD `{$input['field']}` ";
	#type
	if (!eregi('text$',$input['type'])) {
		$sql .= " $input[type] ($input[length]) ";
	}else {
		$sql .= " $input[type] ";
	}
	#null
	if (!$input['null']) {
		$sql .= ' NOT NULL ';
	}else {
		$sql .= ' NULL ';
	}
	#default
	if (!eregi('text$',$input['type'])) {
		$sql .= "DEFAULT '{$input['default']}'";
	}
	if ($ppsql -> ExecNoReturnSQL($sql)) {
		ShowMessage('add.field.success','user_selfdef_main.php');
	}else {
		ShowMessage('add.field.fail');
	}
}else {
	ShowMessage('error');
}
?>