<?php
/*
 * Created on Dec 18, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/PDFUtil.php'; 
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Text.php';

 class Button extends Text
 {
	public $isSubmit = false;
	public $url;
	public $label;
	
	public function write()
	{
		$backgroundColor = DataUtils::get_rgb_color($this->backgroundColor);
		$borderColor = DataUtils::get_rgb_color($this->borderColor);
		$this->componentPdfWriter->SetFillColor(255);
		$this->componentPdfWriter->SetDrawColor(0);
		$this->componentPdfWriter->Rect($this->getPageX(), $this->getPageY(), $this->width, $this->height, "DF");
		$this->value = $this->label;
		parent::write();
		if ($this->isSubmit)
			$this->componentPdfWriter->Link($this->getPageX(), $this->getPageY(), $this->width, $this->height, $this->url);
	}	
 }
?>
