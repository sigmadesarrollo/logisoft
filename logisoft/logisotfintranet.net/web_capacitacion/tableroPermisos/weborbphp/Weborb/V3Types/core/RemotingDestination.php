<?php

class RemotingDestination extends AbstractDestination
{
	public /*String*/ $serviceId;
    public /*String*/ $destinationId;

    public function __construct( /*String*/ $destinationId, /*String*/ $serviceId )
    {
    	$this->destinationId = $destinationId;
        $this->serviceId = $serviceId;
       
    }
}
?>
