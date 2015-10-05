<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ICellRenderer.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/renderers/BaseCellRenderer.php';

class DataGridColumn extends Component 
{
	
	public /*String*/ $dataField = null;
	
	public /*String*/ $headerText = null;
	
    public /*Object*/ $cellRendererClass = null;

    public /*Object*/ $headerRendererClass = null;	
	
	public function __construct(){}
	
	public /*String*/ function getHeaderLabel()
	{
		if ($this->headerText == null)
			return $this->dataField;
		return $this->headerText;
	}
	
	public /*ICellRenderer*/ function getCellRenderer()
    {
        /*Object result;*/

        if( $this->cellRendererClass == null )
            $result = new BaseCellRenderer();
        else if( is_string($this->cellRendererClass) )
            $result = DataUtils::instantiateClass( $this->cellRendererClass );
        else
            $result = $this->cellRendererClass;

        if( $result instanceof ICellRenderer )
            return $result;
        else
            throw new DocumentException( "Cell renderer class should implement ICellRenderer interface!" );
    }

    public /*ICellRenderer*/ function getHeaderRenderer()
    {
        /*Object result;*/
		
        if( $this->headerRendererClass == null )
            $result = new BaseCellRenderer();
        else if( is_string($this->headerRendererClass) )
            $result = DataUtils::instantiateClass( $this->headerRendererClass );
        else
            $result = $this->headerRendererClass;

        if( $result instanceof ICellRenderer )
            return $result;
        else
            throw new DocumentException( "Header renderer class should implement 'ICellRenderer' interface!" );
    }

    public /*override*/ function initComponent(
            /*com.tmc.weborb.pdf.model.Document document,*/
            /*FPDF*/ $pdfWriter,
            	 
            /*iTextSharp.text.Document*/ $pdfDocument,
            /*int*/ $baseX, /*int*/ $baseY,
            /*int*/ $marginTop, /*int*/ $marginLeft, /*int*/ $marginRight, /*int*/ $marginBottom,
            /*int*/ $parentWidth, /*int*/ $parentHeight
            )
    {
    /*PDFMetadata*/ $metadata = $pdfDocument->metadata;

        if( $metadata != null )
        {
            /*Property[]*/ $properties = $metadata->getPropertiesByTarget( $this->dataField );

            if( count($properties) != 0 )
                foreach( $properties as $property )
                    DataUtils::setValue( $this, $property->name, (string)$property->value );
        }
    }	
}
?>