<?php
/**
 * 翻页综合处理类
 * 
 * 翻页处理,提供翻页处理封装.自动计算当前page,自动计算SQL中的Limit等
 * 
 * @author 蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id
*/
class Page{
	/**
	 * 分页列表,存储列表的所有分页链接
	 *
	 * @var array $Pages
	 */
	var $Pages;							//页面列表
	/**
	 * 记录总数
	 *
	 * @var number $Maxnum
	 */
	var $Maxnum;						//页面总数
	/**
	 * 当前请求的页面数
	 *
	 * @var number $Cpage
	 */
	var $Cpage;							//当前页面
	/**
	 * 页面总数,计算得出
	 *
	 * @var number $TotalPage
	 * @access private
	 */
	var $TotalPage;						//总页数
	/**
	 * 页面规格,即每页显示多少条
	 *
	 * @var number $PageSize
	 */
	var $PageSize;						//每页数量
	/**
	 * 分页处理后返回的最终的分页链接
	 *
	 * @var string $PageLink
	 * @access private
	 */
	var $PageLink;						//翻页处理的返回值
	/**
	 * 翻页最大量,0不限制
	 *
	 * @var number $MaxPage
	 */
	var $MaxPage=4;						//翻页最大量,0不限制
	/**
	 * 用于MySQL 的Limit 数据 格式: number,number
	 *
	 * @var string $Limit
	 * @access private
	 */
	var $Limit;							//翻页类处理后的Limit 字段.
	/**
	 * 动态翻页处理的根路径
	 *
	 * @var string $BaseUrl
	 * @access private
	 */
	var $BaseUrl;						//动态翻页处理的根路径.
	/**
	 * 第一页的链接地址
	 *
	 * @var string $FirstPage
	 * @access private
	 */
	var $FirstPage;						//第一页
	/**
	 * 最后一页链接地址
	 *
	 * @var  string $LastPage
	 * @access private
	 */
	var $LastPage;						//最后一页
	
	var $PageUp;						//上一页
	
	var $PageDown;						//下一页
	/**
	 * 列表起始记录数
	 *
	 * @var number $Limit_st
	 */
	var $Limit_st=0;
	
