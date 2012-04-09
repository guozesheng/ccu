<?php
/**
 * 生成简单Select结构
 *
 * @param array $array	获得Select的array
 * @param string $insname	表单名
 * @param mix $selected		选中项的值
 * @param string $show		提示语句
 * @param string $action	动作
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function GetEasySelect($array,$insname='select',$selected=0,$show='',$action='') {
	$action = str_replace("&quot;",'"',$action);
	$header = "<select name=\"$insname\" id=\"$insname\" $action>\r\n";
	$footer = '</select>';
	
	$body = '';
	
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			if ($selected == $v) {
				$body .= "<option value=\"{$v}\" selected>{$v}</option>\r\n";
			}else {
				$body .= "<option value=\"{$v}\">{$v}</option>\r\n";
			}
		}
	}
	if ($show) {
		return $header."<option value=\"\">$show</option>\r\n".$body.$footer;
	}
	return $header.$body.$footer;
}