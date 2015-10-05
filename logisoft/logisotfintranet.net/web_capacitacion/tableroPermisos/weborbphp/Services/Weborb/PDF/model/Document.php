<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ITemplateNodeContainer.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Page.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/PDFUtil.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/UnlicensedException.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/lib/PDF.php';

class Document implements ITemplateNodeContainer
{
	
	public $images = array();
	
	public static $pageFormats = array('a3'=>array(841.89,1190.55), 'a4'=>array(595.28,841.89), 'a5'=>array(420.94,595.28),
		'letter'=>array(612,792), 'legal'=>array(612,1008));
	
	public $pagesCount = 0;
	
	private $elementsOnEachPage = array();
	
    private /*void*/ function showOnEachPage(/*Component*/ $item)
    {
        $this->elementsOnEachPage[] = $item;
    }
    	
    private function parseMetadata()
    {
        if ($this->metadata != null)
        {
            /*Property[]*/ $properties = $this->metadata->getPropertiesByName(Property::$SHOW_ON_ALL_PAGES);
            if (count($properties) != 0)
                foreach ($properties as $property) 
                {
                    $component = $this->getComponentById($property->target);
                    if ($component != null)
                        $this->showOnEachPage($component);
                }
        }
    }	
	
    public function writeEachPageComponents()
    {
        foreach ($this->elementsOnEachPage as $component)
        {
            $component->write();
        }
    }
    	
	public /*int*/ $width = 595; //(int) PageSize.A4.getWidth();

	public /*int*/ $height = 842;//(int) PageSize.A4.getHeight();
	
	public /*PDFMetadata*/ $metadata;
	/**
	 * document page margin
	 * use this property to set default margin for pages
	 */
	public /*int*/ $margin = 0;
	
	public /*int*/ $marginTop = -1;
	
	public /*int*/ $marginLeft = -1;
	
	public /*int*/ $marginRight = -1;
	
	public /*int*/ $marginBottom = -1;
	
	/**
	 * Document author
	 */
	public /*String*/ $author;

	/**
	 * document pages - array of {@link Page}
	 */
	public /*Page[]*/ $pages;
	
	public function __construct(){}
	
	/**
	 * if margin top parameter is less then zero function returns margin parameter
	 * 
	 * @return int value
	 */
	private /*int*/ function getMarginTop()
	{
		if ($this->marginTop == -1) return $this->margin;
		
		return $this->marginTop;
	}
	
	/**
	 * if margin left parameter is less then zero function returns margin parameter
	 * 
	 * @return int value
	 */
	private /*int*/ function getMarginLeft()
	{
		if ($this->marginLeft == -1) return $this->margin;
		
		return $this->marginLeft;
	}

	/**
	 * if margin right parameter is less then zero function returns margin parameter
	 * 
	 * @return int value
	 */
	private /*int*/ function getMarginRight()
	{
		if ($this->marginRight == -1) return $this->margin;
		
		return $this->marginRight;
	}
	
	/**
	 * if margin bottom parameter is less then zero function returns margin parameter
	 * 
	 * @return int value
	 */	
	public /*int*/ function getMarginBottom()
	{
		if ($this->marginBottom == -1) return $this->margin;
		
		return $this->marginBottom;
	}
	
	/**
	 * Write document to PDF output file
	 * 
	 * @param pdfDocument	iText {@link com.lowagie.text.Document}
	 * @param pdfWriter		iText {@link PdfWriter}
	 * 
	 * @throws DocumentException
	 * @throws IOException
	 */
	public /*void*/ function write(PDF $pdfWriter) 
	{
		$pdfWriter->SetAuthor("MeliorSolutions");
		
//		Log::log(LoggingConstants::MYDEBUG, " --- METADATA: " .  $this->metadata);
		
		if ($this->metadata != null)
		{
	        /*Property*/ $pageMarginsProperty = $this->metadata->getPropertyByName(Property::$PAGE_MARGINS);
	        
	        if ($pageMarginsProperty != null)
	        {
	        	Log::log(LoggingConstants::MYDEBUG, " --- PAGE MARGINS: " .  $pageMarginsProperty -> value);
	            $this->marginTop = $this->marginLeft = $this->marginRight = $this->marginBottom = $pageMarginsProperty -> value;
	        }
		}
		
		if (count($this->pages) == 0)
			throw new Exception("Document does not contain any page!");
		
        foreach ($this->pages as $page)
            $page->initComponent($this, $pdfWriter,
            		$this->getMarginTop(), $this->getMarginLeft(), $this->getMarginRight(), $this->getMarginBottom());

		$this->parseMetadata();
		
        foreach($this->pages as $page) 
        {
            try
            {
            	PDFUtil::startNewPage($pdfWriter, $this);
                $page->write();
            }
		    catch (UnlicensedException $e)
		    {
			    break;
		    }
        }
		
		foreach($this->images as $imageData)
		{
			unlink($imageData['fileName']); 
		}
		
//        pdfDocument.close();
	}
	
    public /*Component*/ function getComponentById(/*String*/ $itemId)
    {
        foreach ($this->pages as $page)
        {
            $result = $page->getComponentById($itemId);
            
            if ($result != null) return $result;
        }

        return null;
    }
	
//	@Override
	public /*String*/ function getFieldName() {
		return "pages";
	}

//	@Override
	public /*Class*/ function getItemClass() {
		return new ReflectionClass("Page");
	}
}
?>