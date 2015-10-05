<?php
/*******************************************************************
 * XmlUtil.php
 * Copyright (C) 2006-2007 Midnight Coders, LLC
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is WebORB Presentation Server (R) for PHP.
 * 
 * The Initial Developer of the Original Code is Midnight Coders, LLC.
 * All Rights Reserved.
 ********************************************************************/

class XmlUtil
{

    public static function getElementText(DOMElement $element, $elementName)
    {
        $nodeList = $element->getElementsByTagName($elementName);

        if (is_null($nodeList) || (0 == $nodeList->length))
        {
            return null;
        }

        $node = $nodeList->item(0);

        if (is_null($node))
        {
            return null;
        }

        return $node->nodeValue;
    }
    
    public static function getChild(/*DOMNode*/ $node, /*String*/ $name)
    {
    	if($node instanceof DOMNode )
    		/*DOMNodeList*/ $childNodes = $node->childNodes;
    	elseif ($node instanceof DOMNodeList )
    		$childNodes = $node;
    	else throw new Exception('First parameter must be instanceof DOMNode or DOMNodeList.');
    	
    	if ($childNodes == null) return null;
    	
    	for($i = 0; $i < $childNodes->length; $i++)
    	{
    		if($childNodes->item($i)->nodeName == $name)
    		{
    			return $childNodes->item($i);
    		}
    	}
    	return null;
    }

    public static function getAttributeText(DOMElement $element, $attrName, $elementName = null)
    {
        if (is_null($elementName))
        {
            $attr = $element->getAttribute($attrName);
        }
        else
        {
            $nodeList = $element->getElementsByTagName($elementName);

            if (is_null($nodeList) || (0 == $nodeList->length))
            {
                return null;
            }

            $node = $nodeList->item(0);

            if (is_null($node))
            {
                return null;
            }

            $attr = $node->getAttribute($attrName);
        }

        if (is_null($attr))
        {
            return null;
        }

        return $attr->value;
    }
    
    public static/*DOMDocument*/ function getElementByNodeName(DOMNodeList $nodeList, $nodeName)
    {
    	 for($i = 0; $i < $nodeList->length; $i++)
    	 {
    	 	/*DOMNode*/ $item = $nodeList->item($i);
    	 	if(self::cleearNS($item->nodeName) == $nodeName)
    	 	{    	 		
    	 		return self::getDOMDocument($item);
    	 	}
    	 }
    	 
    	 return null;
    }
    
    public static function cleearNS(/*string*/ $prop)
    {
    	$arr = explode(":", $prop);
    	if(count($arr) == 1)
    	{
    		return $prop;
    	}
    	else
    	{
    		return $arr[1];
    	}
    }
    
    public static function getDOMElement(DOMNode $node)
    {
    	/*DOMDocument*/$domDocument = self::getDOMDocument($node);
    	/*DOMElement*/ $domElement = $domDocument->documentElement;    	
    	return $domElement;
    }
    
    public static function getDOMDocument(DOMNode $node)
    {
    	
    	$domDocument = new DOMDocument();
    	$node = $domDocument->importNode($node, true);
    	$domDocument->appendChild($node);
//    	var_dump($domDocument->saveXML());
    	return $domDocument;
    }

}

?>
