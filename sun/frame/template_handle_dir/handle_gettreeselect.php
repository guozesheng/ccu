<?php
/**
 * 根据一个梯级结构生成Select树形结构
 *
 * @param array $treearray	获得Select的array
 * @param string $insname	表单名
 * @param mix $selected		选中项的值
 * @param string $key		Key标记
 * @param string $val		Val标记
 * @param string $tk		选项前面增加的标记
 * @param string $show		提示语句
 * @param bool $inner		是否只返回Option项
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