<?php
/**
 * ��ģ�����ȡ���ӿڣ���������ģ������
 *
 */
!defined('MODULE') && exit('Forbidden');
function UnUseModuleInterface() {
	$GLOBALS['_INMODULE_'] = '';
	$GLOBALS['_INSTYLE_'] = '';
	$GLOBALS['_INLANG_'] = '';
}