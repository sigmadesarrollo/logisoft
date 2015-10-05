<?php
require_once('ManagementService.php');
require_once('ServiceBrowser/ServiceNode.php');
require_once('ServiceBrowser/ServiceMethod.php');
require_once('ServiceBrowser/ServiceMethodArg.php');
require_once('RBIManagement/ServerConfiguration.php');
require_once(WebOrb . "Util" . DIRECTORY_SEPARATOR . "Logging" . DIRECTORY_SEPARATOR . "Log.php");
require_once(WebOrb . "Util" . DIRECTORY_SEPARATOR . "Logging" . DIRECTORY_SEPARATOR . "LoggingConstants.php");

class RBIManagementService 
{
	public /*ArrayList*/function getServices()
	{
		/*ManagementService*/ $ms = new ManagementService();
		/*ArrayList*/ $services = $ms->getServices();
		
		return $services;
	}
	
	public /*ArrayList*/function getServiceChildren( /*String*/ $servicePackage, /*String*/ $parentName )
	{
		/*ManagementService*/ $ms = new ManagementService();
		/*ArrayList*/ $services = $ms->getServiceChildren( $servicePackage, $parentName );

		
		return $services;
	}
	
	public /*void*/function selectNode($mode, $namespaceName, $fullPath, $serviceName = null, $methodName = null )//ServiceNode $node )
	{	
		$node = null;
		/*ManagementService*/ $ms = new ManagementService();
		$serviceList = array();
		$methodList = array();
		if($namespaceName != "")
		{
			$ar = explode(".", $fullPath);
			$namespaceName = $ar[0];
			$serviceList = $ms->getServiceChildren($namespaceName, $fullPath);
		}
		else 
			$serviceList = $ms->getServices();
		
		if ($serviceName != null)
		{
			foreach ($serviceList as $service)
			{
				if ($service->Name == $serviceName)
				{
					$node = $service;
					break;
				}
			}
			if ($methodName != null)
			{
				$methodList = $node->Items;
				foreach ($methodList as $method)
				{
					if ($method->Name == $methodName)
					{
						$method->Selected = $mode;
						$node = $method;
						break;
					}
				}
			}
		}
		else 
		{
			if (count($serviceList)>0)
				$node = $serviceList[0]->Parent;
		}
		
		$parentNode = null;
		if($methodName != null)
		{
			$parentNode = $node->Parent;
			$parentNode->Items = $methodList;
			$parentNode = $parentNode->Parent;
		}
		else
		{
        	$parentNode = $node->Parent;
		}
        
        while ($parentNode!=null)
        {
            if($parentNode->Parent != null)
            {
                $parentNode->Items = $ms->getServiceChildren($parentNode->Parent->getFullName(), $parentNode->getFullName());
            }
            
            $parentNode = $parentNode->Parent;            
        }        

		$this->setSelection($node, $mode);
		
		/*String*/ $fullName = $node->getFullName();
		/*ServiceNode*/ $baseNode = $node instanceof ServiceMethod ? new ServiceMethod() : new ServiceNode();
		$baseNode->Name = $node->Name;
		$baseNode->Selected = $node->Selected;
		$baseNode->Parent = $node->Parent;
		$baseNode->Items = array_merge_recursive($baseNode->Items, $node->Items );
		
		while( $node->Parent != null )
		{
			$node->Parent->Items = array($node);
			$node = $node->Parent;
		}
		
		$fullName = substr($fullName, 0, strlen($node->Parent->Name) + 1);
		
		/*String[]*/ $fullNameParts = explode(".", $fullName);
		$node->Parent = null;
		/*ServiceNode*/ $tempNode = $node;
		
		for( $i = 1; $i < count($fullNameParts); $i++ )
		{
			/*ServiceNode */$child = $i == count($fullNameParts) - 1 ? $baseNode : $tempNode->findItem( $fullNameParts[ $i ] );
			$tempNode->Items = array();
			$tempNode->Items[] = $child;
			$tempNode = $child;			
		}
		
		$this->cleanNode( $node );
		ORBConfig::getInstance()->getBusinessIntelligenceConfig()->getMonitoredClassRegistry()->addSelectedNode( $node );
		Log::log(LoggingConstants::MYDEBUG,ob_get_contents());		
	}
	
