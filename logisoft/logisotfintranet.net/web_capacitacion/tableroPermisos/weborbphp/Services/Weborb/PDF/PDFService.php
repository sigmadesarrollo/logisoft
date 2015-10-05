<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH. 'PDF/util/PDFUtil.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH. 'PDF/util/DataUtils.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH. 'PDF/model/Document.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH. 'PDF/model/Page.php';

require_once(WebOrb . "V3Types/GUID.php");

class PDFService {
		
	const DOWNLOAD_URL = "http://localhost/weborb_PHP/Services/Weborb/PDF/pdf_files/";
	
	public /*String*/function PrintPDF(/*Document*/ $obj, /*String*/ $fileName = "")
	{
		if($fileName == "")
			$fileName = DataUtils::getOutputFileName();

		$path = WebOrbServicesPath . BASE_PDF_SERVICE_PATH . "PDF\\pdf_files\\";
		
		Log::log(LoggingConstants::MYDEBUG, "Save start");
		
		PDFUtil::saveDocument($obj, $path, $fileName);
		
		Log::log(LoggingConstants::MYDEBUG, "Save finished");
		
		return self::DOWNLOAD_URL . $fileName;
	}
	
	public /*Object*/function getData()
	{
		$array = array();
		$guid = new GUID();
		for($i = 0; $i<100; $i++)
		{
			$array[] = array("Name1" => "name exam.", "Name2" => "14556422_" . $i);
		}
		return $array;
	}
	
	public /*Object*/function getData2()
	{
		/*ArrayList<HashMap<String, String>>*/ $result = array();
		/*String[]*/ $names = array("label", "Name2");
		
		for (/*int*/ $j = 0; $j < 50; $j++) {
			$buf = array();
			for ($i = 0; $i < count($names); $i++) {
				$buf[$names[$i]] = $j . "." . $i;
			}
			$result[] = $buf;
		}
		
		return $result;
		
	}
}
?>