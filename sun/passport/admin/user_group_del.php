<?php
require('ppframe.php');

$id = intval($id);

if ($id < 1) {
	ShowMessage('id.error');
}

$file = ROOT.'config/usergroup_config.php';

if (empty($_usergroup)) {
	$_usergroup = array();
}
foreach ($_usergroup as $k => $v) {
	if ($v['id'] == $id || $v['id'] <= 0) {
		unset($_usergroup[$k]);
	}
}

if (empty($_usergroup)) {
	$_usergroup = array();
}

$str = PP_var_export($_usergroup);

$str = "<?php\r\n \$_usergroup = $str ;\r\n?>";

WriteFile($str,$file);

ShowMessage('do.success');
?>