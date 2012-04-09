<?php
/**
*
* 验证码处理类
* @author  蜻蜓@@ <webmaster@ppframe.com>
* @copyright http://www.ppframe.com
* @version $id
*/
Iimport('dbsql');
class CheckNum{
	/**
	 * 验证码数字(字符串)
	 *
	 * @var string $Number
	 */
	var $Number;
	/**
	 * 验证码过期时间(秒)
	 *
	 * @var number $CkTime
	 */
	var $CkTime;
	/**
	 * 验证码标志ID
	 *
	 * @var string $CkID
	 */
	var $CkID;
	/**
	 * 验证码产生时间
	 *
	 * @var number $CkCrtime
	 */
	var $CkCrtime;
	/**
	 * 验证码会话保持标志
	 *
	 * @var string $CkCkey
	 */
	var $CkCkey="_CkCkey_";
	/**
	 * 是否使用Cookie 进行会话保持
	 *
	 * @var bool $UseCookie
	 */
	var $UseCookie = true;
	/**
	 * 数据库访问类
	 *
	 * @var object $csql
	 */
	var $csql;
	/**
	 * 验证码长度
	 * 改变该参数来改变验证码的字符数目
	 *
	 * @var number $CkLen
	 */
	var $CkLen=4;
	/**
	 * 验证码图片长度,改变该参数来改变验证码图片的长度
	 *
	 * @var number $Img_x
	 */
	var $Img_x=80;
	/**
	 * 验证码图片高,改变该参数来改变验证码图片的高度
	 *
	 * @var number $Img_y
	 */
	var $Img_y=22;
	/**
	 * 验证码背景颜色(使用RGB3原色)
	 *
	 * @var array $Img_back
	 */
	var $Img_back = array(255,255,255);
	/**
	 * 验证码边框
	 *
	 * @var array $Img_border
	 */
	var $Img_border = array(0,0,0);
	/**
	 * 验证码所用字体数组
	 *
	 * @var array
	 * @todo 添加更多GD可用的字体文件
	 */
	var $Img_font = array(1,2,3,4,5);
	/**
	 * 保存验证码的数据库表
	 *
	 * @var unknown_type
	 */
	var $Table = "##__frame_cknum";
	
	var $Domain = '';
	
	/**
	 * PHP5构造函数
	 *
	 * @param number $time 会话保持时间(分钟)
	 */
	function __construct($time=5){
		$this -> Domain = $GLOBALS['rtc']['domain'];
		if(empty($this->Domain) || !eregi(str_replace('.','\.',$this->Domain),'.'.$_SERVER['HTTP_HOST']) || !ereg('\.',$this->Domain) || $this->Domain == 'localhost') {
			$this->Domain = '';
		}
		$this->CkTime = $time * 60;
		if($this->UseCookie){
			if ($_REQUEST[$this->CkCkey]) {
				$this-> CkID = $_REQUEST[$this->CkCkey];
			}else if(GetCookie($this->CkCkey)) $this->CkID = GetCookie($this->CkCkey);
		}else{
			//使用session
			session_save_path(FRAME_ROOT."session");
			session_cache_expire($time);
			@session_start();
			$this->CkID = $_SESSION[$this->CkCkey];
		}
		$this->csql = new dbsql();
		if(empty($this->CkID)) $this->CkID = $this->CreateRandomID();
		$this->GetNumber();
	}
	/**
	 * PHP4构造函数
	 *
	 * @see __construct()
	 */
	function CheckNum($time=5){
		$this->__construct($time);
	}
	/**
	 * 从$CkID获得验证码的相关信息
	 *
	 * @return void
	 */
	function GetNumber(){
		if(empty($this->CkID)) return '';
		$this->csql->SetQueryString("Select `number`,`time` From {$this->Table} where `ckid`='{$this->CkID}'");
		@extract($this->csql->GetOneArray());
		$this->CkCrtime = $time;
		$this->Number = $number;
	}
	
