<?php
require('ppframe.php');
$ppsql = new dbsql();

$tb = $rtc['passport_table'];

if (empty($field) || (!ereg('^selfdef_',$field) && !ereg('^mydef_',$field))) {	//��������ֶ�
	ShowMessage('field.error');
}else if(!$ppsql -> GetFieldList($tb,'',$field)){	//�����ڵ��ֶ�
	ShowMessage('field.not.exist');
}

$ppsql -> SetQueryString("ALTER TABLE `{$tb}` DROP `{$field}` ");
if ($ppsql -> ExecNoReturnSQL()) {
	ShowMessage('del.field.success','user_selfdef_main.php');
}else {
	ShowMessage('del.field.fail');
}
?>