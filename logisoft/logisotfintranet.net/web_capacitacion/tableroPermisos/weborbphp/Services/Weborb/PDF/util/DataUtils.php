<?php

require_once WebOrb . "Config/ORBConfig.php";

class DataUtils {
	
	/**
	 * key word which indicates method name in header
	 */
	public static /*String*/ $METHOD_WORD = "method";
	
	/**
	 * key word which indicates method name in value
	 */
	public static /*String*/ $METHOD_CODE = "method:";
	
	/**
	 * http key to indicate URL based string
	 */
	public static /*String*/ $HTTP = "http:";

	/**
	 * https key to indicate URL based string
	 */
	public static /*String*/ $HTTPS = "https:";
	
	/**
	 * default system template path
	 * It will be taken on the way if weborb settings will be empty 
	 */
	private static /*String*/ $BASE_TEMPLATE_PATH = "pdftemplates";
	
	
	/**
	 * If weborb setting "templateFolder" is empty returns BASE_TEMPLATE_PATH
	 * 
	 * @return template folder
	 */
	public static /*String*/ function getBaseTemplatePath()
	{
		/*String*/ $result = ORBConfig::getInstance()->getConfig("templateFolder");
		
		if (($result != null) && ($result != ""))
			return $result;
		
		return WebOrbServicesPath . BASE_PDF_SERVICE_PATH . "PDF/" . self::$BASE_TEMPLATE_PATH;
	}
	
	/**
	 * If weborb setting "outputFolder" is empty returns ""
	 * 
	 * @return output folder for generated PDF files
	 */
	public static /*String*/ function getOutputFolder()
	{
		/*String*/ $result = ORBConfig::getInstance()->getConfig("outputFolder");
		
		if (($result != null) && ($result != ""))
			return $result;
		
		return "";
	}
	
	/**
	 * Generates random string consists of "length" characters
	 * 
	 * @param length 	result string length
	 * @return 			random string
	 */
	public static /*String*/ function getRandomString(/*int*/ $length)
	{
	    /*String*/ $chars = "abcdefghijklmonpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   	 
   	 	$result = "";
   	 	
   	 	for ($i = 0; $i < $length; $i++) {
			$result .= $chars[rand(0, strlen($chars))];
		}
 
        return $result;
	}
	
	/**
	 * Generates random file name
	 * 
	 * @return file name (*.pdf)
	 */
	public static /*String*/ function getOutputFileName()
	{
		/*String*/ $result = self::getRandomString(10);
		
		$result .= ".pdf";
		
		return $result;
	}
	
	/**
	 * If income value is method function returns method invocation result
	 * another way function return income value 
	 * 
	 * @param value
	 * @return {@link Object}
	 * @throws DocumentException
	 */
	public static /*Object*/ function getValue(/*Object*/ $value)
	{
		/*Object*/ $result = $value;
		
		if (is_string($value))
		{
			if (self::valueIsMethod($value))
			{
				$dataUtils = new DataUtils();
				$result = $dataUtils->getMethodResult($value);
			}
		}
		
		return $result;
	}
	
	public static /*boolean*/function valueIsMethod(/*String*/ $value)
	{
		try
		{
			$result = is_string($value) && stripos($value, self::$METHOD_CODE) == 0  && stripos($value, self::$METHOD_CODE) !== false;
		}
		catch (Exception $e)
		{
			return false;
		}
		return $result;
	}
	
	public static /*boolean*/function valueIsLink(/*String*/ $value)
	{
		$result = is_string($value);
		$result = $result && (stripos($value, self::$HTTP) === 0 || stristr($value, self::$HTTPS) === 0);
		return $result;
	}
	
	public static /*String*/function getFilePath(/*String*/ $name, /*boolean*/ $win = false)
	{
		if ($win)
			return self::getBaseTemplatePath() . "\\" . $name;
		
		return self::getBaseTemplatePath() . "/" . $name;
	}
	
	public static /*boolean*/function valueIsFile(/*String*/ $value)
	{
		return file_exists(self::getFilePath($value));
	}
	
	public static /*Object*/ function instantiateClass(/*String*/ $className)
	{
		$class = TypeLoader::loadType($className);
		$object = $class->newInstance();
		return $object;
	}
	
	protected /*Object*/function getMethodResult(/*String*/ $method)
	{
		/*String*/ $methodString = substr($method,strlen(self::$METHOD_CODE));
		/*String*/ $serviceString = substr($methodString, 0, strrpos($methodString, "."));
		/*String*/ $methodName = substr($methodString, strlen($serviceString)+1, strpos($methodString, "(")-strlen($serviceString)-1);
		/*String*/ $parametrsString = substr($methodString, strpos($methodString, "(")+1, strpos($methodString, ")")-strpos($methodString, "(")-1);
		/*Array*/ $args = $this->parseOperands($parametrsString);
		
		/*ReflectionClass*/ $class = TypeLoader::loadType($serviceString);	
		$object = $class->newInstance();
		/*ReflectionMethod*/ $method = $class->getMethod($methodName);
		$result = $method->invokeArgs($object, $args);
		
		return $result; 
	}
	
