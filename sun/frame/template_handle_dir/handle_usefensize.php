<?php
/**
 * 使用放大倍数处理
 *
 * @param number $fen 放大后的分数
 * @param number $fensize 放大倍数
 * @param number $len 处理后的小数点后位数
 * @return float
 */
!defined('MODULE') && exit('Forbidden');
function UseFenSize($fen,$fensize=1,$len=2) {
	$fensize = intval($fensize);
	if ($fensize < 1) {
		$fensize = 1;
	}
	$fen = $fen / $fensize;
	if (is_float($fen)) {
		return sprintf("%.{$len}f",$fen);
	}else if (is_int($fen)) {
		return $fen;
	}else {
		return sprintf("%.{$len}f",$fen); 
	}
}