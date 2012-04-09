<?php
require('ppframe.php');
$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error');
}
Iimport('Element_Passport_ClassSh');
$element_passport_classsh = new Element_Passport_ClassSh();
$element_passport_classsh -> Load($id);
if (empty($element_passport_classsh -> E)) {
	ShowMessage('id.error');
}else {
	$element_classsh = $element_passport_classsh -> E;
}

switch ($action) {
	case 'del':	//删除
	if($element_passport_classsh -> DoRemove($id)) {
		if ($element_classsh['sh'] == 0 && $element_classsh['apmoney']) {	//返还申请资金
			AddMoney($element_classsh['apmoney'],$element_classsh['uid'],$element_classsh['apmtype'],'apply.class.payback.becauseof.del.id.'.$id);
		}
		ShowMessage('do.success');
	}else {
		ShowMessage('do.fail');
	}
	break;
	case 'pass':
	if ($element_classsh['sh'] != 0) {
		ShowMessage('connt.do');
	}
	Iimport('Element_Passport_Class');
	$element_passport_class = new Element_Passport_Class();
	$element_passport_class -> Load($element_classsh['classid']);
	if (empty($element_passport_class -> E)) {	// 不存在的班级
		if ($element_classsh['sh'] == 0 && $element_classsh['apmoney']) {	//返还申请资金
			AddMoney($element_classsh['apmoney'],$element_classsh['uid'],$element_classsh['apmtype'],'apply.class.payback.becauseof.del.id.'.$id);
		}
		ShowMessage('studentclass.not.exist');
	}else {
		Iimport('PassPort_User');
		#
		$temp_user = new PassPort_User();
		#
		$temp_user -> ReSet();
		$temp_user -> LoadFromDBUsePriID($element_classsh['uid']);
		if ($temp_user -> U_ID < 1) {
			ShowMessage('uid.error');
		}
		if ($element_passport_class -> E['ctype'] == 0) {	//自然班
			$temp_user -> SetUpdateInfo(
				array(
					'grade' => $element_passport_class -> E['grade'],
					'class' => $element_passport_class -> E['id'],
				)
			);
		}else {		//课程班
			$temp_user -> SetUpdateInfo(
				array(
					'classes' => ' ' . implode(' ',array_unique(array_merge(explode(' ',trim($temp_user->U['classes'])),array($element_classsh['classid'])))) . ' ',
				)
			);
		}
		if ($temp_user -> DoReRecord()) {
			$element_passport_classsh -> SetUpdate(
				array(
					'id' => $id,
					'sh' => 1,
				)
			);
			$element_passport_classsh -> DoUpdate();
			ShowMessage('do.success',$_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : -1);
		}else {
			ShowMessage('do.fail');
		}
	}
	break;
	
	case 'refuse':	//拒绝!
	if ($element_classsh['sh'] != 0) {
		ShowMessage('connt.do');
	}
	$element_passport_classsh -> SetUpdate(
		array(
			'id' => $id,
			'sh' => 2,
		)
	);
	if ($element_passport_classsh -> DoUpdate()) {
		if ($element_classsh['sh'] == 0 && $element_classsh['apmoney']) {	//返还申请资金
			AddMoney($element_classsh['apmoney'],$element_classsh['uid'],$element_classsh['apmtype'],'apply.class.payback.becauseof.refuse.id.'.$id);
		}
		ShowMessage('do.success',$_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : -1);
	}else {
		ShowMessage('do.fail');
	}
	break;
	default:
	ShowMessage('action.error');
	break;
}
?>
