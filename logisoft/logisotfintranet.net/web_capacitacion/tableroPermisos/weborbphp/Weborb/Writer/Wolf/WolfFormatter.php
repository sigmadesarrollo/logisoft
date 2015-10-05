<?php

	class WolfFormatter implements IProtocolFormatter
	{
        private /*Stack*/ $stack;
        private /*XmlDocument*/ $doc;
        private /*bool*/ $serializeAsFault;
		private /*IObjectSerializer*/ $objectSerializer;
		private /*ReferenceCache*/ $referenceCache;

        private /*string*/ $WOLF = "WOLF";
        private /*string*/ $ARRAY = "Array";
        private /*string*/ $BOOLEAN = "Boolean";
        private /*string*/ $DATE = "Date";
        private /*string*/ $FIELD = "Field";
        private /*string*/ $NAME = "Name";
        private /*string*/ $VALUE = "Value";
        private /*string*/ $UNDEFINED = "Undefined";
        private /*string*/ $NUMBER = "Number";
        private /*string*/ $OBJECT = "Object";
        private /*string*/ $REFERENCE = "Reference";
        private /*string*/ $STRING = "String";

       /* public WolfFormatter() : this( false )
        {
        }*/

		public function __construct( /*bool*/ $optimize = false )
		{
            if( $optimize )
            {
                $this->WOLF = "a";
                $this->ARRAY = "b";
                $this->BOOLEAN = "c";
                $this->DATE = "d";
                $this->FIELD = "e";
                $this->NAME = "f";
                $this->VALUE = "g";
                $this->UNDEFINED = "h";
                $this->NUMBER = "i";
                $this->OBJECT = "j";
                $this->REFERENCE = "k";
                $this->STRING = "l";
            }

            $this->doc = new DOMDocument();
            $this->stack = array();
            /*XmlElement*/ $element = $this->doc->createElement( $this->WOLF );
            $this->doc->appendChild( $element );
            array_push($this->stack, $element );
			$this->objectSerializer = new ObjectSerializer();
			$this->referenceCache = new ReferenceCache();
        }
        
        public /*ITypeWriter*/function getWriter( /*Type*/ $type )
        {
            return null;
        }

        public /*ReferenceCache*/function getReferenceCache()
		{
			return $this->referenceCache;
		}

        public function resetReferenceCache()
		{
			$this->referenceCache->reset();
		}

        public function beginWriteMessage( Request $message )
        {
            /*Body[]*/ $body = $message->getResponseBodies();
            $this->serializeAsFault = ( count($body) > 0 && $body[ 0 ]->getResponseDataObject() instanceof Exception );
            
        }

        public function endWriteMessage()
        {
        }

        public function writeMessageVersion( /*float*/ $version )
        {
        	$el = array_pop($this->stack);
        	$el->SetAttribute( "version", $version );
            array_push($this->stack, $el);
            array_push($this->stack, $this->doc->createElement( $this->serializeAsFault ? "Fault" : "Response" ) );
        }

        public function beginWriteArray( /*int*/ $length )
        {
            array_push($this->stack, $this->doc->createElement( $this->ARRAY ) );
        }

        public function endWriteArray()
        {
            /*XmlElement*/ $e = array_pop($this->stack);
        	$el = array_pop($this->stack);
        	$el->appendChild( $e );
            array_push($this->stack, $el);
        }

        public function writeBoolean( /*bool*/ $b )
        {
            /*XmlElement*/ $boolElement = $this->doc->createElement( $this->BOOLEAN );
            $boolElement->nodeValue = $b ? "true" : "false";
            $el = array_pop($this->stack);
            $el->appendChild( $boolElement );
            array_push($this->stack, $el);
        }

        public function writeDate( ORBDateTime $datetime )
        {
            /*XmlElement*/ $dateElement = $this->doc->createElement( $this->DATE );
            $dateElement->nodeValue = $datetime->getDateTime();
            $el = array_pop($this->stack);
            $el->appendChild( $dateElement );
            array_push($this->stack, $el);
        }

        public function beginWriteObjectMap( /*int*/ $size )
        {
            $this->beginWriteObject( $size );
        }

        public function endWriteObjectMap()
        {
            $this->endWriteObject();
        }

        public function writeFieldName( /*String*/ $s )
        {
            /*XmlElement*/ $fieldElement = $this->doc->createElement( $this->FIELD );
            /*XmlElement*/ $nameElement = $this->doc->createElement( $this->NAME );
            $nameElement->nodeValue = $s;
            $fieldElement->appendChild( $nameElement );
            array_push($this->stack, $fieldElement );
        }

        public function beginWriteFieldValue()
        {
            array_push($this->stack, $this->doc->createElement( $this->VALUE ) );
        }

        public function endWriteFieldValue()
        {
            // pop Value
            /*XmlElement*/ $e = array_pop($this->stack);
            // add it to Field
            $el = array_pop($this->stack);
            $el->appendChild( $e );
            array_push($this->stack, $el);

            // pop Field
            $e = array_pop($this->stack);
            // add it to Object
            $el = array_pop($this->stack);
            $el->appendChild( $e );
            array_push($this->stack, $el);
        }

        public function writeNull()
        {
        	$el = array_pop($this->stack);
            $el->appendChild( $this->doc->createElement( $this->UNDEFINED ) );
            array_push($this->stack, $el);
        }

        public function writeInteger( /*int*/ $number )
        {
            /*XmlElement*/ $numberElement = $this->doc->createElement( $this->NUMBER );
            $numberElement->nodeValue = $number;
            $el = array_pop($this->stack);
            $el->appendChild( $numberElement );
            array_push($this->stack, $el);
        }

        public function writeDouble( /*double*/ $number )
        {
            /*XmlElement*/ $numberElement = $this->doc->createElement( $this->NUMBER );
            $numberElement->nodeValue = $number;
            $el = array_pop($this->stack);
            $el->appendChild( $numberElement );
            array_push($this->stack, $el);
        }

        public function beginWriteNamedObject( /*string*/ $objectName, /*int*/ $fieldCount = null )
        {
            /*XmlElement*/ $objectElement = $this->doc->createElement( $this->OBJECT );
            $objectElement->setAttribute( "objectName", $objectName );
            array_push($this->stack, $objectElement );
        }

        public function endWriteNamedObject()
        {
            $this->endWriteObject();
        }

        public function beginWriteObject( /*int*/ $fieldCount = null )
        {
            array_push($this->stack, $this->doc->createElement( $this->OBJECT ) );
        }

        public function endWriteObject()
        {
            /*XmlElement*/ $e = array_pop($this->stack);
        	$el = array_pop($this->stack);
            $el->appendChild( $e );
            array_push($this->stack, $el);
        }

        public function writeArrayReference( /*ushort*/ $refID )
		{
			$this->writeReference( $refID );
		}

        public function writeObjectReference( /*ushort*/ $refID )
		{
			$this->writeReference( $refID );
		}

        public function writeStringReference( /*ushort*/ $refID )
		{
			$this->writeReference( $refID );
		}

        public function writeReference( /*ushort*/ $refID )
        {
            /*XmlElement*/ $refElement = $this->doc->createElement( $this->REFERENCE );
            $refElement->nodeValue = $refID;
            $el = array_pop($this->stack);
            $el->appendChild( $refElement );
            array_push($this->stack, $el);
        }

        public function writeString( /*string*/ $s )
        {
            /*XmlElement*/ $strElement = $this->doc->createElement( $this->STRING );
            $strElement->nodeValue = $s;
            $el = array_pop($this->stack);
            $el->appendChild( $strElement );
            array_push($this->stack, $el);
        }

        public function writeXML( /*XmlNode*/ $document )
        {
            // TODO:  Add WolfFormatter.WriteXML implementation
        }

        public function writeXmlNode( /*XmlNode*/ $element )
        {
        	// TODO:  Add WolfFormatter.writeXmlNode
//            $element = $this->doc->ImportNode( $element, true );
//            array_push($this->stack, array_pop($this->stack)->ownerDocument->appendChild( element );
        }
        
        private function importNode( DOMNode $element )
        {
        	$rezElement = $this->doc->createElement($element->nodeName);
        	
        	if($element->hasAttributes())
        	{
        		
        	}
        }

        public /*ProtocolBytes*/function getBytes()
        {
            $this->resolveStack();
//            MemoryStream stream = new MemoryStream();
//            XmlTextWriter writer = new XmlTextWriter( stream, Encoding.UTF8 );
//            doc.WriteTo( writer );
//            writer.Flush();
//            ProtocolBytes protocolBytes = new ProtocolBytes();
//            protocolBytes.length = (int) stream.Length;
//            protocolBytes.bytes = stream.GetBuffer();
//            writer.Close();
//            stream.Close();
//            return protocolBytes;
//			var_dump($this->doc->saveXML());
//			Log::log(LoggingConstants::MYDEBUG, ob_get_contents());
			return str_ireplace("<?xml version=\"1.0\"?>", "", $this->doc->saveXML());
        }

        private /*XmlElement*/function resolveStack()
        {
            /*XmlElement*/ $element = array_pop($this->stack);

            while( count($this->stack) != 0 )
            {
            	$el = array_pop($this->stack);
            	var_dump($el->nodeName);
            	$el->appendChild( $element );
            	
                array_push($this->stack, $el);
                $element = array_pop($this->stack);
                
            }

            return $element;
        }

        public function cleanup()
        {
            // TODO:  Add WolfFormatter.Cleanup implementation
        }

        public  /*string*/function getContentType()
        {
            return "text/xml";
        }

        public  /*IObjectSerializer*/function getObjectSerializer()
		{
			return $this->objectSerializer;
		}

        #endregion

        public /*XmlDocument*/function getDocument()
        {
            $this->resolveStack();
            return $this->doc;
        }
        
		public function directWriteString( /*String*/ $str )
    	{
//    		$this->writeString($str);
  		}

  		public function directWriteInt( /*int*/ $i )
    	{
//    		 $this->writeInteger($i);
  		}

  		public function directWriteBoolean( /*boolean*/ $b )
    	{
//    		$this->writeBoolean($b);
	  	}

  		public function directWriteShort( /*int*/ $s )
    	{
//    		$this->writeInteger($s);
  		}
  		
  		function writeNumber($number)
  		{
  			$this->writeDouble($number);			
  		}
  		
  		function beginWriteBodyContent()
  		{  			
  		}
  		
  		function endWriteBodyContent()
  		{
  		}
    }
?>