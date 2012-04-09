<?php
/**
 * 目录,文件操作相关
 * 
 */
header("Cache-Control:no-cache");
require('ppframe.php');
Iimport('filemanager');
if (isset($Submit)) {
	if(empty($name)) {
		ShowMsg('lack.file.name',-1);
		exit;
	}
	$fm = new FileManager();
	$fm -> SetDir($dir);
	$fm -> SetFile($name);
	if(!file_exists($fm->File) && !eregi('create',$do)) {
		ShowMsg("file.not.exist",-1);
		exit;
	}
	switch ($do) {
		case 'edit'://编辑(文本文件)
			if(!eregi("(.txt|.htm|.html|.php|.tpl)$",$name)) {
				ShowMsg('connt.do',-1);
				exit;
			}
			if($fm->Edit($text)) {
				ShowMsg('edit.success',-1);
				exit;
			}else {
				ShowMsg('edit.fail',-1);
				exit;
			}
			break;
		case 'rename'://改名(文件、目录)
			if(empty($newname)) {
				ShowMsg('value.error',-1);
				exit;
			}
			if (file_exists($fm->Dir.'/'.trim($newname))) {
				ShowMsg("target.file.already.exist",-1);
				exit;
			}
			if ($fm -> Rename($fm->File,$fm->Dir.'/'.trim($newname))) {
				ShowMsg('do.success',"file_manage_main.php?name=".urlencode($newname)."&dir=".urlencode($dir));
				exit;
			}else {
				ShowMsg('do.fail',-1);
				exit;
			}
			break;
		case 'move'://移动(文件)
			if (ereg('\.\.',$newname)) {
				ShowMsg('error.url',-1);
				exit;
			}
			if(file_exists($fm->BaseDir.'/'.$newname.'/'.$name)) {
				ShowMsg('target.dir.already.exist.same.file',-1);
				exit;
			}
			if(FileManager::Rename($fm->File,$fm->BaseDir.'/'.$newname.'/'.$name)) {
				ShowMsg('do.success',"file_manage_main.php?dir=".urlencode($newname)."&name=".urlencode($name)."#".$name);
				exit;
			}else {
				ShowMsg('do.fail',-1);
				exit;
			}
			break;
		case 'del'://删除(文件、目录)
			if(FileManager::Delete($fm->Dir.'/'.$name)) {
				ShowMsg('do.success',"file_manage_main.php?dir=".urlencode($dir));
				exit;
			}else {
				ShowMsg('do.fail',-1);
				exit;
			}
			break;
		case 'createfile':
			if(file_exists($fm->Dir.'/'.$name)) {
				ShowMsg('file.already.exist',-1);
				exit;
			}
			if(Template::WriteFile($text,$fm->Dir.'/'.$name)) {
				ShowMsg('create.file.success',"file_manage_main.php?dir=".urlencode($dir)."&name=".urlencode($name)."#".urlencode($name));
				exit;
			}else {
				ShowMsg('create.file.fail',-1);
				exit;
			}
			break;
		case 'createdir':
			if(is_dir($fm->Dir.'/'.$name)) {
				ShowMsg('dir.already.exist',-1);
				exit;
			}
			if(false !== mkdir($fm->Dir.'/'.$name,777)) {
				ShowMsg('create.dir.success',"file_manage_main.php?dir=".urlencode($dir)."&name=".urlencode($name));
				exit;
			}else {
				ShowMsg('create.dir.fail',-1);
				exit;
			}
			break;
		default:
			break;
	}
}else {
	Iimport('template');
	$tp = new Template();
	$fm = new FileManager();
	$fm -> SetDir($dir);
	$fm -> SetFile($name);
	if(!file_exists($fm->File) && !eregi('create',$do)) {
		ShowMsg("file.not.exist",-1);
		exit;
	}
	switch ($do) {
		case 'edit'://编辑(文件)
			//过滤文件格式
			if(!eregi("(.txt|.htm|.html|.php|.tpl)$",$name)) {
				ShowMsg('connt.do',-1);
				exit;
			}
			$content = Template::ReadFile($fm->File);
			$content = eregi_replace("<textarea","< textarea",$content);
			$content = eregi_replace("</textarea","< /textarea",$content);
			$content = eregi_replace("<form","< form",$content);
			$content = eregi_replace("</form","< /form",$content);
			$tp -> Assign('text',$content);
			break;
		case 'rename':
			break;
		case 'move':
			break;
		case 'del':
			break;
		case 'createfile':
			break;
		case 'createdir':
			break;
		default:
			break;
	}
	$template = "file_manage_do_{$do}.htm";
	$tp -> Assign('workdir',$fm->XDir);
	$tp -> DisPlay($template);
}
?>