<?php
/**
 * 批量生成充值卡
 *
 * @param number $mnum 充值卡货币数量
 * @param number $mtype 充值货币种类
 * @param number $num 生成充值卡数目
 * @param number $timeout 过期时间
 */
function CreateMoneyCards($mnum,$mtype,$num=100,$timeout=0){
	if ($mnum < 1 || !in_array($mtype,$GLOBALS['rtc']['passport_money'])) {
		return false;
	}
	$timeout = intval($timeout);
	Iimport('MoneyCard');
	Iimport('Element_Passport_Moneycard');
	Iimport('Lister_Passport_Moneycard');
	$moneycard = new MoneyCard();
	$moneycard -> SetCardPre(GetMyDate('Y'));
	$element_passport_moneycard = new Element_Passport_Moneycard();
	$lister_passport_moneycard = new Lister_Passport_Moneycard();
	$lister_passport_moneycard -> EnableCache(false);
	$lister_passport_moneycard -> SetConfig(
		array(
			'els' => array('max(`cardno`) as maxcard'),
		));
	
	$lister_passport_moneycard -> GetList();
	if ($lister_passport_moneycard -> Larray[0]['maxcard']) {
		$start = intval(substr($lister_passport_moneycard -> Larray[0]['maxcard'],4,-1));
	}else {
		$start = 0;
	}
	$i = 0;
	while ($i<$num) {
		#生成卡号
		$moneycard -> CreatOneCard($start);
		$start ++;
		#验证重复卡号
		$lister_passport_moneycard -> ClearWhere();
		$lister_passport_moneycard -> SetWhere("`cardno`=$moneycard->CardNO");
		$lister_passport_moneycard -> CreatWhere();
		if ($lister_passport_moneycard -> ExecTotalRecord() > 0) {
			continue;
		}
		#验证重复卡号
		while (true) {
			#验证重复密码
			$lister_passport_moneycard -> ClearWhere();
			$lister_passport_moneycard -> SetWhere("`cardno`=$moneycard->CardNO OR `password`=$moneycard->Password");
			$lister_passport_moneycard -> CreatWhere();
			if ($lister_passport_moneycard -> ExecTotalRecord() > 0) {
				$moneycard -> CreatOnePassword();
			}else {
				break;
			}
			#验证重复密码
		}
		#找到一个卡号和密码都不重复的充值卡
		$element_passport_moneycard -> SetInsert(
			array(
			'cardno' => $moneycard->CardNO,
			'password' => $moneycard->Password,
			'createtime' => $GLOBALS['timestamp'],
			'date' => GetMyDate('Ymd',$GLOBALS['timestamp']),
			'timeout' => $timeout ? $GLOBALS['timestamp'] + $timeout : 0,
			'mnum' => $mnum,
			'mtype' => $mtype,
			'state' => 0,
		));
		if($element_passport_moneycard -> DoRecord()) {
			$i ++;
		}
	}
	return true;
}

/**
 * 获得一个Buss的 ID 序列
 *
 * @param number $id
 * @return string
 */
function GetBussIds($id) {
	$id = intval($id);
	if($id<1) return '';
	!class_exists('Element_Passport_Buss') && Iimport('Element_Passport_Buss');
	$element_element = new Element_Passport_Buss();
	$element_element -> SetCacheState(false);
	$element_element -> Load($id);
	if($element_element -> E) {
		if($element_element->E['istop'] || $element_element->E['upid'] == 0) {
			return $id;
		}else {
			return GetBussIds($element_element->E['upid']) . ',' . $id;
		}
	}
}