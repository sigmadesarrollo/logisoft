<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/IDataCellComponent.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';

class Image extends Component implements IDataCellComponent
{

	public /*byte[]*/ $source;
	
	public /*int*/ $percentage = 100;
	
	public /*Boolean*/ $constraintProportional = false;
	
	public function write()
	{
		parent::write();

		$imageData = $this->getContent();
		
		if ($imageData['source'] == null) return;
		
		$this->componentPdfWriter->Image($imageData['fileName'], $this->getPageX(), $this->getPageY(), $this->width, $this->height);
	}
	
    public function getContent()
    {
    	
		if (($this->id == null) || ($this->id == ''))
			$this->id = DataUtils::getRandomString(10);

	 	Log::log(LoggingConstants::MYDEBUG, "getContent: " . $this->id);
			
		if ( ($this->document->images == null) || ($this->document->images[$this->id] == null) )
			$this->document->images[$this->id] = DataUtils::getImage($this->source);
	 	
    	return $this->document->images[$this->id];
    }
    
    /*Font*/ public function getFont()
    {
    	return parent::getFont();
    }
}
?>