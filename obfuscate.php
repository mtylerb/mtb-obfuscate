<?php

/***
 *
 * REGEX Constants
 *
 ***/

if (!defined('OBF_ALPHA_NUM')) { define('OBF_ALPHA_NUM','/[^A-Za-z0-9]/'); }	// Leave only alpha numeric chars
if (!defined('OBF_ALPHA_ONLY')) { define('OBF_ALPHA_ONLY','/[^A-Za-z]/'); }		// Leave only alpha chars
if (!defined('OBF_ALPHA_SPEC')) { define('OBF_ALPHA_SPEC','/[^A-Za-z\-_\.]/'); }// Leave alpha and special chars
if (!defined('OBF_NUM_ONLY')) { define('OBF_NUM_ONLY','/[^0-9]/'); }			// Leave only numerical chars
if (!defined('OBF_NUM_SPEC')) { define('OBF_NUM_SPEC','/[^0-9\-_\.]/'); }		// Leave numerical and special chars
if (!defined('OBF_SPEC_ONLY')) { define('OBF_SPEC_ONLY','/[^-_\.]/'); }			// Leave only special chars
if (!defined('OBF_ALL')) { define('OBF_ALL','/[^A-Za-z0-9\-\_\.\@]/'); }		// Pretty much don't filter anything

class Encoder
{
	public $alphaNum;
	public $math;
	
	protected function randMath($length = 100)
	{
		$name = '';
		for ($i=0;$i<=$length;$i++)
		{
			$v = preg_replace(OBF_ALPHA_ONLY,'',$this->alphaNum);
			$r = substr($v,rand(1,strlen($v)),1);
			
			$name .= $r;
		}
		
		return $name;
	} // End randMath Function
	
	public function encode($string)
	{
		$matches = str_split($string);
		
		foreach ($matches as $key=>&$match)
		{
			if (preg_match('/[A-Za-z]/',$match) == 1)
			{
				$math = ord(substr($this->math,$key,1));
				$math = (($math <= 90) ? $math-64 : $math-96);
				$match = chr(($match <= 'Z' ? 90 : 122) >= (ord($match) + $math) ? (ord($match) + $math) : ((ord($match) + $math) - 26));
			} else if (preg_match('/[0-9]/',$match) == 1) {
				$math = ord(substr($this->math,$key,1));
				$math = (($math <= 90) ? $math-64 : $math-96);
				$math = (($math <= 9) ? $math : $math % 10);
				$match = chr(57 >= (ord($match) + $math) ? (ord($match) + $math) : ((ord($match) + $math) - 10));
			} else {
				$match = $match;
			}
		}
		unset($match);
		$encoded = implode('',$matches);
		
		return $encoded;
	}

	public function decode($string)
	{
		$matches = str_split($string);
		
		foreach ($matches as $key=>&$match)
		{
			if (preg_match('/[A-Za-z]/',$match) == 1)
			{
				$math = ord(substr($this->math,$key,1));
				$math = (($math <= 90) ? $math-64 : $math-96);
				$match = chr(($match <= 'Z' ? 65 : 97) <= (ord($match) - $math) ? (ord($match) - $math) : ((ord($match) - $math) + 26));
			} else if (preg_match('/[0-9]/',$match) == 1) {
				$math = ord(substr($this->math,$key,1));
				$math = (($math <= 90) ? $math-64 : $math-96);
				$math = (($math <= 9) ? $math : $math % 10);
				$match = chr(48 <= (ord($match) - $math) ? (ord($match) - $math) : ((ord($match) - $math) + 10));
			} else {
				$match = $match;
			}
		}
		unset($match);
		$decoded = implode('',$matches);
		
		return $decoded;
	}
	
	function __construct()
	{
		$this->alphaNum = 'ABCDEFGHIJKLMOBF_NOPQROBF_STUVWXYZabcdefghijklmnopqrstuvwxyz1234567890-_.@';
		$this->math = $this->randMath();
	}
}

class Obfuscate
{
	public $alphaNum;
	public $fnCall;
	public $math;
	public $displayImage;
	public $displayType;
	public $text;
	public $ifgc;
	public $ibgc;
	public $linkClass;
	public $imageClass;
	
