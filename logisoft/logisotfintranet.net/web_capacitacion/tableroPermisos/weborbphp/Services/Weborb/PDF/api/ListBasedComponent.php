<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/lib/Font.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/lib/PDF.php';

abstract class ListBasedComponent extends Component 
{
	public $rowHeight;

	/**
     * table border width
     */
    public $borderWidth = 0.5;

    /**
     * generated data provider
     */
    protected $dataProviderList;

    /**
     * index of element which should be placed 
     */
    protected $currentIndex = 0;

    /**
     * cell padding
     */
    protected $cellPadding = 5;

    /**
     * base data provider. It could be method string, String, array, HashMap array
     */
    public $dataProvider;

    /**
     * flag indicates continue list on new page if it could not be placed on one page
     */
    public $contentPageRollover = true;
	
	/**
	 * generate HashMap to be placed in one row
	 * 
	 * @return
	 * @throws DocumentException
	 */
	protected function getNextRow()
	{
		if (count($this->dataProviderList) <= $this->currentIndex) return null;
			
		$result = $this->dataProviderList[$this->currentIndex];
	
		++$this->currentIndex;
		return $result;
	}
	
	/**
	 * calculate rows count which could be placed on current page
	 * 
	 * @param pdfWriter {@link PdfWriter}
	 * @return
	 */
	protected function rowsPerPage()
	{
		$result = floor(($this->document->height - $this->getPageY())/$this->rowHeight);
		return $result;
	}
	
	/**
	 * Generate data provider array (dataProviderList) based on basic data provider
	 * 
	 * @throws DocumentException
	 */
	protected function generateTableData()
	{
		if (is_string($this->dataProvider))
			$this->generateFromString($this->dataProvider);
		else if (is_array($this->dataProvider))
			$this->generateFromArray($this->dataProvider);
	}
	
	/**
	 * generate data provider array (dataProviderList) based on income string
	 * 
	 * @param value String value
	 * @throws DocumentException
	 */
	protected function generateFromString( $value )
	{
		if (DataUtils::valueIsMethod($value))
		{
			$this->dataProvider = DataUtils::getValue($value);
			$this->generateTableData();
		}
		else
		{
			$bufMap = array();
			$bufMap["name1"] = $value;
			$data = array($bufMap);
			$this->generateFromArray($data);
		}
	}

	/**
	 * Generate data provider array (dataProviderList) based on Object array
	 * 
	 * @param array income Object array each member of it could be 
	 * 				HashMap, Object or Object[]
	 * @throws DocumentException
	 */
	protected function generateFromArray( $array)
	{
		if (count($array) == 1) {
			foreach($array as $val) { 
				if (DataUtils::valueIsMethod($val)) {
					$this->generateFromString( $val );
					return;
				}
				break;
			}
		}
		
		$this->dataProviderList = array();
		
		for ($i = 0; $i < count($array); $i++) 
		{
			$item = array();
			$this->populateRow($item, $array[$i]);			
			$this->dataProviderList[] = $item;
		}
	}
	
	/**
	 * Populate income HashMap row from keys and vals arrays
	 * 
	 * @param row 	{@link HashMap}
	 * @param keys 	Object array of keys for row
	 * @param vals	Object array of values for row
	 */
	protected function populateRow(&$row, $vals)
	{
		if(is_array($vals))
			foreach ($vals as $key => $value)
			{
				if(is_numeric($key))
					$row["Name" . $key] = $value;
				else
					$row[$key] = $value;
			}
		else
		{
			$row["Name"] = $vals;	
		}
	}

	public function initComponent($pdfDocument, $pdfWriter,
            /*int*/ $baseX, /*int*/ $baseY,
            /*int*/ $marginTop, /*int*/ $marginLeft, /*int*/ $marginRight, /*int*/ $marginBottom,
            /*int*/ $parentWidth, /*int*/ $parentHeight)
	{
		parent::initComponent($pdfDocument, $pdfWriter, $baseX, $baseY, $marginTop, $marginLeft, $marginRight, $marginBottom, $parentWidth, $parentHeight);
		$this->rowHeight = $this->fontSize + 2 * $this->cellPadding;
	}
}
?>