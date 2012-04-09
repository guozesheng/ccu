<?php
/**
 * �˳���½Interface
 * �������ļ�����ʵ����֤�������˳���½
 * Э�������
 * 1��$ppf_api_userdata �û��������� �� => ֵ ��
 * 2��$ppf_api_return �˳���½�󷵻�URL
 * 
 * Э���������˼�ǣ��ڰ�����Interface ֮ǰ��׼����������������
 *
 * ����Ҫ�����ǣ�
 * 1��define ppfapi_safe
 * 2��׼�����û���������$ppf_api_userdata ����ѡ��
 * 3��׼���÷���URL$ppf_api_return
 */

#�������ļ�ǰ������define ppfapi_safe
!defined('ppfapi_safe') && exit('Forbidden');

require(dirname(__FILE__) . '/frame/inc_ppf_api.php');
require(dirname(__FILE__) . '/config/c_server.php');

if ($ppf_api_config_server['clients'][1]['key'] && $ppf_api_config_server['clients'][1]['api']) {
	$ppf_api = new PPF_Api();
	$ppf_api -> SetKey($ppf_api_config_server['clients'][1]['key']);
	$ppf_api -> SetParams(
		array(
			'action' => 'logout',
			'data' => $ppf_api_userdata,	//Э�����,�˳���Э�����һ��ֻ���û�UID��Username������ʲô������Ҫ��
			'time' => time(),
			'lang' => $ppf_api_config_server['language'],
			'step' => 1,
			'server' => $ppf_api_config_server['clients'][2] ? $ppf_api_config_server['server'] : 0,
			'return' => $ppf_api_return,	//Э�����
		),1,1
	);
	
	
	if ($ppf_api_usepost) {
		$ppf_api -> CreateApi($ppf_api_config_server['clients'][1]['api']);
	}else {
		#ppfapi ��ת��ַ ��Ŀ��Ӧ���нػ���ת
		$ppf_api_gourl = $ppf_api -> CreateApiUrl($ppf_api_config_server['clients'][1]['api']);
	}
}else {
	//do nothing!
}
?>