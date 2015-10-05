<?php

class RequestParser implements IMessageFactory
{
    private static /*RequestParser*/ $instance;
    private static /*Hashtable*/ $readers;

	public function __construct()
	{
		$this->readers = array();
        $this->readers[ "Array" ] = new ArrayWolfReader();
        $this->readers[ "b" ] = $this->readers[ "Array" ];

        $this->readers[ "Boolean" ] = new BooleanWolfReader();
        $this->readers[ "c" ] = $this->readers[ "Boolean" ];

        $this->readers[ "Date" ] = new DateWolfReader();
        $this->readers[ "d" ] = $this->readers[ "Date" ];

        $this->readers[ "Undefined" ] = new NullWolfReader();
        $this->readers[ "h" ] = $this->readers[ "Undefined" ];

        $this->readers[ "Number" ] = new NumberWolfReader();
        $this->readers[ "i" ] = $this->readers[ "Number" ];

        $this->readers[ "Object" ] = new ObjectWolfReader();
        $this->readers[ "j" ] = $this->readers[ "Object" ];

        $this->readers[ "Reference" ] = new ReferenceWolfReader();
        $this->readers[ "k" ] = $this->readers[ "Reference" ];

        $this->readers[ "String" ] = new StringWolfReader();
        $this->readers[ "l" ] = $this->readers[ "String" ];
        $this->readers[ "XML" ] = new XmlWolfReader();
        $this->instance = $this;
	}

    public static /*RequestParser*/function GetInstance()
    {
    	if( $this->instance == null )
        	$this->instance = new RequestParser();

        return $this->instance;
    }

    public /*IAdaptingType*/function ParseElement( /*XmlNode*/ $xmlNode, /*ParseContext*/ $parseContext )
	{
    	/*IXMLTypeReader*/ $reader = $this->readers[ $xmlNode->nodeName ];
        return $reader->read( $xmlNode, $parseContext );
    }
        #region IMessagetFactory Members

    public /*bool*/ function canParse( /*string*/ $contentType )
    {
    	return (stripos($contentType, "wolf/xml") !== false);
    }

    public /*Request*/function parse( /*Stream*/ $requestStream )
    {
    	/*XmlDocument*/ $document = new DOMDocument();//new System.Xml.XmlDocument();
        $document->loadXML( $requestStream );
//		var_dump($document->saveXML());
//		Log::log(LoggingConstants::MYDEBUG, ob_get_contents());
        if(LOGGING)
        	Log::log( LoggingConstants::DEBUG, $document->saveXML() );

        /*XmlElement*/ $requestRoot = $document->documentElement;//.DocumentElement;
        /*String*/ $version = $requestRoot->getAttribute( "version" );
        /*XmlElement*/ $requestElement = $requestRoot->getElementsByTagName( "Request" )->item(0);
        /*XmlNode*/ $headersElement = $requestElement->getElementsByTagName( "Headers" )->item(0);
        /*ArrayList*/ $headers = array();

        /*int*/ $requestID = $requestElement->getAttribute( "id" );
        $headers[] = new Header( "requestid", false, 0, new NumberObject( $requestID ) );

        if( $headersElement != null )
        	for($i = 0, $max = $headersElement->childNodes->length; $i < $max; $i++)
            {
            	$headerElement = $headersElement->childNodes->item($i);
            	if( !($headerElement instanceof DOMNode) )
                	continue;

                /*IAdaptingType*/ $headerValue = null;

                if( $headerElement->firstChild != null )
                	$headerValue = $this->ParseElement( $headerElement->firstChild, new ParseContext() );
                else
                	$headerValue = new StringType( trim($headerElement->textContent) );

                $headers[] = new Header( $headerElement->nodeName, false, 0, $headerValue );
			}

		/*String*/ $target = trim($requestElement->getElementsByTagName( "Target" )->item(0)->textContent);
		/*String*/ $methodName = trim($requestElement->GetElementsByTagName( "Method" )->item(0)->textContent);
		/*Body*/ $bodyPart = new Body( $target . "." . $methodName, null, 0, $this->parseArguments( $requestElement->getElementsByTagName( "Arguments" )->item(0) ) );
//		/*NumberFormatInfo*/ $formatInfo = new CultureInfo( 0x0409 ).NumberFormat;
		/*Request*/ $msg = new Request( $version, $headers, array($bodyPart));
		$msg->setFormatter( new WolfFormatter() );
		return $msg;
	}

	private /*object[]*/function parseArguments( /*XmlNode*/ $arguments )
	{
		/*ParseContext*/ $parseContext = new ParseContext();
		/*ArrayList*/ $argsList = array();

		for($i=0, $max = $arguments->childNodes->length; $i<$max;$i++)
		{
			$xmlNode = $arguments->childNodes->item($i);
			if( !($xmlNode instanceof DOMNode ) )
            	continue;

			$argsList[] = $this->ParseElement( $xmlNode, $parseContext );
		}

		return $argsList;
	}

}
?>