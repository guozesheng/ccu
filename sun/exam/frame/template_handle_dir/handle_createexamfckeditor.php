<?php
/**
 * 获得Exam模块编辑器
 *
 * @param string $instance 表单名
 * @param string $value	表单初始值
 * @param string $heigth 编辑器高度
 * @param string $width	编辑器宽度
 * @param string $toolbar 编辑器风格
 * @param string $action 
 * @return string
 */
!defined('MODULE') && exit('Forbidden');
function CreateExamFCKEditor($instance,$value='',$heigth='100',$width='90%',$toolbar='PPBasic',$action='') {
	!class_exists('PPExamEditor') && Iimport('PPExamEditor');
	$ppexameditor = new PPExamEditor($instance,$value,$action);
	!in_array($toolbar,array('PPframe','PPBasic')) && $toolbar = 'PPBasic';
	if (!defined('Admin_Safe')) {
		$toolbar = 'PPBasic';
	}
	$width && $ppexameditor -> Width = $width;
	$heigth && $ppexameditor -> Height = $heigth;
	$ppexameditor -> ToolbarSet = $toolbar;
	return $ppexameditor -> CreateHtml();
}