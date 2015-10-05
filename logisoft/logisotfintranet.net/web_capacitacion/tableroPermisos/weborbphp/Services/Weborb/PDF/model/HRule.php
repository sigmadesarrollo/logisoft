<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
 require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
 require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/PDFUtil.php';
 require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';
 
 class HRule extends Component
 {
 	public function write()
 	{
 		$color = DataUtils::get_rgb_color($this->strokeColor);
 		$this->componentPdfWriter->SetDrawColor($color["r"] * 255, $color["g"] * 255, $color["b"] * 255);
// 		$this->componentPdfWriter->SetFillColor($color["r"] * 255 , $color["g"] * 255, $color["b"] * 255);
 		$this->componentPdfWriter->SetLineWidth($this->height);
 		$this->componentPdfWriter->Line($this->getPageX(), $this->getPageY(), $this->getPageX() + $this->width, $this->getPageY());
 	}
 }
?>
