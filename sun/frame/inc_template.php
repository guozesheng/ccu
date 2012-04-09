<?php
/**
 * ģ�崦����
 *
 * @author ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id 
*/
Iimport('dbsql');
class Template{
	/**
	 * �������Token �ͽ������������
	 *
	 * @var array $Tokens
	 */
	var $Tokens=null;						//���������ǩ�ı���
	/**
	 * Ҫ������ģ����Դ�ִ�
	 *
	 * @var string $SourceString
	 */
	var $SourceString=null;					//Ҫ��������Դ�ִ�
	/**
	 * ģ���ļ�����·��
	 *
	 * @var string $File
	 */
	var $File=null;							//ģ���ļ�
	/**
	 * �Ƿ�ʹ�û�����
	 *
	 * @var bool
	 */
	var $UseCache=true;						//�Ƿ�ʹ�û���
	/**
	 * ���ģ���������ִ�
	 *
	 * @var string $ResultString
	 */
	var $ResultString=null;					//����ִ�
	/**
	 * ģ��Token ��ʼ���
	 *
	 * @var char
	 */
	var $StartChar='{';
	/**
	 * ģ��Toke �������
	 *
	 * @var char
	 */
	var $EndChar='}';
	/**
	 * Ƕ���ñ��
	 *
	 * @var unknown_type
	 */
	var $SecandTokenChar = array('[',']');
	/**
	 * �����ļ��е��������ֱ��
	 *
	 * @var string $CacheArrayName
	 */
	var $CacheArrayName="Tokens";
	/**
	 * �����ļ���,����������·��
	 *
	 * @var string $CacheFile
	 * @access private
	 */
	var $CacheFile;
	/**
	 * �����ļ����Ŀ¼
	 *
	 * @var string $CacheDir
	 */
	var $CacheDir;
	/**
	 * PHP5���캯��
	 *
	 * @param string $file
	 */
	function __construct($file='') {
		if($file) $this->File = GetTemplate($file);
		//���ļ���ȡ�����ִ�
		if($this->File)	$this->LoadStringFromFile();
		$this->ReSet();
	}
	/**
	 * PHP4 ���캯��
	 * 
	 *@see __construct()
	 */
	function Template($file=''){
		$this->__construct($file);
	}//end Template()
	/**
	 * ���ַ������ģ���ִ�
	 *
	 * @param string $s �ַ���
	 * @see private
	 */
	function LoadString($s="") {
		$this->SourceString = $s;
		$this->File = '';
		$this->PrepareCache($this->SourceString);
	}//end LoadString()
	/**
	 * ��ģ���ļ����ģ���ִ�
	 *
	 * @param string $file �������ļ�·��
	 * @return bool
	 * @access private
	 */
	function LoadStringFromFile($file = '') {
		!$file && $file = $this->File;
		$file = GetTemplate($file);
		if(file_exists($file)) {
			$this->File = $file;
			$this->SourceString = $this->ReadFile();
			$this->PrepareCache();
			return true;
		}//end if
		return false;
	}//end LoadStringFromFile
	/**
	 * ��ȡһ���ļ�,�����ַ���
	 * �ú������ⲿ����.�����ⲿ���ñ���ָ���ڶ�������
	 *
	 * @param string $file Ҫ��ȡ���ļ� ȱʡ��ȡģ���ļ� $File
	 * @return string
	 * @access public
	 */
	function ReadFile($file="") {
		if(!$file) $file = $this->File;
		if(file_exists($file)){
			$hand = fopen($file,'rb');
		}//end if
		$result = "";
		if(is_resource($hand)) {
			while ($r = fgets($hand,1024)) {
				$result .= $r;
			}//end while
		}//end if
		return $result;
	}//end ReadFile()
	/**
	 * д�ļ�,�ú������ⲿ����.�����ⲿ���ñ���ָ���ڶ�������
	 *
	 * @param string $str ��д�ַ���
	 * @param ��д�ļ� $file �������ļ�·��,ȱʡд�����ļ�
	 * @return bool
	 * @access public
	 */
	function WriteFile($str,$file='') {
		if(!$file) $file = $this->CacheFile;
		return WriteFile($str,$file);
	}//end writefile()
	//����Token
	/**
	 * ������ǰģ���ִ��е�Tokens
	 * ��������ʽ�洢�� $Tokens ��
	 *
	 */
	function Tokens() {
		//��ʼ��token�洢����
		$this->Tokens = array();
		$succ = false;
		if(file_exists($this->CacheFile) && filesize($this->CacheFile)>0) {
			if(filemtime($this->CacheFile) > filemtime($this->File)){
				//����ÿ�ζ�Ҫ���� require
				@require($this->CacheFile);
				$this->Tokens = ${$this->CacheArrayName};
				$succ = true;
			}else {
				$succ = false;
			}
		}//end if
		if(!$succ){		//���뻺��ʧ��
			$sp = 0;
			$ep = 0;
			$sl = strlen($this->SourceString);
			if($sl<3) return ;
			for ($i=0;$i<$sl;){
				$sp = strpos($this->SourceString,$this->StartChar,$i);	//start
				$ep = strpos($this->SourceString,$this->EndChar,$sp);	//end to rewrite
				$sp2 = strpos($this->SourceString,$this->StartChar,$sp+1);	//next start
				if($sp === false || $ep == false){
					break;
				}//end if
				//����֮ǰ�ַ��ֿ�ʼ��ǩ,���¿�ʼ
				if($sp2 && $sp2 < $ep) {
					$i = $sp2;	continue;
				}//end if
				if($ep - $sp <3) {
					$i = $ep +1; continue;
				}//end if
				switch (substr($this->SourceString,$sp+1,1)) {
					//������ǩ
					case ":":
					case "$":
					$rt = $this->TokenValue(substr($this->SourceString,$sp+2,$ep-$sp-2));	//2 = 1 + 1
					if ($rt!==false && is_array($rt)) {
						$rt['tp'] = 'value';	$rt['sp'] = $sp;	$rt['ep'] = $ep;
						$this->Tokens[] = $rt;	unset($rt);
						$i = $ep+1;
					}//end if
					break;
					//���ǩ
					case "#":
					$rt['tp'] = "hong";
					$rt['sp'] = $sp;
					$rt['ep'] = $ep;
					$rt['hs'] = trim(substr(substr($this->SourceString,$sp,$ep-$sp),2));	//2 = 1 + 1
					$this->Tokens[] = $rt; unset($rt);
					$i = $ep +1;
					break;
					//ͨ����չ��ǩ
					case "@":
					$rt['tp'] = 'common';
					$rt['sp'] = $sp;
					$rt['ep'] = $ep;
					$tmp = trim(substr($this->SourceString,$sp+2,$ep-$sp-2));	//2 = 1 + 1
					$fa = explode(' ',$tmp);
					$rt['fn'] = $fa[0];
					unset($fa[0]);
					$fv = explode(',',implode(' ',$fa));
					$rt['fv'] = $fv;
					
					$this->Tokens[] = $rt; unset($rt);
					$i = $ep +1;
					break;
					case '+':	//������ǩ
					$rt['tp'] = 'auto';
					$rt['sp'] = $sp;
					$rt['ep'] = $ep;
					$tmp = trim(substr($this->SourceString,$sp+2,$ep-$sp-2));	//2 = 1 + 1
					$fa = explode(' ',$tmp);
					$rt['tk'] = $fa[0];
					$rt['st'] = $fa[1];
					$rt['add'] = isset($fa[2]) ? intval($fa[2]) : 1;
					$rt['rst'] = isset($fa[3]) ? $fa[3] : '';
					
					$this->Tokens[] = $rt; unset($rt);
					$i = $ep +1;
					break;
					case '*':	//ע��
					$rt['tp'] = 'notes';$rt['sp'] = $sp;$rt['ep'] = $ep;
					$this->Tokens[] = $rt;unset($rt);
					$i = $ep +1;
					 break;
					//�������̿��Ʊ�ǩ
					default:
						$tk = trim(substr($this->SourceString,$sp+1,$ep-$sp-1));
						$tk = explode(' ',$tk);	$tk0 = $tk[0];
						switch ($tk0) {
							case 'php':
							$ssp = strpos($this->SourceString,"{$this->StartChar}/php{$this->EndChar}",$ep);
							if($ssp===false) {$i=$ep+1;	break;}
							$rt['tp'] = 'php'; $rt['sp'] = $sp; $rt['ep'] = $ssp+5;		//5 = 4 + 1
							$rt['ps'] = trim(substr($this->SourceString,$ep+1,$ssp-$ep-1));
							$this->Tokens[] = $rt;	$i = $rt['ep']+1;	unset($rt);
							break;
							
							case 'notes':
							$ssp = strpos($this->SourceString,"{$this->StartChar}/notes{$this->EndChar}",$ep);
							if($ssp===false){$i=$ep+1;	break;}
							$rt['tp'] = 'notes';	$rt['sp'] = $sp;	$rt['ep'] = $ssp+7;
							$this->Tokens[] = $rt;	$i = $rt['ep'] +1; unset($rt);
							break;
							//rewrite for if Ƕ��
							case 'if':
							$ssf = $ssi = $sse = $ep;
							$fend=0;
							while (true) {
								$ssf = strpos($this->SourceString,$this->StartChar.'if'.$this->EndChar,$ssf+1);
								$sse = strpos($this->SourceString,$this->StartChar.'/endif'.$this->EndChar,$sse+1);
								if($sse ===false || $ssf === false) break;
								if($ssf>$sse) break;
							}
							if ($sse === false) {
								$i=$ep+1;	break;
							}
							$ssp = strpos($this->SourceString,"{$this->StartChar}/if{$this->EndChar}",$ep);
							if($ssp === false) {$i=$ep+1;	break;}
							$rt['tp'] = 'if';	$rt['sp'] = $sp;	$rt['ep'] = $sse+7;
							$rt['ic'] = trim(substr($this->SourceString,$ep+1,$ssp-$ep-1));
							$rt['ir'] = substr($this->SourceString,$ssp+5,$sse-$ssp-5);

							$ssp3 = strpos($this->SourceString,$this->StartChar.'else'.$this->EndChar,$rt['ep']+1);
							$sspif = strpos($this->SourceString,$this->StartChar.'if'.$this->EndChar,$rt['ep']+1);
							if($ssp3 === false) {$i = $rt['ep']+1;} else {
								if($sspif === false || $sspif>$ssp3) {
									//rewrite to slse ��Ƕ��
									$ssp4 = strpos($this->SourceString,$this->StartChar.'/else'.$this->EndChar,$ssp3+6);
									if($ssp4 === false) {$i = $rt['ep']+1;}
									$rt['ep'] = $ssp4 + 6;
									$rt['er'] = substr($this->SourceString,$ssp3+6,$ssp4-$ssp3-6);
								}//end if
							}//end else
							$i = $rt['ep']+1;	$this->Tokens[]=$rt;	unset($rt);
							break;
							
							case 'foreach':
							$ssf = $ssp = $ep;
							$fend = 0;
							while (true) {
								$ssf = strpos($this->SourceString,$this->StartChar.'foreach',$ssf+1);
								$eef = strpos($this->SourceString,$this->EndChar,$ssf+8);
								$ssp = strpos($this->SourceString,$this->StartChar.'/foreach'.$this->EndChar,$ssp+1);
								if($ssf===false || $ssp ===false) break;
								if($eef>$ssp) break;
							}//end while
							if($ssp == false) {$i=$ep+1;	break;}
							$rt['tp'] = 'foreach';	$rt['sp'] = $sp;	$rt['ep'] = $ssp + 9;
							$rt['fs'] = substr($this->SourceString,$ep+1,$ssp-$ep-1);
							$rt['fa'] = $tk[1];	$rt['fk'] = $tk[2];	$rt['fv'] =	$tk[3];
							$this->Tokens[] = $rt;
							$i = $rt['ep'] +1; unset($rt);
							break;
							case 'loop':
							$ssf = $ssp = $ep;
							$fend = 0;
							while (true) {
								$ssf = strpos($this->SourceString,$this->StartChar.'loop',$ssf+1);
								$eef = strpos($this->SourceString,$this->EndChar,$ssf+5);
								$ssp = strpos($this->SourceString,$this->StartChar.'/loop'.$this->EndChar,$ssp+1);
								if($ssf===false || $ssp ===false) break;
								if($eef>$ssp) break;
							}//end while
							if($ssp == false) {$i=$ep+1;	break;}
							$rt['tp'] = 'foreach';	$rt['sp'] = $sp;	$rt['ep'] = $ssp + 6;
							$rt['fs'] = substr($this->SourceString,$ep+1,$ssp-$ep-1);
							$rt['fa'] = $tk[1];	$rt['fk'] = $tk[2];	$rt['fv'] =	$tk[3];
							$this->Tokens[] = $rt;
							$i = $rt['ep'] +1; unset($rt);
							break;
							case 'include':
							$rt['tp'] = 'include';	$rt['sp'] = $sp;	$rt['ep'] = $ep;
							$rt['file'] = $tk[1];
							if(isset($tk[2])) $rt['ps'] = $tk[2];
							$this->Tokens[] = $rt;
							$i = $rt['ep']+1;	unset($rt);
							break;
							case 'lang':
							$rt['tp'] = 'lang';		$rt['sp'] = $sp;	$rt['ep'] = $ep;
							$rt['lang'] = $tk[1];	$rt['group'] = $tk[2];
							break;
							default:
							$i = $ep+1;
							break;
						}//end switch
					break;
				}//end switch
			}//end for
			//write cache;
			if($this->UseCache && $this->Tokens && is_writable($this->CacheDir) && $this->CacheFile) {
				$ts = PP_var_export($this->Tokens);
				$str = "<?php\r\n\${$this->CacheArrayName} = {$ts}\r\n?>";
				$this->WriteFile($str);
			}//end if
		}//end else if
	}//end Tokens()
	/**
	 * ���� Value ���͵� token
	 * ���� Value ���͵�token vs �� fs ����
	 *
	 * @param string $s ��������ȥ��ǰ��綨����Ĳ���
	 * @return array
	 */
	function TokenValue($s) {
		$s = trim($s);
		$len = strlen($s);
		if($len<1) return false;
		$spos = 0;
		$state = 0;				//0 ���ұ�����1���Һ���
		$vs = "";
		$fs = null;
		for($i=$spos;$i<$len;) {
			$tp = strpos($s,'|',$spos);
			if($state==0) {
				if($tp === false) {
					$vs = substr($s,$spos,$len-$spos);
					break;
				}else {
					$vs = substr($s,$spos,$tp-$spos);
					$i = $spos = $tp+1;
					$state=1;
					continue;
				}//end else
			}else {
				if($tp === false) {
					$fstring = trim(substr($s,$spos,$len-$spos));
					if(empty($fstring)) break;
					$fa = explode(' ',$fstring);
					$tmp['fn'] = $fa[0];
					unset($fa[0]);
					$fv = explode(',',implode(' ',$fa));
					$tmp['fv'] = $fv;
					$fs[] = $tmp;
					break;
				}else {
					$fstring = trim(substr($s,$spos,$tp-$spos));
					if(empty($fstring)) continue;
					$fa = explode(' ',$fstring);
					$tmp['fn'] = $fa[0];
					unset($fa[0]);
					$fv = explode(',',implode(' ',$fa));
					foreach ($fv as $k => $v) {
						$fv[$k] = $v;
					}
					$tmp['fv'] = $fv;
					$fs[] = $tmp;
					$i=$spos = $tp+1;
					continue;
				}//end else
			}//end else
		}//end for
		$rt = array('vs' => eregi_replace('-&gt;','->',$vs),'fs' => $fs);
		return $rt;
	}//end TokenValue()
	//��������Token	protect
	/**
	 * ��������Tokens ��������洢�� $Tokens
	 *
	 */
	function ParseTokens() {
		if(!is_array($this->Tokens) || count($this->Tokens)<=0)
		return ;
		foreach ($this->Tokens as $k => $v) {
			switch ($v['tp']) {
				case 'value'://done!
				$this->Tokens[$k]['rs'] = $this->ParseValueToken($v);
				break;
				case 'hong'://��ú�ģ���滻���������ٽ����������ݿ��������ʹ��
				$hn = $this->Tokens[$k]['hn'];
				/**
				 * @todo ʵ�� GetHongString()
				 */
				$hs = $this->GetHongString($hn);
				$this -> Tokens[$k]['rs'] = $this -> ParseString($hs);
				break;
				case 'php'://done!
				ob_start();
				@eval($this->ParseString($v['ps']).";");
				$this->Tokens[$k]['rs'] = ob_get_clean();
				break;
				case 'common'://done!
				$v['fn'] = $this->ParseString($v['fn'],$this->SecandTokenChar);
				foreach ($v['fv'] as $kk => $vv) {
					$v['fv'][$kk] = $this->ParseString($vv,$this->SecandTokenChar);
				}
				$vv = explode('::',$v['fn']);
				if(count($vv)==1) {
					if(!is_callable($v['fn'])){
						$this->ImportCommonHandle($v['fn']);
					}
					$this->Tokens[$k]['rs'] = @call_user_func_array($v['fn'],$v['fv']);
				}
				else {//����,����ķ����ĵ���,ʹ��::�޶���
					$vv[0] = $this->ParseString($vv[0]);
					if(!is_callable($vv)) {
						$this->ImportCommonHandle($vv);
					}
					$this->Tokens[$k]['rs'] = call_user_func_array($vv,$v['fv']);
				}
				break;
				case 'notes'://done!
				break;
				case 'if'://�ٽ���//done!
				$tmp = $this->ParseString($this->Tokens[$k]['ic']);
				@eval("\$tmp = $tmp;");
				if($tmp) $this->Tokens[$k]['rs'] = $this->ParseString($v['ir']);
				else if(isset($v['er']))$this->Tokens[$k]['rs'] = $this->ParseString($v['er']);
				break;
				case 'foreach'://done!
				$array = $this->TokenValue($v['fa']);
				$array = $this->ParseValueToken($array);
				
				if(!is_array($array)) {
					$this->Tokens[$k]['rs'] = "";
					break;
				}//end if
				foreach ($array as $kk => $vv) {
					$GLOBALS[$v['fk']] = $kk;
					$GLOBALS[$v['fv']] = $vv;
					$this->Tokens[$k]['rs'] .= $this->ParseString($v['fs']);
				}//end foreach
				break;
				case 'include':
				$file = $v['file'];
				if(file_exists($file)) ;
				else if(file_exists(ROOT.$file)) $file = ROOT.$file;
				else $file = GetTemplate($file);
				if(isset($v['ps']) && $v['ps']) $string = $this->ParseFile($file);
				else $string = $this->ReadFile($file);
				$this->Tokens[$k]['rs'] = $string;
				break;
				//to to ���԰�����
				case 'lang':
				break;
				case 'auto':
				if ($v['tk']) {
					if ($v['rst']) {
						$GLOBALS['__Auto_Increament'][$v['tk']] = $v['st'];
						$this ->Tokens[$k]['rs'] = '';
					}else {
						if (isset($GLOBALS['__Auto_Increament'][$v['tk']])) {
							//
						}else {
							$GLOBALS['__Auto_Increament'][$v['tk']] = isset($v['st']) ? $v['st'] : 0;
						}
						$this ->Tokens[$k]['rs'] = $GLOBALS['__Auto_Increament'][$v['tk']];
						$GLOBALS['__Auto_Increament'][$v['tk']] += $v['add'];
					}
				}
				break;
			}//end switch
		}//end foreach
	}//end ParseTokens()
	/**
	 * ���� Value ���͵�Token 
	 *
	 * @param array $va
	 * @return mix
	 * @access private
	 */
	function ParseValueToken($va) {
		if(!is_array($va)) return "";
		if(!isset($va['vs'])) return "";
		$vs = $this->ParseString($va['vs'],$this->SecandTokenChar);
		$ta = preg_split("/(\.)|(->)/",$vs,-1,PREG_SPLIT_DELIM_CAPTURE);
		$value = "";
		$i = 0;
		$type = 'array';
		foreach ($ta as $k => $v) {
			$v = trim($v);
			if($v=='.') {
				if($i==0) return ;
				$type = 'array';
			}else if($v=='->') {
				if($i==0) return ;
				$type = 'object';
			}else {
				if($i==0) {//����Ѷ��峣��,��ʹ�ó���ֵ
					if(defined($v)){ 
						$value = constant($v);
						break;
					}
				}//end if
				if($type=='array'){
					if($value!='') {
						if(is_array($value)) $value=$value[$v];
					}//end if
					else if(isset($GLOBALS[$v])) $value = $GLOBALS[$v];
				}//end if
				else if($type=='object'){
					if(!is_object($value)) return ;
					$value=$value->$v;
				}//end else if
				$type = 'value';
			}//end else
			$i++;
		}//end foreach
		if(empty($va['fs'])) return $value;
		else if(is_array($va['fs'])) {
			foreach ($va['fs'] as $k => $v) {
				$fs = $this->ParseString($v['fn'],$this->SecandTokenChar);
				if(!is_callable($fs)){
					$this->ImportCommonHandle($fs);
				}
				if(is_callable($fs)) {
					foreach ($v['fv'] as $kk => $vv) {
						if($vv=='@') $v['fv'][$kk]=$value;
						else $v['fv'][$kk] = $this->ParseString($vv,$this->SecandTokenChar);
					}//end foreach
					$value = @call_user_func_array($fs,$v['fv']);
				}//end if
			}//end foreach
			return $value;
		}//end elseif
	}//end ParseValueToken()
	/**
	 * ����ģ�岢������ִ��洢�� $ResultString
	 *
	 */
	function ParseTemplate() {
		$this->Tokens();
		$this->ParseTokens();
		if(!is_array($this->Tokens) || count($this->Tokens)<=0) {
			$this->ResultString=$this->SourceString; return ;
		}//end if
		$rs = '';
		$sp = 0;
		foreach ($this->Tokens as $k => $v) {
			$rs .= substr($this->SourceString,$sp,$v['sp']-$sp);
			isset($v['rs']) && $rs .= $v['rs'];
			$sp = $v['ep']+1;
		}//end foreach
		$rs .= substr($this->SourceString,$sp);
		$this->ResultString = $rs;
	}//end ParseTemplate()
	
