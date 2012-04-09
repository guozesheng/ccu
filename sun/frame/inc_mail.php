<?php
/**
 * 邮件发送处理类 未完成
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class Mail{
	var $To;
	var $Subject;
	var $Message;
	var $MailType=1;			//1、mail函数,2、smtp
	var $Header="";
	
	 function __construct($to,$subject,$message){
	 	$this->To = $to;
	 	$this->Subject = $subject;
	 	$this->Message = $message;
	 }
	 
	 function Mail($to,$subject,$message){
	 	return $this->__construct($to,$subject,$message);
	 }
	 
	 function SendMail(){
	 	switch ($this->MailType){
	 		case 1:
	 		return @mail($this->To,$this->Subject,$this->Message,$this->Header);
	 		break;
	 		case 2:
	 		break;
	 	}
	 }
	 
	 function SetHeader($header){
	 	$this->Header = $header;
	 }
}