<?php
	header("Content-Type:text/html;charset=gb2312");
	require(dirname(__FILE__)."/config_base.php");
	$boss = $_GET['boss'];
	$csql=New Dedesql(false);
	$csql->SetQuery("select * from #@__boss where boss = $boss");
	$csql->Execute();
	$rowcount=$csql->GetTotalRow();
	if($rowcount>0)
	{
		echo "�ù����Ѿ�ע�ᣡ";
	}
	else 
	{
		echo " ";
	}
?>