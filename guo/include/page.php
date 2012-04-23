<?php 
require_once(dirname(__FILE__)."/config_base.php");
$lang_pre_page = "��ҳ";
$lang_next_page = "��ҳ";
$lang_index_page = "��ҳ";
$lang_end_page = "ĩҳ";
//-----------------------------------------------------------------
//��������ԭ�򣬱���ҳ����ԭ����pub_datalist�޸ģ�����ʹ��ģ������
//------------------------------------------------------------------
class DataList
{
	var $sourceSql;
	var $nowPage;
	var $totalResult;
	var $pageSize;
	var $queryTime;
	var $inTagS;
	var $inTagE;
	var $getValues;
	var $dtp;
	var $dsql;
	//���캯��///////
	//-------------
	function __construct()
 	{
 		global $nowpage,$totalresult;
 		if(empty($nowpage)||ereg("[^0-9]",$nowpage)) $nowpage=1;
		if(empty($totalresult)||ereg("[^0-9]",$totalresult)) $totalresult=0;
 	  $this->sourceSql="";
	  $this->pageSize=20;
	  $this->queryTime=0;
	  $this->inTagS = "[";
	  $this->inTagE = "]";
	  $this->getValues=Array();
		$this->dsql = new DedeSql(false);
		$this->nowPage = $nowpage;
		$this->totalResult = $totalresult;
  }
  function DataList(){
  	$this->__construct();
  }
	function Init(){
		return "";
	}
	//������ַ��Get������ֵ
	function SetParameter($key,$value){
		$this->getValues[$key] = $value;
	}
	function SetSource($sql){
		$this->sourceSql = trim($sql);
	}
	//����б�����
	function GetDataList()
	{
		$timedd = "δ֪";
		$starttime = ExecTime(); 
		$DataListValue = "";
		if($this->totalResult==0){
			$query = preg_replace("/^(.*)[\s]from[\s]/is",'Select count(*) as dd From ',$this->sourceSql);
			$rowdm = $this->dsql->GetOne($query);
			$this->totalResult = $rowdm['dd'];
		}
		$this->sourceSql .= " limit ".(($this->nowPage-1)*$this->pageSize).",".$this->pageSize;
		$this->dsql->Query('dm',$this->sourceSql);
		//����ִ��ʱ��
		$endtime = ExecTime();
		$timedd=$endtime-$starttime;
		$this->queryTime = $timedd;
		$GLOBALS["limittime"] = $timedd;
		$GLOBALS["totalrecord"] = $this->totalResult;
		return $this->dsql;
	}
	//��ȡ��ҳ�б�
	function GetPageList($list_len)
	{
		global $lang_pre_page;
		global $lang_next_page;
		global $lang_index_page;
		global $lang_end_page;
		$prepage="";
		$nextpage="";
		$prepagenum = $this->nowPage-1;
		$nextpagenum = $this->nowPage+1;
		if($list_len==""||ereg("[^0-9]",$list_len)) $list_len=3;
		$totalpage = ceil($this->totalResult/$this->pageSize);
		
		if($totalpage<=1&&$this->totalResult>0) return "��1ҳ/".$this->totalResult."����¼"; 
		if($this->totalResult == 0) return "��0ҳ/".$this->totalResult."����¼"; 
		
		$purl = $this->GetCurUrl();
		$geturl="";
		$hidenform="";
		if($this->totalResult!=0) $this->SetParameter("totalresult",$this->totalResult);
		if(count($this->getValues)>0)
		{
			foreach($this->getValues as $key=>$value)
			{
				$value = urlencode($value);
				$geturl.="$key=$value"."&";
				$hidenform.="<input type='hidden' name='$key' value='$value'>\r\n";
			}
		}
		$purl .= "?".$geturl;
		
		//�����һҳ����һҳ������
		if($this->nowPage!=1)
		{
			$prepage.="<a href='".$purl."nowpage=$prepagenum'>$lang_pre_page</a> \r\n";
			$indexpage="<a href='".$purl."nowpage=1'>$lang_index_page</a> \r\n";
		}
		else
		{
			$indexpage="$lang_index_page \r\n";
		}	
		if($this->nowPage!=$totalpage&&$totalpage>1)
		{
			$nextpage.="<a href='".$purl."nowpage=$nextpagenum'>$lang_next_page</a> \r\n";
			$endpage="<a href='".$purl."nowpage=$totalpage'>$lang_end_page</a> \r\n";
		}
		else
		{
			$endpage=" $lang_end_page \r\n";
		}
		//�����������
		$listdd="";
		$total_list = $list_len * 2 + 1;
		if($this->nowPage>=$total_list) 
		{
    		$j=$this->nowPage-$list_len;
    		$total_list=$this->nowPage+$list_len;
    		if($total_list>$totalpage) $total_list=$totalpage;
		}	
		else
		{ 
   			$j=1;
   			if($total_list>$totalpage) $total_list=$totalpage;
		}
		for($j;$j<=$total_list;$j++)
		{
   			if($j==$this->nowPage) $listdd.= "<strong>$j</strong> \r\n";
   			else $listdd.="<a href='".$purl."nowpage=$j'>".$j."</a> \r\n";
		}
	
		$plist = "<div class=\"pagelistbox\">\r\n";
		$plist.="<form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform";
		$plist.=$indexpage;
		$plist.=$prepage;
		$plist.=$listdd;
		$plist.=$nextpage;
		$plist.=$endpage;
		if($totalpage>$total_list)
		{
			$plist.="<input type='text' name='nowpage' style='padding:0px;width:30px;height:18px'>\r\n";
			$plist.="<input type='submit' name='plistgo' value='GO' style='padding:0px;width:30px;height:18px;font-size:9pt'>\r\n";
		}
		$plist.="</form>\r\n</div>\r\n";
		return $plist;
	}
	//���ϵͳ��ռ����Դ
	function Close(){
		$this->dsql->Close();
	}
	function GetCurUrl()
	{
		if(!empty($_SERVER["REQUEST_URI"])){
			$nowurl = $_SERVER["REQUEST_URI"];
			$nowurls = explode("?",$nowurl);
			$nowurl = $nowurls[0];
		}
		else
		{ $nowurl = $_SERVER["PHP_SELF"]; }
		return $nowurl;
	}
}
?>