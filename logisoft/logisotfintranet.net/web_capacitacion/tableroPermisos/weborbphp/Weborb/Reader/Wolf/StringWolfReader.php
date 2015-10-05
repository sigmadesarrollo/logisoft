<?php

	class StringWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read(DOMNode $element, ParseContext $parseContext)
        {
            return new StringType( trim(strtolower($element->textContent)) );        
        }
    }
?>