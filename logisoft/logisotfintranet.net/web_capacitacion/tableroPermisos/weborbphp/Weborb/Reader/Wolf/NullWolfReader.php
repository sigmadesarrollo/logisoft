<?php

	class NullWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read(DOMNode $element, ParseContext $parseContext)
        {
            return new NullType();
        }
	}
?>
