<?php
require('ppframe.php');

#获得自定义字段
$selfdef_fields = GetTableFields($rtc['passport_table'],'selfdef_%');
$mydef_fields = GetTableFields($rtc['passport_table'],'mydef_%');
#获得自定义字段

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_selfdef_main');
?>