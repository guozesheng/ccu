<?php
/**
 * ��ģ�����׼���ӿڣ���������ģ������
 *
 * @param string $m ģ���־
 * @param string $style ����־
 * @param string $language ���Ժ���
 */
!defined('MODULE') && exit('Forbidden');
function UseModuleInterface($m,$style='',$language='') {
	$module = $GLOBALS['module_fields'];
	#����ģ�����
	$GLOBALS['_INMODULE_'] = $module[$m] ? $module[$m] : $m ;
	if ($style) {
		$GLOBALS['_INSTYLE_'] = $style;
	}
	if ($language) {
		$GLOBALS['_INLANG_'] = $language;
	}
}