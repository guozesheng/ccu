<?php
require('ajax_ppframe.php');
$cid = intval($cid);
$grade = intval($grade);

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('ajax_loadclass');
?>