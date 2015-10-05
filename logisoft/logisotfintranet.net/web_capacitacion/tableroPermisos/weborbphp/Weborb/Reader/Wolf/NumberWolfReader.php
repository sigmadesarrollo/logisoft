<?php

	class NumberWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read( DOMNode $element, ParseContext $parseContext )
        {
            /*double*/ $number = trim(strtolower($element->textContent));
            return new NumberObject( $number );
        }
    }
?>
