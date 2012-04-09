<?php
require('ppframe.php');

Iimport('Lister_AdminUser');
$lister_adminuser = new Lister_AdminUser();
$maxnum = $lister_adminuser -> ExecTotalRecord();
Iimport('Page');

$pagesize = 10;

$p = new Page($maxnum,$page,$pagesize);

$p -> SetDpages();
$pagelink = $p -> GetPageLink();

$lister_adminuser -> SetLimit($p -> Limit);

$lister_adminuser = $lister_adminuser -> GetList();

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('sys_user_main');

?>