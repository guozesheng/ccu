<?php
define('UC_CONNECT', 'mysql');				// ���� UCenter �ķ�ʽ: mysql/NULL, Ĭ��Ϊ��ʱΪ fscoketopen()
											// mysql ��ֱ�����ӵ����ݿ�, Ϊ��Ч��, ������� mysql

//���ݿ���� (mysql ����ʱ, ����û������ UC_DBLINK ʱ, ��Ҫ�������±���)
//UCenter ���ò���
define('UC_DBHOST', 'localhost'); 			// UCenter ���ݿ�����
define('UC_DBUSER', 'root'); 			// UCenter ���ݿ��û���
define('UC_DBPW', 'hust'); 				// UCenter ���ݿ�����
define('UC_DBNAME', 'uct'); 			// UCenter ���ݿ�����
define('UC_DBCHARSET', 'gbk'); 		// UCenter ���ݿ��ַ���
define('UC_DBTABLEPRE', '`uct`.uc_'); 	// UCenter ���ݿ��ǰ׺
define('UC_DBCONNECT', '0'); 		// UCenter ���ݿ�־����� 0=�ر�, 1=��

//ͨ�����
define('UC_KEY', 'Wc3cb2Xf69q5t15e50A8Meudvc57Z8PfR2ZeF2g3aeW0maP687y4p7W7Mdf151Fc');				// �� UCenter ��ͨ����Կ, Ҫ�� UCenter ����һ��
define('UC_API', 'http://www.uct.test');				// UCenter �� URL ��ַ, �ڵ���ͷ��ʱ�����˳���
define('UC_CHARSET', 'gbk');		// UCenter ���ַ���
define('UC_IP', '');					// UCenter �� IP, �� UC_CONNECT Ϊ�� mysql ��ʽʱ, ���ҵ�ǰӦ�÷�������������������ʱ, �����ô�ֵ
define('UC_APPID', '5');			// ��ǰӦ�õ� ID
?>