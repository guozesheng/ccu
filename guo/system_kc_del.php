<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>ְ��ɾ��</title>
</head>
<body>
<?php
require_once(dirname(__FILE__)."/include/config_base.php");
require_once(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')ShowMsg('�Ƿ���ִ�в���','system_kc.php');
$username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
if($action=='del'){
$dsql=New Dedesql(false);
$query="select * from #@__mainkc where p_id='$id'";
$dsql->Setquery($query);
$dsql->Execute();
$rowcount=$dsql->GetTotalRow();
if($rowcount==0) //�Ƿ�ID
ShowMsg('ִ���˷Ƿ��Ĳ���','-1');
else{
 $dsql->ExecuteNoneQuery("delete from #@__mainkc where p_id='$id'");
 WriteNote('�ɹ�ɾ������Ʒ('.get_name($id,'name').')',getdatetimemk(time()),getip(),$username);
 ShowMsg('�ɹ�ɾ������Ʒ������Ϣ','system_kc.php');
 }
 $dsql->close();
 }
 else{//����ɾ��
 }
?>
</body>
</html>
