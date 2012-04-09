<?php
require('ppframe.php');
Iimport('filemanager');
Iimport('template');
empty($file_root) && $file_root = ROOT;
empty($dir) && $dir = '';
empty($file) && $file = '';
$fm = new FileManager($file_root);
$fm -> Scan($dir,$file);

//echo "<pre>";
//print_r($fm);exit;

$tpl = new Template();
$tpl -> Assign('files',$fm->FileData);
$tpl -> Assign('dirs',$fm->DirData);
$tpl -> Assign('crunt_dir',urldecode($fm->XDir));
$tpl -> Assign('crunt_file',$fm->File);
$tpl -> DisPlay('file_manage_main.htm');
?>