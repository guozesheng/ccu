<?php
require('ppframe.php');

$file = WORK_DIR.'config/jifenchange_config.php';

@include($file);

if (!in_array($mcf,$rtc['passport_money']) || !in_array($mct,$rtc['passport_money'])) {
	ShowMessage('error.money');
}

if ($mcf == $mct) {
	ShowMessage('money.same.connt.do');
}

$fn = intval($fn);
$tn = intval($tn);

if (!$fn || !$tn) {
	ShowMessage('money.num.error');
}

$_jifen_change[$mcf.'_'.$mct] = array(
	'f' => $mcf,
	't' => $mct,
	'fn' => $fn,
	'tn' => $tn,
);

$str = PP_var_export($_jifen_change);

$str = "<?php\r\n \$_jifen_change = $str ;\r\n?>\r\n";

WriteFile($str,$file);

ShowMessage('do.success');
?>