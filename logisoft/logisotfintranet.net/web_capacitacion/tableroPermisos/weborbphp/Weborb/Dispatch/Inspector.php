<?php
/**
 * <tt>Inspector</tt>
 *
 * @author <a href="http://www.themidnightcoders.com">Midnight Coders, LLC</a>
 */



class Inspector implements IDispatch
{

  /**
   * @param message
   * @return
   * @throws ServiceException
   */
  public /*boolean*/function dispatch( Request &$request )
  {
  	$message = $request;
  	if( !$this->isInspectionRequest( $message ) )
      return false;

    if(LOGGING)
    	Log::log( LoggingConstants::INFO, "Request is recognized as an inspection request. Handling service inspection" );

    /*String*/ $requestURI = $message->getRequestURI();
    /*String*/ $targetObject = substr($requestURI, 0, strrpos($requestURI, '.' ) );
    $targetObject = ServiceRegistry::getMapping( $targetObject );
	if(LOGGING)
	{
	    Log::log( LoggingConstants::DEBUG, "Request URI - " . $requestURI );
	    Log::log( LoggingConstants::DEBUG, "Target Service - " . $targetObject );    
	}
    $responseObject = ORBConfig::getInstance()->getHandlers()->inspect( $targetObject );

    if( $responseObject == null || $responseObject->getObject() instanceof Exception )
    {
      if(LOGGING)
      	Log::log( LoggingConstants::ERROR, "None of the handlers were able to inspect the target service. The service may not be found" );

      /*Exception*/ $exception = $responseObject != null ? $responseObject->getObject() : new InspectionException( $targetObject );
      $message->setResponseBodyPart( $exception );
      $message->setResponseURI( "/onStatus" );
    }
    else
    {
      if(LOGGING)
      	Log::log( LoggingConstants::DEBUG, "Inspection response object is " . $responseObject->getName() );

      $responseObject->setAddress( $targetObject );
      $message->setResponseBodyPart( $responseObject->getObject() );
      $message->setResponseURI( "/onResult" );
    }
	
    return true;
  }

  /**
   * @return
   */
  public /*String*/function getName()
  {
    return "Service Inspector";
  }

  /**
   * @param message
   * @return
   */
  private /*boolean*/function isInspectionRequest( Request $message )
  {
    /*Header*/ $header = $message->getHeaderByName( "DescribeService" );

    if( $header == null )
      $header = $message->getHeaderByName( "InspectService" );	
    return $header != null;
  }

  /**
   * @return
   */
  public /*String*/function getResourceID()
  {
    return "Service Inspector";
  }
}
?>