<?php
require_once(dirname(__FILE__)."/include/config_base.php");
require_once(dirname(__FILE__)."/include/config_rglobals.php");
?>
<HTML>
<HEAD>
<TITLE>ʵ���ҹ���ϵͳ-��¼</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gbk">
<LINK href="style/webcss.css" type=text/css rel=stylesheet>
<STYLE type=text/css>
body,td {font-size:12px;}
</STYLE>
<SCRIPT language=JavaScript type=text/JavaScript>
				nereidFadeObjects = new Object();
				nereidFadeTimers = new Object();
				function nereidFade(object, destOp, rate, delta){
				if (!document.all)
				return
					if (object != "[object]"){ 
						setTimeout("nereidFade("+object+","+destOp+","+rate+","+delta+")",0);
						return;
					}
					clearTimeout(nereidFadeTimers[object.sourceIndex]);
					diff = destOp-object.filters.alpha.opacity;
					direction = 1;
					if (object.filters.alpha.opacity > destOp){
						direction = -1;
					}
					delta=Math.min(direction*diff,delta);
					object.filters.alpha.opacity+=direction*delta;
					if (object.filters.alpha.opacity != destOp){
						nereidFadeObjects[object.sourceIndex]=object;
						nereidFadeTimers[object.sourceIndex]=setTimeout("nereidFade(nereidFadeObjects["+object.sourceIndex+"],"+destOp+","+rate+","+delta+")",rate);
					}
				}
				</SCRIPT>
<SCRIPT language=javascript>   
function login(){
thisname=document.form1.username.value;
thispwd=document.form1.password.value;
thiscode=document.form1.code.value;
if (thisname=='')
{
alert('�������½���ƣ�');
return false;
}
else if (thispwd=='')
{
alert('�������û�����Ӧ�����룡');
return false;
}
else if (thiscode=='')
{
alert('��������֤�룡');
return false;
}
else
return true;
}
</SCRIPT>
<META content="MSHTML 6.00.2900.5583" name=GENERATOR></HEAD>
<BODY leftMargin=0 topMargin=0 onload=document.form1.username.focus() MARGINHEIGHT="0" MARGINWIDTH="0">
<?php
if ($action=='login')
{
 if (GetCkVdValue()==$code)
  {//��½����
  $username = eregi_replace("['\"\$ \r\n\t;<>\*%\?]", '', $username);
  $loginip=getip();
  $logindate=getdatetimemk(time());
  $lsql=new Dedesql(false);
  $sql=str_replace('#@__',$cfg_dbprefix,"select * from #@__boss where boss='$username' and password='".md5($password)."'");
  $lsql->SetQuery($sql);
  $lsql->Execute();
  $rowcount=$lsql->GetTotalRow();
  if ($rowcount==0){
  $message='�û����������ϵͳ�ܾ���½��';
  WriteNote($message,$logindate,$loginip,$username);
  showmsg($message,-1);
  }
  else
  {//����������½��д��½����
  $message="��������ʵ���ҹ���ϵͳ��";
  $row=$lsql->GetArray();
  setcookie('VioomaUserID',$username.$cfg_cookie_encode,time()+$cfg_keeptime*3600);
  $_SESSION['boss']=$username;
  $_SESSION['level']=$row['rank'];
  WriteNote($message,$logindate,$loginip,$username);
  $loginsql=str_replace('#@__',$cfg_dbprefix,"update #@__boss set logindate='$logindate',loginip='$loginip' where boss='$username'");
  mysql_query($loginsql);
  if ($_SESSION['level']==1)
  {
	  $path = "index2.php";
  }
  else 
  {
	  $path = "user/";
  }
//  header("Location:index.php");
  ?>
  	<script language="JavaScript">
		//window.location="index2.php";
		window.location="<?=$path?>";
	</script>
 <?php
  exit();
  }
    mysql_close();
  }
  else
  {
  $errmessage="�������֤�벻��ȷ��";
  showmsg($errmessage,-1);
  }
  }
else
{
  $_SESSION['boss']="";
  $_SESSION['level']=100;
?>
<FORM name="form1" onSubmit="return login()" action="login.php" method="post">
<TABLE height="86%" cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD align=middle height=439>
      <TABLE width=720 border=0>
        <TBODY>
        <TR>
          <TD align=left width=496>
            <TABLE height=337 cellSpacing=0 cellPadding=0 width=491 
            background=images/bsdt.gif border=0>
              <TBODY>
              <TR>
                <TD colSpan=3 height=130></TD>
			   </TR>
              <TR>
                <TD width=140 height=120>&nbsp;</TD>
                <TD align=middle width=312>
                  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width="22%" height=30><SPAN 
                        class=STYLE4>�û�����</SPAN><BR></TD>
                      <TD width="42%"><INPUT id=username size=18 name=username></TD>
                      <TD vAlign=center align=middle width="36%" rowspan="3">
<INPUT type=image height=23 width=71 src="images/login1.gif" name=Submit onMouseOver=nereidFade(this,100,10,5) style="FILTER:alpha(opacity=50)" onMouseOut=nereidFade(this,50,10,5)> 
</TD>
                    <TR>
                      <TD align=middle height=30><SPAN 
                      class=STYLE4>�ܡ��룺</SPAN></TD>
                      <TD><INPUT id=password type=password size=18 
                        name=password></TD>
</TR>
<TR>
                      <TD align=middle height=30><SPAN 
                      class=STYLE4>��֤�룺</SPAN></TD>
                      <TD><INPUT id=code type=text size=5 name=code>&nbsp;&nbsp;
					  <img src="include/getcode.php">
					   </TD>
</TR>
</TBODY>
</TABLE>
</TD>
                <TD width=39>&nbsp;</TD></TR>
              <TR align=middle>
                <TD colSpan=3>
            CopyRights &copy; 2012 Powered By <a href="mailto:guozesheng@gmail.com" target="_blank">������</a> ʵ������������ϵͳ
</TD></TR></TBODY></TABLE></TD></TR>
        <TR>
          <TD>&nbsp;</TD>
          <TD>
		  </TD>
		  </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
   <TD height=4></TD>
  </TR>
</TBODY>
</TABLE>
<input type="hidden" name="action" value="login">
</FORM>
<?php
}
?>
</BODY>
</HTML>
