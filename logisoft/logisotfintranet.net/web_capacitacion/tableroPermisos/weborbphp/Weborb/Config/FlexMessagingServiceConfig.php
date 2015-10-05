<?php

class FlexMessagingServiceConfig extends BaseFlexConfig
{
	const /*String*/ REMOTINGSERVICE_FILE = "remoting-config.xml";
	
		public /*String*/function getConfigFileName()
    {
        return "messaging-config.xml";
    }

    public /*String*/function getDefaultServiceHandlerName()
    {
        return "Weborb.messaging.v3.MessagingServiceHandler";
    }

    public /*IDestination*/function processDestination( ORBConfig $orbConfig, /*String*/ $destinationId, /*Element*/ $destinationNode )
    {
    	if(LOGGING)
        	Log::log( LoggingConstants::INFO, "Registered Flex Messaging destination - " . $destinationId );
        /*MessagingDestination*/ $destination = new MessagingDestination( $destinationId );

        return $destination;
    }

    public function preConfig()
    {
    }

    public function postConfig()
    {
    }
	
}
?>