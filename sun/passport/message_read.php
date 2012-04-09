<?php
!defined('message_safe') && exit('Forbidden');
if(empty($id)) {
	ShowMessage('id_empty');
	exit;
}
Iimport('message');
$message = new Message($passport->U_ID);
if(!$message -> Read($id)) {
	ShowMessage('not.your.message');
	exit;
}
$tpl -> LanguageAssign('title','passport.message.read');
$tpl -> Assign('message',$message->Elements);
$tpl -> DisPlay('message_read');
?>