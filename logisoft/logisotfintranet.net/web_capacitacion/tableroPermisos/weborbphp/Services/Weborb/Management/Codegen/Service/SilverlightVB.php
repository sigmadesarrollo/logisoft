<?php
require_once("ServiceCodegenerator.php");

class SilverlightVB extends ServiceCodegenerator
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
		$this->writeStartFile($this->service->Name . "Service.vb");
			$this->createService();
		$this->writeEndFile();
		
		$this->writeStartFile("I" . $this->service->Name . ".vb");
			$this->createInterface();
		$this->writeEndFile();
		
		$this->writeStartFile($this->service->Name . "Model.vb");
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
								$this->writeStartFile($arg->DataType->Name.".vb");
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
    Imports System.Collections

    Imports System.Collections.Generic

    Imports Weborb.Client

    Namespace ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
    
    Public Class ".$this->service->Name."Service
    
      Private weborbClient As WeborbClient
      Private proxy As I".$this->service->Name."
      Private model As ".$this->service->Name."Model
      
      Public Sub New()

           Me.New(New ".$this->service->Name."Model())

      End Sub
      
      Public Sub New(ByVal model As ".$this->service->Name."Model)

            Me.model = model

            weborbClient = New WeborbClient(\"weborb.php\")

            proxy = weborbClient.Bind(Of I".$this->service->Name.")()

      End Sub
      
      Public Function GetModel() As ".$this->service->Name."Model

            Return Me.model

      End Function");
		
      foreach($this->service->Items as $method)
      {
      	$argAndTypeText = "";
      	$argText = "";
      	foreach($method->Items as $arg)
      	{
      		$argAndTypeText .= "ByVal " . $arg->Name . " As " . $arg->DataType->Name .  ", ";
      		$argText .= $arg->Name . ", ";
      	}
      	$argAndTypeText = substr($argAndTypeText, 0, strlen($argAndTypeText)-2);
      	$argText = substr($argText, 0, strlen($argText)-2);
      	
      	$this->writeText("
      Public Function ".$method->Name."( ".$argAndTypeText." ) As AsyncToken(Of ". $method->ReturnDataType->Name .")	
      	");
      	
      	if($method->ReturnDataType->Name != "void")
      	{
      		$this->writeText("
            Dim asyncToken As AsyncToken(Of ".$method->ReturnDataType->Name.") = proxy.".$method->Name."(".$argText.")
            AddHandler asyncToken.ResultListener, AddressOf ".$method->Name."ResultHandler
            Return asyncToken
      		");	
      	}
      	else
      	{
      		$this->writeText("
      		proxy.".$method->Name."(".$argText.")
      		");
      	}
      	$this->writeText("
      End Function");
      }
	  
	  foreach($this->service->Items as $method)
      {
      	if($method->ReturnDataType->Name != "void")
      	{
      		$this->writeText("
      Private Sub ".$method->Name."ResultHandler(ByVal result As ".$method->ReturnDataType->Name.")
      
        model.".$method->Name."Result = returnValue;

      End Sub
      		");
      	}      	
      }
       
      $this->writeText("
      Public Function onFault() As [function]

      End Function

      Private Event  As 

    End Class

    End Namespace
		");
	}
	
	protected function createInterface()
	{
		$this->writeText("
    Imports System.Collections

    Imports System.Collections.Generic

    Imports Weborb.Client

    Namespace ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
    
      Public Interface I".$this->service->Name."
      ");
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
      		Function ".$method->Name."(".$argAndTypeText.") As AsyncToken(Of ".$method->ReturnDataType->Name.") 
       
      		");	
      	}
      	else
      	{
      		$this->writeText("
      		Sub ".$method->Name."(".$argAndTypeText.")
      
      		");
      	}
      }
	$this->writeText("
	     End Interface
    End Namespace 	
		");
	}
	
	protected function createModel()
	{
		if($this->hasVo)
			$this->writeText("\tImports ".$this->service->getPath()."types");
		else
		{	
		$this->writeText("
     
    Namespace ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
     
      Public Class ".$this->service->Name."Model
      ");
		foreach($this->service->Items as $method)
		{
			if($method->ReturnDataType->Name != "void")
				$this->writeText("
				Public ".$method->Name."Result As ".$method->ReturnDataType->Name."
			");
		}
		$this->writeText("
      End Class
    End Namespace	
		");
		}
	}
	
	protected function createVO($dataType)
	{
		$this->writeText("
     Namespace ".substr($this->service->getPath(), 0, strlen($this->service->getPath())-1)."
     
        Public Class ".$dataType->Name."
        ");
		foreach($dataType->Items as $field)
		{
			$this->writeText("
		      Public ".$field->Name." As ".$field->DataType->Name." 	
				");					
		}
        $this->writeText("
        End Class
     End Namespace	
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