<?php


//define(serialVersionUID,0);
class CommandMessage extends V3Message
{
	public /*int*/$operation;
	
	public function createCmdMessage( /*String*/$operation, /*Object*/ $body )
	{
	    $cmdMessage = new CommandMessage();
		$guid = new GUID();
		$cmdMessage->messageId = $guid->toString();

        /*Hashtable*/ $responseMetadata = /*(Hashtable) */ThreadContext::getProperties();
		if(count($responseMetadata)==0) $responseMetadata = null;
		else $responseMetadata = $responseMetadata[ORBConstants::RESPONSE_METADATA];

        if( $responseMetadata != null )
            $cmdMessage->headers = $responseMetadata;
        else
            $cmdMessage->headers = array();

        $cmdMessage->timestamp = microtime(true);
		$cmdMessage->body = new BodyHolder();
		if(is_array($body))
        	$cmdMessage->body->body = $body;
        else
        	$cmdMessage->body->body = array($body);
		$cmdMessage->timeToLive = 0;
		$cmdMessage->operation = $operation;

		return $cmdMessage;
	}

	public /*V3Message*/function execute(Request $request)
	{
		if(LOGGING)
			Log::log(LoggingConstants::DEBUG, "commandmessage " . $this->operation . " operation" );

		/*Object*/ $returnValue = null;

        if( $this->operation == "0" )
        {
        	/*IDestination*/ $destObj = ORBConfig::getInstance()->getDataServices()->getDestinationManager()->getDestination( $this->destination );
        	/*Hashtable*/ $headers = array();
        	if( $destObj != null )
        	{
        		/*String*/ $selectorName = $this->headers["DSSelector"];
        		/*String*/ $subtopic = $this->headers["DSSubtopic"];
        		/*String*/ $dsId = $this->headers["DSId"];
        		
        		/*Subscriber*/ $subscriber = new Subscriber( $selectorName, $destObj );
        		$subscriber->setDSId( $dsId );
        		$subscriber->setSubtopic( $subtopic );
        		$guid = new GUID();
        		$subscriber->setClientId( $guid->toString());
        		SubscriptionsManager::getInstance()->addSubscriber( $dsId, $subscriber );
        		$destObj->getServiceHandler()->handleSubscribe( $subscriber );
			}
	        else
	      	{
		        /*String*/ $error = "Unknown destination " . $this->destination . ". Cannot handle subscription request";
		
		        if(LOGGING)
		        	Log::log( LoggingConstants::ERROR, $error );
		
		        return new ErrMessage( $this->messageId, new Exception( $error ) );
	      	}
            return new AckMessage( $this->messageId, $clientId, null, $headers );
        }
        else if( $this->operation == "1" )
        {
        	/*String*/ $dsId = $this->headers["DSId"];
      		/*Subscriber*/ $subscriber = SubscriptionsManager::getInstance()->getSubscriber( $dsId );

      		if( $subscriber == null )
        		return new ErrMessage( $this->messageId, new Exception( "Unable to unsubscribe - unknown client" ) );

      		/*IDestination*/ $destination = $subscriber->getDestination();
      		$destination->getServiceHandler()->handleUnsubscribe( $subscriber );
      		SubscriptionsManager::getInstance()->removeSubscriber( $dsId );
        }
        else if( $this->operation == "2"  )
        {
			/*String*/ $dsId = $this->headers["DSId"];
      		/*Subscriber*/ $subscriber = SubscriptionsManager::getInstance()->getSubscriber( $dsId );

      		if( $subscriber == null )
      		{
        		/*String*/ $error = "Invalid client id " . $dsId;

        		if(LOGGING)
        			Log::log( LoggingConstants::ERROR, $error );

        		return new ErrMessage( $this->messageId, new Exception( $error ) );
      		}

      		/*IDestination*/ $destination = $subscriber->getDestination();

      		//Log::log( LoggingConstants::INFO, "Getting messages from " . $destination->getServiceHandler() );
      		/*ArrayList*/ $messages = $destination->getServiceHandler()->getMessages( $subscriber );
      		$subscriber->setLastRequestTime( microtime(true) );		
      		
      		if( count($messages) == 0 )
        		return new AckMessage( null, null, null, array() );
			return $this->createCmdMessage( "4", $messages );
        }
        else if( $this->operation == "5" )
        {
        	/*Hashtable*/ $headers = array();
        	$guid = new GUID();
        	$headers["DSId"] = $guid->toString();

        	return new AckMessage( $this->messageId, $this->clientId, null, $headers );
        }
        else if( $this->operation == "9" )
        {
            ThreadContext::setCallerCredentials( null );
        }
        else if( $this->operation == "8" )
        {
			$arr = $this->body->getBody();
            $adaptingType = $arr[0];

            $authData = split(":", base64_decode($adaptingType->defaultAdapt()));

            $credentials = new Credentials($authData[0],$authData[1]);

            $authHandler = ORBSecurity::getAuthenticationHandler( ThreadContext::getORBConfig() );

            if(LOGGING)
            	Log::log( LoggingConstants::DEBUG, "got auth handler " . get_class($authHandler) );
            
            
            if( $authHandler == null )
            {
                $errorMessage = new ErrMessage( $this->messageId, new ServiceException( "Missing authentication handler" ) );
                $errorMessage->faultCode = "Client.Authentication";
                return $errorMessage;
            }

            try
            {
                $authHandler->checkCredentials($credentials->getUserId(),
                    $credentials->getPassword(),
                    $request );
				if(LOGGING)
                	Log::log( LoggingConstants::DEBUG, "credentials are valid ");

                ThreadContext::setCallerCredentials($credentials);
            }
            catch(Exception $e)
            {
            	if(LOGGING)
            		Log::log( LoggingConstants::EXCEPTION, "authentication exception" . $e );

                $errorMessage = new ErrMessage( $this->messageId, $e );
                $errorMessage->faultCode = "Client.Authentication";

                return $errorMessage;
            }

            return new AckMessage($this->messageId, $this->clientId, null);
        }
        //echo $this->operation; exit;
        return new AckMessage( $this->messageId, $this->clientId, $returnValue, array());
	}
}
?>