<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ITemplateNodeContainer.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ListBasedComponent.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/DataGridColumn.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/PDFUtil.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/builders/DocumentBuildException.php';

class DataGrid extends ListBasedComponent implements ITemplateNodeContainer
{

	//public
	/**
	 * Array of {@link DataGridColumn}
	 */
	public /*DataGridColumn[]*/ $columns;
	
	/**
	 * Flag to show grid column headers
	 */
	public /*Boolean*/ $showColumnHeaders = true;
	
	//private
	
	/**
	 * returns list of header names (labels)
	 */
	private/* String[]*/function getHeaderRow()
	{
		/*String[]*/ $result = array();
		
		for ($i = 0; $i < count($this->columns); $i++) 
		{
			$result[$i] = $this->columns[$i]->headerText != null ? $this->columns[$i]->headerText : $this->columns[$i]->dataField;
		}
		return $result;
	}
	
	public function write()
	{
		parent::write();
		
		if ($this->dataProvider == null)
		{
			throw new Exception("DataProvider is NULL");
		}
		
		$this->generateTableData();

		//------- init columns width ---------------
		$colZero = 0;
		$colMore = 0;
		
		for ($i = 0; $i < count($this->columns); $i++) 
		{
			if ((real) $this->columns[$i]->width == 0)
				$colZero++;
			else
				$colMore += (real) $this->columns[$i]->width;
			
		}
		
		if ((($colZero > 0) && ($colMore > 0)) || ($colMore == 0))
		{
			$avarage = ($this->width - $colMore)/$colZero;
			for ($i = 0; $i < count($this->columns); $i++) 
			{
				if ((real) $this->columns[$i]->width == 0)
					$this->columns[$i]->width = $avarage;
			}
		}
		else if ($colZero == 0) 
		{
			for ($i = 0; $i < count($this->columns); $i++) 
			{
				$perc = (real) $this->columns[$i]->width / $colMore;
				$this->columns[$i]->width = $this->width * $perc;
			}			
		}
		//------- init columns width ---------------
		if ($this->contentPageRollover)
		{
			$this->componentPdfWriter->SetXY($this->getPageX(), $this->getPageY());
			
			/*int*/ $rowCount = count($this->dataProviderList);
			/*int*/ $usedRows = 0;
			
			if ($rowCount < $this->rowsPerPage())
			{
				$this->writeTable($rowCount);
			}
			else
			{
				while ($rowCount - $usedRows > $this->rowsPerPage())
				{
					$usedRows = $usedRows + $this->rowsPerPage();
					$this->writeTable($this->rowsPerPage());
					PDFUtil::startNewPage( $this->componentPdfWriter, $this->document );
					$this->componentPdfWriter->SetXY($this->getPageX(), $this->getPageY());
				}
				
				$this->writeTable( $rowCount - $usedRows );
			}
			
		}
		else
		{
			$this->componentPdfWriter->SetXY($this->getPageX(), $this->getPageY());
			$this->writeTable(count($this->dataProviderList));
		}
	}
	
	protected function generateFromArray(/*Object[]*/ $array)
	{
		parent::generateFromArray($array);
		
		if (count($this->dataProviderList) == 0)
		{
			throw new DocumentBuildException("DataProvider is empty");
		}
		else if ($this->dataProviderList[0][DataUtils::$METHOD_WORD] != null)
		{
			$this->columns = null;
			if (DataUtils::valueIsMethod($this->dataProviderList[0][DataUtils::$METHOD_WORD]))
			{
				$this->generateFromString($this->dataProviderList[0][DataUtils::$METHOD_WORD]);
			}
			else
			{
				$this->generateFromString(DataUtils::$METHOD_CODE . $this->dataProviderList[0][DataUtils::$METHOD_WORD]);
			}
			
			return;
		}
		else if (count($this->dataProviderList[0]) == 1)
		{
			foreach ($this->dataProviderList[0] as $itemValue)
				if (DataUtils::valueIsMethod($itemValue))
				{
					$this->generateFromString($itemValue);
					return;
				}
		}
		
		if (($this->columns == null) || (count($this->columns) == 0))
		{
			$this->columns = array();//new DataGridColumn[colnames.length];
			foreach($this->dataProviderList[0] as $key => $value)
			{
				/*DataGridColumn*/ $col = new DataGridColumn();
				$col->dataField = $key;
				$col->width = $this->getColumnWidth($col);
				$this->columns[] = $col;
			}			
		}
	}
	
