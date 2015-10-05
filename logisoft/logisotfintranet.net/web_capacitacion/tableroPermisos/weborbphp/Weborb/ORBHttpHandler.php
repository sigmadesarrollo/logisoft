<?php
/*******************************************************************
 * ORBHttpHandler.php
 * Copyright (C) 2006-2007 Midnight Coders, LLC
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



if (!defined("WebOrb"))
{
    define("WebOrb", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

if(ini_get("log_errors") == "")
{
	ini_set("log_errors", "1");
	ini_set("error_log", WebOrb . "orb_php_errors.txt");
}

if (!defined("WebOrbCache"))
{
	define("WebOrbCache", dirname(__FILE__) . "/PollCache/");
	if(!file_exists(dirname(__FILE__) . "/PollCache"))
	{
		mkdir(dirname(__FILE__) . "/PollCache");
		chmod(dirname(__FILE__) . "/PollCache", 0777);
	}
}

if (!defined("LOGGING"))
{
    define("LOGGING", true);
}



require_once(WebOrb . "Util/AutoLoader.php");

function __autoload($className) 
{
    $autoLoader = AutoLoader::getInstance();
    $autoLoader->load($className);
}

final class ORBHttpHandler
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

    	if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['service']) && isset($_GET['type']) )
    	{
    		$config = ORBConfig::getInstance();

    		if (!defined("WebOrbServicesPath"))
			{
				define("WebOrbServicesPath", realpath(WebOrb . $config->getServicePath() ) . DIRECTORY_SEPARATOR);
				if(LOGGING)
					Log::log(LoggingConstants::DEBUG, "WebORB services path is - ". WebOrbServicesPath );
			}
			$service = $_GET['service'];
			$type = $_GET['type'];
			require_once(WebOrbServicesPath . "Weborb" . DIRECTORY_SEPARATOR . "Management" . DIRECTORY_SEPARATOR . "ManagementService.php");
			$mS = new ManagementService();
			$arcName = "weborb.codegen.zip";
			if(file_exists(WebOrb . $arcName))
				unlink(WebOrb . $arcName);
			CreateArc::createArchive($mS->generateCode($service, null, $type, false), $arcName);
			echo("<script>document.location.href = 'Weborb/" .$arcName ."';</script>");
			unset($_GET['service']);
			unset($_GET['type']);
			exit;
    	}

        if($_SERVER["REQUEST_METHOD"] == "GET")
        {
          print("WebORB v3.6.0");
          
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

//		Cache::put("inputData",$inputData);
//		$inputData = Cache::get("inputData");
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
		
		if(Dispatchers::dispatch($request))
        {
        	$startSerialize = microtime(true);
        	
            $this->serializeResponse($request);
        }

        $logMessage = sprintf("Final Execute time: %0.3f", microtime(true) - $timeStart);
		if(LOGGING)
					Log::log(LoggingConstants::PERFORMANCE,$logMessage);
    }

    private function serializeResponse(Request $request)
    {
        $formatter = $request->getFormatter();
        
//        var_dump($request);exit;
        
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
