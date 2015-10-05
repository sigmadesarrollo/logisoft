<?php
if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");
		
 	require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ITemplateNodeContainer.php';
 	require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Property.php';
 	
 	class PDFMetadata implements ITemplateNodeContainer
 	{
		public /*Property[]*/ $properties;
	
	    public /*Property[]*/ function getPropertiesByTarget(/*String*/ $propertyTarget)
	    {
	        $result = array();
	
	        foreach ($this->properties as $property)
	        {
	            if ($property->target == $propertyTarget)
	            {
	                $result[] = $property;
	            }
	        }
	
	        return $result;
	    }
	    
	    public /*Property[]*/ function getPropertiesByName(/*String*/ $propertyName)
	    {
	        /*ArrayList<Property>*/ $result = array();//new ArrayList<Property>();
	
	        foreach ($this->properties as $property)
	            if ($property->name == $propertyName)
	                $result[] = $property;
	
	        return $result;
	    }
	    
	    public /*Property*/ function getPropertyByTarget(/*String*/ $propertyTarget)
	    {
		    foreach ($this->properties as $property) 
			    if ($property->target == $propertyTarget)
				    return $property;
		    
		    return null;
	    }
	    
	    public /*Property*/ function getPropertyByName(/*String*/ $propertyName)
	    {
		    foreach ($this->properties as $property) 
			    if ($property->name == $propertyName)
				    return $property;
		    
		    return null;
	    }
	    
	    public /*Property[]*/ function getPropertiesByValue(/*Object*/ $propertyValue)
	    {
	        $result = array();
	
	        foreach ($this->properties as $property)
	            if ($property->value == $propertyValue)
	                $result[] = $property;
	
	        return $result;
	    }
	
	    public /*Class*/ function getItemClass()
	    {
	        return new ReflectionClass("Property");
	    }
	
	    public /*String*/ function getFieldName()
	    {
	        return "properties";
	    }
    } 
?>
