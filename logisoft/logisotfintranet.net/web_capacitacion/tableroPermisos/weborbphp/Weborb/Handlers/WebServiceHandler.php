<?php
	
	class WebServiceHandler implements IInspectionHandler, IInvocationHandler
	{
		private /*string*/ $name = "PHP Web Services Handler";
		private /*Hashtable*/ $serviceCache = array();

		public function __construct()
		{
		}
		
		public function canHandle(/*string*/ $targetObject)
	    {
	    	if(strripos($targetObject, "wsdl") != strlen($targetObject) - 4)
	    		return false;
	    	
	    	return true;
	    }
	
		public /*string*/function getName()
		{
			return $this->name;			
		}

		public /*ServiceDescriptor*/function inspect( /*string*/ $targetObject )
		{
			if( LOGGING )
				Log::log( LoggingConstants::DEBUG, "WebServiceHandler.inspect, targetObject: " . $targetObject );
				
			if( strripos($targetObject, "wsdl") != strlen($targetObject) - 4 )
				return null;

			$proxyhost = '';
			$proxyport = '';
			$proxyusername = '';
			$proxypassword = '';
			
			$client = new soapclient($targetObject, true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
			$err = $client->getError();
			
			if ($err) {
				throw new Exception($err);
			}	
			
			$proxy = $client->getProxy();
			
//			$webInsp = new WebServiceInspector();
//			$webInsp->inspect($targetObject);
//			
//			$serviceDescriptor = $webInsp->serviceDescriptor;
////			if( LOGGING )
////				Log::log( LoggingConstants::MYDEBUG, ob_get_contents());
//			$_SESSION['wsdl'][$targetObject] = serialize($serviceDescriptor);
			$proxyName = get_class($proxy);
			
			$proxyReflection = new ReflectionClass($proxyName);
			
			$serviceDescriptor = ClassInspector::inspectClass($proxyReflection);
			
			if( LOGGING )
				Log::log( LoggingConstants::DEBUG, "web service handler has successfully inspected target service" );
			return $serviceDescriptor;
		}

		
		public /*Value*/ function invoke(/*string*/ $targetObject, /*string*/ $function, /*object[]*/ $arguments)
		{
			$proxyhost = '';
			$proxyport = '';
			$proxyusername = '';
			$proxypassword = '';
			
			$client = new soapclient($targetObject, true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
			$err = $client->getError();
			
			if ($err) 
			{
				throw new Exception($err);
			}
							
			$args = array();
			
			foreach($arguments as $argument)
			{
				if($argument instanceof IAdaptingType )
					$args[] = $argument->defaultAdapt();
				else $args[] = $argument;
			}
			
			$serviceDesription;
						
//			if(isset($_SESSION['wsdl'][$targetObject][$function]))
//			{
//				$serviceDesription = unserialize($_SESSION['wsdl'][$targetObject][$function]);
//				var_dump($serviceDesription);
//			}
//			else
//			{
				$webInsp = new WebServiceInspector();
				$webInsp->inspect($targetObject, $function);
				
				$serviceDesription = $webInsp->serviceDescriptor;
				$_SESSION['wsdl'][$targetObject][$function] = serialize($serviceDesription);
//			}
			$methods = $serviceDesription->getMethods();
			$method = null; 
			foreach($methods as $method)
			{
				if($method->getName() == $function)
					break;
			}

			$postdata = array(array());
			foreach($method->getArguments() as $argument)
			{
				$this->getArrayArguments($postdata[0], $argument->getType(), $args);
			}
			
		
			$result = $client->call($function, $postdata);
	        return new Value($result);		
		}
		
		private function resolveObject(ReflectionClass $class)
	    {
	        try
	        {
	            return $class->newInstance();
	        }
	        catch (ReflectionException $re)
	        {
	            throw new InvocationException($re->getMessage());
	        }
	    }
	    
	    private function getArrayArguments(/*array*/ &$postData, WebServiceType $type, $args)
	    {
//	    	$postData[0] = array();
	    	if(count($type->getProperties())>0)
	    	{
	    		$i = 0;
	    		foreach($type->getProperties() as $propertyName => $property)
	    		{
	    			if($property instanceof WebServiceType)
	    			{
	    				$this->getArrayArguments($postData[$propertyName], $property, array($args[$i]));
	    			}
	    			else
	    			{
	    				$postData[$propertyName] = $args[$i];
	    			}
	    			$i++;
	    		}
	    	}
	    	else
	    	{
	    		if($type->getType() instanceof WebServiceType)
	    		{
	    			$this->getArrayArguments($postData, $type->getType(), $args);
	    		}
	    		else
	    		{
	    			$postData = $args[$i];
	    		}
	    	}
	    	
	    }

		
	}


?>