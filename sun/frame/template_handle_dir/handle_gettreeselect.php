<?php
/**
 * ����һ���ݼ��ṹ����Select���νṹ
 *
 * @param array $treearray	���Select��array
 * @param string $insname	����
 * @param mix $selected		ѡ�����ֵ
 * @param string $key		Key���
 * @param string $val		Val���
 * @param string $tk		ѡ��ǰ�����ӵı��
 * @param string $show		��ʾ���
 * @param bool $inner		�Ƿ�ֻ����Option��
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function GetTreeSelect($treearray,$insname='select',$selected=0,$key='',$val='',$show='',$action='',$tk='',$inner=false) {
	$action = str_replace("&quot;",'"',$action);
	$header = "<select name=\"$insname\" id=\"$insname\" $action>\r\n";
	$footer = '</select>';
	
	$body = '';
	
	if (is_array($treearray)) {
		foreach ($treearray as $k => $v) {
			if ($selected == $v[$key]) {
				$body .= "<option value=\"{$v[$key]}\" selected>$tk{$v[$val]}</option>\r\n";
			}else {
				$body .= "<option value=\"{$v[$key]}\">$tk{$v[$val]}</option>\r\n";
			}
			if ($v['sun'] && is_array($v['sun'])) {
				$body .= GetTreeSelect($v['sun'],'',$selected,$key,$val,'','','&nbsp;&nbsp;&nbsp;'.$tk ,true);
			}
		}
	}
	if ($inner) {
		return $body;
	}else {
		if ($show) {
			return $header."<option value=\"\">$show</option>\r\n".$body.$footer;
		}
		return $header.$body.$footer;
	}
}