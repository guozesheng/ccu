<?php
/**
 * 简单邮件协议 SMTP 封装类
 *
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class smtp {
	/**
	 * hostname
	 *
	 * @var string
	 */
	var $host='localhost';
	/**
	 * smtp port 
	 *
	 * @var int
	 */
	var $port=25;
	/**
	 * debug level
	 *
	 * @var int
	 */
	var $debug=0;
	/**
	 * smtp reply line ending <CRLF>
	 *
	 * @var string
	 */
	var $endchar="\r\n";
	/**
	 * Sets VERP use on/off (default is off)
	 *
	 * @var bool
	 */
	var $verp = false;
	/**
	 * sorcket link symbol
	 *
	 * @var source
	 * @access private
	 */
	var $conn;
	/**
	 * error information
	 *
	 * @var array
	 * @access private
	 */
	var $error;
	/**
	 * reply from the server
	 *
	 * @var string
	 * @access private
	 */
	var $reply;
	
	function smtp() {
		$this->conn = null;
		$this->error=null;
		$this->reply= null;
	}
	/**
   * Connect to the server specified on the port specified.
   * If the port is not specified use the default PORT.
   * If ct is specified then a connection will try and be
   * established with the server for that number of seconds.
   * If ct is not specified the default is 30 seconds to
   * try on the connection.
   *
   * SMTP CODE SUCCESS: 220
   * SMTP CODE FAILURE: 421
   * @access public
   * @return bool
   */
	function Connect($host='',$port=0,$ct=30) {
		empty($host) && $host=$this->host;
		if(empty($host)) {return false;}
		$this->error = null;
		
		if($this->IsConnected()) {
			$this->error = array('error'=>'already connected');
			$this->WarnMsg();
			return true;
		}
		
		if(empty($port)) {
			$port = $this->port;
		}
		
		$this->conn = fsockopen(
								$host,
								$port,
								$errno,
								$errstr,
								$ct
								);
		if(empty($this->conn)) {
			$this->error = array(
						'error'=>'cann\'t connect to server',
						'code' => $errno,
						'msg'=>$errstr,
			);
			$this->ErrorMsg();
			return false;
		}
		
		if(substr(PHP_OS,0,3) != 'WIN') 
		socket_set_timeout($this->conn,$ct,0);
		
		$annouce = $this->GetReply();
		$this->ReplyMsg($annouce);
		return true;
	}
	/**
   * Performs SMTP authentication Login.  Must be run after running the
   * Hello() method.  Returns true if successfully authenticated.
   * @access public
   * @return bool
   */
	function Login($username,$password) {
		if(!$this->CheckConnected()) return false;
		fputs($this->conn,"AUTH LOGIN".$this->endchar);
		
		$rply = $this->GetReply();
		
		$code = substr($rply,0,3);
		
		if($code!=334) {
			$this->error = array(
				'error'=>'AUTH refused by server',
				'code'=>$code,
				'msg'=>substr($rply,4)
			);
			$this->ErrorMsg();
			return false;
		}
		
		//send encode username (base64)
		fputs($this->conn,base64_encode($username).$this->endchar);
		$rply = $this->GetReply();
		$code = substr($rply,0,3);
		
		if($code != 334) {
			$this->error = array(
				'error'=>'Username refused by server',
				'code'=>$code,
				'msg' =>substr($rply,4)
			);
			$this->ErrorMsg();
			return false;
		}
		
		//send encode password(base64)
		fputs($this->conn,base64_encode($password).$this->endchar);
		$rply = $this->GetReply();
		$code = substr($rply,0,3);
		
		if($code != 235) {
			$this->error = array(
				'error'=>'Password refused by server',
				'code'=>$code,
				'msg' =>substr($rply,4)
			);
			$this->ErrorMsg();
			return false;
		}
		return true;
	}
	/**
	 * check the connect whether is active
	 *
	 * @return bool
	 */
	function IsConnected() {
		if($this->conn) {
			$sock_status = socket_get_status($this->conn);
			if($sock_status['eof']) {
				$this->ShowMsg("NOTICE:".$this->endchar."EOF,Check if connected");
				$this->Close();
				return false;
			}
			return true;
		}
		return false;
	}
	/**
	 * Check whether connected
	 *
	 * @return bool
	 */
	function CheckConnected() {
		if(!$this->IsConnected()) {
			$this->error = array('error'=>'not connected');
			$this->ErrorMsg();
			return false;
		}
		return true;
	}
	/**
	 * Close Socket link
	 *
	 */
	function Close() {
		$this->error=null;
		$this->reply=null;
		if($this->conn) {
			@fclose($this->conn);
			$this->conn=null;
		}
	}
	
	/**************************************************************
	 *          smtp cammand functions start                      *
	 **************************************************************/
	
	/**
   * Issues a data command and sends the msg_data to the server
   * finializing the mail transaction. $msg_data is the message
   * that is to be send with the headers. Each header needs to be
   * on a single line followed by a <CRLF> with the message headers
   * and the message body being seperated by and additional <CRLF>.
   *
   * Implements rfc 821: DATA <CRLF>
   *
   * SMTP CODE INTERMEDIATE: 354
   *     [data]
   *     <CRLF>.<CRLF>
   *     SMTP CODE SUCCESS: 250
   *     SMTP CODE FAILURE: 552,554,451,452
   * SMTP CODE FAILURE: 451,554
   * SMTP CODE ERROR  : 500,501,503,421
   * @access public
   * @return bool
   */
	
	function doData($msg_data) {
    $this->error = null; # so no confusion is caused
    $rply = $this->SendCommand("DATA");
    $code = substr($rply,0,3);
    $this->ReplyMsg($rply);
    if($code != 354) {
      $this->error =
        array("error" => "DATA command not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this->ErrorMsg();
      return false;
    }
    # the server is ready to accept data!
    # according to rfc 821 we should not send more than 1000
    # including the CRLF
    # characters on a single line so we will break the data up
    # into lines by \r and/or \n then if needed we will break
    # each of those into smaller lines to fit within the limit.
    # in addition we will be looking for lines that start with
    # a period '.' and append and additional period '.' to that
    # line. NOTE: this does not count towards are limit.
    # normalize the line breaks so we know the explode works
    $msg_data = str_replace("\r\n","\n",$msg_data);
    $msg_data = str_replace("\r","\n",$msg_data);
    $lines = explode("\n",$msg_data);

    # we need to find a good way to determine is headers are
    # in the msg_data or if it is a straight msg body
    # currently I am assuming rfc 822 definitions of msg headers
    # and if the first field of the first line (':' sperated)
    # does not contain a space then it _should_ be a header
    # and we can process all lines before a blank "" line as
    # headers.
    $field = substr($lines[0],0,strpos($lines[0],":"));
    $in_headers = false;
    if(!empty($field) && !strstr($field," ")) {
      $in_headers = true;
    }

    $max_line_length = 998; # used below; set here for ease in change
    while(list(,$line) = @each($lines)) {
      $lines_out = null;
      if($line == "" && $in_headers) {
        $in_headers = false;
      }
      # ok we need to break this line up into several
      # smaller lines
      while(strlen($line) > $max_line_length) {
        $pos = strrpos(substr($line,0,$max_line_length)," ");

        # Patch to fix DOS attack
        if(!$pos) {
          $pos = $max_line_length - 1;
        }

        $lines_out[] = substr($line,0,$pos);
        $line = substr($line,$pos + 1);
        # if we are processing headers we need to
        # add a LWSP-char to the front of the new line
        # rfc 822 on long msg headers
        if($in_headers) {
          $line = "\t" . $line;
        }
      }
      $lines_out[] = $line;
      # now send the lines to the server
      while(list(,$line_out) = @each($lines_out)) {
        if(strlen($line_out) > 0)
        {
          if(substr($line_out, 0, 1) == ".") {
            $line_out = "." . $line_out;
          }
        }
        fputs($this->smtp_conn,$line_out . $this->endchar);
      }
    }

    # ok all the message data has been sent so lets get this
    # over with aleady
    fputs($this->smtp_conn, $this->endchar . "." . $this->endchar);
    $rply = $this->GetReply();
    $code = substr($rply,0,3);
	$this->ReplyMsg($rply);
	
    if($code != 250) {
      $this->error =
        array("error" => "DATA not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this->ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doExpn($name) {
  	$this->error = null; # so no confusion is caused
    $rply = $this->SendCommand("EXPN ",$name);
//    fputs($this->conn,"EXPN " . $name . $this->endchar);
	if(false === $rply) return false;
    $rply = $this->GetReply();
    $code = substr($rply,0,3);

    if($code != 250) {
      $this->error =
        array("error" => "EXPN not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this->ErrorMsg();
      return false;
    }
    # parse the reply and place in our array to return to user
    $entries = explode($this->endchar,$rply);
    while(list(,$l) = @each($entries)) {
      $list[] = substr($l,4);
    }
    return $list;
  }

  function doHello($host = 'localhost') {
  	$this->error = null; # so no confusion is caused
  	$rply = $this->SendCommand("HELLO ",$host);
  	$code = substr($rply,0,3);
  	if($code != 250) {
      $this->error =
        array("error" => $hello . " not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this->ErrorMsg();
      return false;
    }

    $this->reply = $rply;
    return true;
  }
  
  function doHelp($keyword="") {
  	$this->error = null; # so no confusion is caused
  	$rply = $this->SendCommand("HELP ".$keyword);
  	$code = substr($rply,0,3);
  	if($code != 211 && $code != 214) {
  		$this->error =
        array("error" => "HELP not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
        $this->ErrorMsg();
        return false;
  	}
  	return $rply;
  }
  
  function doMailFrom($from) {
  	$this->error = null; # so no confusion is caused
  	$useVerp = ($this->verp ? "XVERP" : "");
  	$rply = $this->SendCommand("MAIL FROM:<",$from.">".$useVerp);
  	$code = substr($rply,0,3);
  	if($code != 250) {
      $this->error =
        array("error" => "MAIL not accepted from server",
              "smtp_code" => $code,
              "smtp_msg" => substr($rply,4));
      $this->ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doNoop() {
  	$this->error = null; # so no confusion is caused
  	$rply = $this->SendCommand("NOOP");
  	$code = substr($rply,0,3);
  	if($code != 250) {
      $this->error =
        array("error" => "NOOP not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this->ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doQuit($close = true) {
  	$this -> error = null;
  	$byemsg = $this -> SendCommand("QUIT");
  	$rval = true;
    $code = substr($byemsg,0,3);
    if($code != 221) {
      # use e as a tmp var cause Close will overwrite $this->error
      $this->error = array("error" => "SMTP server rejected quit command",
                 "code" => $code,
                 "msg" => substr($byemsg,4));
      $rval = false;
      $this->ErrorMsg();
    }
    
    if(empty($this->error) || $close) {
    	$this->Close();
    }
    return $rval;
  }
    /**
   * Sends the command RCPT to the SMTP server with the TO: argument of $to.
   * Returns true if the recipient was accepted false if it was rejected.
   *
   * Implements from rfc 821: RCPT <SP> TO:<forward-path> <CRLF>
   *
   * SMTP CODE SUCCESS: 250,251
   * SMTP CODE FAILURE: 550,551,552,553,450,451,452
   * SMTP CODE ERROR  : 500,501,503,421
   * @access public
   * @return bool
   */
    function doRcptTo($to) {
    	$this->error = null;
    	
    	$rply = $this->SendCommand("RCPT TO:<",$to . ">");
    	$code = substr($rply,0,3);
   	if($code != 250 && $code != 251) {
      $this->error =
        array("error" => "RCPT not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this -> ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doReset() {
  	$this->error = null;
  	$rply = $this->SendCommand("RSET");
  	
  	$code = substr($rply,0,3);
  	
  	if($code != 250) {
      $this->error =
        array("error" => "RSET failed",
              "code" => $code,
              "msg" => substr($rply,4));
      $this->ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doSendFrom($from) {
  	$this -> error = null;
  	
  	$rply = $this -> SendCommand("SEND FROM:<",$from . ">");
  	
  	$code = substr($rply,0,3);
  	
  	if($code != 250) {
      $this->error =
        array("error" => "SEND not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this -> ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doSamlFrom($from) {
  	$this -> error = null;
  	
  	$rply = $this -> SendCommand("SMAL FROM:<",$from . ">");
  	
  	$code = substr($rply,0,3);
  	
  	if($code != 250) {
      $this->error =
        array("error" => "SAML not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this -> ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doSomlFrom($from) {
  	$this -> error = null;
  	
  	$rply = $this -> SendCommand("SOML FROM:<",$from . ">");
  	
  	$code = substr($rply,0,3);
  	
  	if($code != 250) {
      $this->error =
        array("error" => "SOML not accepted from server",
              "code" => $code,
              "msg" => substr($rply,4));
      $this -> ErrorMsg();
      return false;
    }
    return true;
  }
  
  function doTurn() {
  	$this->error = array("error" => "This method, TURN, of the SMTP ".
                                    "is not implemented");
   $this -> ErrorMsg();
    return false;
  }
  
  function doVefy($name) {
  	$this->error = null;
  	$rply = $this -> SendCommand("VRFY ",$name);
  	
  	$code = substr($rply,0,3);
  	
  	if($code != 250 && $code != 251) {
      $this->error =
        array("error" => "VRFY failed on name '$name'",
              "code" => $code,
              "msg" => substr($rply,4));
      $this -> ErrorMsg();
      return false;
    }
    return $rply;
  }
  
  
  /**
   * Send smtp command function
   *
   * @param string $command
   * @param string $name
   * @return string
   */
  function SendCommand($command,$name='') {
  	if(!$this->IsConnected()) {
      $this->error = array(
            "error" => "Called Command $command without being connected");
      $this->ErrorMsg();
      return false;
    }
    if($name) $commandstr = $command . $name .$this->endchar;
    else $commandstr = $command . $this->endchar;
  	fputs($this->conn,$commandstr);
  	$rply = $this->GetReply();
  	$this->ReplyMsg($rply);
  	return $rply;
  }
	/***************************************************************
	*                       INTERNAL FUNCTIONS                      * 
	****************************************************************/
	/**
	 * get server reply 
	 *
	 * @return string
	 */
	function GetReply() {
		if(!$this->CheckConnected()) return null;
		$data = "";
		while ($str = @fgets($this->conn,515)) {
			$this->ShowMsg("GetReply()\$data was {$data}{$this->endchar}",4);
			$this->ShowMsg("GetReply()\$str was {$str}{$this->endchar}",4);
			$data.=$str;
			$this->ShowMsg("GetReply()\$data was {$data}{$this->endchar}",4);
			
			if(substr($str,3,1) == " ") break;
		}
		return $data;
	}
	/**
	 * show error msg
	 * debug level 1
	 */
	function ErrorMsg() {
		if($this->debug>=1) {
			if($this->error['error']) echo $this->error['error'] . $this->endchar;
			if($this->error['code']) echo "error code:".$this->error['code'].$this->endchar;
			if($this->error['msg']) echo "error msg:".$this->error['msg'].$this->endchar;
		}
	}
	/**
	 * show warn msg
	 * 
	 * debug level 2
	 */
	function WarnMsg() {
		if($this->debug>=2) {
			if($this->error['error']) echo $this->error['error'] . $this->endchar;
			if($this->error['code']) echo "error code:".$this->error['code'].$this->endchar;
			if($this->error['msg']) echo "error msg:".$this->error['msg'].$this->endchar;
		}
	}
	/**
	 * show reply msg
	 *
	 * debug level 3
	 * @param string $msg
	 */
	function ReplyMsg($msg) {
		if($this->debug >=3) 
		echo "Server Reply:".$msg.$this->endchar;
	}
	/**
	 * show Say msg
	 * 
	 * debug level 3
	 * @param string $msg
	 */
	function SayMsg($msg) {
		if($this->debug >= 3)
		echo "Client Say:".$msg.$this->endchar;
	}
	/**
	 * show common msg
	 *
	 * @param string $msg msg to show
	 * @param int $dg_lv debug level
	 */
	function ShowMsg($msg,$dg_lv=1) {
		if($this->debug >= $dg_lv)
		echo $msg;
	}
}