	/**
	 * 获得一个随机验数字证码
	 *
	 * @return string 随机的数字验证码
	 */
	function CreateRandomNumber(){
		mt_srand((double)microtime() * 1000000);
		$ck = "";
		for ($i = 0;$i<$this->CkLen;$i++){
			//生成 a -> z 范围的随机验证码
			$ck .= strtolower(chr(mt_rand(ord('a'),ord('z'))));
		}
		return $ck;
	}
	
	/**
	 * 生成一个随机的验证码标志
	 * 改写该函数可获得更加丰富的验证码字符
	 * 
	 * @return string 随机的验证码标志
	 */
	function CreateRandomID(){
		mt_srand((double)microtime() * 1000000);
		$ck = "";
		$len = mt_rand(5,8);
		for ($i=0;$i<$len;$i++){
			$ck .= chr(mt_rand(ord('a'),ord('z')));
		}
		$ck = substr(md5($ck.time().$_SERVER["HTTP_USER_AGENT"]),mt_rand(0,32-$len),$len);
		return $ck;
	}
	
	/**
	 * 验证一个 $num 是否正确
	 *
	 * @param string $num 待验证的验证码串
	 * @return bool
	 */
	function Check($num){
		$num = strtolower($num);
		//验证码错误
		if(empty($this->Number) || $num!=$this->Number)
		$r = false;
		//验证码过期
		else if(time()-$this->CkCrtime>$this->CkTime)
		$r = false;
		//验证码有效
		else if(!empty($this->Number) && $num == $this->Number)
		$r = true;
		//处理数据
		$ar = array(
			'number' => $this->CreateRandomNumber(),
			'time' => $GLOBALS['timestamp'],
		);
		$this->csql->DoUpdate($ar,$this->Table,"`ckid`='{$this->CkID}'");
		if(!$this->UseCookie){
			@session_destroy();
		}
		return $r;
	}
	
	/**
	 * 创建一个验证码图片 (仅简单生成了一个示例图片)
	 * 改写该函数或得更加丰富的验证码图像
	 *
	 */
	function CreateImg(){
		$this->Number = $this->CreateRandomNumber();
		if(empty($this->CkID)) $this->CkID = $this->CreateRandomID();
		//设置标志位
		if($this->UseCookie){
			//该cookie 可以永久保存
			PutCookie($this->CkCkey,$this->CkID,3600*24*20,"/",$this->Domain);
		}else{
			$_SESSION[$this->CkCkey] = $this->CkID;
		}
		//加入数据库
		$ar = array(
			'ckid' => $this->CkID,
			'number' => $this->Number,
			'time' => $GLOBALS['timestamp']
		);
		$this->csql->DoInsert($ar,$this->Table,"replace");
		//create验证图片
		if(function_exists('imagecreate') && function_exists('imagecolorallocate') 
		&& function_exists('imagepng') && function_exists('imagesetpixel') 
		&& function_exists('imageString') && function_exists('imagedestroy') 
		&& function_exists('imagefilledrectangle') && function_exists('imagerectangle')){
			//GD库可用
			$img = imagecreate($this->Img_x,$this->Img_y);
			$back = imagecolorallocate($img,$this->Img_back[0],$this->Img_back[1],$this->Img_back[2]);
			$border = imagecolorallocate($img,$this->Img_border[0],$this->Img_border[1],$this->Img_border[2]);
			imagefilledrectangle($img,0,0,$this->Img_x-1,$this->Img_y-1,$back);
			imagerectangle($img,0,0,$this->Img_x-1,$this->Img_y-1,$border);
			
			//获得字库
			$font = imageloadfont(FRAME_ROOT."font.gdf");
			
			//画图
			for ($i=0;$i<strlen($this->Number);$i++){
				imagestring($img,$font,$i*$this->Img_x/$this->CkLen+mt_rand(1,5),mt_rand(1,6),strtoupper($this->Number[$i]),imagecolorallocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100)));
			}
			header("Pragma:no-cache");
			header("Cache-control:no-cache");
			header("Content-type: image/png");
			imagepng($img);
			imagedestroy($img);
			exit;
		}else{
			//GD不可用时
		}
	}
}