<?php

if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ITemplateNodeContainer.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/PDFUtil.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ListBasedComponent.php';

/**
 * Class for printing list to PDF file.
 * List is almost the same as grid. But List place list data in few columns. 
 * 
 * @author Segey.Kukurudzyak
 *
 */
class ListComponent extends ListBasedComponent implements ITemplateNodeContainer 
{
    /**
     * column count to show list
     */
    public $columnCount = 1;

    /**
     * label field to get data from HashMap
     */
    public $labelField = "label"; 

    /**
     * the width of each column in list
     */
    public $columnWidth = 0;
	
    public function write()
    {
	    parent::write();
		
	    if ($this->dataProvider === null)
		    throw new Exception("DataProvider is NULL");
		    
	    $this->generateTableData();
	    $this->writeList();
    }
	
    private function writeList()
    {
    	$this->getFont()->setFont($this->componentPdfWriter);
		$this->componentPdfWriter->SetFillColor(255);
		
	    while (($row = $this->getNextRow()) != null)
		    $this->writeRow($row[$this->labelField]);
    }
	
    /**
     * Write one row of data into the table: {@link PdfPTable}
     * 
     * @param table {@link PdfPTable}
     * @param row array of string values to be placed
     * @param rowFontStyle font style for current row
     * @throws DocumentException 
     */
    private function writeRow($row)
    {
    	$value = DataUtils::getValue($row);
    	$this->componentPdfWriter->Cell($this->width, $this->rowHeight, $value, 0, 0, "L", true);
    	$this->componentPdfWriter->Ln();
    }

    protected function generateFromArray($array)
    {
	    if ($array == null)
	    	$array = $this->dataProvider;
		
	    parent::generateFromArray($array);

	    if (count($this->dataProviderList) == 0)
	    {
		    throw new Exception("DataProvider is empty");
	    }
	    else if ($this->dataProviderList[0][DataUtils::$METHOD_WORD] != null)
	    {
		    if (DataUtils::valueIsMethod($this->dataProviderList[0][DataUtils::$METHOD_WORD]))
			    $this->generateFromString($this->dataProviderList[0][DataUtils::$METHOD_WORD]);
		    else
			    $this->generateFromString(DataUtils::$METHOD_CODE + $this->dataProviderList[0][DataUtils::$METHOD_WORD]);
	    }
	    else
        {
			foreach ($this->dataProviderList as $key => $val)
            if ( DataUtils::valueIsMethod( $val ) )
                $this->generateFromString( $val );
	    }
    }

    public function getItemClass()
    {
        return "array";
    }

    public function getFieldName()
    {
        return "dataProvider";
    }
}
?>