	/**
	 * Place table on the page
	 * 
	 * @param pdfWriter {@link PdfWriter}
	 * @param rowCount Row count to be placed on the page
	 * @throws DocumentException
	 */
	private function writeTable(/*int*/ $rowCount)
	{
		/*int*/ $rowIterator = -1;
	
		/*int*/ $height = $this->rowHeight * $rowCount;
		
		if ($this->showColumnHeaders)
		{
			$this->writeRow($this->getHeaderRow(), "B", true);
			$this->componentPdfWriter->Ln();
			$this->componentPdfWriter->SetX($this->getPageX());
			$height += $this->rowHeight; 
			$rowIterator++;
		}
		
		while (($rowIterator++ < $rowCount) && (($row = $this->getNextRow()) != null))
		{
			$this->writeRow($row);
			$this->componentPdfWriter->Ln();
			$this->componentPdfWriter->SetX($this->getPageX());
		}
	}
	
	/**
	 * Place one row on the page based on string array
	 * 
	 * @param table	{@link PdfPTable}
	 * @param row	strings should be placed in one row
	 * @param rowFontStyle default font style for current row
	 * @throws DocumentException
	 */
	private /*void*/function writeRow(/*String[]*/ $row, /*int*/ $rowFontStyle = "", $header_row = false)
	{
		$logRow = "";
		for (/*int*/ $i = 0; $i < count($row); $i++) 
			$this->writeCell(DataUtils::getValue($row[$i]), $i, $rowFontStyle, $header_row);
	}
	
	protected function writeCell($cellValue, $colNo, $rowFontStyle = "", $header_row = false) 
	{
		$col = $this->columns[$colNo];
		
		if ($header_row)
			$renderer = $col->getHeaderRenderer();
		else
			$renderer = $col->getCellRenderer();
		
		$font = $this->columns[$colNo]->getFont(-1, $rowFontStyle);
		$componentForCell = $renderer->getComponent($cellValue, $font, $this->currentIndex, $colNo);
		$componentForCell->getFont()->setFont($this->componentPdfWriter);
		$content = $componentForCell->getContent();
		
		if (is_string($content))
		{
			$this->componentPdfWriter->SetFillColor(255);
			$this->componentPdfWriter->Cell($this->columns[$colNo]->width, $this->rowHeight, $content, 1, 0, "L", true);
		}
		else if (is_subclass_of($componentForCell, "Image") && is_subclass_of($componentForCell, "Component"))
		{
			$this->componentPdfWriter->Image($content['fileName']);
		}
	}
	
	protected /*String[]*/function getNextRow()
	{
		/*String[]*/ $result = array();
		for ($i = 0; $i < count($this->columns); $i++) 
		{
			if ($this->currentIndex < count($this->dataProviderList))
			{
				$result[$i] = $this->dataProviderList[$this->currentIndex][$this->columns[$i]->dataField];
			}
			else
			{
				$result = null;
			}
		}
		
		++$this->currentIndex;
		return $result;
	}


	protected function rowsPerPage()
	{
		/*int*/ $result = parent::rowsPerPage();
		if ($this->showColumnHeaders) --$result;
		return $result;
	}

	public function getColumnWidth(DataGridColumn $column)
	{
		$lenths = array();
		foreach($this->dataProviderList as $data)
		{
			$lenths[] = strlen(DataUtils::getValue($data[$column->dataField]))*$this->fontSize;
		}
		return max($lenths);
	}
		
	
	public /*String*/function getFieldName() 
	{
		return "columns";
	}

	public /*Class*/function getItemClass() 
	{
		return new ReflectionClass("DataGridColumn");
	}
	
    public /*override*/ function InitComponent(
            		$pdfDocument,
            /*FPDF*/ $pdfWriter,
            /*int*/ $baseX, /*int*/ $baseY,
            /*int*/ $marginTop, /*int*/ $marginLeft, /*int*/ $marginRight, /*int*/ $marginBottom,
            /*int*/ $parentWidth, /*int*/ $parentHeight
            )
    {
        parent::initComponent( 
            $pdfDocument, 
            $pdfWriter, 
            $baseX, $baseY, 
            $marginTop, $marginLeft, $marginRight, $marginBottom, 
            $parentWidth, $parentHeight );

        foreach( $this->columns as $component)
            $component->initComponent($pdfDocument, $pdfWriter, $this->x, $this->y, $this->marginTop, $this->marginLeft, $this->marginRight, $this->marginBottom, $this->width, $this->height);
            
    }
}
?>