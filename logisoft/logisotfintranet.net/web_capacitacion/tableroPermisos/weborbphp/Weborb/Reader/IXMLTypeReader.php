<?php

interface IXMLTypeReader
{
    public /*AdaptingType*/function read( DOMNode $element, ParseContext $parseContext );
}
?>