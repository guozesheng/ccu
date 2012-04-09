<?php
require('ppframe.php');
$id = intval($id);
if ($id<1) {
	ShowMessage('id.error');
}
if (strlen($gpname) < 2) {
	ShowMessage('group.name.too.short');
}

$file = ROOT.'config/usergroup_config.php';

$_usergroup[$id] = array(
	'id' => $id,
	'name' => $gpname,
);
foreach ($_usergroup as $k => $v) {
	if ($v['id'] <= 0) {
		unset($_usergroup[$k]);
		break;
	}
}
$str = PP_var_export($_usergroup);

$str = "<?php\r\n \$_usergroup = $str ;\r\n?>";

WriteFile($str,$file);

ShowMessage('do.success');
?>