<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

//require_once(WebOrb . "Util/Fpdf/fpdf.php");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/builders/Builder.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Property.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/Component.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/UnlicensedException.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/lib/PDF.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/lib/Font.php';

class PDFUtil 
{
	
	private static /*int*/ $unlicensedPagesCount = 2;
	
	public static function saveDocument(/*Object*/ $dataObject, /*string*/ $path, $fileName)
	{
		if(file_exists($path.$fileName))
		{
			unlink($path.$fileName);
		}

		try 
		{
			/*Document*/ $data = Builder::buildDocument($dataObject);
			if ($data == null)
			{
				throw new Exception("Can't build document based on " . get_class($dataObject));
			}
			
			
			if ($data->metadata != null)
			{
	            /*Property*/ $pageSizeProperty = $data->metadata->getPropertyByName( Property::$PAGE_FORMAT );
	
	            if( $pageSizeProperty != null )
	            {
	                if( $pageSizeProperty->value == Property::$PAGE_FORMAT_A4)
	                {
	                	$data->width = Document::$pageFormats['a4'][0];
	                	$data->height = Document::$pageFormats['a4'][1];
	                }
	                else if( $pageSizeProperty->value == Property::$PAGE_FORMAT_A4_ALBUM)
	                {
	                	$data->width = Document::$pageFormats['a4'][1];
	                	$data->height = Document::$pageFormats['a4'][0];
	                }
	            }
			}
			
			$pdfwr = null;
			
			Log::log(LoggingConstants::MYDEBUG, "PDF: create file");
			
			if(!is_numeric($data->width))
				$pdfwr = new PDF("P", "pt", $data->width);
			else
				$pdfwr = new PDF("P", "pt", array($data->width, $data->height));
			
			$pdfwr->SetAutoPageBreak(false);//, abs($data->marginBottom));

			$data->write($pdfwr);
			
			Log::log(LoggingConstants::MYDEBUG, "WRITE FINISHED");

			$pdfwr->Output($path.$fileName, "F");

		}
		catch (Exception $exception)
		{
			Log::log(LoggingConstants::MYDEBUG, $exception->getMessage() . "\n" . $exception->getTrace());
			throw $exception;
		}
		
//		fwrite($fileHandler, $buf);
//		fclose($fileHandler);
				
	}
	
	public static function startNewPage(/*com.lowagie.text.Document*/ $pdfWriter, $pdfDocument)
	{
		if (($pdfDocument->pagesCount >= self::$unlicensedPagesCount) && !self::isLicensed())
		{
			throw new UnlicensedException("Only 2 pages are available");
		}
		else
		{
			$pdfWriter->AddPage();
			$pdfDocument->writeEachPageComponents();
			self::showTestMode($pdfDocument, $pdfWriter);
			$pdfDocument->pagesCount++;
		}
	}
	
	/**
	 * Place watermark on the page if it is not licensed version
	 * 
	 * @param pdfWriter {@link PdfWriter}
	 * @throws DocumentException
	 */
	 
	private static function showTestMode( $pdfDocument, $pdfWriter )
	{
		if (self::isLicensed()) return;
		$font = new Font();
		$font->setFontSize(25);
		$font->setFontColor("#B5B5B5");
		$font->setFont($pdfWriter);
		$text = "WebORB PDF Gen Evaluation Copy";
		$width = $pdfWriter->GetStringWidth($text);
		$center["x"] = $pdfDocument->width / 2;
		if ($width / 2 > $center["x"]) $width = $center["x"] * 2;
		$center["y"] = $pdfDocument->height / 2;
		$pdfWriter->Rotate(45, $center["x"], $center["y"]);
		$pdfWriter->SetXY($center["x"] - $width / 2, $center["y"]);
		$pdfWriter->Cell($width, 36, $text, 0, 0, "C");
		$pdfWriter->Rotate(0, $center["x"], $center["y"]);
	}
	
	/**
	 * should check license and return true if it is valid
	 * 
	 * @return
	 * @throws DocumentException
	 */
	private static /*boolean*/ function isLicensed()
	{
		return false;
	}
	

}
?>