	/**
	 * ��ý�������ִ�
	 *
	 * @return string
	 */
	function GetResult() {
		return $this->ResultString;
	}//end GetResult()
	/**
	 * ����һ��ģ�岢���֮
	 *
	 * @param string $file Ҫ������ģ�������·��,ȱʡʱʹ�� $File
	 */
	function DisPlay($file='') {
		echo $this->Parse($file);
	}//end DisPlay()
	
	function Parse($file='') {
		if($file) {
			if($this->LoadStringFromFile($file))
			$this->ParseTemplate();
		}//end if
		return $this->ResultString;
	}
	/**
	 * ����$var �������� $varname Ϊ��־��ȫ�������Ա�ģ����ʹ��{:varname} ���øñ���
	 * ��������ʹ�øú�������������ȫ����.������ʹ�Ժ�ı�� ������� ��������д.��ǰ,ȫ�������������.
	 * 
	 * @param string $varname ������
	 * @param string $var ����ֵ
	 */
	function Assign($varname,$var){
		$GLOBALS[$varname] = $var;
	}
	/**
	 * ��������,������
	 *
	 * @param string $varname ������
	 * @param string $var ����ֵ
	 */
	function LanguageAssign($varname,$var) {
		$this->Assign($varname,GetMsg($var));
	}
	/**
	 * ����������ִ�д����̬�ļ�,�ɹ�����true,ʧ�ܷ���false
	 *
	 * @param string $file �������ļ�·��
	 * @return bool
	 */
	function WriteDocument($file) {
		return $this->WriteFile($this->ResultString,$file);
	}//end WriteDocument()
	/**
	 * ��ú�����������ִ�
	 *
	 * @param string $hn ���־�ַ���
	 * @return string ��������ַ���
	 * @todo ʵ��,(�����Ƿ�ʹ�����ݿ�)
	 */
	function GetHongString($hn) {
		//��ú��滻���ĺ���
		return '';
	}//end GetHongString()
	/**
	 * ����ǰ��Ԥ����
	 *
	 */
	function PrepareCache($string='') {
		if(!$string && $this->File){
			$this->CacheDir = (defined('WORK_DIR') ? WORK_DIR : ROOT ) . 'temp/template_cache_dir/';
		}else {
			$this->CacheDir = (defined('WORK_DIR') ? WORK_DIR : ROOT ) . 'temp/template_cache_dir/';
		}
		!file_exists($this->CacheDir) && PPMakeDir($this->CacheDir);
		if($this->UseCache && $string) {
			is_array($string) && $string = serialize($string);
			$this->CacheFile = $this->CacheDir . md5($string) .'.scache';
		}else if($this->UseCache && $this->File){
			$file = str_replace(array(':\\','/','\\',':/'),'-',$this->File);
			$this->CacheFile = $this->CacheDir . $file . '.cache';
		}else {
			$this->CacheFile = "";
		}//end else
	}//end PrepareCache()
	/**
	 * ���ٽ���һ���ַ���
	 * �ú������ں��ǩ�Ŀ��ٽ���,�ݹ����.
	 *
	 * @param string $s ��Ҫ�������ַ���
	 * @param array $tokenchar �µ�ģ��綨��
	 * @param bool $cache �Ƿ�ʹ�û���
	 * @return string ��������ַ���
	 */
	function ParseString($s,$tokenchar=array(),$cache=true) {
		$tmp = new Template();
		if(!$cache) $tmp -> UseCache = false;
		$tmp -> SetTokenChar($tokenchar);
		$tmp -> LoadString($s);
		$tmp -> ParseTemplate();
		return $tmp -> GetResult();
	}
	/**
	 * ���ٽ���һ���ļ�,
	 * �ú�������include��ǩ�Ŀ��ٽ���,�ݹ����.
	 *
	 * @param  string $file
	 * @return string ������Ľ���ַ���
	 */
	function ParseFile($file) {
		$tmp = new Template($file);
		$tmp -> ParseTemplate();
		return $tmp -> GetResult();
	}
	
