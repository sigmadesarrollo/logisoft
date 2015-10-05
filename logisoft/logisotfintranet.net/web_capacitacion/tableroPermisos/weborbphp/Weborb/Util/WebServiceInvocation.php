<?php

class WebServiceInvocation
{
	
	
	private $soapClient = null;
	private $error = false;		
	private $errorMessage = "";
	
	public function __construct(/*string*/ $wsdlURL)
	{
		$this->soapClient = new soapclient($wsdlURL,true);
	
	}
	
	
	public function call($operation, $arguments=array())
	{
				
		$data = $client->call($operation, $arguments); 
	
		if ($error = $client->getError())
		{
			$this->setError($error);
			return;
		}
	
		return $data;
	}


	public function setError($message)
	{
		$this->error = true;
		$this->errorMessage = $message;
	}
	
	
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
	function isError()
	{
		return $this->error;
	}
	
	function resetError()
	{
		$this->error = false;
		$this->errorMessage = "";
	}
}
?>