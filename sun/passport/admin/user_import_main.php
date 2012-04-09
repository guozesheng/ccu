<?php
require('ppframe.php');

#user table fields
$ppsql = new dbsql();
$userfields = $ppsql -> GetFieldList($rtc['passport_table']);
#

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_import_main');
?>