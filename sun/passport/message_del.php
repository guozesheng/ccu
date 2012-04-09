<?php
!defined('message_safe') && exit('Forbidden');
if(empty($id)) {
	ShowMessage('id.empty');
	exit;
}
Iimport('message');
$message = new Message($passport->U_ID);
if($box == 'in' || $box =='sys') {
	$do = $message -> DelMessage($id);
}else if ($box == 'del') {
	$do = $message -> DelMessage($id,2);
}else if($box=='out'){
	$do = $message -> DelStoreMessage($id);
}else {
	$do = false;
}
if($do === -1) {
	ShowMessage('do.no.right',-1);
	exit;
}else if($do){
	ShowMessage('do.success','message.php');
	exit;
}else {
	ShowMessage('do.fail','message.php');
	exit;
}
?>