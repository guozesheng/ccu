<?php
require(dirname(__FILE__)."/"."global.php");
$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];
//处理同步登录
if($webdb[passport_type]&&$webdb[passport_TogetherLogin])
{
	if($action=="quit")
	{
		if( ereg("^dzbbs",$webdb[passport_type]) )
		{
			//5.0使用$tablepre,5.5使用$cookiepre
			set_cookie("{$cookiepre}auth","");
			set_cookie("{$cookiepre}sid","");
			set_cookie("{$tablepre}auth","");
			set_cookie("{$tablepre}sid","");
			set_cookie("passport","");
			header("location:$FROMURL");
			//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
			//header("location:$FROMURL");
			exit;
		}
		elseif( ereg("^pwbbs",$webdb[passport_type]) )
		{
			set_cookie(CookiePre().'_winduser',"");
			header("location:$FROMURL");
			//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
			//header("location:$FROMURL");
			exit;
		}
		elseif( ereg("^dvbbs",$webdb[passport_type]) )
		{
			set_cookie("{$cookieprename}userid","");
			set_cookie("{$cookieprename}username","");
			set_cookie("{$cookieprename}password","");
			header("location:$FROMURL");
			//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
			//header("location:$FROMURL");
			exit;
		}
		else
		{
			header("location:$TB_url/$TB_quit");
			exit;
		}
	}
	elseif($webdb[passport_TogetherLogin]==2||($webdb[passport_TogetherLogin]==1&&!ereg("^pwbbs",$webdb[passport_type])&&!ereg("^dzbbs",$webdb[passport_type])&&!ereg("^dvbbs",$webdb[passport_type])))
	{
		header("location:$TB_url/$TB_login");
		exit;
	}
}

//退出
if($action=="quit")
{
	set_cookie("passport","");
	
	#ppframe add! PPFAPI logout
	define('ppfapi_safe',true);
	$ppf_api_userdata = array();
	$ppf_api_return = $forward;
	require('ppfapi/interface_logout.php');
	if ($ppf_api_gourl) {
		$webdb['www_url'] = $FROMURL = $ppf_api_gourl ? $ppf_api_gourl : $forward;
	}
	#ppframe add!
	
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]'>";
	//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
	//header("location:$FROMURL");
	exit;
}
else
{	//登录
	if($lfjid)
	{
		showerr("你已经登录了,请不要重复登录,要重新登录请点击<A HREF='$webdb[www_url]/login.php?action=quit'>安全退出</A>");
	}

	if($step==2)
	{
		if(defined("UC_CONNECT")){
			$rs=$db_uc->get_one("SELECT * FROM ".UC_DBTABLEPRE."members WHERE username='$username'");
			$password=md5($password).$rs[salt];
		}else{
			$rs=$db->get_one("SELECT $TB[uid] AS uid,$TB[username] AS username,$TB[password] AS password FROM $TB[table] WHERE $TB[username]='$username'");
		}
		if(!$rs){
			showerr("当前用户不存在,请重新输入");
		}elseif($rs[password]!=pwd_md5($password)){
			showerr("密码不正确,点击重新输入");
		}else{

			if($webdb[passport_type]&&$webdb[passport_TogetherLogin])
			{
				if(eregi("^pwbbs",$webdb[passport_type]))
				{
					if($db_ifsafecv)
					{
						@extract($db->get_one("SELECT safecv FROM $TB[table] WHERE  username='$username'"));
					}
					set_cookie(CookiePre().'_winduser',StrCode($rs[uid]."\t".PwdCode($rs[password])."\t$safecv"),$cookietime);
					set_cookie('lastvisit','',0);
				}
				elseif(eregi("^dzbbs",$webdb[passport_type]))
				{
					
					@extract($rs=$db->get_one("SELECT *,secques AS discuz_secques FROM {$tablepre}members WHERE  username='$username'"));
					$discuz_auth_key = md5($_DCACHE['settings']['authkey'].$_SERVER['HTTP_USER_AGENT']);
					set_cookie("{$cookiepre}auth",authcode("$rs[password]\t$discuz_secques\t$rs[uid]", 'ENCODE'),$cookietime);
					set_cookie("{$cookiepre}sid","");
				}
				elseif(eregi("^dvbbs",$webdb[passport_type]))
				{
					@extract($db->get_one("SELECT truepassword FROM {$TB[table]} WHERE username='$username'"));
					set_cookie("{$cookieprename}userid",$rs[uid],$cookietime);
					set_cookie("{$cookieprename}username",$username,$cookietime);
					set_cookie("{$cookieprename}password",$truepassword,$cookietime);
				}
			}
			else
			{
				set_cookie("passport","$rs[uid]\t$username\t".mymd5("$rs[password]"),$cookietime);
			}
			
			if($fromurl&&!eregi("login\.php",$fromurl)&&!eregi("reg\.php",$fromurl)){
				$jumpto=$fromurl;
			}elseif($FROMURL&&!eregi("login\.php",$FROMURL)&&!eregi("reg\.php",$FROMURL)){
				$jumpto=$FROMURL;
			}else{
				$jumpto=$webdb[www_url];
			}
			#ppframe add! PPFAPI login
			#这将不影响其它系统的整合
				define('ppfapi_safe',true);
				$ppf_api_userdata = array(
					'uid' => $rs['uid'],
					'username' => $username,
					'password' => $password,
					'email' => '@',
				);
				$ppf_api_return = $jumpto;
				require('ppfapi/interface_login.php');
				if ($ppf_api_gourl) {
					$jumpto = $ppf_api_gourl;
				}
			#ppframe add!
			refreshto("$jumpto","登录成功",1);
		}
	}
	require(PHP168_PATH."inc/head.php");
	require(html("login"));
	require(PHP168_PATH."inc/foot.php");
}

?>