<?php
class WebServiceMethod
{
	private $name;
	
	private $arguments = array();
	
	private $return;
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function addArgument(WebServiceType $arg)
	{
		$this->arguments[] = $arg;
	}
	
	public function getArguments()
	{
		return $this->arguments;
	}
	
	public function getReturn()
	{
		return $this->return;
	}
	
	public function setReturn($return)
	{
		$this->return = $return;
	}
}
?>