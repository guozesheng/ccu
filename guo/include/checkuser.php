<?php
	require(dirname(__FILE__)."/config_base.php");
	$csql=New Dedesql(false);
	$csql->SetQuery("select * from #@__boss");
	$csql->Execute();
	$rowcount=$csql->GetTotalRow();
	if($rowcount>0)
	{
		echo "该工号已经被注册！";
	}
	else 
	{
	}
	echo "12345";
?>