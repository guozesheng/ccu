<?php
#��������
$tixing_db = array(
	1 => 'tx_danxuan',	//base
	2 => 'tx_duoxuan',	//base
	3 => 'tx_jianda',	//������	base
	4 => 'tx_xinli',	//base (up)
	5 => 'tx_panduan',	//���ڵ�ѡ
	6 => 'tx_tiankong',	//���ڼ��
	7 => 'tx_zuhe',		//�������
);
#���Ͷ�Ӧ������������
$tixing_db_base = array(
	1 => 1,
	2 => 2,
	3 => 3,
	4 => 4,
	5 => 1,
	6 => 3,
	7 => 7,
);
//�������������
$tixing_db_xinliti = array(
	'4' => 4,
);

/**
 * ������ͻ�����ID
 *
 * @param number $t
 * @return number
 */

function GetTxBaseType($t) {
	if ($GLOBALS['tixing_db_base'][$t]) {
		return $GLOBALS['tixing_db_base'][$t];
	}else {	//Ĭ�ϵ�ѡ��
		return 1;
	}
}

function GetTxName($t) {
	return GetMsg($GLOBALS['tixing_db'][$t]);
}
?>