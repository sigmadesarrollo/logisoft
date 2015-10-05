<?php
class WebServiceGenerator
{
	private $output = '';
	
	public function generate($wsdlUrl)
	{
		/*WebServiceInspector*/ $webServiceInspector = new WebServiceInspector();
		/*WebServiceDescriptor*/ $service = $webServiceInspector->inspect($wsdlUrl);
		$this->write("
<?php
class {$service->getName()} 
{");
		
		$methods = $service->getMethods();
		
		foreach($methods as $method)
		{
			
		}

		$this->write("	
}		
?>		");
	}
	
	private function write($str)
	{
		$this->output .= $str;
	}
}
?>