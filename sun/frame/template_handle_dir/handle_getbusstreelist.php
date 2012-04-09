<?php
/**
 * 获得Buss的列表 根据上级栏目id
 * 
 * @param  number $top	上级栏目ID
 * @return array
 */
function GetBussTreeList($top=0,$hard=false) {
	$top = intval($top);
	!class_exists('Lister_Passport_Buss') && Iimport('Lister_Passport_Buss');
	$lister_lister = new Lister_Passport_Buss();
	$lister_lister -> CreateWhere(array("`upid`='$top'"));
	if ($hard) {
		$lister_lister -> EnableCache(false);
	}
	$rtlister = $lister_lister -> GetLister();

	if ($rtlister && is_array($rtlister)) {
		foreach ($rtlister as $k => $v) {
			if ($v['id'] == $top) {
				continue;		//防止死循环
			}
			$rr = GetBussTreeList($v['id']);
			if ($rr && is_array($rr)) {
				$rtlister[$k]['sun'] = $rr;
			}
		}
		return $rtlister;
	}else {
		return array();
	}
}