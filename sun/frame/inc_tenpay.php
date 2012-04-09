<?php
/**
 * �Ƹ�ͨ�ӿڴ�����
 *
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class TenPay {
	#֧������
	var $GateWay='https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi';
	#
	var $CmdNo=1;
	
	#������
	var $SpId;
	#��Կ
	var $SpKey;
	#����URL
	var $ReturnUrl;
	#������Ϣ
	var $Attach;
	#֧�����
	var $PayFee;
	#��Ʒ��
	var $Desc;
	#������
	var $TransactionId;
	#�ڲ�������
	var $Billno;
	#1��RMB 2��USD 3��HKD
	var $FeeType=1;
	#֧������
	var $PayDate;
	/*��������:	
      0		  �Ƹ�ͨ
  		1001	��������   
  		1002	�й���������  
  		1003	�й���������  
  		1004	�Ϻ��ֶ���չ����   
  		1005	�й�ũҵ����  
  		1006	�й���������  
  		1008	���ڷ�չ����   
  		1009	��ҵ����
  	*/
	var $BankType=0;
	
	var $uselog=false;
	
	function __construct() {
		$this->SpId = $GLOBALS['rtc']['olpay_info']['tenpay']['spid'];
		$this->SpKey = $GLOBALS['rtc']['olpay_info']['tenpay']['spkey'];
		$this->FeeType = 1;
		
		$this->PayDate = GetMyDate('Ymd');
	}
	/**
	 * ����֧�����������Ϣ
	 * 
	 * @param array $array
	 */
//	array(
//		'returnurl' => '',
//		'attach' => '',
//		'payfee' => '',
//		'desc' => '',
//		'trid' => '',
//		'billno' => '',
//		'feetype' => 1,
//	);
	function SetInfo($array){
		if (is_array($array)) {
			$array['returnurl'] && $this->ReturnUrl = $array['returnurl'];
			$array['attach'] && $this->Attach = $array['attach'];
			$array['payfee'] && $this->PayFee = $array['payfee'];
			$array['desc'] && $this->Desc = $array['desc'];
			$array['trid'] && $this->TransactionId = $array['trid'];
			$array['billno'] && $this->Billno = $array['billno'];
			$array['feetype'] && $this->FeeType = $array['feetype'];
			$array['banktype'] && $this->BankType = $array['banktype'];
			$array['paydate'] && $this->PayDate = $array['paydate'];
			$array['spid'] && $this->SpId = $array['spid'];
			$array['spkey'] && $this->SpKey = $array['spkey'];
		}
	}
	
	function CreatePayRequest() {
		if ($this->Billno && $this->PayFee) {
			$sign_text ="cmdno=1&date={$this->PayDate}&bargainor_id={$this->SpId}&transaction_id={$this->TransactionId}&sp_billno={$this->Billno}&total_fee={$this->PayFee}&fee_type={$this->FeeType}&return_url={$this->ReturnUrl}&attach={$this->Attach}";
			$sign_text2="cmdno=1&date={$this->PayDate}&bargainor_id={$this->SpId}&transaction_id={$this->TransactionId}&sp_billno={$this->Billno}&total_fee={$this->PayFee}&fee_type={$this->FeeType}&return_url=".urlencode($this->ReturnUrl)."&attach={$this->Attach}";
			$strSign = strtoupper(md5($sign_text."&key=".$this->SpKey));
			$return = $this->GateWay . "?" . $sign_text2 . "&sign=" . $strSign."&desc=".urlencode($this->Desc)."&bank_type=".$this->BankType;
			if ($this->uselog) {
				$this->log_result('create url'.$return);
			}
			return $return;
		}else {
			return '';
		}
	}
	
	function Tenpay_Notify() {
//		import_request_variables("gpc", "frm_");
	  /*ȡ���ز���*/
		$strCmdno			= $_GET['cmdno'];
		$strPayResult		= $_GET['pay_result'];
		$strPayInfo		= $_GET['pay_info'];
		$strBillDate		= $_GET['date'];
		$strBargainorId	= $_GET['bargainor_id'];
		$strTransactionId	= $_GET['transaction_id'];
		$strSpBillno		= $_GET['sp_billno'];
		$strTotalFee		= $_GET['total_fee'];
		$strFeeType		= $_GET['fee_type'];
		$strAttach			= $_GET['attach'];
		$strMd5Sign		= $_GET['sign'];
				  
		$strResponseText  = "cmdno=" . $strCmdno . "&pay_result=" . $strPayResult . 
				                  "&date=" . $strBillDate . "&transaction_id=" . $strTransactionId .
					                "&sp_billno=" . $strSpBillno . "&total_fee=" . $strTotalFee .
					                "&fee_type=" . $strFeeType . "&attach=" . $strAttach .
					                "&key=" . $this->SpKey;
		$strLocalSign = strtoupper(md5($strResponseText));
		
		if ($this->uselog) {
			$this->log_result('sign to md5 '.$strResponseText);
			$this->log_result('recive sign '.$strMd5Sign);
			$this->log_result('create sign '.$strLocalSign);
		}
		
		 if( $strLocalSign  != $strMd5Sign){
		 	#У�����
		 	return -1;
		 }
		
		 if($this->SpId!= $strBargainorId ){
	  		#������̻���
	  		return -2;
	  	}
	  	
	  	if ($strPayResult != "0") {
	  		#֧��ʧ��
	  		return -3;
	  	}
	  	
		if( $strPayResult == "0" ) {
			#֧���ɹ�
			$this -> TransactionId = $strTransactionId;
			$this -> Billno = $strSpBillno;
			$this ->FeeType = $strFeeType;
			$this ->PayFee = $strTotalFee/100;
			return 1;	
		}
	}
	
	function ShowExitMsg($location){
		$strMsg="<meta name=\"TENCENT_ONELINE_PAYMENT\" content=\"China TENCENT\">\n<html>\n";
		$strMsg.= "<script language=javascript>\n";
		$strMsg.= "window.location.href='$location';\n";
		$strMsg.= "</script>\n</html>";
		Exit($strMsg);
	}
	
	function TenPay(){
		$this->__construct();
	}
	
	function  log_result($word) { 
		$fp = fopen(ROOT."tenpay_log.txt","a");	
		flock($fp, LOCK_EX) ;
		fwrite($fp,$word."  Record Time��".strftime("%Y%m%d %H:%I:%S",time())."\r\n\r\n");
		flock($fp, LOCK_UN); 
		fclose($fp);
	}
}