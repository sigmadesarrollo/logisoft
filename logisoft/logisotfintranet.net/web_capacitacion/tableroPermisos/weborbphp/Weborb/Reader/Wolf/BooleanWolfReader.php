<?php

	class BooleanWolfReader implements  IXMLTypeReader
	{
        public /*IAdaptingType*/function read( DOMNode $element, ParseContext $parseContext )
        {
            /*string*/ $booleanValue = trim(strtolower($element->textContent));
            return new BooleanType( ($booleanValue == "true") );
        }
        
    }
?>
