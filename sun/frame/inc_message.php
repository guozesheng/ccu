<?php
/**
 * ͨ��վ����Ϣ��װ��
 * 
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
Iimport('element');
class Message extends Element {

	var $Table = '##__message';
	var $UseCache = false;
	
	var $UserId = '';
	
	var $UserTable = '##__passport';
	var $UserNameKey = 'username';
	var $UserIdKey = 'uid';
	
	var $Supper = false;
	
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
		if($id>0) $this->UserId = $id;
		$this -> UserTable = $GLOBALS['rtc']['passport_table'];
		$this->UserNameKey = $GLOBALS['rtc']['passport_uniqueid'];
		$this->UserIdKey = $GLOBALS['rtc']['passport_prikey'];
		$configs = array(
			'table' => $this->Table,
			'prikey' => $this->PriKey,
			'auto' => true,
			'usecache' => false,
			'safearray' => array(),
		);
		parent::__construct($configs);
	}
	function message($id) {
		return $this->__construct($id);
	}
	/**
	 * ��ȡһ������Ϣ����Ȩ�޷���false
	 *
	 * @param int $id
	 * @param bol $set
	 * @return bool
	 */
	function Read($id) {
		$this->Load($id);
		if($this->Elements[$this->FromKey] != $this->UserId && $this->Elements[$this->ToKey]!= $this->UserId && !$this->Supper) {
			unset($this->Elements);
			return false;
		}
		
		if($this->UserId == $this->Elements['toid'] && !$this->Elements[$this->ReadKey]) {
			$this->SetUpdate(array($this->PriKey=>$id,$this->ReadKey=>time()));
			$this->DoUpdate();
		}
		
		$elm = new Element(array('table'=>$this->UserTable,'prikey'=>$this->UserIdKey,'unikey'=>$this->UserNameKey,'auto'=>true,'usecache'=>false));
		$elm -> Load($this->Elements[$this->FromKey]);
		$this->Elements[$this->FromKey.'_name'] = $elm -> Elements[$this->UserNameKey];
		return true;
	}
	
	function Write($userid,$subject,$body,$store=0,$sys=0) {
		if($userid<=0) return 0;
		if ($userid == $this->UserId) {
			return -2;	//�����Լ���!
		}
		if ($userid<=0 && $sys==0) {
			return  -3;
		}
		$array = array(
			$this->FromKey => $this->UserId,
			$this->ToKey => $userid,
			$this->SubjectKey => $subject,
			$this->ReadKey => 0,
			$this->DelKey => 0,
			$this->StoreKey=>$store,
			$this->TimeKey => time(),
			$this->BodyKey => htmlspecialchars($body),
			$this->SysKey => $sys ? 1 : 0,
			'ip' => GetIP(),
		);
		$this -> SetInsert($array);
		return $this -> DoRecord();
	}
	
	function WriteUseName($username,$subject,$body,$store=0,$sys=0) {
		$elem = new Element(array('table' => $this->UserTable,'prikey'=>$this->UserIdKey,'unikey'=>$this->UserNameKey,'auto'=>true,'usecache'=>false));
		$elem -> E = array();
		$elem -> Load('',$username);
		if(empty($elem->E) || !$elem->E[$this->UserIdKey] || $elem ->E[$this->UserIdKey] < 0) return -1;
		return $this->Write($elem->E[$this->UserIdKey],$subject,$body,$store,$sys);
	}
	
	//ɾ����Ϣ
	function DelMessage($id,$hard=1) {
		if(!$this->CheckToSelf($id)) {
			return -1;		//��Ȩ��
		}
		if($hard==1) {//�������վ
			$this->SetUpdate(array($this->PriKey=>$id,$this->DelKey => 1));
			return $this->DoUpdate();
		}else if($hard==2){//����ȫɾ��
			$this->SetUpdate(array($this->PriKey=>$id,$this->DelKey => 2));
			return $this->DoUpdate();
		}else if($hard==3) {//��ȫɾ��
			return $this->DoRemove($id);
		}
	}
	//ɾ����������Ϣ
	function DelStoreMessage($id) {
		if(!$this->CheckFromSelf($id)) {
			return -1;		//��Ȩ��
		}
		$this->SetUpdate(array($this->PriKey=>$id,$this->StoreKey => 0));
		return $this->DoUpdate();
	}
	
	function EnableSupper($e = true) {
		$this -> Supper = $e;
	}
	//����Ƿ����Լ�������Ϣ
	function CheckFromSelf($id) {
		$this->Load($id);
		if(!$this->Supper && $this->Elements[$this->FromKey] != $this->UserId) {
			return false;
		}else {
			return true;
		}
	}
	//����Ƿ��Ƿ����Լ�����Ϣ
	function CheckToSelf($id) {
		$this->Load($id);
		if(!$this->Supper && $this->Elements[$this->ToKey] != $this->UserId) {
			return false;
		}else {
			return true;
		}
	}
}