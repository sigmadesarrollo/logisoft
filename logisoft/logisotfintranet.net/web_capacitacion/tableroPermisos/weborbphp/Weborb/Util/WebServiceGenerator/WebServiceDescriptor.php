<?php
class WebServiceDescriptor
{
	private $name;
	private $methods = array();
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function addMethod(WebServiceMethod $method)
	{
		if(array_key_exists($method->getName(), $this->methods))
		{
			if(in_array($method, $this->methods))
				return false;
			
			$method->setName($method->getName() . "_" . str_replace(" ", "_", microtime(false)));
		}
		
		$this->methods[$method->getName()] = $method;
	}
	
	public function getMethods()
	{
		return $this->methods;
	}
}
?>