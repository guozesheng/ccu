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
		//δɾ��.
		$meslist -> SetBoxList();
		//�ռ���.
		$meslist -> SetToId();
		//��ϵͳ��Ϣ
		$meslist -> SetSystem();
		$tpl -> Assign('boxname','inbox');
	}else if($box == 'out') {
		//������.
		$meslist -> SetFromID();
		//�����ڷ������е�.
		$meslist ->SetStore();
		$meslist -> SetSystem();
		$tpl -> Assign('boxname','outbox');
	}else if($box == 'del') {
		//������ɾ���˵�.
		$meslist -> SetRemoveList();
		//��Ȼ���ռ����
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