<?php
/**
 * 跨模块调用取消接口，仅仅用在模板里面
 *
 */
!defined('MODULE') && exit('Forbidden');
function UnUseModuleInterface() {
	$GLOBALS['_INMODULE_'] = '';
	$GLOBALS['_INSTYLE_'] = '';
	$GLOBALS['_INLANG_'] = '';
}