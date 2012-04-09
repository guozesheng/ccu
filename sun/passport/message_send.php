<?php
!defined('message_safe') && exit('Forbidden');
if(isset($Submit)) {
	if(empty($to)) {
		ShowMessage('user.empty');
		exit;
	}
	if(strlen($subject) < 4) {
		ShowMessage('subject.too.short');
		exit;
	}
	if(strlen($body) < 10) {
		ShowMessage('context.too.short');
		exit;
	}
	Iimport('message');
	$message = new Message($passport->U_ID);
	$send = $message -> WriteUseName($to,$subject,$body,$store);
	if ($send === -2) {
		ShowMessage('connt.send.to.self');
		exit;
	}else if($send === -1) {
		ShowMessage('user.not.exist');
		exit;
	}else if($send==1) {
		ShowMessage('success','message.php');
		exit;
	}else {
		ShowMessage('fail');
		exit;
	}
}else {
	$tpl -> LanguageAssign('title','passport.message.send');
	$tpl -> DisPlay('message_send');
}
?>