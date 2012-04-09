<?php
/**
 * IP������/��鹤��
 * IPV4
 * 
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */

class IpCheck {
	
	/**
	 * IP���ƹ��� CIDR��ʾ����
	 *
	 * @var string
	 */
	var $IpRule;
	/**
	 * �������룬ʹ�ø�λ1������ 1 - 32
	 *
	 * @var number
	 */
	var $Master=24;
	/**
	 * IP Host
	 *
	 * @var number
	 */
	var $IpHost = '';
	
	function SetIprule($rule) {
		$this -> IpRule = $rule;
		if (ereg('/',$this->IpRule)) {
			$t = explode('/',$this->IpRule);
			$m = intval($t[1]);
			if ($m > 1 && $m < 32) {
				$this -> Master = $m;
			}else {
				$this -> Master = 24;
			}
			#��ʼ����IP��ֹ�㡣
			$this -> IpHost = $this -> ExecHost($t[0],$this->Master);
		}else {
			$this -> Master = 'N';
		}
	}
	
	function ExecHost($ip,$master) {
		#�������͵�IP��ַ
		$intip = ip2long($ip);
		$intmaster = bindec(str_pad(str_repeat('1',$master),32,0,STR_PAD_RIGHT));
		#����������ַ
		if ($intip && $intip != -1) {
			return $intip &  $intmaster;
		}else {	##
			return 'N';
		}
	}
	
	function CheckIPIn($ip) {
		if ($this -> IpHost) {	//IP�εıȽ�
			if ($this -> IpHost == 'N') {
				return 0;
			}
			#������ͬ����IP����
			if ($this->ExecHost($ip,$this->Master) == $this -> IpHost) {
				return 1;
			}else {
				return 0;
			}
		}else {
			//�򵥱Ƚ�
			if (ereg('^'.str_replace('.','\.',$this->IpRule),$ip)) {
				return 1;
			}else {
				return 0;
			}
		}
	}
	
	function ReSet() {
		$this -> IpHost = '';
		$this -> IpRule = '';
		$this -> Master = 24;
	}
}