	public function deselectNode($mode, $namespaceName, $fullPath, $serviceName = null, $methodName = null )//ServiceNode $node )
	{	
		$node = null;
		/*ManagementService*/ $ms = new ManagementService();
		$serviceList = array();
		$methodList = array();
		if($namespaceName != "")
		{
			$ar = explode(".", $fullPath);
			$namespaceName = $ar[0];
			$serviceList = $ms->getServiceChildren($namespaceName, $fullPath);
		}
		else 
			$serviceList = $ms->getServices();
		
		if ($serviceName != null)
		{
			foreach ($serviceList as $service)
			{
				if ($service->Name == $serviceName)
				{
					$node = $service;
					break;
				}
			}
			if ($methodName != null)
			{
				$methodList = $node->Items;
				foreach ($methodList as $method)
				{
					if ($method->Name == $methodName)
					{
						$method->Selected = $mode;
						$node = $method;
						break;
					}
				}
			}
		}
		else 
		{
			if (count($serviceList)>0)
				$node = $serviceList[0]->Parent;
		}
		
		$parentNode = null;
		if($methodName != null)
		{
			$parentNode = $node->Parent;
			$parentNode->Items = $methodList;
			$parentNode = $parentNode->Parent;
		}
		else
		{
        	$parentNode = $node->Parent;
		}
        
        while ($parentNode!=null)
        {
            if($parentNode->Parent != null)
            {
                $parentNode->Items = $ms->getServiceChildren($parentNode->Parent->getFullName(), $parentNode->getFullName());
            }
            
            $parentNode = $parentNode->Parent;            
        }        

		$this->setSelection($node, $mode);
		
		/*ServiceNode*/ $rootNode = $node; 
		
		while( $rootNode->Parent != null )
        {
        	$rootNode->Parent->Items = array($rootNode);
			$rootNode = $rootNode->Parent;
        }
        $this->cleanNode( $rootNode );
	    /*String*/ $fullName = $node->getFullName();
//	    $fullName = substr($fullName, 0, strlen($rootNode->Parent->Name) + 1);
	    $rootNode->Parent = null;
		
		ORBConfig::getInstance()->getBusinessIntelligenceConfig()->getMonitoredClassRegistry()->removeSelectedNode( $rootNode, $fullName );
		Log::log(LoggingConstants::MYDEBUG,ob_get_contents());
		return $node;
	}
    
		private function setSelection(ServiceNode $node, $mode)
        {
         	$node->Selected = $mode;
        	
        	//set childrens selection
        	$this->selectChild($node);
        	
        	// set parent selection
        	$this->selectParent($node, $mode);       	
        }

        private function selectChild(ServiceNode $node)
        {
        	foreach ($node->Items as $childNode)
        	{
        		$childNode->Selected = $node->Selected;
        		$this->selectChild($childNode);
        	}
        }
        
        private function selectParent(ServiceNode $node, $mode)
        {
        	if ($node->Parent != null)
        	{
    			$isAllSelected = true;
    			foreach ($node->Parent->Items as $child )
    			{
    				if (!($child->Selected == $mode))
    				{
    					$isAllSelected = false;
    					break;	
    				}
    			}
    			
    			if ($isAllSelected)
    				$node->Parent->Selected = $mode;
    			else
    				$node->Parent->Selected = ServiceNode::PARTLY_SELECTED;
    			
    			$this->selectParent($node->Parent, $mode);
        	}
        } 
	
	private function cleanNode( ServiceNode $node )
	{
//		if( $node instanceof ServiceMethod )
//		{
//			$node->Name = $this->createMethodName( $node );
//			$node->Items = array();
//		}
		
		if( $node->Selected == ServiceNode::FULLY_SELECTED || $node->Selected == ServiceNode::NOT_SELECTED )
			$node->Items = array();
		else
			for( $i = 0; $i < count($node->Items); $i++ )
			{
				/*ServiceNode*/ $child = $node->Items[$i];
				$this->cleanNode( $child );
			}
	}
	
	private /*String*/function createMethodName( ServiceMethod $method )
	{
		/*String*/ $name = $method->Name . "( ";
		
		for( $i = 0; $i < count($method->Items); $i++ )
		{
			if( $i != 0 )
				$name .= ", ";
			
			/*ServiceMethodArg*/ $arg = $method->Items[$i];
			$name .= $arg->DataType->ServerSideName;
		}
		
		$name .= " )";
		
		return $name;
	}
	
	public function saveConfiguration( /*ServerConfiguration*/ $configuration )
	{
		ORBConfig::getInstance()->getBusinessIntelligenceConfig()->saveServerConfiguration( $configuration );
	}
	
	public /*ServerConfiguration*/function loadConfiguration()
	{
		return ORBConfig::getInstance()->getBusinessIntelligenceConfig()->getServerConfiguration();
	}
	
	public function saveSelection()
	{
		ORBConfig::getInstance()->getBusinessIntelligenceConfig()->saveMonitoredClassRegistry();
	}
	
	public /*ArrayList*/ function cancelSelection()
	{
		ORBConfig::getInstance()->getBusinessIntelligenceConfig()->validate();
		
		return $this->getServices();
	}
}
?>