<?php
/*******************************************************************
 * DrupalHttpHandler.php
 * Copyright (C) 2006-2009 Midnight Coders, LLC
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is WebORB Presentation Server (R) for PHP.
 *
 * The Initial Developer of the Original Code is Midnight Coders, LLC.
 * All Rights Reserved.
 ********************************************************************/





require_once(WebOrb . "Util/AutoLoader.php");

function __autoload($className) 
{
    $autoLoader = AutoLoader::getInstance();
    $autoLoader->load($className);
}

final class DrupalHttpHandler
{

    public function __construct()
    {
        session_start();
    	if(ini_get("log_errors") == "")
		{
			ini_set("log_errors", "On");
			ini_set("error_log", WebOrb . "orb_php_errors.txt");
		}
    }

    public function processRequest()
    {

    	if($_SERVER["REQUEST_METHOD"] == "GET")
        {
          print("WebORB v3.5.0");
        }

		$timeStart = microtime(true);

        ob_start();

        $inputData = file_get_contents("php://input");

		if(isset($_FILES['Filedata']))
		{
			$config = ORBConfig::getInstance();

    		if (!defined("WebOrbServicesPath"))
			{
				define("WebOrbServicesPath", realpath(WebOrb . $config->getServicePath() ) . DIRECTORY_SEPARATOR);
				if(LOGGING)
					Log::log(LoggingConstants::DEBUG, "WebORB services path is - ". WebOrbServicesPath );
			}
			require_once(WebOrbServicesPath . "Weborb/Examples/Upload.php");
			upload();
			exit;
		}


		$startRead = microtime(true);
        try
        {	
        	$config = ORBConfig::getInstance();
        	if (!defined("WebOrbServicesPath"))
			{
				define("WebOrbServicesPath", realpath(WebOrb . $config->getServicePath() ) . DIRECTORY_SEPARATOR);
				if(LOGGING)
					Log::log(LoggingConstants::DEBUG, "WebORB services path is - ". WebOrbServicesPath );
			}
			$contentType = "text/html";
			if( stripos($_SERVER["CONTENT_TYPE"], "wolf/xml") !== false )
			{
				$contentType = "wolf/xml";
			}
			elseif( stripos($_SERVER["CONTENT_TYPE"], "application/x-amf") !== false )
			{
				$contentType = "application/x-amf";
			}
			
            $request = $config->getProtocolRegistry()->buildMessage( $contentType, $inputData);

        }
        catch(Exception $e)
        {
           if(LOGGING)
					Log::logException(LoggingConstants::ERROR,"Internal error",$e);
			ob_clean();
           return;
        }
		

        if(DrupalDispatcher::dispatch($request))
        {
        	$this->serializeResponse($request);
        }

        $logMessage = sprintf("Final Execute time: %0.3f", microtime(true) - $timeStart);
		if(LOGGING)
			Log::log(LoggingConstants::PERFORMANCE,$logMessage);
    }

    private function serializeResponse(Request $request)
    {
        $formatter = $request->getFormatter();
        
        MessageWriter::writeObject($request, $formatter);

        $byteBuffer = $formatter->getBytes();
        
        

        ob_clean();

        header("Content-type: " . $formatter->getContentType());
        header("Content-length: " . strlen($byteBuffer));

        $formatter->cleanup();

        print($byteBuffer);

        ob_end_flush();
    }

}
?>
