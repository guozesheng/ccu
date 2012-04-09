<?php
/**
 * ʹ��һ��ģ��,���һ���Ծ��б����ͼ
 *
 * @param string $tpl		ģ���ļ�
 * @param number $classid	����id
 * @param number $tikuid	���id
 * @param number $limit		��������
 * @param string $sjtitle	�Ծ����
 * @param number $money		�Ƿ���Ҫ֧������
 * @param number $jnum		�Ƿ��н���
 * @param number $orderby	����ʽ
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function GetShijuanListerView($temp,$classid,$tikuid=0,$limit=0,$sjtitle='',$money=0,$jnum=0,$orderby='') {
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
		'allowgroups',
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
	$lister_exam_shijuan -> EnableCache(true);
	$tikuid = intval($tikuid);
	$classid = intval($classid);
	if ($tikuid>0) {
		$lister_exam_shijuan -> SetWhere("`tiku`='$tikuid'");
	}
	$classid = intval($classid);
	if ($classid>0) {
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
		$limit = str_replace('-',',',$limit);
		$lister_exam_shijuan -> SetLimit($limit);
	}
	$list = $lister_exam_shijuan -> GetList();
	
	!class_exists('Template') && Iimport('Template');
	$tpl = new Template();
	if ($GLOBALS['_INMODULE_']) {	#��δ��������ģ����������⡣
		@include(ROOT.$GLOBALS['_INMODULE_'].'/config/baseconfig.php');
		$tpl -> Assign('_f_ub',$base_config['exam_root']);
	}
	$tpl -> Assign('_f_l',$list);
	$rtstr = $tpl -> Parse($temp);
	return $rtstr;
}