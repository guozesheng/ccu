<?php
/**
 * 跨模块调用准备接口，仅仅用在模板里面
 *
 * @param string $m 模块标志
 * @param string $style 风格标志
 * @param string $language 语言函数
 */
!defined('MODULE') && exit('Forbidden');
function UseModuleInterface($m,$style='',$language='') {
	$module = $GLOBALS['module_fields'];
	#设置模块入口
	$GLOBALS['_INMODULE_'] = $module[$m] ? $module[$m] : $m ;
	if ($style) {
		$GLOBALS['_INSTYLE_'] = $style;
	}
	if ($language) {
		$GLOBALS['_INLANG_'] = $language;
	}
}