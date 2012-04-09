<?php
require('ppframe.php');

#
Iimport('Lister_Passport_User');
$lister_user = new Lister_Passport_User();
#
if ($username) {
	if ($jq) {
		$lister_user -> SetWhere("`username` like '$username'");
	}else {
		$lister_user -> SetWhere("`username` like '%$username%'");
	}
}
	if ($input['u_cid']) {
		$lister_user -> SetWhere("`u_cid`=$input[u_cid]");
	}
	if ($input['u_pid']) {
		$lister_user -> SetWhere("`u_pid`=$input[u_pid]");
	}
	if ($input['u_bid']) {	//搜索部门
		$u_bids = GetBussSunIds($input['u_bid']);
		if ($u_bids) {
			$lister_user -> SetWhere("`u_bid` in ($u_bids)");
		}
	}
	if (isset($input['group'])) {
		$lister_user -> SetWhere("`group`=$input[group]");
	}
	if ($input['grade']) {
		$lister_user -> SetWhere("`grade`=$input[grade]");
	}
	if ($input['class']) {
		$lister_user -> SetWhere("`class`=$input[class]");
	}
	if ($input['classes']) {
		$lister_user -> SetWhere("`classes` like '% $input[classes] %'");
	}
	if (is_array($$morefields)) {
		foreach ($morefields as $k => $v) {
			if ($v) {
				if (in_array($k,$selfdef_fields) || in_array($k,$mydef_fields)) {
					$lister_user -> SetWhere("`{$k}`='{$v}'");
				}
			}
		}
	}
	
$lister_user -> CreateWhere();
$maxnum = $lister_user -> ExecTotalRecord();
Iimport('Page');

$pagesize = 10;

$p = new Page($maxnum,$page,$pagesize);

$p -> SetDpages();
$pagelink = $p -> GetPageLink();

$lister_user -> SetLimit($p -> Limit);

$lister_user = $lister_user -> GetList();

#年级
$grades = GetGradeS();
$base_class = GetStudentClassList(0);
$add_class = GetStudentClassList(1);

$selfdef_fields = GetTableFields($rtc['passport_table'],'selfdef_%');
$mydef_fields = GetTableFields($rtc['passport_table'],'mydef_%');

Iimport('Template');
$tpl = new Template();
$tpl -> DisPlay('user_main');

?>