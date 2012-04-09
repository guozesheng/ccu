<?php
require('ajax_ppframe.php');

$go = $_SERVER['HTTP_REFERER'];
Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('ajax_userstate');

?>