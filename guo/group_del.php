<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��Աɾ��</title>
</head>
<body>
<?php
require_once(dirname(__FILE__)."/include/config_base.php");
require_once(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')ShowMsg('�Ƿ���ִ�в���','guest_group.php');
//������ĵȼ�
$username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
$dsql=New Dedesql(false);
$query="select * from #@__group where id='$id'";
$dsql->Setquery($query);
$dsql->Execute();
$rowcount=$dsql->GetTotalRow();
if($rowcount==0) //�Ƿ�ID
ShowMsg('ִ���˷Ƿ��Ĳ���','-1');
else{
 $dsql->ExecuteNoneQuery("delete from #@__group where id='$id'");
 WriteNote('�ɹ�ɾ����Ա��������(IDΪ'.$id.')',getdatetimemk(time()),getip(),$username);
 ShowMsg('�ɹ�ɾ����Ա��������','guest_group.php');
 }
 $dsql->close();
?>
</body>
</html>
