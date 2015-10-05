<?php

	class ReferenceWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read(DOMNode $element, ParseContext $parseContext)
        {
            /*int*/ $refID = trim(strtolower($element->textContent));
            return $parseContext->getReference( $refID );
        }

    }
?>
