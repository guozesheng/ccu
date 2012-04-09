<?php
/**
 * ³äÖµ¿¨´¦ÀíÀà
 *
 * @author  òßòÑ@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class MoneyCard {
	/**
	 * ¿¨ºÅ
	 *
	 * @var string
	 */
	var $CardNO;
	/**
	 *  ÃÜÂë
	 *
	 * @var string
	 */
	var $Password;
	/**
	 * ¿¨ºÅ³¤¶È
	 *
	 * @var number
	 */
	var $CardNOLen=15;
	/**
	 * ÃÜÂë³¤¶È
	 *
	 * @var number
	 */
	var $PassLen=18;
	/**
	 * ¿¨ÅúºÅ
	 *
	 * @var string
	 */
	var $CardPre;
	
	function __construct() {
		
	}
	
	function MoneyCard() {
		$this->__construct();
	}
	
	function SetCardPre($pre) {
		$pre = intval($pre);
		$this->CardPre = $pre;
	}
	
	function CreatOneCard($start) {
		$len = $this->CardNOLen-strlen($this->CardPre)-1;
		$start = substr(str_pad($start,$len,0,STR_PAD_LEFT),0,$len);
		$this -> CardNO = $this->CardPre . str_pad($start + 1,$len,0,STR_PAD_LEFT);
		$this -> CardNO = $this->CardNO . $this->ExecCheckNum($this->CardNO);
		$this -> CreatOnePassword();
	}
	
	function CreatOnePassword() {
		$p = '';
		for ($i = 0; $i< $this->PassLen-1 ; $i++) {
			if ($i == 0) {
				$p .= mt_rand(1,9);
			}else {
				$p .= mt_rand(0,9);
			}
		}
		$this->Password = $p . $this->ExecCheckNum($p);
	}
	
	function ExecCheckNum($str) {
		$num = 0;
		for ($i=1;$i<=strlen($str);$i++) {
			$num += $i * $str{$i-1};
		}
		return $num % 10;
	}
	
	function CheckCard($card) {
		if (!in_array(strlen($card),array($this->CardNOLen,$this->PassLen))) {
			return false;
		}
		if (substr($card,-1,1) == $this->ExecCheckNum(substr($card,0,-1))) {
			return true;
		}else {
			return false;
		}
	}
}