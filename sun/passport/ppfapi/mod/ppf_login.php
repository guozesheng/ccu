<?php
require(WORK_DIR.'config/baseconfig.php');
Iimport('PassPort_User');
$passport = new PassPort_User();
$passport -> ReSet();
if (PPF_API_GetUserData($passport -> U_UniqueID) && PPF_API_GetUserData($passport->U_PasswordKey)) {
	#���� �û���/�û�ID �������ֶ�
	if (PPF_API_GetUserData($passport->PriKey) > 0) {		#����Uid
		$passport -> LoadFromDBUsePriID(PPF_API_GetUserData($passport->PriKey));
		if ($passport -> U_ID > 0 && $passport -> U_Uname != PPF_API_GetUserData($passport -> U_UniqueID)) {
			#�û�����ͬ�������û�����
			$passport -> SetUpdateInfo(
				array(
					$passport -> PriKey => $passport -> U_ID,
					$passport->U_UniqueID => PPF_API_GetUserData($passport->U_UniqueID)
				)
			);
			$passport -> DoReRecord();
		}else {
			$passport -> LoadFromDBuseUniqID(PPF_API_GetUserData($passport->U_UniqueID));
		}
	}
	if ($passport -> U_ID > 0) {	//�Ѵ��ڵ��û�
		if ($passport -> U[$passport->U_PasswordKey] != $passport -> PassWordEnCode(PPF_API_GetUserData($passport->U_PasswordKey))) {
			#��������
			$passport -> PsNeedEncode = true;
			$passport -> SetUpdateInfo(
				array(
					$passport -> PriKey => $passport -> U_ID,
					$passport->U_PasswordKey => PPF_API_GetUserData($passport->U_PasswordKey)
				)
			);
			$passport -> DoReRecord();
		}
	}else {							//�����ڵ��û�
		$ppsql = new dbsql();
		$table_fields = $ppsql -> GetFieldList($rtc['passport_table']);
		$safearray = array_keys($table_fields);
		$passport -> SetInsertInfo($userdata);
		$passport -> DoRecordUser($safearray);
		if (PPF_API_GetUserData($passport->PriKey)) {
			$passport -> LoadFromDBUsePriID(PPF_API_GetUserData($passport->PriKey));
		}else {
			$passport -> LoadFromDBuseUniqID(PPF_API_GetUserData($passport->U_UniqueID));
		}
	}
	$passport -> PassCheckRebuild();
	$passport -> PutLoginedInfo();
}else {
	//do nothing
}

?>