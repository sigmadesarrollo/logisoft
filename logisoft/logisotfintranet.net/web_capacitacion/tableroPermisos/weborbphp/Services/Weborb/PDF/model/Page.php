<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ITemplateNodeContainer.php';

class Page implements ITemplateNodeContainer
{
	public /*Component[]*/ $content;
	
	public function write()
	{
		Log::log(LoggingConstants::MYDEBUG, "Write Page");	
		foreach( $this->content as $child ) 
            if( ($child != null) && (get_class($child) != "") && (!$child->onEachPage)  ) $child->write();
	}
	
    public /*Component*/ function getComponentById( /*string*/ $itemId )
    {

        foreach( $this->content as $component )
        {
        	$result = null;
        	
            if( is_subclass_of($component, "Container" ))
                $result = $component->getComponentById( $itemId );

            if( $result == null )
                $result =
                    ($component->id == $itemId )
                         ? $component
                         : null;
                         
            if( $result != null ) return $result;
        }

        return null;
    }

    public /*void*/ function initComponent( 
        /*Document*/ $document,
        /*PdfWriter*/ $pdfWriter,
        //iTextSharp.text.Document pdfDocument,
        /*int*/ $marginTop, /*int*/ $marginLeft, /*int*/ $marginRight, /*int*/ $marginBottom )
    {
        foreach( $this->content as $component )
        {
            if (get_class($component) != "")
            $component->initComponent( 
                $document, $pdfWriter,0, 0, $marginTop, $marginLeft, $marginRight, $marginBottom, 
                $document->width, $document->height );
        }
    }
    
	public /*String*/function getFieldName() 
	{
		return "content";
	}

	public /*Class<?>*/function getItemClass() 
	{
		return new ReflectionClass("Component");
	}	

}
?>