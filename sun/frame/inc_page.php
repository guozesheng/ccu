<?php
/**
 * ��ҳ�ۺϴ�����
 * 
 * ��ҳ����,�ṩ��ҳ�����װ.�Զ����㵱ǰpage,�Զ�����SQL�е�Limit��
 * 
 * @author ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 * @version $id
*/
class Page{
	/**
	 * ��ҳ�б�,�洢�б�����з�ҳ����
	 *
	 * @var array $Pages
	 */
	var $Pages;							//ҳ���б�
	/**
	 * ��¼����
	 *
	 * @var number $Maxnum
	 */
	var $Maxnum;						//ҳ������
	/**
	 * ��ǰ�����ҳ����
	 *
	 * @var number $Cpage
	 */
	var $Cpage;							//��ǰҳ��
	/**
	 * ҳ������,����ó�
	 *
	 * @var number $TotalPage
	 * @access private
	 */
	var $TotalPage;						//��ҳ��
	/**
	 * ҳ����,��ÿҳ��ʾ������
	 *
	 * @var number $PageSize
	 */
	var $PageSize;						//ÿҳ����
	/**
	 * ��ҳ����󷵻ص����յķ�ҳ����
	 *
	 * @var string $PageLink
	 * @access private
	 */
	var $PageLink;						//��ҳ����ķ���ֵ
	/**
	 * ��ҳ�����,0������
	 *
	 * @var number $MaxPage
	 */
	var $MaxPage=4;						//��ҳ�����,0������
	/**
	 * ����MySQL ��Limit ���� ��ʽ: number,number
	 *
	 * @var string $Limit
	 * @access private
	 */
	var $Limit;							//��ҳ�ദ����Limit �ֶ�.
	/**
	 * ��̬��ҳ����ĸ�·��
	 *
	 * @var string $BaseUrl
	 * @access private
	 */
	var $BaseUrl;						//��̬��ҳ����ĸ�·��.
	/**
	 * ��һҳ�����ӵ�ַ
	 *
	 * @var string $FirstPage
	 * @access private
	 */
	var $FirstPage;						//��һҳ
	/**
	 * ���һҳ���ӵ�ַ
	 *
	 * @var  string $LastPage
	 * @access private
	 */
	var $LastPage;						//���һҳ
	
	var $PageUp;						//��һҳ
	
	var $PageDown;						//��һҳ
	/**
	 * �б���ʼ��¼��
	 *
	 * @var number $Limit_st
	 */
	var $Limit_st=0;
	
