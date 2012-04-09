<?php
/**
 * Server 端配置文件
 * 增加clients 数组，增加Client端
 */
$ppf_api_config_server = array(
	'language' => 'gbk',	//小写 Server 端语言版本
	'server' => 'http://www.ppframe.test/passport/ppfapi/server.php',	//这是你的认证服务器的server.php地址！请保证配置正确
	'clients' => array(		//以1为起点的一个连续数组，后面的可以省略数组键值。0起点可能出现很多判断失误，所以以1为起点。
	//以下是 Client列表
		1 => array(
			'api' => 'http://www.pptest.test/passport/ppfapi/client.php',	//Client端的Client.php地址！请保证正确
			'key' => 'abclasdkfa',			//每个Client可以用不同的Key 加密
		),
//		array(
//			'api' => 'http://www.dede53.test/ppfapi/client.php',	//Client端的Client.php地址！请保证正确
//			'key' => 'abclasdkfa',			//每个Client可以用不同的Key 加密
//		),
	)
);
?>