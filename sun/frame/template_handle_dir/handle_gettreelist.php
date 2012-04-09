<?php
/**
 * 递归的获得一个分级列表
 *
 * @param array $array
 * @param string $temp
 * @return string
 */
function GetTreeList($array,$temp,$class='treelist') {
	if($class) {
		$r = "<ul class=\"$class\">";
	}
	else {
		$r = "<ul>";
	}
	$temp || $temp = 'fun_gettreelist';
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			$tpl = new Template();
			$tpl -> Assign('_f_a',$v);
			$r .= $tpl -> ParseFile($temp);
			if (is_array($v['sun'])) {
				$r .= GetTreeList($v['sun'],$temp,$class='sun');
			}
		}
	}
	return $r.'</ul>';
}