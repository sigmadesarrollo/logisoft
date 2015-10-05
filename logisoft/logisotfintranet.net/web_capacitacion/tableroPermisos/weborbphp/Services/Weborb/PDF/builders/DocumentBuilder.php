<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/builders/Builder.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Document.php';

class DocumentBuilder extends Builder
{

	protected function __construct()
	{
		
	}
	
	public /*Document*/function buildOnMethod(/*String*/ $value)
	{
		return Builder::buildDocument(DataUtils::getValue($value));		
	}
	
	public /*Document*/function buildOnURLTemplate(/*String*/ $value)
	{
		/*DOMDocument*/ $xmldoc = new DOMDocument();
		$xmldoc->load($value);
		return $this->buildOnDOMDocument($xmldoc);
		
	}

	public /*Document*/function buildOnFileTemplate(/*String*/ $value)
	{
		if (strpos("/") == strpos("\\"))
			$value = DataUtils::getBaseTemplatePath() . "/" . $value;
			Log::log(LoggingConstants::MYDEBUG, " Build on file template ");
		/*DOMDocument*/ $xmldoc = new DOMDocument();
		$xmldoc->load($value);
		return $this->buildOnDOMDocument($xmldoc);		
	}
	
	public /*Document*/function buildOnDOMDocument(DOMDocument $xmldoc)
	{
		/*Element*/ $mainElement = $xmldoc->documentElement;
		/*Document*/ $document = $this->parseNode($mainElement);
		return $document;
	}
	
	public /*Document*/function buildOnString(/*String*/ $value)
	{
		/*Document*/ $document = new Document();
		$document->pages = array();
		$document->width = 400;
		$document->height = 200;
		/*Page*/ $page = new Page();
		$page->content = array();
		/*Text*/ $text = new Text();
		$text->x = 10;
		$text->y = 10;
		$text->value = value;
		$page->content[] = $text;
		$document->pages[] = $page;
		return $document;
	}
	
	public static /*Document*/function build(/*String*/ $value)
	{
		/*DocumentBuilder*/ $builder = new DocumentBuilder();
		
		if (DataUtils::valueIsMethod($value)) 
		{
			return $builder->buildOnMethod($value);
		}
		else if (DataUtils::valueIsLink($value))
		{
			return $builder->buildOnURLTemplate($value);
		}
		else if (DataUtils::valueIsFile($value))
		{
			return $builder->buildOnFileTemplate($value);
		}
		else 
		{
			return $builder->buildOnString($value);
		}
		
	}	
}
