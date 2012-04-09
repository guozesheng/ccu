<?php
require_once(DEDEINC."/memberlogin.class.php");

$keeptime = isset($keeptime) && is_numeric($keeptime) ? $keeptime : -1;
$cfg_ml = new MemberLogin($keeptime);

if (PPF_API_GetUserData('userid') && PPF_API_GetUserData('pwd')) {
	$loginid = 0;
	$mtype = '个人';
	#存在 用户名/用户ID 与密码字段
	if (PPF_API_GetUserData('mid') > 0) {
		$mid = PPF_API_GetUserData('mid');
		$row = $dsql -> GetOne("Select mid,userid,pwd From #@__member where mid=".PPF_API_GetUserData('mid'));
		if ($row['userid'] ) {	#存在一个用户
			if ($row['userid'] != PPF_API_GetUserData('userid') || $row['pwd'] != md5(PPF_API_GetUserData('pwd'))) {
				#是用户名不相同,Update
				$dsql -> ExecNoneQuery("Update #@__member set userid='".PPF_API_GetUserData('userid')."',pwd='" . md5(PPF_API_GetUserData('pwd')) . "' where mid=".PPF_API_GetUserData('mid'));
			}
			$cfg_ml -> PutLoginInfo($mid);
			$need_insert = false;
		}else {
			$need_insert = true;
		}
	}else {		
		$row = $dsql -> GetOne("Select mid,userid,pwd From #@__member where userid like '".PPF_API_GetUserData('userid') ."'");
		if ($row['mid'] > 0) {	#存在一个用户
			if ($row['pwd'] != md5(PPF_API_GetUserData('pwd'))) {	#修改密码
				$dsql -> ExecNoneQuery("Update #@__member set userid='".PPF_API_GetUserData('userid')."',pwd='" . md5(PPF_API_GetUserData('pwd')) . "' where userid like '".PPF_API_GetUserData('mid')."'");
			}
			$cfg_ml -> PutLoginInfo($row['mid']);
			$need_insert = false;
		}else {	#不存在,插入
			$mid = 0;
			$need_insert = true;
		}
	}
	
	if ($need_insert) {
		#不存在用户,插入一个用户
		$userid = trim(PPF_API_GetUserData('userid'));
		$dfscores = 0;
		$dfmoney = 0;
		$dfrank = $dsql->GetOne("Select money,scores From `#@__arcrank` where rank='10' ");
		if(is_array($dfrank))
		{
			$dfmoney = $dfrank['money'];
			$dfscores = $dfrank['scores'];
		}
		$uname = PPF_API_GetUserData('uname');
		$jointime = time();
		$logintime = time();
		$joinip = GetIP();
		$loginip = GetIP();
		$pwd = md5(PPF_API_GetUserData('pwd'));
	
		if ($mid > 0) {
			$inQuery = "INSERT INTO `#@__member` (`mid`,`mtype` ,`userid` ,`pwd` ,`uname` ,`sex` ,`rank` ,`uprank` ,`money` ,
			 	 `upmoney` ,`email` ,`scores` ,`matt` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
			   VALUES ('$mid','$mtype','$userid','$pwd','$uname','$sex','10','0','$dfmoney','0',
			   '$email','$dfscores','0','','$safequestion','$safeanswer','$jointime','$joinip','$logintime','$loginip'); ";
		}else {
				$inQuery = "INSERT INTO `#@__member` (`mtype` ,`userid` ,`pwd` ,`uname` ,`sex` ,`rank` ,`uprank` ,`money` ,
			 	 `upmoney` ,`email` ,`scores` ,`matt` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
			   VALUES ('$mtype','$userid','$pwd','$uname','$sex','10','0','$dfmoney','0',
			   '$email','$dfscores','0','','$safequestion','$safeanswer','$jointime','$joinip','$logintime','$loginip'); ";
		}
			
		if ($dsql -> ExecNoneQuery($inQuery)) {
			if (!$mid > 0) {
				$mid = $dsql -> GetLastID();
			}
				//写入默认会员详细资料
			if($mtype=='个人')
			{
				$infosquery = "INSERT INTO `#@__member_person` (`mid` , `onlynet` , `sex` , `uname` , `qq` , `msn` , `tel` , `mobile` , `place` , `oldplace` ,
			          `birthday` , `star` , `income` , `education` , `height` , `bodytype` , `blood` , `vocation` , `smoke` , `marital` , `house` ,
			           `drink` , `datingtype` , `language` , `nature` , `lovemsg` , `address`,`uptime`)
		             VALUES ('$mid', '1', '{$sex}', '{$uname}', '', '', '', '', '0', '0',
		             '1980-01-01', '1', '0', '0', '160', '0', '0', '0', '0', '0', '0','0', '0', '', '', '', '','0'); ";
				$space='person';
			}
			else if($mtype=='企业')
			{
				$infosquery = "INSERT INTO `#@__member_company`(`mid`,`company`,`product`,`place`,`vocation`,`cosize`,`tel`,`fax`,`linkman`,`address`,`mobile`,`email`,`url`,`uptime`,`checked`,`introduce`)
		              VALUES ('{$mid}','{$uname}','product','0','0','0','','','','','','{$email}','','0','0',''); ";
				$space='company';
			}
				/** 此处增加不同类别会员的特殊数据处理sql语句 **/
		
			$dsql->ExecuteNoneQuery($infosquery);
		
				//写入默认统计数据
			$membertjquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
		               VALUES ('$mid','0','0','0','0','0','0','0','0'); ";
			$dsql->ExecuteNoneQuery($membertjquery);
		
				//写入默认空间配置数据
			$spacequery = "Insert Into `#@__member_space`(`mid` ,`pagesize` ,`matt` ,`spacename` ,`spacelogo` ,`spacestyle`, `sign` ,`spacenews`)
			            Values('{$mid}','10','0','{$uname}的空间','','$space','',''); ";
			$dsql->ExecuteNoneQuery($spacequery);
		
			//----------------------------------------------
				//模拟登录
				//---------------------------
			$cfg_ml -> PutLoginInfo($mid);
		}
	}
}else {
	//do nothing
}

?>