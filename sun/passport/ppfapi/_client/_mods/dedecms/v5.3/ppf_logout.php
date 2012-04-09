<?php
require_once(DEDEINC."/memberlogin.class.php");
$keeptime = isset($keeptime) && is_numeric($keeptime) ? $keeptime : -1;
$cfg_ml = new MemberLogin($keeptime);

$cfg_ml -> ExitCookie();
?>