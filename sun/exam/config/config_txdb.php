<?php
#题型配置
$tixing_db = array(
	1 => 'tx_danxuan',	//base
	2 => 'tx_duoxuan',	//base
	3 => 'tx_jianda',	//主观题	base
	4 => 'tx_xinli',	//base (up)
	5 => 'tx_panduan',	//基于单选
	6 => 'tx_tiankong',	//基于简答
	7 => 'tx_zuhe',		//组合题型
);
#题型对应基础题型配置
$tixing_db_base = array(
	1 => 1,
	2 => 2,
	3 => 3,
	4 => 4,
	5 => 1,
	6 => 3,
	7 => 7,
);
//心理测试题配置
$tixing_db_xinliti = array(
	'4' => 4,
);

/**
 * 获得题型基础库ID
 *
 * @param number $t
 * @return number
 */

function GetTxBaseType($t) {
	if ($GLOBALS['tixing_db_base'][$t]) {
		return $GLOBALS['tixing_db_base'][$t];
	}else {	//默认单选题
		return 1;
	}
}

function GetTxName($t) {
	return GetMsg($GLOBALS['tixing_db'][$t]);
}
?>