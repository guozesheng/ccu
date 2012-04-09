<?php
!defined('MODULE') && exit('Forbidden');
function GetRadioLister($larray,$insname='',$checked='',$key='',$val='',$aval='') {
	$body = "";
	if (is_array($larray)) {
		foreach ($larray as $k => $v) {
			if ($checked && $v[$key] == $checked) {
				$body .= " <input name=\"$insname\" type=\"radio\" value=\"$v[$key]\" checked=\"checked\"/>$v[$val]";
			}else {
				$body .= " <input name=\"$insname\" type=\"radio\" value=\"$v[$key]\"/>$v[$val]";
			}
			if ($aval && $v[$aval]) {
				$body .= "($v[$aval]) ";
			}else {
				$body .= ' ';
			}
		}
	}
	return $body;
}