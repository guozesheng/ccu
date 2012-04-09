<?php
!defined('MODULE') && exit('Forbidden');
function GetCheckBoxLister($larray,$insname='',$checked='',$key='',$val='',$aval='') {
	$body = "";
	if (is_array($larray)) {
		foreach ($larray as $k => $v) {
			if ($checked && ereg(' ' . $v[$key] . ' ',$checked)) {
				$body .= " <input name=\"$insname\" type=\"checkbox\" value=\"$v[$key]\" checked=\"checked\"/>$v[$val]";
			}else {
				$body .= " <input name=\"$insname\" type=\"checkbox\" value=\"$v[$key]\"/>$v[$val]";
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