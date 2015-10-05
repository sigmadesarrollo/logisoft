<?php
/*******************************************************************
 * TypeLoader.php
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



class TypeLoader
{

    static public function loadType($typeName)
    {
        if($typeName == "Weborb.V3Types.ReqMessage")
        {
          return new ReflectionClass("ReqMessage");
        }
        else if($typeName == "Weborb.V3Types.CommandMessage")
        {
          return new ReflectionClass("CommandMessage");
        }
        else if($typeName == "Weborb.V3Types.AsyncMessage")
        {
          return new ReflectionClass("AsyncMessage");
        }
        else if($typeName == "Weborb.V3Types.AckMessage")
        {
          return new ReflectionClass("AckMessage");
        }
        else if($typeName == "Weborb.V3Types.DataMessage")
        {
          return new ReflectionClass("DataMessage");
        }
        else if($typeName == "Weborb.V3Types.PagedMessage")
        {
          return new ReflectionClass("PagedMessage");
        }
        else if($typeName == "Weborb.V3Types.SeqMessage")
        {
          return new ReflectionClass("SeqMessage");
        }
        else if($typeName == "Weborb.V3Types.ErrMessage")
        {
          return new ReflectionClass("ErrMessage");
        }
        else if($typeName == "Weborb.V3Types.ErrDataMessage")
        {
          return new ReflectionClass("ErrDataMessage");
        }
        else if($typeName == "Weborb.Reader.Dataset.DataSetInfo")
        {
          return new ReflectionClass("DataSetInfo");
        }
        else if($typeName == "Weborb.Security.WebORBAuthorizationHandler")
        {
          return new ReflectionClass("WebORBAuthorizationHandler");
        }
        else if($typeName == "Weborb.Security.WebORBAuthenticationHandler")
        {
          return new ReflectionClass("WebORBAuthenticationHandler");
        }
     	else if($typeName == "Weborb.V3Types.core.RemotingHandler")
        {
          return new ReflectionClass("RemotingHandler");
        }
    	else if($typeName == "Weborb.messaging.v3.MessagingServiceHandler")
        {
          return new ReflectionClass("MessagingServiceHandler");
        }
    	else if($typeName == "Weborb.Security.WebORBRolesProvider")
        {
          return new ReflectionClass("WebORBRolesProvider");
        }
        else if($typeName == 'flex.messaging.services.remoting.adapters.JavaAdapter')
        {
        	return new ReflectionClass("WebORBRolesProvider");
        }
        else if($typeName == 'Weborb.Inspection.ArgumentDescriptor')
        {
        	return new ReflectionClass("ArgumentDescriptor");
        }
        else if($typeName == 'Weborb.Inspection.MethodDescriptor')
        {
        	return new ReflectionClass('MethodDescriptor');
        }
        else if($typeName == 'Weborb.Inspection.ServiceDescriptor')
        {
        	return new ReflectionClass('ServiceDescriptor');
        }
        

        //-------------------------------------
        if(strpos($typeName, "com.tmc.weborb.pdf") !== false ) 
        {
	        if(strpos($typeName, "com.tmc.weborb.pdf.model.List") !== false )
	        	$typeName = str_replace("com.tmc.weborb.pdf.model.List", "Weborb.PDF.model.ListComponent", $typeName);
	        else
        		$typeName = str_replace("com.tmc.weborb.pdf", "Weborb.PDF", $typeName);
        		
        }
        //-------------------------------------
        
                

        $config = ORBConfig::getInstance();
        $servicePath = $config->getServicePath();

        $prefix = realpath(WebOrb . $servicePath) . DIRECTORY_SEPARATOR;

        $path = $prefix . str_replace(".", DIRECTORY_SEPARATOR, $typeName) . '.php';

        if (!file_exists($path))
        {
            $path = $prefix . str_replace(
                ".", DIRECTORY_SEPARATOR, $config->getServiceRegistry($typeName)->getMapping($typeName)) . '.php';
        }

		if(!file_exists($path))
		{
		   	if(LOGGING)
		   		Log::log(LoggingConstants::ERROR, "Unable to load file $path");
		    throw new Exception("Unable to load file $path");

			//return null;
		}
		include_once($path);

        $pieces = explode('.', $typeName);
		return new ReflectionClass($pieces[count($pieces) - 1]);
    }

    static public function getFullClassName(ReflectionClass $class)
    {
      $config = ORBConfig::getInstance();

      $servicePath = realpath(WebOrb . $config->getServicePath());

      $fileName = $class->getFileName();

      $excludingServicePath = substr($fileName,strlen($servicePath)+1);
      //$excludingServicePath = substr($excludingServicePath,strlen($servicePath)+1);
      $excludingServicePath = substr($excludingServicePath,0,strlen($excludingServicePath)-4); // removing .php
      $excludingServicePath = str_replace(DIRECTORY_SEPARATOR, '.', $excludingServicePath);

      return $excludingServicePath;
    }

}

?>
