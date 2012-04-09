<?php
set_time_limit(0);
require('ppframe.php');

if (isset($Submit)) {
	Iimport('FileManager');
	
	$ClearDir = array('','admin/','passport/','exam/','pplog/','cms/');
	
	foreach ($ClearDir as $key => $val ) {
		#清除根目录
		$dir = ROOT . $val . 'temp/';
		if (!file_exists($dir)) {
			continue;
		}
		$dh = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
			
			if (ereg('^\.',$filename)) {
				continue;
			}
			if (is_dir($dir.$filename)) {
				if (($cache_type && eregi($cache_type,$filename)) || !$cache_type) {
					$dir2 = $dir . $filename.'/';
					$dh2 = opendir($dir2);
					
					if ($see) {
						$echo .= '<font color=red>Start Clear Dir : ' . $dir2 . '</font><br />';
					}
					while (false !== ($filename2 = readdir($dh2))) {
						if (ereg('^\.',$filename2)) {
							continue;
						}
						if (is_file($dir2.$filename2)) {
							if ($see) {
								$echo .= 'Try Delete '. $filename2 . '<br />';
							}
							unlink($dir2 . $filename2);
						}
					}
					if ($see) {
						$echo .= '<font color=red>Clear Dir :' . $dir2 .' Over</font><br />';
					}
				}
			}
		}
	}
	ShowMessage($echo.'<br />.do.success',-1,1);
}else {
	Iimport('Template');
	$tpl = new Template();
	$tpl -> DisPlay('cache_clear');
}
//php5下 不要用对象来递归
?>