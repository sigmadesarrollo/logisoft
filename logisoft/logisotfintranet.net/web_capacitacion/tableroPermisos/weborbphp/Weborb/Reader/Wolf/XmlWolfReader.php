<?php
	class XmlWolfReader implements IXMLTypeReader
	{
        public /*IAdaptingType*/function read( DOMNode $element, ParseContext $parseContext )
        {
            /*System.Xml.XmlDocument*/ $xmlDoc = new DOMDocument();
            $xmlDoc->loadXML( $element->textContent );
            return new XmlDataType( $xmlDoc );
        }
    }
?>