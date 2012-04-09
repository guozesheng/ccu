<?php
require('ajax_ppframe.php');
$cid = intval($cid);

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('ajax_loadzhuanye');
?>