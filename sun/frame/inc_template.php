<?php
/**
 * 模板处理类
 *
 * @author 蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id 
*/
Iimport('dbsql');
class Template{
	/**
	 * 保存解析Token 和解析结果的数组
	 *
	 * @var array $Tokens
	 */
	var $Tokens=null;						//保存解析标签的变量
	/**
	 * 要解析的模板资源字串
	 *
	 * @var string $SourceString
	 */
	var $SourceString=null;					//要解析的资源字串
	/**
	 * 模板文件完整路径
	 *
	 * @var string $File
	 */
	var $File=null;							//模板文件
	/**
	 * 是否使用缓存标记
	 *
	 * @var bool
	 */
	var $UseCache=true;						//是否使用缓存
	/**
	 * 结果模板解析结果字串
	 *
	 * @var string $ResultString
	 */
	var $ResultString=null;					//结果字串
	/**
	 * 模板Token 开始标记
	 *
	 * @var char
	 */
	var $StartChar='{';
	/**
	 * 模板Toke 结束标记
	 *
	 * @var char
	 */
	var $EndChar='}';
	/**
	 * 嵌套用标记
	 *
	 * @var unknown_type
	 */
	var $SecandTokenChar = array('[',']');
	/**
	 * 缓存文件中的数组名字标记
	 *
	 * @var string $CacheArrayName
	 */
	var $CacheArrayName="Tokens";
	/**
	 * 缓存文件名,而不是完整路径
	 *
	 * @var string $CacheFile
	 * @access private
	 */
	var $CacheFile;
	/**
	 * 缓存文件存放目录
	 *
	 * @var string $CacheDir
	 */
	var $CacheDir;
	/**
	 * PHP5构造函数
	 *
	 * @param string $file
	 */
	function __construct($file='') {
		if($file) $this->File = GetTemplate($file);
		//从文件读取解析字串
		if($this->File)	$this->LoadStringFromFile();
		$this->ReSet();
	}
	/**
	 * PHP4 构造函数
	 * 
	 *@see __construct()
	 */
	function Template($file=''){
		$this->__construct($file);
	}//end Template()
	/**
	 * 从字符串获得模板字串
	 *
	 * @param string $s 字符串
	 * @see private
	 */
	function LoadString($s="") {
		$this->SourceString = $s;
		$this->File = '';
		$this->PrepareCache($this->SourceString);
	}//end LoadString()
	/**
	 * 从模板文件获得模板字串
	 *
	 * @param string $file 完整的文件路径
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
	 * 读取一个文件,返回字符串
	 * 该函数可外部调用.但是外部调用必须指定第二个参数
	 *
	 * @param string $file 要读取的文件 缺省读取模板文件 $File
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
	 * 写文件,该函数可外部调用.但是外部调用必须指定第二个参数
	 *
	 * @param string $str 待写字符串
	 * @param 待写文件 $file 完整的文件路径,缺省写缓存文件
	 * @return bool
	 * @access public
	 */
	function WriteFile($str,$file='') {
		if(!$file) $file = $this->CacheFile;
		return WriteFile($str,$file);
	}//end writefile()
	//分析Token
	/**
	 * 分析当前模板字串中的Tokens
	 * 以数组形式存储在 $Tokens 中
	 *
	 */
	function Tokens() {
		//初始化token存储数组
		$this->Tokens = array();
		$succ = false;
		if(file_exists($this->CacheFile) && filesize($this->CacheFile)>0) {
			if(filemtime($this->CacheFile) > filemtime($this->File)){
				//必须每次都要重新 require
				@require($this->CacheFile);
				$this->Tokens = ${$this->CacheArrayName};
				$succ = true;
			}else {
				$succ = false;
			}
		}//end if
		if(!$succ){		//载入缓存失败
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
				//结束之前又发现开始标签,重新开始
				if($sp2 && $sp2 < $ep) {
					$i = $sp2;	continue;
				}//end if
				if($ep - $sp <3) {
					$i = $ep +1; continue;
				}//end if
				switch (substr($this->SourceString,$sp+1,1)) {
					//变量标签
					case ":":
					case "$":
					$rt = $this->TokenValue(substr($this->SourceString,$sp+2,$ep-$sp-2));	//2 = 1 + 1
					if ($rt!==false && is_array($rt)) {
						$rt['tp'] = 'value';	$rt['sp'] = $sp;	$rt['ep'] = $ep;
						$this->Tokens[] = $rt;	unset($rt);
						$i = $ep+1;
					}//end if
					break;
					//宏标签
					case "#":
					$rt['tp'] = "hong";
					$rt['sp'] = $sp;
					$rt['ep'] = $ep;
					$rt['hs'] = trim(substr(substr($this->SourceString,$sp,$ep-$sp),2));	//2 = 1 + 1
					$this->Tokens[] = $rt; unset($rt);
					$i = $ep +1;
					break;
					//通用扩展标签
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
					case '+':	//自增标签
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
					case '*':	//注释
					$rt['tp'] = 'notes';$rt['sp'] = $sp;$rt['ep'] = $ep;
					$this->Tokens[] = $rt;unset($rt);
					$i = $ep +1;
					 break;
					//其他流程控制标签
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
							//rewrite for if 嵌套
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
									//rewrite to slse 内嵌套
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
	 * 分析 Value 类型的 token
	 * 返回 Value 类型的token vs 和 fs 参数
	 *
	 * @param string $s 解析变量去除前后界定符后的部分
	 * @return array
	 */
	function TokenValue($s) {
		$s = trim($s);
		$len = strlen($s);
		if($len<1) return false;
		$spos = 0;
		$state = 0;				//0 查找变量，1查找函数
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
	//解析所有Token	protect
	/**
	 * 解析所有Tokens 并将结果存储于 $Tokens
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
				case 'hong'://获得宏模板替换变量，并再解析！与数据库配合适用使用
				$hn = $this->Tokens[$k]['hn'];
				/**
				 * @todo 实现 GetHongString()
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
				else {//对类,对象的方法的调用,使用::限定符
					$vv[0] = $this->ParseString($vv[0]);
					if(!is_callable($vv)) {
						$this->ImportCommonHandle($vv);
					}
					$this->Tokens[$k]['rs'] = call_user_func_array($vv,$v['fv']);
				}
				break;
				case 'notes'://done!
				break;
				case 'if'://再解析//done!
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
				//to to 语言包配置
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
	 * 解析 Value 类型的Token 
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
				if($i==0) {//如果已定义常量,则使用常量值
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
	 * 解析模板并将结果字串存储于 $ResultString
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
	 * 获得解析结果字串
	 *
	 * @return string
	 */
	function GetResult() {
		return $this->ResultString;
	}//end GetResult()
	/**
	 * 解析一个模板并输出之
	 *
	 * @param string $file 要解析的模板的完整路径,缺省时使用 $File
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
	 * 将以$var 解析到以 $varname 为标志的全局域中以便模板中使用{:varname} 调用该变量
	 * 建议总是使用该函数将变量加入全局域.这样即使以后改变该 变量域后 程序不用重写.当前,全局数据无需解析.
	 * 
	 * @param string $varname 变量名
	 * @param string $var 变量值
	 */
	function Assign($varname,$var){
		$GLOBALS[$varname] = $var;
	}
	/**
	 * 解析变量,并翻译
	 *
	 * @param string $varname 变量名
	 * @param string $var 变量值
	 */
	function LanguageAssign($varname,$var) {
		$this->Assign($varname,GetMsg($var));
	}
	/**
	 * 将解析结果字串写到静态文件,成功返回true,失败返回false
	 *
	 * @param string $file 完整的文件路径
	 * @return bool
	 */
	function WriteDocument($file) {
		return $this->WriteFile($this->ResultString,$file);
	}//end WriteDocument()
	/**
	 * 获得宏标记所代替的字串
	 *
	 * @param string $hn 宏标志字符串
	 * @return string 宏的完整字符串
	 * @todo 实现,(考虑是否使用数据库)
	 */
	function GetHongString($hn) {
		//获得宏替换串的函数
		return '';
	}//end GetHongString()
	/**
	 * 缓存前的预处理
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
	 * 快速解析一个字符串
	 * 该函数用于宏标签的快速解析,递归调用.
	 *
	 * @param string $s 需要解析的字符串
	 * @param array $tokenchar 新的模板界定符
	 * @param bool $cache 是否使用缓存
	 * @return string 解析后的字符串
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
	 * 快速解析一个文件,
	 * 该函数用于include标签的快速解析,递归调用.
	 *
	 * @param  string $file
	 * @return string 解析后的结果字符串
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
	 * 载入一个自定义的函数供模板解析使用
	 *
	 * @param mix $fn
	 */
	function ImportCommonHandle($fn) {
		if($fn && !is_array($fn)) {		//载入函数 handle
			$fn = strtolower($fn);
			if ($GLOBALS['_INMODULE_'] && file_exists(ROOT.$GLOBALS['_INMODULE_']."/frame/template_handle_dir/handle_$fn.php")) {
				@include_once(ROOT.$GLOBALS['_INMODULE_']."/frame/template_handle_dir/handle_$fn.php");
			}else if(defined('WORK_DIR') && file_exists(WORK_DIR."frame/template_handle_dir/handle_$fn.php")) {
				@include_once(WORK_DIR."frame/template_handle_dir/handle_$fn.php");
			}else if (file_exists(FRAME_ROOT."template_handle_dir/handle_$fn.php")) {
				@include_once(FRAME_ROOT."template_handle_dir/handle_$fn.php");
			}
		}else if($fn[0]) {	//载入类
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