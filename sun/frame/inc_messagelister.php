<?php
/**
 * 消息列表封装类
 * 
 * @author  蜻蜓@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
Iimport('lister');
class MessageLister extends Lister  {

	var $Table = '##__message';
	
	var $UserId = '';
	
	var $UserTable = '##__passport';
	var $UserNameKey = 'username';
	var $UserIdKey = 'uid';
	
	#private
	var $PriKey = 'id';
	var $FromKey = 'fromid';
	var $ToKey = 'toid';
	var $ReadKey = 'read';
	var $DelKey = 'del';
	var $StoreKey = 'store';
	var $TimeKey = 'time';
	var $SubjectKey = 'subject';
	var $BodyKey = 'body';
	var $SysKey = 'system';
	#private
	
	function __construct($id) {
		$this -> SetUserID($id);
		$this -> UserTable = $GLOBALS['rtc']['passport_table'];
		$this->UserNameKey = $GLOBALS['rtc']['passport_uniqueid'];
		$this->UserIdKey = $GLOBALS['rtc']['passport_prikey'];
		parent::__construct(
			array(
			'table' => $this->Table,
			'limit' => '',	//与page 类搭配
			'where' =>array(),
			'orderby'=> "`$this->TimeKey`" . ' desc',
			'els' => array(),
			'safearray' => array(),
			)
		);
		$this->Where = array();
	}
	function MessageLister($id) {
		return $this->__construct($id);
	}
	
	function SetUserID($id) {
		$this -> UserId = $id;
	}
	
	function SetWhere($where,$key='') {
		if(empty($key)) $this->Where [] = $where;
		else $this->Where[$key] = $where;
	}
	
	##设置查看级别，互斥条件
	//收件箱
	function SetBoxList() {
		$this -> SetWhere('a.`'.$this->DelKey.'`=0','del');
	}
	//回收站
	function SetRemoveList() {
		$this -> SetWhere('a.`'.$this->DelKey.'`=1','del');
	}
	//完全删除历史记录，需管理员权限
	function SetHardRemoveList() {
		$this -> SetWhere('a.`'.$this->DelKey.'`=2','del');
	}
	//所有记录
	function SetAllList() {
		$this ->SetWhere('','del');
	}
	##设置查看级别
	
	##设置是发件箱还是收件箱，互斥条件
	//发件箱条件设置
	function SetFromID() {
		$this -> SetWhere('a.`'.$this->FromKey.'`='.$this->UserId,'ft');
	}
	//收件箱条件设置
	function SetToId() {
		$this -> SetWhere('a.`'.$this->ToKey.'`='.$this->UserId,'ft');
	}
	##设置是发件箱还是收件箱
	
	##设置是否保存在发件箱
	function SetStore($st=1) {
		$this->SetWhere('a.`'.$this->StoreKey.'`='.$st,'st');
	}
	
	##设置是否是系统消息
	function SetSystem($sys=0) {
		$this->SetWhere('a.`'.$this->SysKey.'`='.$sys,'sys');
	}
	##创建条件子句
	function CreatWhere() {
		if(is_array($this->Where)) $this -> Where = implode(' and ',$this->Where);
	}
	
	function ExecTotalRecord() {
		if($this->Table && $this->Where) {
			$ppsql = new dbsql();
			$ppsql -> SetQueryString("Select count(*) as c From {$this->Table} a where {$this->Where}");
			$row = $ppsql -> GetOneArray();
			$this -> Total = $row['c'];
		}
	}
	//计算我总共的断消息数目
	function ExecMyTotalRecord() {
		//me
		$this->SetToId();
		//inbox
		$this->SetBoxList();
		$this->CreatWhere();
		$ppsql = new dbsql();
		$ppsql -> SetQueryString("Select count(*) as c From {$this->Table} a where {$this->Where}");
		$row = $ppsql -> GetOneArray();
		$this->ClearWhere();
		return $row['c'];
	}
	//计算我的未读信息数目
	function ExecMyNoneReadRecord() {
		//me
		$this->SetToId();
		//inbox
		$this->SetBoxList();
		//未读
		$this->SetWhere('a.`'.$this->ReadKey.'`=0');
		$this->CreatWhere();
		$ppsql = new dbsql();
		$ppsql -> SetQueryString("Select count(*) as c From {$this->Table} a where {$this->Where}");
		$row = $ppsql -> GetOneArray();
		$this->ClearWhere();
		return $row['c'];
	}
	//清除所有条件
	function ClearWhere() {
		$this-> Where = array();
	}
	
	function GetList($pf = '') {
		if($this->Table && $this->Limit && $this->Where) {
			$ppsql = new dbsql();
			if(is_array($this->Els)) {
				$el = "";
				foreach ($this->Els as $k => $v) {
					if($el) $el .= ",a.{$v}";
					else $el = 'a.'.$v;
				}
			}
			!$el && $el = "a.*";
			$el .= ",b.{$this->UserNameKey} as namefrom,c.{$this->UserNameKey} as nameto";
			$sql = "Select {$el} From {$this->Table} a left join {$this->UserTable} b on a.{$this->FromKey}=b.{$this->UserIdKey} left join {$this->UserTable} c on a.{$this->ToKey}=c.{$this->UserIdKey} where {$this->Where}";
			if($this->Orderby) $sql .= " order by {$this->Orderby}";
			$this->Limit && $sql .= " limit {$this->Limit}";
			$ppsql -> SetQueryString($sql);
			$ppsql -> ExecReturnSQL();
			$this -> Larray = array();
			while ($row = $ppsql-> GetArray()) {
				//预处理 $row
				if($pf && function_exists($pf)) $row = call_user_func($pf,$row);
				$this->Larray[] = $row;
			}
		}
		return $this->Larray;
	}
	
}