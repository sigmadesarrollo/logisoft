<?php

	class ObjectWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read( DOMNode $element, ParseContext $parseContext )
        {
            /*int*/ $refID = -1;
            $element = $element->ownerDocument->documentElement;
            
            if( $element->hasAttribute( "referenceID" ) )
                $refID = $element->getAttribute( "referenceID" );

            /*Hashtable*/ $properties = array();
            /*RequestParser*/ $xmlRequestParser = RequestParser::GetInstance();

            /*XmlNodeList*/ $xmlNodes = $element->getElementsByTagName( "Field" );

            for($i = 0; $i < $xmlNodes->childNodes->length; $i++)
            {
            	$xmlNode = $xmlNodes->childNodes->item($i);
                /*string*/ $fieldName = null;
                /*IAdaptingType*/ $fieldValue = null;

                for($j = 0; $j < $xmlNode->childNodes->length; $j++)
                {
                	$fieldNode = $xmlNode->childNodes->item($j);
                    switch( $fieldNode->nodeName )
                    {
                        case "Name":
                            $fieldName = trim($fieldNode->textContent);
                            break;

                        case "Value":
                            $fieldValue = $xmlRequestParser->ParseElement( $fieldNode->firstChild, $parseContext );
                            break;
                    }
                }

                $properties[ $fieldName ] = $fieldValue;
            }

            /*IAdaptingType*/ $obj = new AnonymousObject( $properties );

            /*string*/ $objectName = $element->getAttribute( "objectName" );

            if( $objectName != null && strlen(trim($objectName)) != 0 )
                $obj = new NamedObject( $objectName, $obj );

            if( $refID != -1 )
                $parseContext->addReference( $obj, $refID );

            return $obj;
        }
    }
?>
