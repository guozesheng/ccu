<?php
/**
 * ͼƬˮӡ����
 * @author  ����@@ <webmaster@ppframe.com>
 * @copyright http://www.ppframe.com
 */
class ImageWaterMark {
	/**
	 * ҪˮӡͼƬ�ĸ�ʽ ����֧��1��2��3
	 * 1 = GIF��2 = JPG��3 = PNG��4 = SWF��5 = PSD��6 = BMP��7 = TIFF(intel byte order)��8 = TIFF(motorola byte order)��9 = JPC��10 = JP2��11 = JPX��12 = JB2��13 = SWC��14 = IFF��15 = WBMP��16 = XBM
	 *
	 * @var number 
	 */
	var $ImageType;
	/**
	 * ˮӡͼƬ�ĸ�ʽ
	 *
	 * @var number
	 */
	var $WtImageType;
	/**
	 * ��ͼƬ��X����ֳɵĸ���
	 *
	 * @var number
	 */
	var $TPosX = 10;
	/**
	 * ��ͼƬ��Y����ֳɵĸ���
	 *
	 * @var number
	 */
	var $TPosY = 10;
	/**
	 * ˮӡλ��X�ָ�
	 *
	 * @var number
	 */
	var $PosX=10;
	/**
	 * ˮӡλ��Y�ָ�
	 *
	 * @var number
	 */
	var $PosY=10;
	/**
	 * Ҫˮӡ��ͼƬ
	 *
	 * @var string
	 */
	var $BackImage;
	/**
	 * ˮӡ��ͼƬ
	 *
	 * @var string
	 */
	var $WarterImage;
	/**
	 * ˮӡ������
	 *
	 * @var string
	 */
	var $WarterString;
	/**
	 * ˮӡ��������ɫ
	 *
	 * @var string
	 */
	var $StringColor='#CCCCCC';
	/**
	 * ���������С
	 *
	 * @var number
	 */
	var $StringFont = 20;
	/**
	 * ͼƬ�ĳ�
	 *
	 * @var number
	 */
	var $ImageX;
	/**
	 * ͼƬ�Ŀ�
	 *
	 * @var number
	 */
	var $ImageY;
	
	var $WtImageX;
	
	var $WtImageY;
	
	function __construct($backimage,$warterimage='',$warterstring='',$stringcolor='') {
		$this -> SetBackImage($backimage);
		$warterimage && $this -> SetWarterImage($warterimage);
		$warterstring && $this -> SetWarterString($warterstring);
		$stringcolor && $this -> SetStringColor($stringcolor);
		$this -> SetTotalPos(10,10);
	}

	function ImageWaterMark() {
		$this->__construct();
	}
	
	function SetBackImage($bkimage) {
		if (file_exists($bkimage)) {
			$this->BackImage = $bkimage;
			$image = getimagesize($this->BackImage);
			$this->ImageType = $image[2];
			$this->ImageX = $image[0];
			$this->ImageY = $image[1];
		}else {
			echo 'No.BackImage';
		}
	}
	
	function SetWarterImage($wtimage) {
		if (file_exists($wtimage)) {
			$this->WarterImage = $wtimage;
			$image = getimagesize($this->WarterImage);
			$this->WtImageType = $image[2];
			$this->WtImageX = $image[0];
			$this->WtImageY = $image[1];
		}else {
			echo 'No.WarterImage';
		}
	}
	
	function SetWarterString($wtstring) {
		$this->WarterString = $wtstring;
	}
	//�� SetTotalPos �����,�м�!
	function SetPos($x=0,$y=0) {
		$x && $this->PosX = abs(intval($x));
		$y && $this->PosY = abs(intval($y));
	}
	
	function SetTotalPos($tposx=0,$tposy=0) {
		$tposx>0 && $this->TPosX = intval($tposx);
		$tposy>0 && $this->TPosY = intval($tposy);
		if ($this->WtImageType) {	//ͼƬˮӡʱ,��ȷ��ҪˮӡͼƬ��ˮӡͼƬ��,����Ҫ��ö�,���ǳ���Ա������
			$px = floor($this->ImageX/$this->WtImageX);
			$py = floor($this->ImageY/$this->WtImageY);
			$this->TPosX = min($px,$this->TPosX);
			$this->TPosY = min($py,$this->TPosY);
		}else if ($this->WarterString && function_exists('imagettfbbox')) {
			$temp = imagettfbbox(ceil($this->StringFont), 0 ,FRAME_ROOT.'font.ttf',$this->WarterString); //ȡ��ʹ�� TrueType ������ı��ķ�Χ 
	        $this ->WtImageX = $temp[2] - $temp[6]; 
	        $this ->WtImageY = $temp[3] - $temp[7];
			$px = floor($this->ImageX/$this->WtImageX);
			$py = floor($this->ImageY/$this->WtImageY);
			$this->TPosX = min($px,$this->TPosX);
			$this->TPosY = min($py,$this->TPosY);
		}
	}
	
