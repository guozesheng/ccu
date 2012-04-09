<?php
/**
 * 支付宝接口处理类
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class AliPay {
	
	var $partner = "";//合作伙伴ID
	var $security_code = "";//安全检验码
	var $seller_email = "hubin999421@gmail.com";//卖家邮箱
	var $_input_charset = "GBK"; //字符编码格式  目前支持 GBK 或 utf-8
	var $sign_type = "MD5"; //加密方式  系统默认(不要修改)
	var $notify_url = "";// 异步返回地址
	var $return_url = ""; //同步返回地址
//	var $show_url   ="";  //你网站商品的展示地址,可以为空	
	
	var $gateway = "https://www.alipay.com/cooperate/gateway.do?";
	
	var $notify_type = 'http';
	
	var $notify_gateway = "https://www.alipay.com/cooperate/gateway.do?";
	
	var $parameter=array();
	
	var $sign='';
	
	var $loguse = false;
	
	function __construct() {
		//配置变量初始化
		$this->partner = $GLOBALS['rtc']['olpay_info']['alipay']['partnerid'];
		$this->security_code = $GLOBALS['rtc']['olpay_info']['alipay']['key'];
		
		$this->seller_email = $GLOBALS['rtc']['olpay_info']['alipay']['username'];
		$this->notify_url = $GLOBALS['rtc']['passport_root'].'api/alipay_notify.php';
		$this->return_url = $GLOBALS['rtc']['passport_root'].'alipay_return.php';
		
		$this->_input_charset = $GLOBALS['rtc']['language'];
		
		$this->parameter = array(
			'service' => 'create_direct_pay_by_user',
			'partner' => $this -> partner,
			'return_url' => $this -> return_url,
			'notify_url' => $this -> notify_url,
			'_input_charset' => $this -> _input_charset,
			'payment_type' => 1,
			'seller_email' => $this->seller_email,
		);
		
		if($this->notify_type == 'https' ) {
			$this->notify_gateway = "https://www.alipay.com/cooperate/gateway.do?";
		}else {
			$this -> notify_gateway = "http://notify.alipay.com/trade/notify_query.do?";
		}
	}
	
	function AliPay() {
		$this->__construct();
	}
	
	function SetConfig($array) {
		if (is_array($array)) {
			$array['partner'] && $this->partner = $array['partner'];
			$array['code'] && $this->security_code = $array['code'];
			$array['email'] && $this->seller_email = $array['email'];
			$array['charset'] && $this->_input_charset = $array['charset'];
			$array['sign_type'] && $this->sign_type = $array['sign_type'];
			$array['n_url'] && $this->notify_url = $array['n_url'];
			$array['r_url'] && $this->return_url = $array['r_url'];
			$array['gate_way'] && $this->gateway = $array['gate_way'];
			$array['n_gate_way'] && $this->notify_gateway = $array['n_gate_way'];
		}
	}
	/**
	 * 设置参数
	 *
	 * @param array $param
	 * @param bool $overwrite
	 * @param bool $rewrite
	 */
	function SetParams($param,$overwrite=true,$rewrite=false) {
		if ($rewrite) {
			$this->parameter = array();
		}
		foreach ($param as $k => $v) {
			if ($k == 'sign' || $k == 'sign_type' || !$v ) {
				continue;
			}
			if ($overwrite) {
				$this->parameter[$k] = $v;
			}else {
				!$this->parameter[$k] && $this->parameter[$k] = $v;
			}
		}
	}
	/**
	 * 初始化参数
	 *
	 */
	function InitParams() {
		foreach ($this->parameter as $k => $v) {
			if ($k == 'sign' || $k == 'sign_type' || !$v) {
				unset($this->parameter[$k]);
			}
		}
		ksort($this->parameter);
		reset($this->parameter);
	}
	/**
	 * 根据参数生成签名
	 *
	 * @return string
	 */
	function CreateSign() {
		$this->InitParams();
		$sign = array();
		foreach ($this->parameter as $k => $v) {
			$sign[] = "$k=$v";
		}
		if ($sign) {
			$sign = implode('&',$sign);
			switch (strtolower($this->sign_type)) {
				case 'md5':
				$this->sign = md5($sign.$this->security_code);
				#
				if ($this->loguse) {
					$this->log_result('create sign:'.$this->sign);
				}

				return $this->sign;
				case 'dsa':
				die('developing');
				break;
			}
		}else {
			die('no.params.to.sign');
		}
	}
	/**
	 * 生成支付请求URL
	 *
	 * @return string
	 */
	function CreateUrl() {
		$this->CreateSign();
		$params = array();
		foreach ($this->parameter as $k => $v) {
			$params[] = "$k=".urlencode($v);
		}
		$params = implode('&',$params);
		return $this->gateway.$params.'&sign='.$this->sign.'&sign_type='.$this->sign_type;
	}
	
	/**
	 * 支付宝通知处理
	 */
	
	function Alipay_Notify($s=1) {
		$this -> SetParams($_POST,1,1);
		$this->CreateSign();
		#验证请求是否是alipay发送!
		if ($this->notify_type == 'https') {
			$veryfy_url = $this->notify_gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_POST["notify_id"];
		}else {
			//文档竟然不对！汗
//			$veryfy_url = $this->notify_gateway. "msg_id=" . $_POST['notify_id']. "&email=".$_POST['buyer_email']."&order_no=".$_POST['out_trade_no'];
			$veryfy_url = $this->notify_gateway. 'notify_id='.$_POST['notify_id'].'&partner=' .$this->partner;
		}
		#
		if ($this->loguse) {
			$this->log_result('notify sign:'.$_POST['sign']);
		}
		
		if ($this->sign == $_POST['sign']) {
			if ($s == 0) {
				return true;
			}
			if (eregi('true$',$this->GetVerify($veryfy_url))) {
				return true;
			}
		}
		return false;
	}
	
	function Alipay_Return() {
		$this -> SetParams($_GET,1,1);
		$this -> CreateSign();
		#
		if ($this->loguse) {
			$this->log_result('return sign:'.$_GET['sign']);
		}
		if ($this->sign == $_GET['sign']) {
			return true;
		}else {
			return false;
		}
	}
	
	function GetVerify($url,$time_out = "60") {
		$urlarr = parse_url($url);
		$errno = "";
		$errstr = "";
		$transports = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr["query"] . "\r\n\r\n");
			while(!feof($fp)) {
				$info[]=@fgets($fp, 1024);
			}

			fclose($fp);
			$info = implode(",",$info);
			while (list ($key, $val) = each ($_POST)) {
				$arg.=$key."=".$val."&";
			}

			#
			if ($this->loguse) {
				$this->log_result("notify_url_log=".$url.$info);
				$this->log_result("notify_url_log=".$arg);				
			}
			return $info;
		}
	}
	
	function  log_result($word) { 
		$fp = fopen(ROOT."log.txt","a");	
		flock($fp, LOCK_EX) ;
		fwrite($fp,$word." Record Time ".strftime("%Y%m%d %H:%I:%S",time())."\r\n\r\n");
		flock($fp, LOCK_UN); 
		fclose($fp);
	}
}