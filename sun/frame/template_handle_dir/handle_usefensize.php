<?php
/**
 * ʹ�÷Ŵ�������
 *
 * @param number $fen �Ŵ��ķ���
 * @param number $fensize �Ŵ���
 * @param number $len ������С�����λ��
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