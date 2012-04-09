<?php
require(dirname(__FILE__)."/../rte.php");
define('MODULE','admin');
define('WORK_DIR',ROOT.MODULE."/");
Iimport('Admin_User');
$Admin = new Admin_User();
$Admin ->ExitLogin();
ShowMessage('logout.success','login.php');
?>