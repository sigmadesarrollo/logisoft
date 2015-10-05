<?php
class WebServiceType
{
	private $name;
	private $type;
	private $properties = array();
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getProperty($name)
	{
		if(isset($this->properties[$name]))
		{
			return $this->properties[$name];
		}

		return false;
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
	
	public function setProperty($name, $type)
	{
		$this->properties[$name] = $type;
	}
	
	
}
?>