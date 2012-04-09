<?php
class PPF_Api {
	
	var $Key = '';
	
	var $Params = array();
	
	var $SignType = 'md5';
	
	function PPF_Api() {
		$this -> __construct();
	}
	
	function __construct() {
		
	}
	
	function CreateApiSign() {
		$sign = array();
		foreach ($this->Params as $k => $v) {
			$sign[] = "$k=$v";
		}
		if ($sign) {
			$sign = implode('&',$sign);
			switch (strtolower($this->SignType)) {
				case 'md5':
				return  strtolower(md5($sign.$this->Key));
				case 'dsa':
				die('for future');
				break;
			}
		}else {
			die('no.params.to.sign');
		}
	}
	
	function SetKey($key) {
		$this -> Key = $key;
	}
	/**
	 * ����Server/Client��API��
	 * ʹ��POST������
	 *
	 */
	function CreateApi($client) {
		$form_header = <<<E
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<title>PPF Api</title>
</head>

<body>
<p>
Loading...
<br />
Please Wait...
</p>
<form id="form1" name="form1" method="post" action="{$client}">

E;
		$form_footer = <<<E

</form>
<script language="javascript">
//setTimeout('document.form1.submit();',10);
</script>
</body>
</html>
E;

		$form_body = '';
		foreach ($this -> Params as $k => $v) {
			$form_body .= "<input name=\"$k\" type=\"hidden\" value=\"$v\" />";
		}
		$form_body .= "<input name=\"sign\" type=\"hidden\" value=\"" . $this -> CreateApiSign() . "\" />";
		$form_body .= "<input name=\"sign_type\" type=\"hidden\" value=\"{$this -> SignType}\" />";
//		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: no-cache");
		echo $form_header . $form_body . $form_footer ;
		exit;
	}
	
	function CreateApiUrl($client) {
		$params = array();
		foreach ($this -> Params as $k => $v) {
			$params[] = "$k=".urlencode($v);
		}
		$params[] = "sign=" . $this -> CreateApiSign();
		$params[] = "sign_type={$this -> SignType}";
		return $client . '?' . implode('&',$params);
	}

	/**
	 * ���ò��� Server All Need
	 * array(
	 * 	'action' => $action,		client need!
	 *  'data' => array(),			client need!	//�û���Ϣ����
	 *  'time' => $timestamp,		client need!
	 *  'lang' => gbk / utf-8,
	 *  'step' => step,				client need!
	 *  'server' => server api address,
	 *  'return' => return url,		client need!
	 * )
	 *
	 * @param array $param ��������
	 * @param bool $overwrite �Ƿ񸲸�
	 * @param bool $rewrite �Ƿ���д
	 */
	function SetParams($param,$overwrite=true,$rewrite=false) {
		if ($rewrite) {
			$this->Params = array();
		}
		foreach ($param as $k => $v) {
			if ($k == 'sign' || $k == 'sign_type') {
				continue;
			}
			if ($k == 'data') {
				if (is_array($v)) {
					$tmp = array();
					foreach ($v as $kk => $vv) {
						$tmp[] = "$kk=$vv";
					}
					if ($tmp) {
						$v = implode('&',$tmp);
					}
					$ppf_api_encoder = new PPF_API_Encoder();
					$ppf_api_encoder -> SetKey($this -> Key);
					$v = $ppf_api_encoder -> Encode($v);
				}else {
					
				}
			}
			if ($overwrite) {
				$this->Params[$k] = $v;
			}else {
				!isset($this->Params[$k]) && $this->Params[$k] = $v;
			}
		}
		$this -> InitParams();
	}
	
	function InitParams() {
		foreach ($this->Params as $k => $v) {
			if ($k == 'sign' || $k == 'sign_type' || !$v) {
				unset($this->Params[$k]);
			}
		}
		ksort($this->Params);
		reset($this->Params);
	}
}

/**
 * ������һ����Կ Key ��һ���ַ������ܳ�һ���� ���� �� a-z ���ַ���ɵ��ַ���
 * ������ͬһ��Key �������ַ�����ԭ.
 * ���ܺ�ռ��2���Ĵ洢�ռ�!
 * 
 * ������ azencoder ��ȫ��ͬ����ppfapi����д��Ϊ�� ����һ�����Ͽ�����
 *
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * 
 */
class PPF_API_Encoder{
	
	var $Key = 'LAKDJU0F3093&^#$(@*$)jDLKjsoidsk';
	/**
	 * ���ܺ���ʹ����Key �ļ���
	 *
	 * @param string $string
	 * @return string
	 */
	function Encode($string) {
		return PPF_API_Encoder::azStringEncode($this->keyEncode($string));
	}
	/**
	 * ���ܺ���ʹ��Key �Ľ���
	 *
	 * @param string $string
	 * @return string
	 */
	function Decode($string) {
		$string = PPF_API_Encoder::azStringDecode($string);
		return $this->keyEncode($string,'DECODE');
	}
	/**
	 * ascii��ת������
	 *
	 * @param number $i
	 * @return number
	 */
	function asciiEncode($i){
		return (255 + $i -97)%255;
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
		$char = PPF_API_Encoder::asciiEncode(ord($char));
		$to = $char % 26;
		$no = intval($char / 26);
		$s = PPF_API_Encoder::asciiDecode($to);
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
		$i = $char[0] * 26 + PPF_API_Encoder::asciiEncode(ord($char[1]));
		$i = PPF_API_Encoder::asciiDecode($i);
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
			$s .= PPF_API_Encoder::azCharEncode($string[$i]);
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
			$s .= PPF_API_Encoder::azCharDecode(substr($string,$i,2));
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
?>