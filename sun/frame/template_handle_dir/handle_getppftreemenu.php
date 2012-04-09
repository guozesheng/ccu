<?php
!defined('MODULE') && exit('Forbidden');
function GetPPFTreeMenu($treearray,$temp='fun_getppftreemenu',$style=1,$width='100%',$a='',$in=0) {
	!in_array($style,array(1,2,3)) && $style = 1;
	$treeheader = <<<E
	<div class="PPFTreeMenu" id="PPFTreeMenu{$style}">
	  <p>
	  <a id="AllOpen_{$style}" href="javascript:;" onClick="MyPPFTreeMenu{$style}.SetNodes(0);Hd(this);Sw('AllClose_{$style}');return false;">Open All</a>
	<a id="AllClose_{$style}" href="javascript:;" onClick="MyPPFTreeMenu{$style}.SetNodes(1);Hd(this);Sw('AllOpen_{$style}');return false;" style="display:none;">Close All</a>
	</p>	
E;
	$treefooter = <<<E
	</div>
<script type="text/javascript">
<!--
function Ob(o){
var o=document.getElementById(o)?document.getElementById(o):o;
return o;
}
function Hd(o) {
Ob(o).style.display="none";
}
function Sw(o) {
Ob(o).style.display="block";
}
function ExCls(o,a,b,n){
var o=Ob(o);
for(i=0;i<n;i++) {o=o.parentNode;}
o.className=o.className==a?b:a;
}
function PPFTreeMenu(id,TagName0) {
this.id=id;
this.TagName0=TagName0==""?"li":TagName0;
this.AllNodes = Ob(this.id).getElementsByTagName(TagName0);
this.InitCss = function (ClassName0,ClassName1,ClassName2,ImgUrl) {
this.ClassName0=ClassName0;
this.ClassName1=ClassName1;
this.ClassName2=ClassName2;
this.ImgUrl=ImgUrl || "{$GLOBALS['rtc']['sourceurl']}css/images/ppframe/s.gif";
this.ImgBlankA ="<img src=\""+this.ImgUrl+"\" class=\"s\" onclick=\"ExCls(this,'"+ClassName0+"','"+ClassName1+"',1);\" alt=\"Open/Close\" />";
this.ImgBlankB ="<img src=\""+this.ImgUrl+"\" class=\"s\" />";
for (i=0;i<this.AllNodes.length;i++ ) {
   this.AllNodes[i].className==""?this.AllNodes[i].className=ClassName1:"";
   this.AllNodes[i].innerHTML=(this.AllNodes[i].className==ClassName2?this.ImgBlankB:this.ImgBlankA)+this.AllNodes[i].innerHTML;
   }
}
this.SetNodes = function (n) {
var sClsName=n==0?this.ClassName0:this.ClassName1;
for (i=0;i<this.AllNodes.length;i++ ) {
   this.AllNodes[i].className==this.ClassName2?"":this.AllNodes[i].className=sClsName;
}
}
}
var MyPPFTreeMenu{$style}=new PPFTreeMenu("PPFTreeMenu{$style}","li");
MyPPFTreeMenu{$style}.InitCss("Opened","Closed","Child","{$GLOBALS['rtc']['sourceurl']}css/images/ppframe/s.gif");
-->
</script>
E;
	if (is_array($treearray)) {
		$rstr = '<ul>'."\r\n";
		foreach ($treearray as $k => $v) {
			Iimport('Template');
			$tpl = new Template();
			
			if (!$v['sun'] || !is_array($v['sun'])) {
				$v['_is_finall_'] = 1;
			}
			if (!$in) {
				$v['_is_top_'] = 1;
			}
			if ($GLOBALS['_INMODULE_']) {	#这段代码兼容扩模块的链接问题。
				@include(ROOT.$GLOBALS['_INMODULE_'].'/config/baseconfig.php');
				$tpl -> Assign('_f_ub',$base_config['exam_root']);
			}
			$tpl -> Assign('_f_ta',$v);
			$rstr .= $tpl -> Parse($temp);
			if ($v['sun'] && is_array($v['sun'])) {
				$rstr .= GetPPFTreeMenu($v['sun'],$temp,$style,$width,$a,1) ;
			}
		}
		$rstr .= '</ul>'."\r\n";
		if ($in) {
			return $rstr;
		}else {
			return $treeheader . $rstr . $treefooter;
		}
	}
	return '';
}