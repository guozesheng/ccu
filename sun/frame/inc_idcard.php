<?php
class IdCard {
	var $Card;
	
	function __construct($card='') {
		if ($card) {
			$this -> SetCard($card);
		}
	}
	
	function IdCard($card='') {
		$this -> __construct($card);
	}
	
	function SetCard($card) {
		$this->Card = $card;
	}
	
	function Idcard_Verify_Number($idcard_base){
		if (strlen($idcard_base) != 17){
		   return false;
		}
	    $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); //debug ��Ȩ����
	    $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); //debug У�����Ӧֵ
	    $checksum = 0;
	    for ($i = 0; $i < strlen($idcard_base); $i++){
	        $checksum += substr($idcard_base, $i, 1) * $factor[$i];
	    }
	    $mod = $checksum % 11;
	    $verify_number = $verify_number_list[$mod];
	    return $verify_number;
	}
	/*/
	# �������ܣ���15λ���֤������18λ
	# �������ƣ�idcard_15to18
	# ������ ��string $idcard ʮ��λ���֤����
	# ����ֵ ��string
	# ����ʱ�䣺Fri Mar 28 09:49:13 CST 2008
	/*/
	function IdCard_15to18($idcard){
	    if (strlen($idcard) != 15){
	        return false;
	    }else{// ������֤˳������996 997 998 999����Щ��Ϊ�����������˵��������
	        if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
	            $idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
	        }else{
	            $idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
	        }
	    }
	    $idcard = $idcard . $this -> Idcard_Verify_Number($idcard);
	    return $idcard;
	}
	/*/
	# �������ܣ�18λ���֤У������Ч�Լ��
	# �������ƣ�Idcard_Checksum18
	# ������ ��string $idcard ʮ��λ���֤����
	# ����ֵ ��bool
	# ����ʱ�䣺Fri Mar 28 09:48:36 CST 2008
	/*/
	function Idcard_Checksum18($idcard){
	    if (strlen($idcard) != 18){ return false; }
	    $idcard_base = substr($idcard, 0, 17);
	    if ($this->Idcard_Verify_Number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
	        return false;
	    }else{
	        return true;
	    }
	}
	/*/
	# �������ܣ����֤������ӿں���
	# �������ƣ�Check
	# ������ ��string $idcard ���֤����
	# ����ֵ ��bool �Ƿ���ȷ
	# ����ʱ�䣺Fri Mar 28 09:47:43 CST 2008
	/*/
	function Check($idcard='') {
		if (!$idcard) {
			$idcard = $this -> Card;
		}
		if(strlen($idcard) == 15 || strlen($idcard) == 18){
		   if(strlen($idcard) == 15){
		    $idcard = $this->IdCard_15to18($idcard);
		   }
		   return $this->Idcard_Checksum18($idcard);
		}else{
		   return false;
		}
	}
}
