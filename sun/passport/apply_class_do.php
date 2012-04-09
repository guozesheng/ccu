<?php
require('ppframe.php');
$id = intval($id);
if ($id < 1) {
	ShowMessage('id.error');
}
Iimport('Element_Passport_Class');
$element_passport_class = new Element_Passport_Class();
$element_passport_class -> EnableCache(false);
$element_passport_class -> Load($id);
if (!$element_passport_class -> E) {
	ShowMessage('id.error');
}
if (!$element_passport_class -> E['allowapply']) {
	ShowMessage('not.allow');
}
if ($element_passport_class -> E['ctype'] == 0) {
	if ($passport -> U['class'] > 0) {
		ShowMessage('already.have.a.studentclass');
	}
}else {
	if (ereg(" $id ",$passport -> U['classes'])) {
		ShowMessage('already.in.studentclass');
	}
}

#apply

if ($element_passport_class -> E['apmoney']) {	//需要花费金钱
	$usemoney = UseMoney($element_passport_class->E['apmoney'],$passport->U_ID,$element_passport_class->E['apmtype'],'apply.class.use.id.'.$id);
	if ($usemoney != 1) {
		ShowMessage('pay.' . $money_types[$element_passport_class->E['apmtype']] . '.fail');
	}
}

if ($element_passport_class -> E['applysh']) {		//需要审核
	Iimport('Lister_Passport_ClassSh');
	$lister_passport_classsh = new Lister_Passport_ClassSh();
	$lister_passport_classsh -> SetWhere("`uid`=$passport->U_ID");
	$lister_passport_classsh -> SetWhere("`classid`=$id");
	
	$lister_passport_classsh -> CreateWhere();
	$lister_passport_classsh -> EnableCache(false);
	if ($lister_passport_classsh -> GetTotalRecord() > 0) {
		ShowMessage('already.apply');
	}
	
	Iimport('Element_Passport_ClassSh');
	$element_passport_classsh = new Element_Passport_ClassSh();
	$element_passport_classsh -> SetInsert(
		array(
			'uid' => $passport -> U_ID,
			'classid' => $id,
			'time' => $timestamp,
			'sh' => 0,
		)
	);
	if ($element_passport_classsh -> DoRecord()) {
		ShowMessage('apply.success.please.wait.admin.sh');
	}else {
		if ($element_passport_class -> E['apmoney']) {
			AddMoney($element_passport_class->E['apmoney'],$passport->U_ID,$element_passport_class->E['apmtype'],'apply.class.payback.becauseof.fail.id.'.$id);
		}
		ShowMessage('apply.fail');
	}
}else {		//无需审核,直接加入
	if ($element_passport_class -> E['ctype'] == 0) {	//加入自然班
		$passport -> SetUpdateInfo(array('class' => $id,'grade'=>$element_passport_class -> E['grade']));
		if($passport -> DoReRecord()) {
			ShowMessage('do.success','index.php');
		}else {
			if ($element_passport_class -> E['apmoney']) {
				AddMoney($element_passport_class->E['apmoney'],$passport->U_ID,$element_passport_class->E['apmtype'],'apply.class.payback.becauseof.fail.id.'.$id);
			}
			ShowMessage('do.fail');
		}
	}else {
		$classes = explode(' ',trim($passport->U['classes']));
		$classes[] = $id;
		$classes = ' ' . implode(' ',$classes) . ' ';
		$passport -> SetUpdateInfo(array('classes' => $classes));
		if($passport -> DoReRecord()) {
			ShowMessage('do.success','index.php');
		}else {
			if ($element_passport_class -> E['apmoney']) {
				AddMoney($element_passport_class->E['apmoney'],$passport->U_ID,$element_passport_class->E['apmtype'],'apply.class.payback.becauseof.fail.id.'.$id);
			}
			ShowMessage('do.fail');
		}
	}
}
?>