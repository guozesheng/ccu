<?php
	header("Content-Type:text/html;charset=gb2312");
	require(dirname(__FILE__)."/config_base.php");
	$boss = $_GET['boss'];
	if($boss!="")
	{
		$csql=New Dedesql(false);
		$csql->SetQuery("select * from #@__boss where boss = $boss");
		$csql->Execute();
		$rowcount=$csql->GetTotalRow();
		if($rowcount>0)
		{
			echo "<font color=\"#FF0000\">该工号已经注册！</font>";
		}
		else 
		{
			echo " ";
		}
	}
?>