	var $Page_tag = 'page';
	/**
	 * ��ҳ��ʽ���� , ����ʹ�� SetPattern() ����
	 *
	 * @var string $Pattern
	 * @access private
	 */
	var $Pattern="<span class=\"pagelink_num\"><a href=\"{~~pagelink~~}\">{~~page~~}</a></span>";	
	/**
	 * ��̬��ʽ����,ͨ������Ҫ,���Զ���Pattern���,��Ҳ�ɶ��� ʹ��SetStPattern() ����
	 *
	 * @var string $StPattern
	 * @access private
	 */
	var $StPattern="<span class=\"pagelink_num_nonce\">{~~page~~}</span>";
	/**
	 * ��ҳ��ʽPattern
	 *
	 * @var string $FpPattern
	 * @access private
	 */
	var $FpPattern="<span class=\"pagelink_pre\"><a href=\"{~~pagelink~~}\">First</a></span><span class=\"pagelink_pre\"><a href=\"{~~pageup~~}\">Pre</a></span>";
	/**
	 * ĩҳ��ʽPattern
	 *
	 * @var string $LpPattern
	 * @access private
	 */
	var $LpPattern="<span class=\"pagelink_next\"><a href=\"{~~pagedown~~}\">Next</a></span><span class=\"pagelink_next\"><a href=\"{~~pagelink~~}\">Last</a></span>";
	/**
	 * ��ҳǰ��ʽPattern
	 *
	 * @var string $PagePrePattern
	 * @access private 
	 */
	var $PagePrePattern = "<span class=\"pagelink_pre_nolink\">{~~num~~}R/{~~page~~}P {~~cpage~~}</span>";
	/**
	 * ��ҳ����ʽPattern
	 *
	 * @var string $PageTailPattern
	 * @access private
	 */
	var $PageTailPattern = "<span>
		  <input name=\"{~~tag~~}\" type=\"text\" id=\"{~~tag~~}\" size=\"4\" />
		  <input type=\"submit\" value=\"��ת\" />
		</span>";
	/**
	 * PHP5���캯��
	 *
	 * @param ��¼���� $maxnum
	 * @param ��ǰҳ���� $cpage
	 * @param ÿҳ������ $pagesize
	 */
	function __construct($maxnum=0,$cpage=1,$pagesize=20){
		$this->Maxnum = intval($maxnum);
		$pagesize = abs(intval($pagesize));
		$this->PageSize = $pagesize ? $pagesize:20;
		$this->TotalPage = ceil($this->Maxnum/$this->PageSize);
		$this->Cpage = min(max(1,$cpage),$this->TotalPage);
		$this->Limit_st = max(0,($this->Cpage-1)) * $this->PageSize;
		$this->Limit = $this->Limit_st.",".$this->PageSize;
		//$this->BaseUrl�������ڶ�̬��ҳ�Զ�����

			$this-> BaseUrl = $_SERVER['SCRIPT_NAME'];
			
			# for seo
			if (strpos($_SERVER['SCRIPT_NAME'],'-')) {	//���ü�ǿ��UrlRewriteʱ
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
	 * PHP4���캯��
	 *
	 *@see __construct()
	 */
	function Page($maxnum=0,$cpage=1,$pagesize=20){
		$this->__construct($maxnum,$cpage,$pagesize);
	}
	
	/**
	 * ���ö�̬��ҳ
	 *
	 */
	function SetDpages(){
		$ar = array();
		if ($this->Cpage>1) {	//������ҳ����һҳ����
			$this->FirstPage = $this->BaseUrl."{$this->Page_tag}=1";
			$this->PageUp = $this->BaseUrl."{$this->Page_tag}=" . ($this->Cpage-1);
		}
		if ($this -> Cpage < $this->TotalPage) {	//�������ҳ����һҳ����
			$this->LastPage = $this->BaseUrl."{$this->Page_tag}={$this->TotalPage}";
			$this->PageDown = $this->BaseUrl."{$this->Page_tag}=" . ($this->Cpage+1);
		}
		//������������
		for ($i=1;$i<=$this->TotalPage;$i++){
			if($this->MaxPage >0 && abs($i-$this->Cpage)>$this->MaxPage) {
				continue;
			}
			$ar[$i] = $this->BaseUrl."{$this->Page_tag}={$i}";
		}
		$this->SetPages($ar);
	}
	/**
	 * ���þ�̬��ҳ,�÷�ҳ�����������һ������{~~page~~} ��pattern
	 * @example $pattern = "list_{~~page~~}.html" {~~page~~}�����滻��ҳ������
	 *
	 * @param string $pattern
	 */
	function SetSpages($pattern){
		$ar = array();
		if ($this->Cpage>1) {	//������ҳ����һҳ����
			$this->FirstPage = str_ireplace("{~~page~~}",1,$pattern);
			$this->PageUp = str_ireplace("{~~page~~}",$this->Cpage-1,$pattern);
		}
		if ($this -> Cpage < $this->TotalPage) {	//�������ҳ����һҳ����
			$this->LastPage = str_ireplace("{~~page~~}",$this->TotalPage,$pattern);
			$this->PageDown = str_ireplace("{~~page~~}",$this->Cpage+1,$pattern);
		}
		for ($i=1;$i<=$this->TotalPage;$i++){
			$ar[$i] = str_ireplace("{~~page~~}",$i,$pattern);
		}
		$this -> SetPages($ar);
	}
	/**
	 * ��һ����������ķ�ҳ�����������ø� Pages �����洦��
	 * �÷����� SetDpages() SetSpages()���õ�.�����ǵĹ�������
	 *
	 * @param array $array
	 * @access private
	 */
	function SetPages($array){
		$this-> Pages = $array;
		//���������ദ��!,����˶��������,ͨ������Ҫ.
	}
	/**
	 * ���÷�ҳPattern
	 *
	 * @param string $p
	 */
	function SetPattern($p) {
		$this->Pattern = $p;
	}
	/**
	 * ���õ�ǰҳpattern
	 * 
	 * @param string $p
	 */
	function SetStPattern($p) {
		$this->StPattern = $p;
	}
	/**
	 * ������ҳ��ʽpattern
	 *
	 * @param string $p
	 */
	function SetFpPattern($p) {
		$this->FpPattern = $p;
	}
	/**
	 * ����ĩҳ��ʽpattern
	 *
	 * @param string $p
	 */
	function SetLpPattern($p) {
		$this->LpPattern = $p;
	}
	/**
	 * ���÷�ҳǰ��ʽ
	 *
	 * @param string $p
	 */
	function SetPpPattern($p) {
		$this->PagePrePattern = $p;
	}
	/**
	 * ���÷�ҳ����ʽ
	 *
	 * @param string $p
	 */
	function SetPtPattern($p) {
		$this->PageTailPattern = $p;
	}
	/**
	 * ��������ķ�ҳ���봮
	 *
	 * @return string ���������ķ�ҳ���봮
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
		//Add more ��ҳ����
		$pagepre = str_replace(array('{~~num~~}','{~~page~~}','{~~cpage~~}'),array($this->Maxnum,$this->TotalPage,$this->Cpage),$this->PagePrePattern);
		//add ��ҳ
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