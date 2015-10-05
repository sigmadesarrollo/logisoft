<?php
require_once("ServiceCodegenerator.php");

class SilverlightCSharp extends ServiceCodegenerator
{
	private $hasVo = false;
	 
	protected function doGenerate()
	{
		$this->hasVo = false;
		foreach($this->service->Items as $method)
		{
			foreach($method->Items as $arg)
			{
				if($arg->DataType->Name != "String")
				{
					$this->hasVo = true;
					break;
				}
			}
			if($this->hasVo)
				break;
		}
				
		$parent = $this->service->Parent;
		
		if($parent == null)
		{
			$this->createCode();
		}
		else
		{
			$allParents = array();
			$allParents[] = $parent;
			
			while($parent->Parent != null)
			{					
				$parent = $parent->Parent;
				$allParents[] = $parent;
			}
			$allParents = array_reverse($allParents);
						
			foreach($allParents as $parent)
			{
				$this->writeStartFolder($parent->Name);
				$this->package.=$parent->Name.".";
			}
			
			$this->serviceParents = $allParents;
			$this->createCode();
				
		}
	}
	
	protected function createCode()
	{
		$this->writeStartFile($this->service->Name . "Service.cs");
			$this->createService();
		$this->writeEndFile();
		
		$this->writeStartFile("I" . $this->service->Name . ".cs");
			$this->createInterface();
		$this->writeEndFile();
		
		$this->writeStartFile($this->service->Name . "Model.cs");
			$this->createModel();
		$this->writeEndFile();
		if($this->hasVo)
		{
			$this->writeStartFolder("types");
				$arrNames = array();
				foreach($this->service->Items as $method)
				{
					foreach($method->Items as $arg)
					{
						if($arg->DataType->Name != "String")
						{
							if (!in_array($arg->DataType->Name, $arrNames))
							{
								$this->writeStartFile($arg->DataType->Name.".cs");
								$this->createVO($arg->DataType);
								$this->writeEndFile();
		
								$arrNames[] = $arg->DataType->Name;
							}
						}
					}
				}
			$this->writeEndFolder();
		}
	}
	
	protected function createService()
	{
		$this->writeText("
    using System.Collections;
    using System.Collections.Generic;
    using Weborb.Client;

    namespase ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
    {
    public class ".$this->service->Name."Service
    {
      private WeborbClient weborbClient;
      private I".$this->service->Name." proxy;
      private ".$this->service->Name."Model model;

      public ".$this->service->Name."Service() : this( new ".$this->service->Name."Model() )
      {
      }
      
      public ".$this->service->Name."Service( ".$this->service->Name."Model model )
      {
        this.model = model
        weborbClient = new WeborbClient(\"weborb.php\"); 
        proxy = weborbClient.Bind<I".$this->service->Name.">();
      }

      public ".$this->service->Name."Model GetModel()
      {
        return this.model;
      }");
      foreach($this->service->Items as $method)
      {
      	$argAndTypeText = "";
      	$argText = "";
      	foreach($method->Items as $arg)
      	{
      		$argAndTypeText .= $arg->DataType->Name . " " . $arg->Name . ", ";
      		$argText .= $arg->Name . ", ";
      	}
      	$argAndTypeText = substr($argAndTypeText, 0, strlen($argAndTypeText)-2);
      	$argText = substr($argText, 0, strlen($argText)-2);
      	
      	$this->writeText("
      public AsyncToken<".$method->ReturnDataType->Name."> ".$method->Name."( ".$argAndTypeText." )	
      {
      	");
      	
      	if($method->ReturnDataType->Name != "void")
      	{
      		$this->writeText("
            AsyncToken<".$method->ReturnDataType->Name."> asyncToken = proxy.".$method->Name."(".$argText.");
            asyncToken.ResultListener += ".$method->Name."ResultHandler;
            return asyncToken;
      		");	
      	}
      	else
      	{
      		$this->writeText("
      		proxy.".$method->Name."(".$argText.");
      		");
      	}
      	$this->writeText("
      }");
      }
	  
	  foreach($this->service->Items as $method)
      {
      	if($method->ReturnDataType->Name != "void")
      	{
      		$this->writeText("
      void ".$method->Name."ResultHandler(".$method->ReturnDataType->Name." result)
      {
        model.".$method->Name."Result = returnValue;
      }	
      		");
      	}      	
      }
       
      $this->writeText("
      public function onFault (event:FaultEvent):void
      {
        Alert.show(event.fault.faultString, \"Error\");
      }
    }
  } 
		");
	}
	
	protected function createInterface()
	{
		$this->writeText("
    using System.Collections;
    using System.Collections.Generic;
    using Weborb.Client;

    namespace ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
    {
      public interface I".$this->service->Name."
      {");
	  foreach($this->service->Items as $method)
      {
      	$argAndTypeText = "";
      	foreach($method->Items as $arg)
      	{
      		$argAndTypeText .= $arg->DataType->Name . " " . $arg->Name . ", ";
      	}
      	$argAndTypeText = substr($argAndTypeText, 0, strlen($argAndTypeText)-2);
      	
      	
      	if($method->ReturnDataType->Name != "void")
      	{
      		$this->writeText("
      		AsyncToken<".$method->ReturnDataType->Name."> ".$method->Name."(".$argAndTypeText.");
       
      		");	
      	}
      	else
      	{
      		$this->writeText("
      		void ".$method->Name."(".$argAndTypeText.");
     
      		");
      	}
      }
	$this->writeText("
	     }
    } 	
		");
	}
	
	protected function createModel()
	{
		$using = "";
		if($this->hasVo)
			$using = "using ".$this->service->getPath()."types.*;";
			
		$this->writeText("
    ".$using." 
    namespace ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
    { 
      public class ".$this->service->Name."Model
      {");
		foreach($this->service->Items as $method)
		{
			if($method->ReturnDataType->Name != "void")
				$this->writeText("
				public ".$method->ReturnDataType->Name." ".$method->Name."Result;
			");
		}
		$this->writeText("
      }
    }	
		");
	}
	
	protected function createVO($dataType)
	{
		$this->writeText("
     namespace ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
     {
        public class ".$dataType->Name."
        {");
		foreach($dataType->Items as $field)
		{
			if(!in_array($field, $fieldsArray))
			{
				$this->writeText("
		      public ".$field->DataType->Name." ".$field->Name.";	
				");				
			}
		}
        $this->writeText("
        }
     }	
		");
	}
	
	protected function writeHeader($name)
	{
			
	}
	
	protected function createVOCode($vars)
	{
		
	}
}
?>