	protected function randName($type)
	{
		$name = '';
		switch ($type)
		{
			case 'var':
				$count = rand(5,15);
				break;
			case 'fn':
				$count = rand(9,20);
				break;
			case 'id':
				$count = rand(5,10);
				break;
		}
		for ($i=0;$i<=$count;$i++)
		{
			if ($i <= 1) 
			{
				$v = preg_replace(OBF_ALPHA_ONLY,'',$this->alphaNum);
				$r = substr($v,rand(1,strlen($v)),1);
			} else {
				if ($type == 'id')
				{
					$v = preg_replace(OBF_ALL,'',$this->alphaNum);
					$r = substr($v,rand(1,strlen($v)),1);
				} else {
					$v = preg_replace(OBF_ALPHA_NUM,'',$this->alphaNum);
					$r = substr($v,rand(1,strlen($v)),1);
				}
			}
			
			$name .= $r;
		}
		
		return $name;
	} // End randName Function
	
	public function setClass($string = false)
	{
		$this->linkClass = $string;
		$this->imageClass = $string.'Image';
	}
	
	public function setType($string = 'text')
	{
		$string = strtolower($string);
		$this->displayType = $string;
		if($this->displayType == 'text') $this->displayImage = false;
		else if ($this->displayType == 'image') $this->displayImage = true;
	}
	
	public function setText($string)
	{
		$this->text = $string;
	}
	
	public function setEmail($string)
	{
		$this->email = $string;
	}
	
	public function setImageBG($rgb = "#000000")
	{
		// HEX color code, e.g. #000000 for black.
		$rgb = preg_replace('/[^a-zA-Z0-9]/','',$rgb);
		$this->ibgc = $rgb;
	}
	
	public function setImageFG($rgb = "#ffffff")
	{
		// HEX color code, e.g. #ffffff for white.
		$rgb = preg_replace('/[^a-zA-Z0-9]/','',$rgb);
		$this->ifgc = $rgb;
	}
	
	public function linkOut()
	{
		if (empty($this->linkClass) || empty($this->imageClass)) $this->setClass();
		if (empty($this->ifgc)) $this->setImageFG();
		if (empty($this->ibgc)) $this->setImageBG();
		if (empty($this->displayType)) $this->setType();
		$ePart = explode('@',$this->email);
		foreach ($ePart as &$part)
		{
			$part = $this->encoder->encode($part);
		}
		unset($part);
		$f = (!$this->ifgc) ? '' : '&amp;f='.$this->ifgc;
		$b = (!$this->ibgc) ? '' : '&amp;b='.$this->ibgc;
		$href[0] = '<a href="http://www.'.$ePart[1].'/'.$ePart[0].'" rel="nofollow" onclick="javascript:'.$this->fnCall['crypt'][$this->displayType].'(this);" onmouseover="javascript:'.$this->fnCall['inner'][$this->displayType].'(this);" onmouseout="javascript:'.$this->fnCall['inner'][$this->displayType].'(this);" title="'.$this->text.'">';
		$href[1] = (!$this->displayImage) ? $this->text : '<img src="'.PLUGINS_URI.'mtb_obfuscate/images/image.php?s=http://www.'.$ePart[1].'/'.$ePart[0].'&amp;m='.substr($this->math,0,strlen($this->email)).$f.$b.'" alt="'.$this->text.'" />';
		$href[2] = '</a>';
		
		$out = implode('',$href);
		echo $out;
	}
	
