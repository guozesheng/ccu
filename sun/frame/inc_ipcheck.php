<?php
/**
 * IP段限制/检查工具
 * IPV4
 * 
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */

class IpCheck {
	
	/**
	 * IP限制规则 CIDR表示方法
	 *
	 * @var string
	 */
	var $IpRule;
	/**
	 * 子网掩码，使用高位1的数字 1 - 32
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
			#开始计算IP起止点。
			$this -> IpHost = $this -> ExecHost($t[0],$this->Master);
		}else {
			$this -> Master = 'N';
		}
	}
	
	function ExecHost($ip,$master) {
		#整数类型的IP地址
		$intip = ip2long($ip);
		$intmaster = bindec(str_pad(str_repeat('1',$master),32,0,STR_PAD_RIGHT));
		#返回主机地址
		if ($intip && $intip != -1) {
			return $intip &  $intmaster;
		}else {	##
			return 'N';
		}
	}
	
	function CheckIPIn($ip) {
		if ($this -> IpHost) {	//IP段的比较
			if ($this -> IpHost == 'N') {
				return 0;
			}
			#主机相同则在IP段内
			if ($this->ExecHost($ip,$this->Master) == $this -> IpHost) {
				return 1;
			}else {
				return 0;
			}
		}else {
			//简单比较
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