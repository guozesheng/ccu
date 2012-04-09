<?php
/**
 * ������һ����Կ Key ��һ���ַ������ܳ�һ���� ���� �� a-z ���ַ���ɵ��ַ���
 * ������ͬһ��Key �������ַ�����ԭ.
 * ���ܺ�ռ��2���Ĵ洢�ռ�!
 *
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * 
 */
class azEncoder{
	
	var $Key = 'LAKDJU0F3093&^#$(@*$)jDLKjsoidsk';
	/**
	 * ���ܺ���ʹ����Key �ļ���
	 *
	 * @param string $string
	 * @return string
	 */
	function Encode($string) {
		return azEncoder::azStringEncode($this->keyEncode($string));
	}
	/**
	 * ���ܺ���ʹ��Key �Ľ���
	 *
	 * @param string $string
	 * @return string
	 */
	function Decode($string) {
		$string = azEncoder::azStringDecode($string);
		return $this->keyEncode($string,'DECODE');
	}
	/**
	 * ascii��ת������
	 *
	 * @param number $i
	 * @return number
	 */
	function asciiEncode($i){
		return (255 + $i -97) % 255;
	}
	/**
	 * ascii �뻹ԭ
	 *
	 * @param number $i
	 * @return number
	 */
	function asciiDecode($i) {
		return ($i + 97)%255;
	}
	/**
	 * a-z �Ŀ�������㷨���ַ����ܺ���
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
	 * a-z �Ŀ�������㷨˫�ַ����ܺ���
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
	 * a-z �Ŀ�������㷨�ַ������ܺ���
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
	 * a-z �ļӿ������㷨�ַ������ܺ���
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
	 * ��������Կ��һ���򵥼��ܽ��ܺ���.
	 * ������ǰ��������֤��.
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