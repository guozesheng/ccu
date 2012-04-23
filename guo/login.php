<HTML>
<HEAD>
<TITLE>ViooMA进销存系统登录</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gbk">
<LINK href="/css/webcss.css" type=text/css rel=stylesheet>
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
alert('请输入登陆名称！');
return false;
}
else if (thispwd=='')
{
alert('请输入用户名对应的密码！');
return false;
}
else if (thiscode=='')
{
alert('请输入验证码！');
return false;
}
else
return true;
}
</SCRIPT>
<META content="MSHTML 6.00.2900.5583" name=GENERATOR></HEAD>
<BODY leftMargin=0 topMargin=0 onload=document.form1.username.focus() MARGINHEIGHT="0" MARGINWIDTH="0">
<?php
require_once(dirname(__FILE__)."/include/config_rglobals.php");
require_once(dirname(__FILE__)."/include/config_base.php");
if ($action=='login')
{
 if (GetCkVdValue()==$code)
  {//登陆处理
  $username = eregi_replace("['\"\$ \r\n\t;<>\*%\?]", '', $username);
  $loginip=getip();
  $logindate=getdatetimemk(time());
  $lsql=new Dedesql(false);
  $sql=str_replace('#@__',$cfg_dbprefix,"select * from #@__boss where boss='$username' and password='".md5($password)."'");
  $lsql->SetQuery($sql);
  $lsql->Execute();
  $rowcount=$lsql->GetTotalRow();
  if ($rowcount==0){
  $message='用户或密码错误被系统拒绝登陆！';
  WriteNote($message,$logindate,$loginip,$username);
  showmsg($message,-1);
  }
  else
  {//可以正常登陆，写登陆数据
  $message="正常登入进销存系统！";
  setcookie('VioomaUserID',$username.$cfg_cookie_encode,time()+$cfg_keeptime*3600);
  WriteNote($message,$logindate,$loginip,$username);
  $loginsql=str_replace('#@__',$cfg_dbprefix,"update #@__boss set logindate='$logindate',loginip='$loginip' where boss='$username'");
  mysql_query($loginsql);
  header("Location:index.php");
  }
    mysql_close();
  }
  else
  {
  $errmessage="输入的验证码不正确！";
  showmsg($errmessage,-1);
  }
  }
else
{
?>
<FORM name="form1" onSubmit="return login()" action="login.php" method="post">
<TABLE height="86%" cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD align=middle height=439>
      <TABLE width=720 border=0>
        <TBODY>
        <TR>
          <TD align=middle width=214>
            <P><IMG height=140 src="images/dt01.jpg" width=197></P>
            <P><IMG height=194 src="images/dt02.jpg" width=195></P></TD>
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
                        class=STYLE4>用户名：</SPAN><BR></TD>
                      <TD width="42%"><INPUT id=username size=18 name=username></TD>
                      <TD vAlign=center align=middle width="36%" rowspan="3">
<INPUT type=image height=23 width=71 src="images/login1.gif" name=Submit onMouseOver=nereidFade(this,100,10,5) style="FILTER:alpha(opacity=50)" onMouseOut=nereidFade(this,50,10,5)> 
</TD>
                    <TR>
                      <TD align=middle height=30><SPAN 
                      class=STYLE4>密　码：</SPAN></TD>
                      <TD><INPUT id=password type=password size=18 
                        name=password></TD>
</TR>
<TR>
                      <TD align=middle height=30><SPAN 
                      class=STYLE4>验证码：</SPAN></TD>
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
            CopyRights &copy;2008~2009 Powered By <a href="http://www.viooma.com" target="_blank">www.ViooMA.com</a> Web进销存 2008版
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
