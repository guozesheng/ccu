<?php
/**
 * ���ɼ�Select�ṹ
 *
 * @param array $array	���Select��array
 * @param string $insname	����
 * @param mix $selected		ѡ�����ֵ
 * @param string $show		��ʾ���
 * @param string $action	����
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