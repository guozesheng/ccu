<?php
require('ppframe.php');

$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error',-1);
}

Iimport('PassPort_User');
#
$temp_user = new PassPort_User();
#

$temp_user -> ReSet();
$temp_user -> LoadFromDBUsePriID($id);
if ($temp_user -> U_ID<1) {
	ShowMessage('user.not.exist');
}
if ($temp_user -> RemoveUser($id)) {
	ShowMessage('delete.user.success','user_main.php');
}else {
	ShowMessage('delete.user.fail');
}
?>