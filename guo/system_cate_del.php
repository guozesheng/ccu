<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����ɾ��</title>
</head>
<body>
<?php
require_once(dirname(__FILE__)."/include/config_base.php");
require_once(dirname(__FILE__)."/include/config_rglobals.php");
if($id=='')ShowMsg('�Ƿ���ִ�в���','system_class.php');
//������ĵȼ�
$username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
$dsql=New Dedesql(false);
$query="select * from #@__categories where id='$id'";
$dsql->Setquery($query);
$dsql->Execute();
$rowcount=$dsql->GetTotalRow();
if($rowcount==0) //�Ƿ�ID
ShowMsg('ִ���˷Ƿ��Ĳ���','-1');
else{
 $row=$dsql->GetArray();
 if($row['reid']==0){ //ɾ����������
 $msql=New Dedesql(false);
 $msql->SetQuery("select * from #@__categories where reid='".$row['id']."'");
 $msql->Execute();
 if($msql->GetTotalRow()>=1)
 echo "<script language='javascript'>alert('��Ҫɾ���Ķ������������ӷ���,����ɾ�����ӷ���!');history.go(-1);</script>";
 else{
 $msql->ExecuteNoneQuery("delete from #@__categories where id='$id'");
 WriteNote('�ɹ�ɾ����������'.$row['categories'],getdatetimemk(time()),getip(),$username);
 ShowMsg('ɾ������ɹ�','system_class.php');
 }
 $msql->close();
 }
 else{//ɾ���ӷ���
  $msql=New Dedesql(false);
  $msql->ExecuteNoneQuery("delete from #@__categories where id='$id'");
  WriteNote('�ɹ�ɾ���ӷ���'.$row['categories'],getdatetimemk(time()),getip(),$username);
  ShowMsg('�ɹ�ɾ���ӷ���','system_class.php');
  $msql->close();
 }
 $dsql->close();
}

?>
</body>
</html>
