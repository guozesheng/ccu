<?php
require('ppframe.php');
$ppsql = new dbsql();

$tb = $rtc['passport_table'];

if (empty($field) || (!ereg('^selfdef_',$field) && !ereg('^mydef_',$field))) {	//不允许的字段
	ShowMessage('field.error');
}else {
	$selfdef_fields = $ppsql -> GetFieldList($tb,'',$field);
	if (empty($selfdef_fields) || count($selfdef_fields) < 1) {	//不存在的字段
		ShowMessage('field.not.exist');
	}
}

if (isset($Submit)) {
	
	if (empty($input['field'])) {
		ShowMessage('please.give.field.name');
	}
	if ($input['editonce']) {
		$once = 'no';
	}else {
		$once = 'yes';
	}
	
	if ($input['autocreate']) {
		$input['field'] = 'selfdef_' . $once . '_' . $input['field'];
	}else {
		$input['field'] = 'mydef_' . $once . '_' . $input['field'];
	}
	
	if ($field != $input['field']) {
		#field exist?
		if($ppsql -> GetFieldList($tb,'',$input['field'])) {
			ShowMessage('field.exist');
		}
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
	
	$sql = "ALTER TABLE `$tb` CHANGE `{$field}`  `{$input['field']}` ";
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
		ShowMessage('edit.field.success','user_selfdef_main.php');
	}else {
		ShowMessage('edit.field.fail');
	}
}else {
	$selfdef_fields = $selfdef_fields[$field];
	if (eregi('^selfdef_',$selfdef_fields[0])) {
		if (eregi('^selfdef_yes_',$selfdef_fields[0])) {
			$edit['field'] = substr($selfdef_fields[0],strlen('selfdef_yes_'));
			$edit['yes'] = 1;
		}else {
			$edit['field'] = substr($selfdef_fields[0],strlen('selfdef_no_'));
			$edit['no'] = 1 ;
		}
		
		$edit['auto'] = 1 ;
		
	}else if(eregi('^mydef_',$selfdef_fields[0])){
		if (eregi('^selfdef_yes_',$selfdef_fields[0])) {
			$edit['field'] = substr($selfdef_fields[0],strlen('mydef_yes_'));
			$edit['yes'] = 1;
		}else {
			$edit['field'] = substr($selfdef_fields[0],strlen('mydef_no_'));
			$edit['no'] = 1 ;
		}
		$edit['auto'] = 0;
	}else {
		ShowMessage('error');
	}
	$edit['type'] = trim(substr($selfdef_fields[1],0,strpos($selfdef_fields[1],'(')));
	$edit['length'] = substr($selfdef_fields[1],strpos($selfdef_fields[1],'(')+1,strpos($selfdef_fields[1],')')-strpos($selfdef_fields[1],'(')-1);
	$edit['null'] = $selfdef_fields[2] == 'NO' ? 0 : 1;
	$edit['default'] = $selfdef_fields[4];
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('user_selfdef_edit');
}
?>