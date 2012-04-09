<?php
require('ppframe.php');

@include(WORK_DIR.'config/install_config.php');

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('index_body');

?>