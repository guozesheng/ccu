<?php
require('ppframe.php');

$file = WORK_DIR.'config/jifenchange_config.php';

@include($file);

if (empty($_jifen_change)) {
	$_jifen_change = array();
}
unset($_jifen_change[$id]);

if (empty($_jifen_change)) {
	$_jifen_change = array();
}

$str = PP_var_export($_jifen_change);

$str = "<?php\r\n \$_jifen_change = $str ;\r\n?>\r\n";

WriteFile($str,$file);

ShowMessage('do.success');
?>