	function SetStringColor($color) {
		$this -> StringColor = $color;
	}
	
	function SetStingFont($font) {
		$this -> StringFont = intval($font);
	}
	
	function GetWarterPosX() {
		if ($this -> PosX < 1 || $this-> PosX > $this->TPosX) {
			return floor($this->ImageX /$this->TPosX) * ($this->TPosX -1);
		}else {
			return floor($this->ImageX/$this->TPosX) * ($this -> PosX -1);
		}
	}
	
	function GetWarterPosY() {
		if ($this -> PosY < 1 || $this-> PosY > $this->TPosY) {
			return floor($this->ImageY /$this->TPosY) * ($this->TPosY -1);
		}else {
			return floor($this->ImageY/$this->TPosY) * ($this->PosY -1);
		}
	}
	
	function Warter() {
		if ($this -> BackImage && in_array($this->ImageType,array(1,2,3))) {	//���� //1 = GIF��2 = JPG��3 = PNG
			$back_im = '';
			if ($this->ImageType == 1 && function_exists('imagecreatefromgif')) {
				$back_im = imagecreatefromgif($this->BackImage);
			}else if ($this->ImageType == 2 && function_exists('imagecreatefromjpeg')) {
				$back_im = imagecreatefromjpeg($this->BackImage);
			}else if ($this ->ImageType ==3 && function_exists('imagecreatefrompng')) {
				$back_im = imagecreatefrompng($this->BackImage);
			}
			if ($back_im) {
				if ($this -> WarterImage && in_array($this->WtImageType,array(1,2,3))) {	//ʹ��ͼƬˮӡ
					$warter_im = '';
					if ($this->WtImageType == 1 && function_exists('imagecreatefromgif')) {
						$warter_im = imagecreatefromgif($this->WarterImage);
					}else if ($this->WtImageType == 2 && function_exists('imagecreatefromjpeg')) {
						$warter_im = imagecreatefromjpeg($this->WarterImage);
					}else if ($this->WtImageType == 3 && function_exists('imagecreatefrompng')) {
						$warter_im = imagecreatefrompng($this->WarterImage);
					}
				}
				
				imagealphablending($back_im,true);
				
				if ($warter_im) {	//ʹ��ͼƬ��ˮӡ
					imagecopy($back_im,$warter_im,$this->GetWarterPosX(),$this->GetWarterPosY(),0,0,$this->WtImageX,$this->WtImageY);
				}else {		// ʹ������ˮӡ
					$R = hexdec(substr($this->StringColor,1,2)); 
		            $G = hexdec(substr($this->StringColor,3,2)); 
		            $B = hexdec(substr($this->StringColor,5,2));
		           	if (function_exists('imagettftext')) {	//ʹ�� imagettftext
						imagettftext($back_im,$this->StringFont,0,$this->GetWarterPosX(),$this->GetWarterPosY()+$this->WtImageY,imagecolorallocate($back_im,$R,$G,$B),FRAME_ROOT.'font.ttf',$this->WarterString);
					}else if(function_exists('imagestring')){
						//����ֿ�,imagestring ����ԭ�������Ͻ�
						$font = imageloadfont(FRAME_ROOT."font.gdf");
						imagestring($back_im,$font,$this->GetWarterPosX()+ $this->StringFont * 2.5,$this->GetWarterPosY(),$this->WarterString,imagecolorallocate($back_im,$R,$G,$B));
					}
				}
				
				//д���ļ�
				if ($this->ImageType == 1) {
					imagegif($back_im,$this->BackImage);
				}else if ($this->ImageType == 2) {
					imagejpeg($back_im,$this->BackImage);
				}else if ($this->ImageType ==3) {
					imagepng($back_im,$this->BackImage);
				}
				#�ͷ��ڴ�
				imagedestroy($back_im);
				if ($warter_im) {
					imagedestroy($warter_im);
				}
			}
		}
	}
}