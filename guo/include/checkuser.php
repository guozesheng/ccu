<?php
	require(dirname(__FILE__)."/config_base.php");
	$csql=New Dedesql(false);
	$csql->SetQuery("select * from #@__boss");
	$csql->Execute();
	$rowcount=$csql->GetTotalRow();
	if($rowcount>0)
	{
		echo "�ù����Ѿ���ע�ᣡ";
	}
	else 
	{
	}
	echo "12345";
?>