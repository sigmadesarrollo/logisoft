<?php

	class ArrayWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read( DOMNode $element, ParseContext $parseContext )
        {
            /*int*/ $refID = -1;
            $element = $element->ownerDocument->documentElement;
            if( $element->hasAttribute( "referenceID" ) )
                $refID = $element->getAttribute( "referenceID" );

            /*ArrayList*/ $arrayElements = array();
            /*RequestParser*/ $requestParser = RequestParser::GetInstance();

            for($i = 0; $i < $element->childNodes->length; $i++)
            {
            	$xmlNode = $element->childNodes->item($i);
                if( !($xmlNode instanceof DOMNode) )
                    continue;

                $arrayElements[] = $requestParser->ParseElement( $xmlNode, $parseContext ) ;
            }

//           /* ArrayType*/ array = new ArrayType( (IAdaptingType[]) arrayElements.ToArray( typeof( IAdaptingType ) ) );

            if( $refID != -1 )
                $parseContext->addReference( $arrayElements, $refID );

            return $arrayElements;
        }

        
    }

?>