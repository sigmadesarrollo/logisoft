<?php
class DrupalDispatcher
{

    public function dispatch(Request &$request)
    {
        $config = ORBConfig::getInstance();

        for($i = 0, $max = $request->getBodyCount(); $i < $max; $i++)
        {
            $request->setCurrentBody($i);

            $requestURI = $request->getRequestURI();
			
            if(LOGGING)
            	Log::log(LoggingConstants::INFO, "requestURI = $requestURI");

            $serviceId = substr($requestURI, 0 , strrpos($requestURI,"."));
            $methodName = substr($requestURI, strlen($serviceId) + 1);

            $arg = $request->getRequestBodyData();

            if(!is_array($arg))
            {
              $arg = array($request->getRequestBodyData());
            }
			
			try
			{
            	$value = self::handleInvoke($request, $serviceId, $methodName, $arg);
            	
            	$namedObject = $arg[0];
            	$correlationId = $namedObject->defaultAdapt()->messageId;
				
            	$ackMessage = new AckMessage($correlationId, null, $value);
            	            	
            	$request->setResponseBodyPart($ackMessage);
            	$request->setResponseURI("/onResult");
            }
            catch( Exception $e )
            {
            	$request->setResponseBodyPart($e);
            	$request->setResponseURI("/onStatus");
            }
        }

        return true;
    }

    public static function handleInvoke(Request $request, $targetObject, $function, &$arg)
    {

		$namedObject = $arg[0];
		$source = $namedObject->defaultAdapt()->source;
		$operation = $namedObject->defaultAdapt()->operation;
		$bodys = $namedObject->defaultAdapt()->body->getBody();
		$arguments = array();
		foreach($bodys as $body)
		{
			$arguments[] = $body->defaultAdapt();
		}
		
		$arguments = unserialize(str_replace('s:6:"userid";', 's:3:"uid";', serialize($arguments)));
		
		$result = services_method_call($source. "." . $operation, $arguments);
		
		$result = unserialize(str_replace('s:3:"uid";', 's:6:"userid";', serialize($result)));		
    		
		return $result;
    }    
   
}
?>