<?php
/**
 * 依赖与一个密钥 Key 将一个字符串加密成一个由 数字 和 a-z 的字符组成的字符串
 * 并能以同一个Key 将加密字符串还原.
 * 加密后将占用2倍的存储空间!
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * 
 */
class azEncoder{
	
	var $Key = 'LAKDJU0F3093&^#$(@*$)jDLKjsoidsk';
	/**
	 * 加密函数使用了Key 的加密
	 *
	 * @param string $string
	 * @return string
	 */
	function Encode($string) {
		return azEncoder::azStringEncode($this->keyEncode($string));
	}
	/**
	 * 解密函数使用Key 的解密
	 *
	 * @param string $string
	 * @return string
	 */
	function Decode($string) {
		$string = azEncoder::azStringDecode($string);
		return $this->keyEncode($string,'DECODE');
	}
	/**
	 * ascii码转换函数
	 *
	 * @param number $i
	 * @return number
	 */
	function asciiEncode($i){
		return (255 + $i -97) % 255;
	}
	/**
	 * ascii 码还原
	 *
	 * @param number $i
	 * @return number
	 */
	function asciiDecode($i) {
		return ($i + 97)%255;
	}
	/**
	 * a-z 的可逆加密算法单字符加密函数
	 *
	 * @param char $string
	 * @return string
	 */
	function azCharEncode($char) {
		$char = azEncoder::asciiEncode(ord($char));
		$to = $char % 26;
		$no = intval($char / 26);
		$s = azEncoder::asciiDecode($to);
		$s = chr($s);
		return $no . $s;
	}
	/**
	 * a-z 的可逆加密算法双字符解密函数
	 *
	 * @param string $string
	 * @return char
	 */
	function azCharDecode($char) {
		$i = $char[0] * 26 + azEncoder::asciiEncode(ord($char[1]));
		$i = azEncoder::asciiDecode($i);
		return chr($i);
	}
	/**
	 * a-z 的可逆加密算法字符串加密函数
	 *
	 * @param string $string
	 * @return string
	 */
	function azStringEncode($string){
		$s = '';
		for ($i=0; $i<strlen($string);$i++) {
			$s .= azEncoder::azCharEncode($string[$i]);
		}
		return $s;
	}
	/**
	 * a-z 的加可逆密算法字符串解密函数
	 *
	 * @param string $string
	 * @return string
	 */
	function azStringDecode($string) {
		$s = '';
		for ($i=0;$i<strlen($string);$i++) {
			if(!is_numeric($string[$i])) {
				$i++;
				continue;
			}
			$s .= azEncoder::azCharDecode(substr($string,$i,2));
			$i++;
		}
		return $s;
	}
	/**
	 * 依赖于密钥的一个简单加密解密函数.
	 * 增加了前后两个验证串.
	 *
	 * @param string $string
	 * @param string $do ENCODE or DECODE
	 * @return string
	 */
	function keyEncode($string , $do = 'ENCODE') {
		$s = '';
		$kl = strlen($this->Key);
		if ($do == 'DECODE') {
			$ckpre = substr($string,0,4);
			$ckaft = substr($string,-4);
			$string = substr($string,4,-4);
		}
		
		for ($i = 0;$i<strlen($string);$i++) {
			$s .= $string[$i] ^ $this->Key[$i%$kl];
		}
		
		if ($do == 'ENCODE') {
			$ckpre = substr(md5($this->Key.$string),8,4);
			$ckaft = substr(md5($this->Key.$string),16,4);
			return $ckpre.$s.$ckaft;
		}else if($do == 'DECODE') {
			if ($ckpre == substr(md5($this->Key.$s),8,4) && $ckaft == substr(md5($this->Key.$s),16,4)) {
				return $s;
			}else {
				return '';
			}
		}
	}
	
	function SetKey($key) {
		$this->Key = $key;
	}
}