	function ReSet() {
		$this->Tokens = array();
		$this->ResultString = null;
	}
	/**
	 * ����һ���Զ���ĺ�����ģ�����ʹ��
	 *
	 * @param mix $fn
	 */
	function ImportCommonHandle($fn) {
		if($fn && !is_array($fn)) {		//���뺯�� handle
			$fn = strtolower($fn);
			if ($GLOBALS['_INMODULE_'] && file_exists(ROOT.$GLOBALS['_INMODULE_']."/frame/template_handle_dir/handle_$fn.php")) {
				@include_once(ROOT.$GLOBALS['_INMODULE_']."/frame/template_handle_dir/handle_$fn.php");
			}else if(defined('WORK_DIR') && file_exists(WORK_DIR."frame/template_handle_dir/handle_$fn.php")) {
				@include_once(WORK_DIR."frame/template_handle_dir/handle_$fn.php");
			}else if (file_exists(FRAME_ROOT."template_handle_dir/handle_$fn.php")) {
				@include_once(FRAME_ROOT."template_handle_dir/handle_$fn.php");
			}
		}else if($fn[0]) {	//������
			$class = $fn[0];
//			Iimport($class);
			if($class=='dbsql') require_once(FRAME_ROOT."inc_db_mysql.php");
			else if(file_exists(FRAME_ROOT."inc_{$class}.php")) {
				@include_once(FRAME_ROOT."inc_{$class}.php");
			}else if($GLOBALS['_INMODULE_'] && file_exists(ROOT.$GLOBALS['_INMODULE_']."/frame/inc_{$class}.php")) {
				@include_once(ROOT.$GLOBALS['_INMODULE_']."/frame/inc_{$class}.php");
			}else if(defined('WORK_DIR') && file_exists(WORK_DIR."frame/inc_{$class}.php")) {
				@include_once(WORK_DIR."frame/inc_{$class}.php");
			}
		}
	}
	
	function SetTokenChar($char = array()) {
		if(is_array($char) && $char && $char[0] && $char[1]) {
			$this->StartChar = $char[0];
			$this->EndChar = $char[1];
		}
	}
}//end class