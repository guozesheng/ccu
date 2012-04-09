<?php
/**
 * ����Ծ��б�
 *
 * @param number $classid	����id
 * @param number $tikuid	���id
 * @param number $limit		��������
 * @param string $sjtitle	�Ծ����
 * @param number $money		�Ƿ���Ҫ֧������
 * @param number $jnum		�Ƿ��н���
 * @param number $orderby	����ʽ
 * @return array
 */
!defined('MODULE') && exit('Forbidden');
function GetShijuanLister($classid,$tikuid='',$limit=0,$sjtitle='',$money=0,$jnum=0,$orderby='') {
	Iimport('Lister_Exam_Shijuan','exam');
	$lister_exam_shijuan = new Lister_Exam_Shijuan();
	$els = array(
		'id',
		'title',
		'descrip',
		'dotime',
		'limitime',
		'start',
		'end',
		'fensize',
		'money',
		'mtype',
		'tiku',
		'class',
		'fix',
		'xinli',
		'random',
		'selfpf',
		'open',
		'protect',
		'cjbm',
		'protecttime',
		'ktimes',
		'ltime',
		'jrate',
		'jnum',
		'jmtype',
		'adminid',
				'okrate',
				'okban',
				'helplink',
				'dotimes',
				'dtuptime',
				'totals',
				'showadmin',
				'dostyle',
				'allowcolleges',
				'allowpros',
				'allowgrades',
				'allowgroups',
				'allowclass',
				'allowclasses',
		'del',
		'time',
	);
	$array = array(
		'els' => $els,
	);
	if ($orderby) {
		$array['orderby'] = $orderby;
	}
	$lister_exam_shijuan -> SetConfig($array);
//	$lister_exam_shijuan -> EnableCache(true);
	$tikuid = intval($tikuid);
	$classid = intval($classid);
	if ($tikuid>0) {
		$lister_exam_shijuan -> SetWhere("`tiku`='$tikuid'");
	}
	if ($classid) {
		$ids = GetClassSunIds($classid);
		if($ids){
			$lister_exam_shijuan -> SetWhere("`class` in ($ids)");
		}
	}
	if ($sjtitle) {
		$lister_exam_shijuan -> SetWhere("`title` like '%$sjtitle%'");
	}
	if ($money == 1) {
		$lister_exam_shijuan -> SetWhere("`money`>0");
	}else if ($money == 2) {
		$lister_exam_shijuan -> SetWhere("`money`=0");
	}
	if ($jnum == 1) {
		$lister_exam_shijuan -> SetWhere("`jnum`>0");
	}
	
	$lister_exam_shijuan -> SetWhere("`del`='0'");
	$lister_exam_shijuan -> SetWhere("`open`='1'");
	$lister_exam_shijuan -> CreateWhere();
	if ($limit) {
		$limit = str_replace('-',',');
		$lister_exam_shijuan -> SetLimit($limit);
	}
	return $lister_exam_shijuan -> GetList();
}