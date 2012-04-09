<?php
/**
 * Http 操作类
 * 
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class Http {
	
	var $Scheme='http';
	
	var $Host;
	
	var $Port='80';
	
	var $Path;
	
	var $User;
	
	var $Pass;
	
	var $Query;
	
	var $Fragment;
	
	var $FullPath;
	
	var $Url;
	
	var $Info;
	
	var $Recive;
	
	var $Socket;
	
	var $HttpVersion;
	
	var $HeadInfo;
	
	var $HttpState;
	
	var $Jptimes=0;
	
	var $BaseHost;
	
	var $BaseDir;
	
	var $Error;
	
	var $Method;
	
	function __construct() {
		$this->HttpVersion = 'HTTP/1.1';
	}
	function Http() {
		return $this->__construct();
	}
	
	function OpenUrl($url='',$method='GET') {
		$this->ParseUrl($url);
		$this->Method = strtolower($method) == 'get' ? 'GET' : 'POST';
		$this->ConnectHost();
	}
	/**
	* 分析URL
	*/
	function ParseUrl($url='') {
		if($url=='') $url = $this->Url;
		if($url=='') return ;
		$urls = array();
		$urls = @parse_url($url);
		$this->Url = $url;
		if($urls['host']) $this->Host = $urls['host'];
		if($urls['scheme']) $this->Scheme = $urls['scheme'];
		if($urls['port']) $this->Port = $urls['port'];
		if($urls['pass']) $this->Pass = $urls['pass'];
		if($urls['path']) $this->Path = $urls['path'];
		if($urls['query']) $this->Query = $urls['query'];
		if($urls['fragment']) $this->Fragment = $urls['fragment'];
		
		if($this->Query) $this->FullPath = $this->Path . '?' . $this->Query;
		else $this->FullPath = $this->Path;
		
		$this->BaseHost = $this->Scheme.'://'.$this->Host;
		$this->BaseDir = substr($this->Path,0,strrpos($this->Path,'/'));
	}
	/**
	 * 连接主机
	 * 
	 */
	function ConnectHost() {
		if($this->Host) {
			$errno = '';
			$errmsg = '';
			if ($this->Port == 443) {
				$prefix = 'ssl://';
			}else {
				$prefix = 'tcp://';
			}
			$this->Socket = @fsockopen($prefix.$this->Host,$this->Port,$errno,$errmsg,30);
			if(!$this->Socket) {
				$this->Error = $errmsg;
				return false;
			}else {
				return true;
			}
		}
	}
	
	function Send($data=null) {
		$headers = array(
				'Host' => $this->Host,
				'Accept' => '*/*',
				'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
		);
		if ($this->Method == 'POST' && $data) {	//post
			if (is_array($data)) {
				$data = $this->CreatePostData($data);
			}
			
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['Content-Length'] = strlen($data);
			
			if($this->Socket && $this->FullPath) {
				fputs($this->Socket,'POST '.$this->Path . ' ' . $this->HttpVersion."\r\n");
					
				$this->PutHeader($headers);
				
				if($this->HttpVersion == 'HTTP/1.1') {
					fputs($this->Socket,"Connection: Close\r\n\r\n");
				}else {
					fputs($this->Socket,"\r\n");
				}
				fputs($this->Socket,$data);
			}
		}else if ($this->Method == 'GET') {
			if($this->Socket && $this->FullPath) {
				
				fputs($this->Socket,'GET '.$this->FullPath.' ' . $this->HttpVersion."\r\n");
				
				$this->PutHeader($headers);
				
				if($this->HttpVersion == 'HTTP/1.1') {
					fputs($this->Socket,"Connection: Close\r\n\r\n");
				}else {
					fputs($this->Socket,"\r\n");
				}
			}
		}
		
		$state = fgets($this->Socket,256);
		$state = explode(' ',$state);
		$this->HttpVersion = $state[0];
		$this->HttpState = $state[1];	
		
		if ($this->StateOK() || $this->StateLocation()) {
			while (!feof($this->Socket)) {
				$headline = trim(fgets($this->Socket,512));
				if($headline=='') break;
				$hpos = strpos($headline,':');
				if($hpos === false) break;
				$hkey = trim(substr($headline,0,$hpos));
				$hvalue= trim(substr($headline,$hpos+1));
				$this->HeadInfo[$hkey] = $hvalue;
			}
		}
//		if($this -> Method == 'GET' && $this->StateLocation() && $this->Jptimes<4) {	//GET方式可以跳转
//			$this->Jptimes ++;
//			if(eregi('^http|https|ftp',$this->GetHeader('Location'))) {
//				$this->OpenUrl($this->GetHeader('Location'));
//			}else {
//				if(ereg('^/',$this->GetHeader('Location'))) {
//					$this->OpenUrl($this->BaseHost.$this->GetHeader('Location'));
//				}else {
//					$this->OpenUrl($this->BaseHost.$this->BaseDir.'/'.$this->GetHeader('Location'));
//				}
//			}
//		}
		$this->GetData();
		return $this->Recive;
	}
	
	function CreatePostData($data=array()) {
		if (is_array($data) && $data) {
			$ar = array();
			foreach ($data as $k => $v) {
				$ar[] = "$k=".urlencode($v);
			}
			return implode('&',$ar);
		}else {
			return '';
		}
	}
	/**
	 * @access private , can only get once
	 *
	 * @return none
	 */
	function GetData() {
		if ($this->Socket) {
			$this->Recive = '';
			if(!$this->StateOK()) return '';
			while (!feof($this->Socket)) {
				$this->Recive .= @fread($this->Socket,1024);
			}
			$this->Close();
		}else {
			//error,socket closed! have loaded!
		}
	}
	/**
	 * 获得文本文件
	 *
	 * @return string
	 */
	function GetText() {
		if(!$this->StateOK()) return '';
		if($this->IsText()) {
			return $this->Recive;
		}else {
			return ;
		}
	}
	/**
	 * 判断状态是否OK
	 *
	 * @return bool
	 */
	function StateOK() {
		if(ereg('^2',$this->HttpState)) {
			return true;
		}else {
			return false;
		}
	}
	/**
	 * 判断是否是转向响应
	 *
	 * @return bool
	 */
	function StateLocation() {
		if(ereg('^3',$this->HttpState)) {
			return true;
		}else {
			return false;
		}
	}
	/**
	 * 判断是否是文本类型
	 *
	 * @return bool
	 */
	function IsText() {
		if(eregi('^text',$this->GetHeader('Content-Type'))) {
			return true;
		}else {
			return false;
		}
	}
	/**
	 * 返回头信息
	 *
	 * @param string $head
	 * @return unknown
	 */
	function GetHeader($head) {
		return $this->HeadInfo[$head];
	}
	/**
	 * 发送头信息
	 *
	 * @param array $array
	 */
	function PutHeader($array=array()) {
		if(is_array($array) && $array) {
			foreach ($array as $k => $v) {
				$k = trim($k);
				$v = trim($v);
				if($k && $v){
					fputs($this->Socket,"$k: $v\r\n");
				}
			}
		}
	}
	/**
	 * 关闭连接
	 *
	 */
	function Close() {
		@fclose($this->Socket);
		$this->Socket = null;
	}
}