<?php
require('ppframe.php');

#����Զ����ֶ�
$selfdef_fields = GetTableFields($rtc['passport_table'],'selfdef_%');
$mydef_fields = GetTableFields($rtc['passport_table'],'mydef_%');
#����Զ����ֶ�

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_selfdef_main');
?>