	public function jsOut()
	{
		$finished = false;
		while (!$finished)
		{
			$fnName[0] = (!isset($fnName[0]) || empty($fnName[0])) ? $this->randName('fn') : $fnName[0];
			$this->fnCall['crypt']['text'] = $fnName[0];
			$fnName[1] = (!isset($fnName[1]) || empty($fnName[1])) ? $this->randName('fn') : $fnName[1];
			$this->fnCall['crypt']['image'] = $fnName[1];
			$fnName[2] = (!isset($fnName[2]) || empty($fnName[2])) ? $this->randName('fn') : $fnName[2];
			$fnName[3] = (!isset($fnName[3]) || empty($fnName[3])) ? $this->randName('fn') : $fnName[3];
			$this->fnCall['inner']['text'] = $fnName[3];
			$fnName[4] = (!isset($fnName[4]) || empty($fnName[4])) ? $this->randName('fn') : $fnName[4];
			$this->fnCall['inner']['image'] = $fnName[4];
			$var[0] = (!isset($var[0]) || empty($var[0])) ? $this->randName('var') : $var[0];
			$var[1] = (!isset($var[1]) || empty($var[1])) ? $this->randName('var') : $var[1];
			$var[2] = (!isset($var[2]) || empty($var[2])) ? $this->randName('var') : $var[2];
			$var[3] = (!isset($var[3]) || empty($var[3])) ? $this->randName('var') : $var[3];
			$var[4] = (!isset($var[4]) || empty($var[4])) ? $this->randName('var') : $var[4];
			$var[5] = (!isset($var[5]) || empty($var[5])) ? $this->randName('var') : $var[5];
			$fnName = array_unique($fnName);
			$fnName = array_values($fnName);
			$var = array_unique($var);
			$var = array_values($var);
			if (!empty($fnName[4]) || (!empty($var[5]))) $finished = true;
		}
		$javascript  = "<script type=\"text/javascript\">"."\r\n";
		$javascript .= "//<![CDOBF_ATA["."\r\n";
		$javascript .= "function $fnName[0]($var[0])"."\r\n";
		$javascript .= "{"."\r\n";
		$javascript .= "	var $var[1] = $var[0].href;"."\r\n";
		$javascript .= "	var $var[1] = $var[1].replace('http://www.','');"."\r\n";
		$javascript .= "	var $var[2] = $var[1].split('\/');"."\r\n";
		$javascript .= "	var $var[3] = $fnName[2]($var[2][1]);"."\r\n";
		$javascript .= "	var $var[4] = $fnName[2]($var[2][0]);"."\r\n";
		$javascript .= "	var $var[5] = $fnName[2]('".$this->encoder->encode('mailto:')."');"."\r\n";
		$javascript .= "	$var[0].href = $var[5] + $var[3] + '@' + $var[4];"."\r\n";
		$javascript .= "	$var[0].setAttribute('onclick','');"."\r\n";
		$javascript .= "	$var[0].setAttribute('onmouseover','javascript:$fnName[3](this,\'dc\');');"."\r\n";
		$javascript .= "	$var[0].setAttribute('onmouseout','javascript:$fnName[3](this,\'dc\');');"."\r\n";
		$javascript .= "}"."\r\n";
		$javascript .= "function $fnName[1]($var[0])"."\r\n";
		$javascript .= "{"."\r\n";
		$javascript .= "	var $var[1] = $var[0].href;"."\r\n";
		$javascript .= "	var $var[1] = $var[1].replace('http://www.','');"."\r\n";
		$javascript .= "	var $var[2] = $var[1].split('\/');"."\r\n";
		$javascript .= "	var $var[3] = $fnName[2]($var[2][1]);"."\r\n";
		$javascript .= "	var $var[4] = $fnName[2]($var[2][0]);"."\r\n";
		$javascript .= "	var $var[5] = $fnName[2]('".$this->encoder->encode('mailto:')."');"."\r\n";
		$javascript .= "	$var[0].href = $var[5] + $var[3] + '@' + $var[4];"."\r\n";
		$javascript .= "	$var[0].setAttribute('onclick','');"."\r\n";
		$javascript .= "}"."\r\n";
		$javascript .= "function $fnName[2]($var[0])"."\r\n";
		$javascript .= "{"."\r\n";
		$javascript .= "	var $var[1];"."\r\n";
		$javascript .= "	var $var[2];"."\r\n";
		$javascript .= "	var $var[3];"."\r\n";
		$javascript .= "	var $var[4];"."\r\n";
		$javascript .= "	var $var[5] = '$this->math';"."\r\n";
		$javascript .= "	"."\r\n";
		$javascript .= "	$var[1] = $var[0].split('');"."\r\n";
		$javascript .= "	"."\r\n";
		$javascript .= "	for ($var[2] in $var[1])"."\r\n";
		$javascript .= "	{"."\r\n";
		$javascript .= "		if ($var[1][$var[2]].search(/[A-Za-z]/) != (-1))"."\r\n";
		$javascript .= "		{"."\r\n";
		$javascript .= "			$var[3] = $var[5].charCodeAt($var[2]);"."\r\n";
		$javascript .= "			$var[3] = (($var[3] <= 90) ? $var[3]-64 : $var[3]-96);"."\r\n";
		$javascript .= "			$var[1][$var[2]] = String.fromCharCode(($var[1][$var[2]] <= 'Z' ? 65 : 97) <= ($var[1][$var[2]].charCodeAt(0) - $var[3]) ? ($var[1][$var[2]].charCodeAt(0) - $var[3]) : (($var[1][$var[2]].charCodeAt(0) - $var[3]) + 26));"."\r\n";
		$javascript .= "		} else if ($var[1][$var[2]].search(/[0-9]/) != (-1))"."\r\n";
		$javascript .= "		{"."\r\n";
		$javascript .= "			$var[3] = $var[5].charCodeAt($var[2]);"."\r\n";
		$javascript .= "			$var[3] = (($var[3] <= 90) ? $var[3]-64 : $var[3]-96);"."\r\n";
		$javascript .= "			$var[3] = (($var[3] <= 9) ? $var[3] : $var[3] % 10);"."\r\n";
		$javascript .= "			$var[1][$var[2]] = String.fromCharCode(48 <= ($var[1][$var[2]].charCodeAt(0) - $var[3]) ? ($var[1][$var[2]].charCodeAt(0) - $var[3]) : (($var[1][$var[2]].charCodeAt(0) - $var[3]) + 10));"."\r\n";
		$javascript .= "		} else {"."\r\n";
		$javascript .= "			$var[1][$var[2]] = $var[1][$var[2]];"."\r\n";
		$javascript .= "		}"."\r\n";
		$javascript .= "	}"."\r\n";
		$javascript .= "	$var[4] = $var[1].join('');"."\r\n";
		$javascript .= "	"."\r\n";
		$javascript .= "	return $var[4];"."\r\n";
		$javascript .= "}"."\r\n";
		$javascript .= "function $fnName[3]($var[0], $var[1])"."\r\n";
		$javascript .= "{"."\r\n";
		$javascript .= "	var $var[1] = (typeof $var[1] == 'undefined') ? 'ec' : $var[1];"."\r\n";
		$javascript .= "	"."\r\n";
		$javascript .= "	if ($var[1] == 'ec')"."\r\n";
		$javascript .= "	{"."\r\n";
		$javascript .= "		var $var[2] = $var[0].href;"."\r\n";
		$javascript .= "		$var[2] = $var[2].replace('http://www.','');"."\r\n";
		$javascript .= "		var $var[3] = $var[2].split('\/');"."\r\n";
		$javascript .= "		$var[4] = ($var[2][0] + $var[2][1]).length;"."\r\n";
		$javascript .= "		$var[0].innerHTML = ($var[0].innerHTML == $var[0].title) ? $fnName[2]($var[3][1]) + '@' + $fnName[2]($var[3][0]) : $var[0].title;"."\r\n";
		$javascript .= "	} else if ($var[1] == 'dc') {"."\r\n";
		$javascript .= "		var $var[2] = $var[0].href;"."\r\n";
		$javascript .= "		var $var[3] = $fnName[2]('".$this->encoder->encode('mailto:')."');"."\r\n";
		$javascript .= "		$var[2] = $var[2].replace($var[3],'');"."\r\n";
		$javascript .= "		$var[0].innerHTML = ($var[0].innerHTML == $var[0].title) ? $var[2] : $var[0].title;"."\r\n";
		$javascript .= "	}"."\r\n";
		$javascript .= "}"."\r\n";
		$javascript .= "function $fnName[4]($var[0])"."\r\n";
		$javascript .= "{"."\r\n";
		$javascript .= "	var $var[2] = $var[0].href;"."\r\n";
		$javascript .= "	$var[2] = $var[2].replace('http://www.','');"."\r\n";
		$javascript .= "	var $var[3] = $var[2].split('\/');"."\r\n";
		$javascript .= "	$var[4] = ($var[2][0] + $var[2][1]).length;"."\r\n";
		$javascript .= "	$var[0].innerHTML = $fnName[2]($var[3][1]) + '@' + $fnName[2]($var[3][0]);"."\r\n";
		$javascript .= "	$var[0].setAttribute('onmouseover','');"."\r\n";
		$javascript .= "	$var[0].setAttribute('onmouseout','');"."\r\n";
		$javascript .= "}"."\r\n";
		$javascript .= "//]]>"."\r\n";
		$javascript .= "</script>"."\r\n";
				
		echo $javascript;
	} // End jsOut Function
	
	function __construct()
	{
		$this->alphaNum = 'ABCDEFGHIJKLMOBF_NOPQROBF_STUVWXYZabcdefghijklmnopqrstuvwxyz1234567890-_.@';
		$this->encoder = new Encoder;
		$this->math = $this->encoder->math;
	} // End __construct Function
} // End Obfuscate Class

?>