<?php
/**
 * ���Examģ��༭��
 *
 * @param string $instance ����
 * @param string $value	����ʼֵ
 * @param string $heigth �༭���߶�
 * @param string $width	�༭�����
 * @param string $toolbar �༭�����
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