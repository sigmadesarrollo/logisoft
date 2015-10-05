<?php
class WebServiceInspector
{
	public $serviceDescriptor;
	
	private /*DOMNodeList*/ $definitions;
	private $types = array();
	
	public /*WebServiceDescriptor*/function inspect(/*string*/ $wsdlUrl, $methodName = "")
	{
//		$wsdlUrl = "http://ws.cdyne.com/DemographixWS/DemographixQuery.asmx?wsdl";
//		$wsdlUrl = "http://ws.cdyne.com/emailverify/Emailvernotestemail.asmx?wsdl";
//		$wsdlUrl = "http://ws.cdyne.com/NotifyWS/PhoneNotify.asmx?wsdl";
//		$methodName = "NotifyPhoneBasic";

		$wsdlXml = new DOMDocument();
		$wsdlXml->load($wsdlUrl);
		
		/*WebServiceDescriptor*/ $this->serviceDescriptor = new WebServiceDescriptor();
		
		$this->definitions = $wsdlXml->getElementsByTagName("definitions")->item(0)->childNodes;
		
		$this->getTypes();
		
				
		/*DOMDocument*/ $service = XmlUtil::getElementByNodeName($this->definitions, "service");
		
		/*string*/ $serviceName = $service->documentElement->getAttribute("name");
		
		$this->serviceDescriptor->setName($serviceName);
		
		$proxyhost = '';
		$proxyport = '';
		$proxyusername = '';
		$proxypassword = '';
		
		$client = new soapclient($wsdlUrl, true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
		$err = $client->getError();
		
		if ($err) 
		{
			throw new Exception($err);
		}

		$operations = $client->getOperations($methodName);
//		var_dump($operations);
//		if(LOGGING)
//			Log::log(LoggingConstants::MYDEBUG, ob_get_contents());
		foreach($operations as $operation)
		{
			/*WebServiceMethod*/ $webServiceMethod = new WebServiceMethod();
			$this->parseMessageInput($operation['input']['message'], $webServiceMethod);
			$webServiceMethod->setReturn($this->parseMessageOutput($operation['output']['message']));
			$webServiceMethod->setName($operation["name"], $webServiceMethod);	
			
			$this->serviceDescriptor->addMethod($webServiceMethod);		
		}
	}

	private function parseMessageInput(/*string*/ $messageName, WebServiceMethod &$method)
	{
		/*DomElement*/ $messageNode = null;
		for($i=0; $i < $this->definitions->length; $i++ )
		{
			/*DOMElement*/ $item = XmlUtil::getDOMElement($this->definitions->item($i));

			if(XmlUtil::cleearNS($item->nodeName) == "message" && 
				XmlUtil::cleearNS($item->getAttribute("name")) == $messageName)
			{
				$messageNode = $item;
				break;
			}
		}
		
		$partNodes = $messageNode->getElementsByTagName("part");
		
		for($i=0; $i < $partNodes->length; $i++)
		{
			/*DOMElement*/ $item = XmlUtil::getDOMElement($partNodes->item($i));
			$webServiceType = new WebServiceType();
			$webServiceType->setName(XmlUtil::cleearNS($item->getAttribute("name")));
			$webServiceType->setType($this->parseType(XmlUtil::cleearNS($item->getAttribute("element"))));
			$method->addArgument($webServiceType);			
		}
	}
	
	private function parseMessageOutput(/*string*/ $messageName)
	{
		/*DomElement*/ $messageNode = null;
		for($i=0; $i < $this->definitions->length; $i++ )
		{
			/*DOMElement*/ $item = XmlUtil::getDOMElement($this->definitions->item($i));
			if(XmlUtil::cleearNS($item->nodeName) == "message" && 
				XmlUtil::cleearNS($item->getAttribute("name")) == $messageName)
			{
				$messageNode = $item;
				break;
			}
		}
		
		$partNodes = $messageNode->getElementsByTagName("part");
		/*DOMElement*/ $item = XmlUtil::getDOMElement($partNodes->item(0));
		if($partNodes->length == 0 )
			return null;
		
		$webServiceType = new WebServiceType();
		$webServiceType->setName(XmlUtil::cleearNS($item->getAttribute("name")));
		$webServiceType->setType($this->parseType(XmlUtil::cleearNS($item->getAttribute("element"))));

		return $webServiceType;
		
	}
	
	private function parseType(/*string*/ $name)
	{
		/*DomDocument*/ $node = null;
		/*WebServiceType*/ $nodeType;
		
		foreach($this->types as $type)
		{
			if(XmlUtil::cleearNS($type->documentElement->nodeName) == "element" && XmlUtil::cleearNS($type->documentElement->getAttribute("name")) == $name)
			{
					$node = $type;
					break;
			}
		}
		if($node == null)
			return $name;
		
		$nodeType = new WebServiceType();
		$nodeType->setName($name);
				
		if($node->documentElement->hasAttribute("type"))
		{
			
			$nodeType->setType($this->parseComplexType($node->documentElement->getAttribute("type")));
			if(!$nodeType)
			{
				$nodeType->setType($node->documentElement->getAttribute("type"));
			}
		}
		else
		{
			
			if(!$this->parseComplexType(XmlUtil::getDOMDocument($node->childNodes->item(0)), $nodeType))
			{
//				$nodeType = "undefined";
			}
				
		}
		return $nodeType;
	}
	
	private function parseComplexType($type, WebServiceType &$webServiceType = null)
	{
		/*DomDocument*/$nodeType = null;
		if(is_string($type))
		{
			foreach($this->types as $node)
			{
				if(XmlUtil::cleearNS($node->documentElement->nodeName) == "complexType" && XmlUtil::cleearNS($node->documentElement->getAttribute("name")) == $type)
				{
						$nodeType = $node;
						break;
				}
			}
			$webServiceType = new WebServiceType();
			$webServiceType->setName($type);
		}
		else
		{
			$nodeType = $type;
		}
		
		if($nodeType == null)
		{
			return false;
		}
		
		/*DOMNodeList*/$elements = $nodeType->documentElement->getElementsByTagName("element");
		
		if($elements->length == 0)
		{
			$this->parseComplexType(XmlUtil::getDOMDocument($nodeType->childNodes->item(0)), $webServiceType);
		}
		
		
		for($i=0; $i < $elements->length; $i++)
		{
			$elementName = null;
			$elementType = null;
			
			/*DOMElement*/ $element = XmlUtil::getDOMElement($elements->item($i));
			
			$elementName = XmlUtil::cleearNS($element->getAttribute("name"));
			
			if($element->hasAttribute("maxOccurs") && 
				($element->getAttribute("maxOccurs") == "unbounded" || $element->getAttribute("maxOccurs")*1 > 1))
				{
					$elementType = "array";
				}
			else
			{
				$elementType = $this->parseComplexType(XmlUtil::cleearNS($element->getAttribute("type")));
				if($elementType == false)
				{
					$elementType = XmlUtil::cleearNS($element->getAttribute("type"));
				}
			}
			
			$webServiceType->setProperty($elementName, $elementType);
		}
		
		return $webServiceType;
		
	}
	
	private function getTypes()
	{
		$schemas = null;
		
		for($i=0; $i < $this->definitions->length; $i++)
		{
			$item = $this->definitions->item($i);
			if(XmlUtil::cleearNS($item->nodeName) == "types")
			{ 
				$schemas = $item->childNodes;
				for($j=0; $j < $schemas->length; $j++)
				{
					$schema = null;
					if(XmlUtil::cleearNS($schemas->item($j)->nodeName) == "schema")
					{
						$schema = $schemas->item($j)->childNodes;
						for($k=0; $k < $schema->length; $k++)
						{
							$this->types[] = XmlUtil::getDOMDocument($schema->item($k));
						}
						
					}
				}
				break;
			}
		}		
	}	
	
	
}
?>