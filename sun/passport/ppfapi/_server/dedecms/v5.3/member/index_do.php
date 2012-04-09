<?php
require_once(dirname(__FILE__)."/config.php");
if(empty($dopost))
{
	$dopost = '';
}
if(empty($fmdo))
{
	$fmdo = '';
}

/*********************
function Case_user()
*******************/
if($fmdo=='user')
{

	//����û����Ƿ����
	if($dopost=="checkuser")
	{
		AjaxHead();
		$msg = '';
		$uid = trim($uid);
		if($cktype==0)
		{
			$msgtitle='�û��ǳ�';
		}
		else
		{
			$msgtitle='�û���';
		}
		$msg = CheckUserID($uid,$msgtitle);
		if($msg=='ok')
		{
			$msg = "<font color='#4E7504'><b>��{$msgtitle}����ʹ��</b></font>";
		}
		else
		{
			$msg = "<font color='red'><b>��{$msg}</b></font>";
		}
		echo $msg;
		exit();
	}

	//���email�Ƿ����
	else  if($dopost=="checkmail")
	{
		AjaxHead();
		if($cfg_md_mailtest=='N')
		{
			$msg = "<font color='#4E7504'><b>�̿���ʹ��</b></font>";
		}
		else
		{
			$row = $dsql->GetOne("Select mid From `#@__member` where email like '$email' limit 1");
			if(!is_array($row))
			{
				$msg = "<font color='#4E7504'><b>�̿���ʹ��</b></font>";
			}
			else
			{
				$msg = "<font color='red'><b>��Email�Ѿ�����һ���ʺ�ռ�ã�</b></font>";
			}
		}
		echo $msg;
		exit();
	}

	//����ע��ҳ��
	else if($dopost=="regnew")
	{

		require_once(dirname(__FILE__)."/reg_new.php");
		exit();
	}

	//��������
	else if($dopost=="uprank")
	{
		CheckRank(0,0);
		if(empty($uptype))
		{
			ShowMsg("������Ч��","-1");
			exit();
		}
		$uptype = GetAlabNum($uptype);
		if($uptype < $cfg_ml->M_Rank)
		{
			ShowMsg("���Ͳ��ԣ���ļ������Ŀǰ����ļ���Ҫ�ߣ�","-1");
			exit();
		}
		$dsql->SetQuery("update `#@__member` set `uprank`='$uptype' where mid='".$cfg_ml->M_ID."' ");
		$dsql->Execute();
		ShowMsg("�ɹ�������������ȴ�����Ա��ͨ��","index.php?".time());
		exit();
	}

	//�������
	else if($dopost=="addmoney")
	{
		CheckRank(0,0);
		$svali = GetCkVdValue();
		if(strtolower($vdcode)!=$svali || $svali=="")
		{
			ResetVdValue();
			ShowMsg("��֤�����","-1");
			exit();
		}
		if(empty($money))
		{
			ShowMsg("��ûָ��Ҫ������ٽ�ң�","-1");
			exit();
		}
		$dsql->SetQuery("update #@__member set upmoney='$money' where mid='".$cfg_ml->M_ID."'");
		$dsql->Execute();
		ShowMsg("�ɹ��ύ������룡","index.php?".time());
		exit();
	}


}

/*********************
function login()
*******************/
else if($fmdo=='login')
{

	//�û���¼
	if($dopost=="login")
	{
		if(!isset($vdcode))
		{
			$vdcode = '';
		}
		$svali = GetCkVdValue();
		if(strtolower($vdcode)!=$svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg("��֤�����","-1");
			exit();
		}
		if(CheckUserID($userid,'',false)!='ok')
		{
			ShowMsg("��������û��� {$userid} ���Ϸ���","-1");
			exit();
		}
		if($pwd=='')
		{
			ShowMsg("���벻��Ϊ�գ�","-1",0,2000);
			exit();
		}

		//����ʺ�
		$rs = $cfg_ml->CheckUser($userid,$pwd);
		if($rs==0)
		{
			ShowMsg("�û��������ڣ�","-1",0,2000);
			exit();
		}
		else if($rs==-1) {
			ShowMsg("�������","-1",0,2000);
			exit();
		}
		else if($rs==-2) {
			ShowMsg("����Ա�ʺŲ������ǰ̨��¼��","-1",0,2000);
			exit();
		}
		else
		{
			#ppfapi add! login.
			#�����Ҫʹ��DEDECMS��ΪPPFAPI�ķ���ˣ���Ҫ�⼸��
			define('ppfapi_safe',true);
			$row = $dsql -> GetOne("Select mid,userid,pwd,email From #@__member where userid like '$userid'");
			$ppf_api_userdata = array(
				'uid' => $row['mid'],
				'username' => $row['userid'],
				'password' => $pwd,
				'email' => $row['email'] ? $row['email'] : '@',
			);
			$ppf_api_return = $gourl ? $gourl : $cfg_clihost.'/index.php';
			if (!ereg('^http://',$ppf_api_return)) {
				$ppf_api_return = 'http://' .$_SERVER['HTTP_HOST'] . $ppf_api_return;
			}
			include('../ppfapi/interface_login.php');
			if ($ppf_api_gourl) {
				$gourl = $ppf_api_gourl;
			}
			
			#
			#ppfapi add!
			if(empty($gourl) || eregi("action|_do",$gourl))
			{
				ShowMsg("�ɹ���¼��5���Ӻ�ת��ϵͳ��ҳ...","index.php",0,2000);
			}
			else
			{
				ShowMsg("�ɹ���¼������ת��ָ��ҳ��...",$gourl,0,2000);
			}
			exit();
		}
	}

	//�˳���¼
	else if($dopost=="exit")
	{
		$cfg_ml->ExitCookie();
		#ppfapi add! logout.
		#�����Ҫʹ��DEDECMS��ΪPPFAPI�ķ���ˣ���Ҫ�⼸��
		define('ppfapi_safe',true);
		$ppf_api_userdata = array();
		$ppf_api_return = $gourl ? $gourl : 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';
		if (!ereg('^http://',$ppf_api_return)) {
			$ppf_api_return = $cfg_clihost . $ppf_api_return;
		}
		include('../ppfapi/interface_logout.php');
		if ($ppf_api_gourl) {
			$gourl = $ppf_api_gourl;
		}
		#
		#ppfapi add!
		ShowMsg("�ɹ��˳���¼��","index.php",0,2000);
		exit();
	}
}
else
{
	ShowMsg("��ҳ���ֹ����!","index.php");
}

?>