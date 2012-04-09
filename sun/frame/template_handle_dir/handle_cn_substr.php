<?php
!defined('MODULE') && exit('Forbidden');
function cn_substr($content,$length,$add=''){
	global $rtc;
	if($length && strlen($content)>$length){
		if(strtolower($rtc['language']) != 'utf-8'){
			$retstr='';
			for($i = 0; $i < $length - 2; $i++){
				$retstr .= ord($content[$i]) > 127 ? $content[$i].$content[++$i] : $content[$i];
			}
			return $retstr.$add;
		} else{
			$str = substr($content,0,$length);
			$len = strlen($str);
			for($i=strlen($str)-1;$i>=0;$i-=1){
				$hex .= ' '.ord($str[$i]);
				$ch   = ord($str[$i]);
				if(($ch & 128)==0)	return substr($str,0,$i);
				if(($ch & 192)==192)return substr($str,0,$i);
			}
			return($str.$hex.$add);
		}
	}
	return $content;
}