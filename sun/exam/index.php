<?php
require('ppframe.php');

#使用EasyCache
if ($base_config['easy_use_index']) {
	Iimport('EasyCache');
	$easycache = new EasyCache($base_config['easy_cachetime']);
	$easycache -> UseCache();
}
//
$topclasslister = GetTopClassLister();
Iimport('Template');
$tpl = new Template();
$tpl -> LanguageAssign('title','online.exam.system');
$tpl -> DisPlay('index');

#存储EasyCache
if ($base_config['easy_use_index']) {
	$easycache -> StoreCache();
}

#批处理功能
if (!isset($base_config['exam_batupdate_start'])) {
	$base_config['exam_batupdate_start'] = 1;
}
if (!isset($base_config['exam_batupdate_end'])) {
	$base_config['exam_batupdate_end'] = 3;
}
$t = GetMyDate('G');
if ($base_config['easy_use_index'] || ($t >= $base_config['exam_batupdate_start'] && $t< $base_config['exam_batupdate_end'])) {
	#update state!
	@set_time_limit(600);
	@ignore_user_abort(TRUE);
	$ppsql = new dbsql();
	
	#更新未及时交卷状态
	$ppsql -> SetQueryString("Update ##__exam_kaojuan a left join ##__exam_shijuan b on a.`shijuan`=b.`id` Set a.`end`=a.`start` + b.dotime*60 where a.`end`=0 and a.`start`>0 and a.`start`+b.dotime*60<$timestamp");
	$ppsql -> ExecNoReturnSQL();
	
	#未及时开始答卷 清空 or 删除?
	$base_config['kaojuan_livetime'] = $base_config['kaojuan_livetime'] > 8 ? $base_config['kaojuan_livetime'] : 8;
	$timeout = $base_config['kaojuan_livetime'] * 3600 ;
	$ppsql -> SetQueryString("Update ##__exam_kaojuan Set `content`='',`close`=0 Where `start`=0 and $timestamp -`time`>$timeout");
	$ppsql -> ExecNoReturnSQL();
	$ppsql -> Close();
}
#批处理结束
?>