<?php
class Font {
	private $fontFamily = "Verdana";

	private $fontSize = 12;

	private $fontStyle = "";

	private $fontColor = 0;

	public function setFontFamily($newFontFamily) 
	{
		$this->fontFamily = $newFontFamily;
	}
	
	public function getFontFamily()
	{
		return $this->fontFamily;
	}

	public function setFontSize($newFontSize) 
	{
		$this->fontSize = $newFontSize > 0 ? $newFontSize +0 : $this->fontSize;
	}
	
	public function getFontSize()
	{
		return $this->fontSize;
	}

	public function setFontStyle($bold, $italic) {
		$b = ((strtolower($bold) == "bold") || (strtolower($bold) == "b")) ? "b" : "";
		$i = ((strtolower($italic) == "italic") || (strtolower($italic) == "i")) ? "i" : "";
		$this->fontStyle = $b . $i;
	}
	
	public function fontWeight()
	{
		return strpos($this->fontStyle, "b") > -1 ? "bold" : "normal"; 
	}
	
	public function fontStyle()
	{
		return strpos($this->fontStyle, "i") > -1 ? "italic" : "normal";
	}

	public function setFontColor(/*int*/
	$rgbColor, $gColor = "", $bColor = "") {
		if (is_int($rgbColor) && is_int($gColor) && is_int($bColor)) {
			$this->fontColor["r"] = $rgbColor;
			$this->fontColor["g"] = $gColor;
			$this->fontColor["b"] = $bColor;
		} else
			$this->fontColor = DataUtils :: get_rgb_color($rgbColor);
	}
	
	public function getFontColor()
	{
		if (count($this->fontColor) == 0)
			$this->setFontColor(0, 0, 0);
		return DataUtils :: get_hex_rgb($this->fontColor);
	}
	
	public function getRColor() 
	{
		if (count($this->fontColor) == 0)
			$this->setFontColor(0, 0, 0);		
		return $this->fontColor['r'];
	}

	public function getGColor() 
	{
		if (count($this->fontColor) == 0)
			$this->setFontColor(0, 0, 0);
		return $this->fontColor['g'];
	}

	public function getBColor() 
	{
		if (count($this->fontColor) == 0)
			$this->setFontColor(0, 0, 0);
		return $this->fontColor['b'];
	}

	public function setFont($pdfWriter) {
		if ($this->fontColor === 0)
			$this->setFontColor($this->fontColor);
		$pdfWriter->AddFont($this->fontFamily, $this->fontStyle);
		$pdfWriter->SetFont($this->fontFamily, $this->fontStyle);
		$pdfWriter->SetFontSize((int) $this->fontSize);
		$pdfWriter->SetTextColor($this->getRColor(), $this->getGColor(), $this->getBColor());
	}
}
?>