	var $Page_tag = 'page';
	/**
	 * 翻页样式变量 , 可以使用 SetPattern() 设置
	 *
	 * @var string $Pattern
	 * @access private
	 */
	var $Pattern="<span class=\"pagelink_num\"><a href=\"{~~pagelink~~}\">{~~page~~}</a></span>";	
	/**
	 * 静态样式变量,通常不需要,可自动从Pattern获得,但也可定制 使用SetStPattern() 定制
	 *
	 * @var string $StPattern
	 * @access private
	 */
	var $StPattern="<span class=\"pagelink_num_nonce\">{~~page~~}</span>";
	/**
	 * 首页样式Pattern
	 *
	 * @var string $FpPattern
	 * @access private
	 */
	var $FpPattern="<span class=\"pagelink_pre\"><a href=\"{~~pagelink~~}\">First</a></span><span class=\"pagelink_pre\"><a href=\"{~~pageup~~}\">Pre</a></span>";
	/**
	 * 末页样式Pattern
	 *
	 * @var string $LpPattern
	 * @access private
	 */
	var $LpPattern="<span class=\"pagelink_next\"><a href=\"{~~pagedown~~}\">Next</a></span><span class=\"pagelink_next\"><a href=\"{~~pagelink~~}\">Last</a></span>";
	/**
	 * 翻页前样式Pattern
	 *
	 * @var string $PagePrePattern
	 * @access private 
	 */
	var $PagePrePattern = "<span class=\"pagelink_pre_nolink\">{~~num~~}R/{~~page~~}P {~~cpage~~}</span>";
	/**
	 * 翻页后样式Pattern
	 *
	 * @var string $PageTailPattern
	 * @access private
	 */
	var $PageTailPattern = "<span>
		  <input name=\"{~~tag~~}\" type=\"text\" id=\"{~~tag~~}\" size=\"4\" />
		  <input type=\"submit\" value=\"跳转\" />
		</span>";
	/**
	 * PHP5构造函数
	 *
	 * @param 记录总数 $maxnum
	 * @param 当前页码数 $cpage
	 * @param 每页多少条 $pagesize
	 */
	function __construct($maxnum=0,$cpage=1,$pagesize=20){
		$this->Maxnum = intval($maxnum);
		$pagesize = abs(intval($pagesize));
		$this->PageSize = $pagesize ? $pagesize:20;
		$this->TotalPage = ceil($this->Maxnum/$this->PageSize);
		$this->Cpage = min(max(1,$cpage),$this->TotalPage);
		$this->Limit_st = max(0,($this->Cpage-1)) * $this->PageSize;
		$this->Limit = $this->Limit_st.",".$this->PageSize;
		//$this->BaseUrl变量用于动态分页自动生成

			$this-> BaseUrl = $_SERVER['SCRIPT_NAME'];
			
			# for seo
			if (strpos($_SERVER['SCRIPT_NAME'],'-')) {	//采用加强的UrlRewrite时
				$this-> BaseUrl = substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],'-'));
			}
			unset($_GET[$_SERVER['QUERY_STRING']]);
			$key = str_replace('.','_',$_SERVER['QUERY_STRING']);
			unset($_GET[$key]);
			# for seo
			
			$this -> BaseUrl .= '?';
			$this -> BaseUrl .= $this->CreateQuery(array_merge($_POST,$_GET));
		isset($GLOBALS['rtc']['page_pattern']) && $this->Pattern = $GLOBALS['rtc']['page_pattern'];
		isset($GLOBALS['rtc']['page_stpattern']) && $this->StPattern = $GLOBALS['rtc']['page_stpattern'];
		isset($GLOBALS['rtc']['page_fppattern']) && $this->FpPattern = $GLOBALS['rtc']['page_fppattern'];
		isset($GLOBALS['rtc']['page_lppattern']) && $this->LpPattern = $GLOBALS['rtc']['page_lppattern'];
		isset($GLOBALS['rtc']['page_pageprepattern']) && $this->PagePrePattern = $GLOBALS['rtc']['page_pageprepattern'];
		isset($GLOBALS['rtc']['page_pagetailpattern']) && $this->PageTailPattern = $GLOBALS['rtc']['page_pagetailpattern'];
	}
	/**
	 * PHP4构造函数
	 *
	 *@see __construct()
	 */
	function Page($maxnum=0,$cpage=1,$pagesize=20){
		$this->__construct($maxnum,$cpage,$pagesize);
	}
	
	/**
	 * 设置动态分页
	 *
	 */
	function SetDpages(){
		$ar = array();
		if ($this->Cpage>1) {	//设置首页、上一页链接
			$this->FirstPage = $this->BaseUrl."{$this->Page_tag}=1";
			$this->PageUp = $this->BaseUrl."{$this->Page_tag}=" . ($this->Cpage-1);
		}
		if ($this -> Cpage < $this->TotalPage) {	//设置最后页、下一页链接
			$this->LastPage = $this->BaseUrl."{$this->Page_tag}={$this->TotalPage}";
			$this->PageDown = $this->BaseUrl."{$this->Page_tag}=" . ($this->Cpage+1);
		}
		//设置其他链接
		for ($i=1;$i<=$this->TotalPage;$i++){
			if($this->MaxPage >0 && abs($i-$this->Cpage)>$this->MaxPage) {
				continue;
			}
			$ar[$i] = $this->BaseUrl."{$this->Page_tag}={$i}";
		}
		$this->SetPages($ar);
	}
	/**
	 * 设置静态分页,该分页方法必须给出一个包含{~~page~~} 的pattern
	 * @example $pattern = "list_{~~page~~}.html" {~~page~~}将被替换成页码数字
	 *
	 * @param string $pattern
	 */
	function SetSpages($pattern){
		$ar = array();
		if ($this->Cpage>1) {	//设置首页、上一页链接
			$this->FirstPage = str_ireplace("{~~page~~}",1,$pattern);
			$this->PageUp = str_ireplace("{~~page~~}",$this->Cpage-1,$pattern);
		}
		if ($this -> Cpage < $this->TotalPage) {	//设置最后页、下一页链接
			$this->LastPage = str_ireplace("{~~page~~}",$this->TotalPage,$pattern);
			$this->PageDown = str_ireplace("{~~page~~}",$this->Cpage+1,$pattern);
		}
		for ($i=1;$i<=$this->TotalPage;$i++){
			$ar[$i] = str_ireplace("{~~page~~}",$i,$pattern);
		}
		$this -> SetPages($ar);
	}
	/**
	 * 将一个经过处理的分页数据数组设置给 Pages 供后面处理
	 * 该方法被 SetDpages() SetSpages()调用的.是他们的公共部分
	 *
	 * @param array $array
	 * @access private
	 */
	function SetPages($array){
		$this-> Pages = $array;
		//这里做更多处理!,如过滤多余变量等,通常不需要.
	}
	/**
	 * 设置翻页Pattern
	 *
	 * @param string $p
	 */
	function SetPattern($p) {
		$this->Pattern = $p;
	}
	/**
	 * 设置当前页pattern
	 * 
	 * @param string $p
	 */
	function SetStPattern($p) {
		$this->StPattern = $p;
	}
	/**
	 * 设置首页样式pattern
	 *
	 * @param string $p
	 */
	function SetFpPattern($p) {
		$this->FpPattern = $p;
	}
	/**
	 * 设置末页样式pattern
	 *
	 * @param string $p
	 */
	function SetLpPattern($p) {
		$this->LpPattern = $p;
	}
	/**
	 * 设置翻页前样式
	 *
	 * @param string $p
	 */
	function SetPpPattern($p) {
		$this->PagePrePattern = $p;
	}
	/**
	 * 设置翻页后样式
	 *
	 * @param string $p
	 */
	function SetPtPattern($p) {
		$this->PageTailPattern = $p;
	}
	/**
	 * 获得完整的分页代码串
	 *
	 * @return string 返回完整的分页代码串
	 */
	function GetPageLink($seo=false){
		
		if(empty($this->StPattern)) $this->StPattern = ereg_replace("/<a(.*)href=\"(.*)\"(.*)>(.*)</a>/isU","\\4",$this->Pattern);
		if(!is_array($this->Pages)){
			return "";
		}
		
		foreach ($this->Pages as $k => $v){
			
			if(!is_numeric($k)){
				unset($this->Pages[$k]);
				continue;
			}
			
			if($k == $this->Cpage){
				$str = $this->StPattern;
			}else{
				$str = $this->Pattern;
			}
			if ($seo) {
//				$str = ereg_replace('"','',$str);
				$v = $GLOBALS['__seo'] -> BaseUrlChange($v);
			}
			$str = str_replace(array('{~~page~~}','{~~pagelink~~}'),array($k,$v),$str);
			$this->PageLink .= $str;
		}
		//Add more 翻页修饰
		$pagepre = str_replace(array('{~~num~~}','{~~page~~}','{~~cpage~~}'),array($this->Maxnum,$this->TotalPage,$this->Cpage),$this->PagePrePattern);
		//add 首页
		if($this->Cpage>1) {
			if ($seo) {
				$this->FirstPage = $GLOBALS['__seo'] -> BaseUrlChange($this->FirstPage);
			}
			$pagepre .= str_replace(array('{~~pagelink~~}','{~~pageup~~}'),array($this->FirstPage,$this->PageUp),$this->FpPattern);
		}
		
		$this -> PageLink = $pagepre.$this->PageLink;
		
		if($this->Cpage<$this->TotalPage) {
			if ($seo) {
				$this->LastPage = $GLOBALS['__seo'] -> BaseUrlChange($this->LastPage);
			}
			$pageaft = str_replace(array('{~~pagelink~~}','{~~page~~}','{~~pagedown~~}')
								,array($this->LastPage,$this->TotalPage,$this->PageDown),$this->LpPattern);
		}
		$this -> PageLink .= $pageaft;
		
		$this -> PageLink .= str_replace(array('{~~cpage~~}','{~~tag~~}'),array($this->Cpage,$this->Page_tag),$this->PageTailPattern);
		
		//Add A Form;
		if (eregi('{~~tag~~}', $this->PageTailPattern)) {
			$this->PageLink = '<form action="' . $this->BaseUrl . '">' . $this-> PageLink . $this->CreateHiddenInput(array_merge($_POST,$_GET)) . '</form>';
		}
		$this -> PageLink = "<div class=\"pagelink\">{$this->PageLink}</div>";
		
		// to do:
		//add a select!
		return $this -> PageLink;
	}
	function CreateQuery($query,$key='') {
		$rtq = '';
		if(is_array($query)) {
			foreach ($query as $k => $v) {
				if(!(empty($key) && $k == $this->Page_tag)){
					if(!is_array($v)) {
						if(empty($key)) $rtq .= "{$k}=".urlencode($v)."&";
						else $rtq .= $key."[$k]=".urlencode($v)."&";
					}else {
						if($key) $kk = $key . "[$k]";
						else $kk = $k;
						$rtq .= $this-> CreateQuery($v,$kk);
					}
				}
			}
		}
		return $rtq;
	}
	function CreateHiddenInput($query,$key='') {
		$rtq = '';
		if(is_array($query)) {
			foreach ($query as $k => $v) {
				if(!(empty($key) && $k == $this->Page_tag)){
					if(!is_array($v)) {
						if(empty($key)) $rtq .= "<input name=\"$k\" type=\"hidden\" value=\"$v\" />";
						else $rtq .= "<input name=\"{$key}[{$k}]\" type=\"hidden\" value=\"$v\" />";
					}else {
						if($key) $kk = $key . "[$k]";
						else $kk = $k;
						$rtq .= $this-> CreateHiddenInput($v,$kk);
					}
				}
			}
		}
		return $rtq;
	}
}