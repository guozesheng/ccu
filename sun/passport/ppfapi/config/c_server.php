<?php
/**
 * Server �������ļ�
 * ����clients ���飬����Client��
 */
$ppf_api_config_server = array(
	'language' => 'gbk',	//Сд Server �����԰汾
	'server' => 'http://www.ppframe.test/passport/ppfapi/server.php',	//���������֤��������server.php��ַ���뱣֤������ȷ
	'clients' => array(		//��1Ϊ����һ���������飬����Ŀ���ʡ�������ֵ��0�����ܳ��ֺܶ��ж�ʧ��������1Ϊ��㡣
	//������ Client�б�
		1 => array(
			'api' => 'http://www.pptest.test/passport/ppfapi/client.php',	//Client�˵�Client.php��ַ���뱣֤��ȷ
			'key' => 'abclasdkfa',			//ÿ��Client�����ò�ͬ��Key ����
		),
//		array(
//			'api' => 'http://www.dede53.test/ppfapi/client.php',	//Client�˵�Client.php��ַ���뱣֤��ȷ
//			'key' => 'abclasdkfa',			//ÿ��Client�����ò�ͬ��Key ����
//		),
	)
);
?>