<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Text.php';
 
class TextInput extends Text
{
	public $backgroundColor = "#FFFFFF";
	
	public $borderColor = 0;
	
	public $multiline;
	
	public function write()
	{
		$backgroundColor = DataUtils::get_rgb_color($this->backgroundColor);
		$borderColor = DataUtils::get_rgb_color($this->borderColor);
		
		$this->componentPdfWriter->SetFillColor($backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
		$this->componentPdfWriter->SetDrawColor($borderColor['r'], $borderColor['g'], $borderColor['b']);
		$this->componentPdfWriter->Rect($this->getPageX(), $this->getPageY(), $this->width, $this->height, "DF");
		parent::write();
	}
}
?>
