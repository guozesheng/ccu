<?php
require('ppframe.php');

#系统信息Url
$frame_info_urls = array(
	'http://61.232.206.214/softinfo.'. str_replace('-','.',$rtc['language']) . '.html',
	'http://info1.ppframeinfo.ppframe.com/softinfo.'. str_replace('-','.',$rtc['language']) . '.html',
	'http://info2.ppframeinfo.ppframe.com/softinfo.'. str_replace('-','.',$rtc['language']) . '.html',
);
#获得系统通知信息
$frame_info = GetSoftInfo($frame_info_urls);

function GetSoftInfo($urls) {
	$cachedir = ROOT.'temp/easy_cache_dir/';
	if (!file_exists($cachedir)) {
		mkdir($cachedir);
	}
	$cachefile = $cachedir.'frame_info.text';
	$info = ReadOverFile($cachefile);
	if (file_exists($cachefile) && time() - filemtime($cachefile) < 3600) {
		return $info;
	}else {
		Iimport('Http');
		$http = new Http();
		if (is_array($urls)) {
			foreach ($urls as $k => $v) {
				$http -> OpenUrl($v);
				$http -> Send();
				if ($http -> StateOK() && $http->Recive) {
					break;
				}
			}
			
			if ($http -> Recive) {
				WriteFile($http -> Recive,$cachefile);
			}
		}
		return $http -> Recive ? $http -> Recive : $info;
	}
}
@include(GetTemplate('index_body'));
?>