<?php
require(WORK_DIR.'config/baseconfig.php');
Iimport('PassPort_User');
$passport = new PassPort_User();
$passport -> ReSet();
if (PPF_API_GetUserData($passport -> U_UniqueID) && PPF_API_GetUserData($passport->U_PasswordKey)) {
	#存在 用户名/用户ID 与密码字段
	if (PPF_API_GetUserData($passport->PriKey) > 0) {		#存在Uid
		$passport -> LoadFromDBUsePriID(PPF_API_GetUserData($passport->PriKey));
		if ($passport -> U_ID > 0 && $passport -> U_Uname != PPF_API_GetUserData($passport -> U_UniqueID)) {
			#用户名不同，更新用户名。
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
	if ($passport -> U_ID > 0) {	//已存在的用户
		if ($passport -> U[$passport->U_PasswordKey] != $passport -> PassWordEnCode(PPF_API_GetUserData($passport->U_PasswordKey))) {
			#更改密码
			$passport -> PsNeedEncode = true;
			$passport -> SetUpdateInfo(
				array(
					$passport -> PriKey => $passport -> U_ID,
					$passport->U_PasswordKey => PPF_API_GetUserData($passport->U_PasswordKey)
				)
			);
			$passport -> DoReRecord();
		}
	}else {							//不存在的用户
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