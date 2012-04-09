<?php
require(WORK_DIR.'config/baseconfig.php');
Iimport('PassPort_User');
$passport = new PassPort_User();
$passport -> ReSet();

$passport -> ExitLogin();

?>