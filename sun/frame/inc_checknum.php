<?php
/**
*
* ��֤�봦����
* @author  ����@@ <webmaster@ppframe.com>
* @copyright http://www.ppframe.com
* @version $id
*/
Iimport('dbsql');
class CheckNum{
	/**
	 * ��֤������(�ַ���)
	 *
	 * @var string $Number
	 */
	var $Number;
	/**
	 * ��֤�����ʱ��(��)
	 *
	 * @var number $CkTime
	 */
	var $CkTime;
	/**
	 * ��֤���־ID
	 *
	 * @var string $CkID
	 */
	var $CkID;
	/**
	 * ��֤�����ʱ��
	 *
	 * @var number $CkCrtime
	 */
	var $CkCrtime;
	/**
	 * ��֤��Ự���ֱ�־
	 *
	 * @var string $CkCkey
	 */
	var $CkCkey="_CkCkey_";
	/**
	 * �Ƿ�ʹ��Cookie ���лỰ����
	 *
	 * @var bool $UseCookie
	 */
	var $UseCookie = true;
	/**
	 * ���ݿ������
	 *
	 * @var object $csql
	 */
	var $csql;
	/**
	 * ��֤�볤��
	 * �ı�ò������ı���֤����ַ���Ŀ
	 *
	 * @var number $CkLen
	 */
	var $CkLen=4;
	/**
	 * ��֤��ͼƬ����,�ı�ò������ı���֤��ͼƬ�ĳ���
	 *
	 * @var number $Img_x
	 */
	var $Img_x=80;
	/**
	 * ��֤��ͼƬ��,�ı�ò������ı���֤��ͼƬ�ĸ߶�
	 *
	 * @var number $Img_y
	 */
	var $Img_y=22;
	/**
	 * ��֤�뱳����ɫ(ʹ��RGB3ԭɫ)
	 *
	 * @var array $Img_back
	 */
	var $Img_back = array(255,255,255);
	/**
	 * ��֤��߿�
	 *
	 * @var array $Img_border
	 */
	var $Img_border = array(0,0,0);
	/**
	 * ��֤��������������
	 *
	 * @var array
	 * @todo ��Ӹ���GD���õ������ļ�
	 */
	var $Img_font = array(1,2,3,4,5);
	/**
	 * ������֤������ݿ��
	 *
	 * @var unknown_type
	 */
	var $Table = "##__frame_cknum";
	
	var $Domain = '';
	
	/**
	 * PHP5���캯��
	 *
	 * @param number $time �Ự����ʱ��(����)
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
			//ʹ��session
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
	 * PHP4���캯��
	 *
	 * @see __construct()
	 */
	function CheckNum($time=5){
		$this->__construct($time);
	}
	/**
	 * ��$CkID�����֤��������Ϣ
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
	 * ���һ�����������֤��
	 *
	 * @return string �����������֤��
	 */
	function CreateRandomNumber(){
		mt_srand((double)microtime() * 1000000);
		$ck = "";
		for ($i = 0;$i<$this->CkLen;$i++){
			//���� a -> z ��Χ�������֤��
			$ck .= strtolower(chr(mt_rand(ord('a'),ord('z'))));
		}
		return $ck;
	}
	
	/**
	 * ����һ���������֤���־
	 * ��д�ú����ɻ�ø��ӷḻ����֤���ַ�
	 * 
	 * @return string �������֤���־
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
	 * ��֤һ�� $num �Ƿ���ȷ
	 *
	 * @param string $num ����֤����֤�봮
	 * @return bool
	 */
	function Check($num){
		$num = strtolower($num);
		//��֤�����
		if(empty($this->Number) || $num!=$this->Number)
		$r = false;
		//��֤�����
		else if(time()-$this->CkCrtime>$this->CkTime)
		$r = false;
		//��֤����Ч
		else if(!empty($this->Number) && $num == $this->Number)
		$r = true;
		//��������
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
	 * ����һ����֤��ͼƬ (����������һ��ʾ��ͼƬ)
	 * ��д�ú�����ø��ӷḻ����֤��ͼ��
	 *
	 */
	function CreateImg(){
		$this->Number = $this->CreateRandomNumber();
		if(empty($this->CkID)) $this->CkID = $this->CreateRandomID();
		//���ñ�־λ
		if($this->UseCookie){
			//��cookie �������ñ���
			PutCookie($this->CkCkey,$this->CkID,3600*24*20,"/",$this->Domain);
		}else{
			$_SESSION[$this->CkCkey] = $this->CkID;
		}
		//�������ݿ�
		$ar = array(
			'ckid' => $this->CkID,
			'number' => $this->Number,
			'time' => $GLOBALS['timestamp']
		);
		$this->csql->DoInsert($ar,$this->Table,"replace");
		//create��֤ͼƬ
		if(function_exists('imagecreate') && function_exists('imagecolorallocate') 
		&& function_exists('imagepng') && function_exists('imagesetpixel') 
		&& function_exists('imageString') && function_exists('imagedestroy') 
		&& function_exists('imagefilledrectangle') && function_exists('imagerectangle')){
			//GD�����
			$img = imagecreate($this->Img_x,$this->Img_y);
			$back = imagecolorallocate($img,$this->Img_back[0],$this->Img_back[1],$this->Img_back[2]);
			$border = imagecolorallocate($img,$this->Img_border[0],$this->Img_border[1],$this->Img_border[2]);
			imagefilledrectangle($img,0,0,$this->Img_x-1,$this->Img_y-1,$back);
			imagerectangle($img,0,0,$this->Img_x-1,$this->Img_y-1,$border);
			
			//����ֿ�
			$font = imageloadfont(FRAME_ROOT."font.gdf");
			
			//��ͼ
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
			//GD������ʱ
		}
	}
}