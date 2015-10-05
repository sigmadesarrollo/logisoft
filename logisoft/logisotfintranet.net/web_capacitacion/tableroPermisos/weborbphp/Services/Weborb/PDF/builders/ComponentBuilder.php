<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

require_once(WebOrb . "Util/TypeLoader.php");
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/builders/Builder.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/DataGridColumn.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/DataGrid.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Text.php';
require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Image.php';


class ComponentBuilder extends Builder 
{
	private static /*String[]*/ $basePath = array("Weborb.PDF.model.");

	protected function __construct()
	{
		
	}
	
	public /*Object*/function buildList(/*NodeList*/ $nodes, /*Class*/ $className) 
	{
		if ($nodes->length == 0) return null;
			
		if( $className == new ReflectionClass("Page") )
			return $this->buildPageList($nodes);
		else if( $className == new ReflectionClass("DataGridColumn") )
			return $this->buildColumnList($nodes);
		else if( $className == "array")
			return $this->buildArrayList($nodes);
	    else if( $className == new ReflectionClass("Property") )
	        return $this->buildMetadataList( $nodes );			
		else 
			return $this->buildComponentList($nodes);
	}
	
    private function buildMetadataList( $nodes )
    {
	    $resultList = array();
	
	    for( $i = 0; $i < $nodes->length; $i++ )
	    {
	        $node = $nodes->item($i);
	
	        if( $node->nodeName == "Property" )
	        {
		        $property = $this->parseNode( $node );
		        $resultList[] = $property;
	        }
	    }
	    
		return $resultList;
//	    Property[] result = new Property[resultList.size()];
//	    return resultList.toArray( result );
    }
  	
	public function buildArrayList(/*NodeList*/ $nodes)
	{
		$resultList = array();
		
		for ($i = 0; $i < $nodes->length; $i++) 
		{
			$node = $nodes->item($i);
		    if( ($node->nodeName != "#text")
		        && ($node->nodeName != "#comment") )
				$resultList[] = $this->generateArray( $node );
      		else if ( ($node->nodeName == "#text") && ($nodes->length == 1))
		        $resultList[$node->nodeName] = $node->nodeValue;
		}
		
		return $resultList;
	}
	
  private function generateArray( $node )
  {
	    $result = array();
	
	    $attrName = $node->nodeName;
	    $attrValue = $node->textContent;
	    if( $attrValue != null ) 
	    	$result[$attrName] = $attrValue;
	
	    //reading node attributes list
		$attributes = $node->attributes;	
	
		for($i = 0; $i < $attributes->length; $i++)
		{
			$node = $attributes->item($i);
			/*String*/ $attrName = $node->nodeName;
			/*String*/ $attrValue = $node->nodeValue;
			$result[$attrName] = $attrValue;
		}		
			    
	    //reading child nodes
	    $childNodes = $node->childNodes;
	    for($i = 0; $i < $childNodes->length; $i++ )
	    {
	        $node = $childNodes->item($i);
			/*String*/ $attrName = $node->nodeName;
			/*String*/ $attrValue = $node->nodeValue;
	        if( ($attrName != "#text")
	                && ($attrName != "#comment") )
	    	    $result[$attrName] = $attrValue;
	    }
	
	    return $result;
  }	
	
	private /*DataGridColumn[]*/function buildColumnList(/*NodeList*/ $nodes)
	{
		/*ArrayList<DataGridColumn>*/ $resultList = array();
		
		for ($i = 0; $i < $nodes->length; $i++) {
			/*Node*/ $node = $nodes->item($i);
			if ($node->nodeName == "DataGridColumn")
			{
				/*DataGridColumn*/ $dataGridColumn = $this->parseNode($node);
				$resultList[] = $dataGridColumn;
			}
		}
		
		return $resultList;
	}
	
	private /*Page[]*/function buildPageList(/*NodeList*/ $nodes)
	{
		$resultList = array();
		for ($i = 0; $i < $nodes->length; $i++) {
			/*Node*/ $node = $nodes->item($i);
			if ($node->nodeName == "Page")
			{
				/*Page*/ $page = $this->parseNode($node);
				$resultList[] = $page;
			}
		}
						
		return $resultList;
	}
	
	private /*Component[]*/function buildComponentList(/*NodeList*/ $nodes)
	{
		/*ArrayList<Component>*/ $resultList = array();
		
		for ($i = 0; $i < $nodes->length; $i++) 
		{
			
			/*Node*/ $node = $nodes->item($i);
			if (($node->nodeName != "#text")  && ($node->nodeName != "#comment")) {
				
				/*Object*/ $obj = $this->parseNode($node);

				if ($obj instanceof Component)
				{
					$component = $obj;
					$resultList[] = $component;
				}
			}
		}		
				
		return $resultList;
	}
	
	private static /*Object*/function createClassInstance(/*String*/ $name)
	{
		if($name == "List")
		{
			$name = "ListComponent";
		}
		
		/*Class*/ $itemClass = self::getClass($name);
		if($itemClass != null)
		{
//			Log::log(LoggingConstants::MYDEBUG," element class: " . $name);
			return $itemClass->newInstance();
		}
	}
	
	private static /*Class*/function getClass(/*String*/ $name)
	{
		for ($i = 0; $i <= count(self::$basePath); $i++) 
		{
			try
			{
				if ($i == count(self::$basePath)) 
				{
					$result = TypeLoader::loadType($name);
					return $result;
				} 
				else 
				{	
					$result = TypeLoader::loadType(self::$basePath[$i] . $name);
					return $result;
				}
			}
			catch (ReflectionException $e)
			{
				if ($i == count(self::$basePath))
				{
					return null;
					throw new Exception("No class found for element [" . $name . "]");
				}
			}
			catch (Exception $e)
			{
				if ($i == count(self::$basePath))
				{
					return null;
					throw new Exception("No class found for element [" . $name . "]");
				}
			}
		}
		
		return null;
	}
	
	public static /*Object*/function buildComponent(/*Node*/ $node,/* Class<?>*/ $className = "buildComponentNode")
	{
		if($className == "buildComponentNode")
			return self::buildComponentNode($node);
			
		/*ComponentBuilder*/ $builder = new ComponentBuilder();
		
//		Log::log(LoggingConstants::MYDEBUG, "---" . $node->nodeName . ' = ' . $node->childNodes->item(0));
		
//		if ($node->childNodes->length == 0)
//			return $node->textContent;
			
		/*Object*/ $result = $builder->buildList($node->childNodes, $className);
		
		return $result;
	}
	
	private static /*Object*/function buildComponentNode(/*Node*/ $node)
	{
		/*Object*/ $result = self::createClassInstance($node->nodeName);
		return $result;
	}
}
?>