<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/IDataCellComponent.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';

class Text extends Component implements IDataCellComponent
{
	
	public /*String*/ $value;
	
	public function write()
	{
		parent::write();
		
		if (($this->value == null) || (strlen($this->value) == 0) || ($this->fontSize == 0)) return;
		
		$font = $this->getFont();
		$font->setFont($this->componentPdfWriter);

        
		$this->componentPdfWriter->SetXY($this->getPageX(), $this->getPageY());
		$val = DataUtils::getValue($this->value);
		
        if ($this->width == 0)
        {
        	$this->width = $this->componentPdfWriter->GetStringWidth($val);
        }
        
		$this->componentPdfWriter->MultiCell($this->width + 6, $this->fontSize + 6, $val, 0, "L", false);
	}
	
    public function getContent()
    {
    	return $this->value;
    }
    
    /*Font*/ public function getFont()
    {
    	return parent::getFont();
    }
}
?>