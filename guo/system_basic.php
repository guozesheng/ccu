<?php
require(dirname(__FILE__)."/include/config_base.php");
require(dirname(__FILE__)."/include/config_rglobals.php");
if($action=='save')
{
$configfile = dirname(__FILE__)."/include/config_hand.php";
$configfile_bak = dirname(__FILE__)."/include/config_hand_bak.php";
if(!is_writeable($configfile)){
	echo "�����ļ�'{$configfile}'��֧��д�룬�Ͻ��޸�ϵͳ���ò�����";
	exit();
}
$savesql = new DedeSql(false);
foreach($_POST as $k=>$v){
	if(ereg("^edit___",$k)){
		$v = ${$k};

	}else continue;
	$k = ereg_replace("^edit___","",$k);
		if(strlen($v) > 250){
			showmsg("$k ̫�������ܳ���250�ֽ�",'-1');
			exit;
		}
		$savesql->ExecuteNoneQuery("Update #@__config set `config_value`='$v' where `config_name`='$k' ");
		}
	$savesql->SetQuery("Select `config_name`,`config_value` From `#@__config` order by `id` asc");
  $savesql->Execute();
  if($savesql->GetTotalRow()<=0){
		$savesql->Close();
		ShowMsg("�ɹ���������������ݿ��ȡ��������ʱʧ�ܣ��޷����������ļ���","javascript:;");
	  exit();
	}
  @copy($configfile,$configfile_bak);
	$fp = @fopen($configfile,'w');
	@flock($fp,3);
	@fwrite($fp,"<"."?php\r\n") or die("�����ļ�'{$configfile}'��֧��д�룬���β�����Ч��<a href='system_basic.php'>����</a>");
  while($row = $savesql->GetArray()){
  	$row['value'] = str_replace("'","\\'",$row['config_value']);
  	fwrite($fp,"\${$row['config_name']} = '".$row['config_value']."';\r\n");
  }
  fwrite($fp,"?>");
  fclose($fp);
    $message="�ɹ��޸���ϵͳ�����ļ�config_base.php";
	$logindate=getdatetimemk(time());;
	$loginip=getip();
	$username=str_replace($cfg_cookie_encode,'',$_COOKIE["VioomaUserID"]);
	$savesql->Close();
	WriteNote($message,$logindate,$loginip,$username);
	ShowMsg("�ɹ�����ϵͳ���ã�","system_basic.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="style/main.css" rel="stylesheet" type="text/css" />
<title><?php echo $cfg_softname;?>ϵͳ����</title>
</head>
<body>
<table width="100%" border="0" id="table_style_all" cellpadding="0" cellspacing="0">
  <tr>
    <td id="table_style" class="l_t">&nbsp;</td>
    <td>&nbsp;</td>
    <td id="table_style" class="r_t">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
	<table width="100%" border="0" cellpadding="0" cellspacing="2">
     <tr>
      <td><strong>&nbsp;ϵͳ������Ϣ����</strong></td>
     </tr><form action="system_basic.php?action=save" method="post">
     <tr>
      <td bgcolor="#FFFFFF">
       <table width="100%" cellspacing="0" cellpadding="0" border="0" id="table_border">
	    <tr>
	      <td class="cellcolor" width="50%">ϵͳ����: </td>
	      <td>&nbsp;<input type="" name="edit___cfg_webname" value="<?php echo $cfg_webname;?>" size="50"/></td></tr>
		<tr>
		<tr>
		<tr>
		<tr>				
		  <td class="cellcolor">ʵ���ҹ���ϵͳ��·��: <br>(Ҳ���������URL,�ڰ�װʱϵͳ���Զ���ȡ.)</td>
		  <td>&nbsp;<input type="" name="edit___cfg_basehost" value="<?php echo $cfg_basehost;?>" size="50"/></td></tr>
		<tr>
		  <td class="cellcolor">ʵ���ҹ���ϵͳ��װĿ¼:<br>(����Ƿ���Ŀ¼��װ����Ҫ���) </td>
		  <td>&nbsp;<input type="" name="edit___cfg_cmspath" value="<?php echo $cfg_cmspath;?>" size="20"/></td></tr>
		<tr>
		  <td class="cellcolor">Cookie�����ַ���: <br>(Ϊ��֤��ȫ,��ϵͳCookie�����˼���,���趨�����ܸ��ӵļ����ַ���)</td>
		  <td>&nbsp;<input type="" name="edit___cfg_cookie_encode" value="<?php echo $cfg_cookie_encode;?>" size="20"/></td></tr>
		<tr>
		  <td class="cellcolor">��½����ʱ��(��λ:Сʱ): </td>
		  <td>&nbsp;<input type="" name="edit___cfg_keeptime" value="<?php echo $cfg_keeptime;?>" size="5"/>&nbsp;Сʱ</td></tr>
		<tr>
		  <tr>		
		  <td class="cellcolor">���ݱ���Ŀ¼: </td>
		  <td>&nbsp;<input type="" name="edit___cfg_backup_dir" value="<?php echo $cfg_backup_dir;?>" size="20"/>&nbsp;</td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" value=" �������� "></td></tr>
	   </table>
	  </td>
     </tr>
    </table>
	</td>
    <td>&nbsp;</td>
  </tr></form>
  <tr>
    <td id="table_style" class="l_b">&nbsp;</td>
    <td>&nbsp;</td>
    <td id="table_style" class="r_b">&nbsp;</td>
  </tr>
</table>
<?php
copyright();
?>
</body>
</html>