	private /*Object[]*/function parseOperands(/*String*/ $operands)
	{
		$result = array();
		$operandsAr = explode(",",trim($operands));
		foreach($operandsAr as $operand)
		{
			$result[] = trim($operand);
		}
		return $result;
	}
	
	public static function setValue(/*Object*/ $obj, /*String*/ $name, /*Object*/ $value)
	{
		if($obj == null)
			return;
		try
		{
			$reflectionClass = new ReflectionClass(get_class($obj));
			if($reflectionClass->hasProperty($name))
			{
				$property = $reflectionClass->getProperty($name);	
				$property->setValue($obj, $value);			
			}
		}
		catch (ReflectionException $e)
		{
			var_dump($obj);
			Log::log(LoggingConstants::MYDEBUG, ob_get_contents());	
			throw new Exception("DataUtils::setValue(". get_class($obj) .", ". $name .", ". get_class($value) .") : " . $e->getMessage()); 
		}
	}
	

	private static /*boolean*/function isHexValue(/*String*/ $value)
	{
		return ((strpos($value, "0x") === 0) || (strpos($value, "#") === 0));
	}
	
	private static /*int*/function convertFromHex(/*String*/ $value)
	{
		/*int*/ $result = 0;
		/*String*/ $hex = substr($value, 2, strlen($value));
		$result = hexdec($hex);

		return $result;
	}
	
	private static /*String*/function convertToString(/*byte[]*/ $array)
	{
		$data = "";
		foreach ($array as $byte)
			$data .= pack("C", $byte);
		return $data;

	}
	
	public static /*Image*/function getImage(/*byte[]*/ $source)
	{
		$ext = "png";
		
		if (self::valueIsLink($source))
		{
			$str = file_get_contents($source);
			$ext = substr($source, -3);
		}
		else if (file_exists(WebOrbServicesPath . BASE_PDF_SERVICE_PATH . "PDF/" .  $source))
		{
			$path = WebOrbServicesPath . BASE_PDF_SERVICE_PATH . "PDF/" .  $source;
			$f = fopen($path, "rb");
			$str = fread($f, filesize($path));
			$ext = substr($path, -3);
		}
		else
		{
			$str = self::convertToString($source);
		}
		
		$fname = WebOrbServicesPath . BASE_PDF_SERVICE_PATH . "PDF/pdf_files/" . "temp_image_" . microtime(true) . ".png";
		$fname2 = WebOrbServicesPath . BASE_PDF_SERVICE_PATH . "PDF/pdf_files/" . "temp_image_" . microtime(true) . "2.png";
		
		$result['fileName'] = $fname;
		
		$hf = fopen($fname, "wb");
		fwrite($hf, $str);
		fclose($hf);
		
		if ($ext == "png")
			$result['source'] = imagecreatefrompng($fname);
		elseif ($ext == "gif")
			$result['source'] = imagecreatefromgif($fname);
		elseif ($ext == "jpg")
			$result['source'] = imagecreatefromjpeg($fname);
			
		imagesavealpha($result['source'], true);
		
		imagepng($result['source'], $fname);
	
		return $result;			
	}
	
	public static function get_rgb_color($hexcolor)
	{
		
		if(is_numeric($hexcolor))
		{
			$hexcolor *= 1;
			$hexcolor = dechex($hexcolor);
		}
		if($hexcolor[0] == '#')
		{
			$hexcolor = substr($hexcolor, 1);
		}
		elseif( ($hexcolor[0] == '0') && (strlen($hexcolor) > 1) && ($hexcolor[1] == 'x'))
		{
			$hexcolor = substr($hexcolor, 2);
		}
		
		$lenHexColor = strlen($hexcolor);
		for($i = 0; $i < (6-$lenHexColor); $i++)
		{
			$hexcolor = "0" . $hexcolor;
		}	
		
		$color['r'] = hexdec(substr($hexcolor, 0, 2));
	    $color['g'] = hexdec(substr($hexcolor, 2, 2));
	    $color['b'] = hexdec(substr($hexcolor, 4, 2));
	    
	    return $color;//"{rgb ". $color['r'] . " " . $color['g'] . " " . $color['b'] . "}";
	}
	
	public static function pdf_set_default_color(PDFLib $pdf, $type = '')
	{
       $color['r'] = hexdec(substr(self::$defaultColor, 0, 2))/255;
       $color['g'] = hexdec(substr(self::$defaultColor, 2, 2))/255;
       $color['b'] = hexdec(substr(self::$defaultColor, 4, 2))/255;
       if ($type != 'fill' && $type != 'stroke')  $type = 'both';
       $pdf->setcolor($type, 'rgb', $color['r'], $color['g'], $color['b'], 0);
	}
	
	public static function get_hex_rgb($RGBcolor)
	{
		return '#' . dechex($RGBcolor['r']) . dechex($RGBcolor['g']) . dechex($RGBcolor['b']);
	}
}
?>

 