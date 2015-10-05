<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ITemplateNodeContainer.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/builders/ComponentBuilder.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/builders/DocumentBuilder.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/util/DataUtils.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/DataGridColumn.php';

abstract class Builder 
{

	protected function parseNode(/*Node*/ $element)
	{
		Log::log(LoggingConstants::MYDEBUG," element: " . $element->nodeName);
		
		/*Object*/ $object = ComponentBuilder::buildComponent($element);
		
		$this->populate($object, $element);
		
		/*NodeList*/ $nodes = $element->childNodes;
		
		/*String*/ $field = "";
		
		if ($object instanceof ITemplateNodeContainer)
		{
				
			/*IContainer*/ $obj = $object;
			
			$field = $obj->getFieldName();
				
			/*int*/ $j = -1;
			
			for (/*int*/ $i = 0; $i < $nodes->length; $i++) 
			{
				if ($nodes->item($i)->nodeName == $obj->getFieldName())
				{
					$j = $i; 
					break;
				}
			}			
			
			/*Object*/ $value;
			
			if ($j != -1) 
			{
				$value = ComponentBuilder::buildComponent($nodes->item($j), $obj->getItemClass());
			} 
			else
			{				
				$value = ComponentBuilder::buildComponent($element, $obj->getItemClass());
			}
					
//			Log::log(LoggingConstants::MYDEBUG, " --- " . get_class($object) . ' : ' . $obj->getFieldName() . ' = ' . $value);
			DataUtils::setValue($object, $obj->getFieldName(), $value);
		}
		
			
		for ($i = 0; $i < $nodes->length; $i++) 
		{
			/*Node*/ $node = $nodes->item($i);
			
			if (($node->nodeName != "#text") && ($node->nodeName != "#comment") && ($node->nodeName != $field))
			{
			    if( $node->nodeName == "PDFMetadata" )
			    {
			      DataUtils::setValue( $object, "metadata", $this->parseNode( $node ) );
			    }
			    else if( $node->nodeName == "dataProvider" )
			    {
			      $value = ComponentBuilder::buildComponent( $node, "array" );
			      DataUtils::setValue( $object, $node->nodeName, $value );
			    }
			    else
			    {
			         DataUtils::setValue($object, $node->nodeName, $node->textContent);
			    }					
				
						
			}
		}
		
		return $object;
	}
	
	private function populate(/*Object*/ $object, /*Node*/ $element)
	{
		$i = 0;
		$attributes = $element->attributes;		
		while($attributes->item($i) != null)
		{
			$node = $attributes->item($i);
			/*String*/ $attrName = $node->nodeName;
			/*String*/ $attrValue = $node->nodeValue;
			DataUtils::setValue($object, $attrName, $attrValue);
			$i++;
		}
	}
	
	public static /*Document*/function buildDocument(/*Object*/ $dataObject)
	{
		if (is_string($dataObject))
		{
			return DocumentBuilder::build($dataObject);
		}
		else if ($dataObject instanceof Document)
		{
			return $dataObject;
		}
		else if ($dataObject instanceof DOMNode)
		{
			$docBuilder = new DocumentBuilder();
			return $docBuilder->buildOnDOMDocument($dataObject);
		}
			
		return null;
	}
}
?>