<?php

include('../obfuscate.php');

class emailImage
{
	public $bgc; // Background color variable
	public $fgc; // Foreground color (text) variable
	public $fsize; // Font size in pixels
	public $text; // Desired string
	public $iwidth; // Image width
	public $iheight; // Image height
	public $image; // Image resource
	public $pad; // Image padding
	
	public function bgColor()
	{
		$rgb = (!empty($_GET['b']) ? $_GET['b'] : 'ffffff');
		$rgb = preg_replace('/[^a-zA-Z0-9]/','',$rgb);
		$red = hexdec(substr($rgb,0,2));
		$green = hexdec(substr($rgb,2,2));
		$blue = hexdec(substr($rgb,4,2));
		$this->bgc = imagecolorallocate($this->image,$red,$green,$blue);
	}
	
	public function textColor()
	{
		$rgb = (!empty($_GET['f']) ? $_GET['f'] : '000000');
		$rgb = preg_replace('/[^a-zA-Z0-9]/','',$rgb);
		$red = hexdec(substr($rgb,0,2));
		$green = hexdec(substr($rgb,2,2));
		$blue = hexdec(substr($rgb,4,2));
		$this->fgc = imagecolorallocate($this->image,$red,$green,$blue);
	}
	
	public function setText()
	{
		$decode = new Encoder;
		$decode->math = (!empty($_GET['m']) ? $_GET['m'] : die('Math not set'));
		$string = (!empty($_GET['s']) ? $_GET['s'] : die('String not set'));
		if (strpos('http://www.',$string) >= 0)
		{
			$string = str_replace('http://www.','',$string);
			$string = explode('/',$string);
			foreach ($string as &$piece)
			{
				$piece = $decode->decode($piece);
			}
			unset($piece);
			$string = implode('@',array_reverse($string));
		}
		return $string;
	}
	
	public function outputImage()
	{
		imagefill($this->image,0,0,$this->bgc);
		$base = ($this->iheight*.80);
		imagettftext($this->image,($this->fsize*2),0,$this->pad,$base,$this->fgc,'tahoma.ttf',$this->text);
		imagepng($this->image);
		imagedestroy($this->image);
	}
	
	function __construct($size = 3)
	{
		header("Content-type: image/png");
		$this->fsize  = $size;
		$this->text = $this->setText();
		$this->iwidth  = imagefontwidth($this->fsize) * (strlen($this->text) * .775);
		$this->pad = 1;
		$this->iheight = imagefontheight($this->fsize);
		$this->image = imagecreatetruecolor ($this->iwidth,$this->iheight);
		$this->bgColor();
		$this->textColor();
		$this->outputImage();
	}
}

$image = new emailImage(5);
?>