<?php
!defined('MODULE') && exit('Forbidden');
function GetEasyCheckBoxLister($larray,$insname='',$checked='') {
	$body = "";
	if (is_array($larray)) {
		foreach ($larray as $k => $v) {
			if ($checked && ereg(' ' . $v . ' ',$checked)) {
				$body .= " <input name=\"$insname\" type=\"checkbox\" value=\"$v\" checked=\"checked\"/>$v ";
			}else {
				$body .= " <input name=\"$insname\" type=\"checkbox\" value=\"$v\"/>$v ";
			}
			$body .= ' ';
		}
	}
	return $body;
}