<?php
require('ppframe.php');

if (!$rtc['passport_message']) {
	ShowMessage('system.forbidden',-1,1);
}

Iimport('messagelister');
Iimport('page');
Iimport('template');
$tpl = new Template();
if(empty($box) || !in_array($box,array('in','out','del','sys'))) {
	$box = 'in';
}

if(!$action) {
	$tpl -> LanguageAssign('title','passport.message');
	$meslist = new MessageLister($passport->U_ID);
	$mesnum = $meslist -> ExecMyTotalRecord();
	$nonereadmesnum = $meslist -> ExecMyNoneReadRecord();
	if($box == 'in') {
		//未删除.
		$meslist -> SetBoxList();
		//收件箱.
		$meslist -> SetToId();
		//非系统消息
		$meslist -> SetSystem();
		$tpl -> Assign('boxname','inbox');
	}else if($box == 'out') {
		//发件箱.
		$meslist -> SetFromID();
		//保存在发件箱中的.
		$meslist ->SetStore();
		$meslist -> SetSystem();
		$tpl -> Assign('boxname','outbox');
	}else if($box == 'del') {
		//必须是删除了的.
		$meslist -> SetRemoveList();
		//必然是收件箱的
		$meslist -> SetToId();
		$meslist -> SetSystem();
		$tpl -> Assign('boxname','removebox');
	}else if($box == 'sys') {
		$meslist -> SetToId();
		$meslist -> SetSystem(1);
		$tpl -> Assign('boxname','sysbox');
	}else {
		exit('Forbidden');
	}
	$meslist -> CreatWhere();
	$maxnum = $meslist -> ExecTotalRecord();
	empty($pagesize) && $pagesize = 10;
	$p = new Page($maxnum,$page,$pagesize);
	$p -> SetDpages();
	$pagelink = $p -> GetPageLink();
	$meslist -> SetConfig(array('limit'=>$p->Limit));
	$meslist = $meslist -> GetList();
	$tpl -> DisPlay('message');
}else {
	define('message_safe',true);
	if(file_exists('message_'.$action.'.php')) {
		require('message_'.$action.'.php');
	}else {
		exit('Forbidden');
	}
}
?>