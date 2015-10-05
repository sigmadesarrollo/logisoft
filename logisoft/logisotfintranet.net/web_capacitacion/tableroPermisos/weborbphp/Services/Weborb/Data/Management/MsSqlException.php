<?php
class MsSqlException extends Exception
{
	public function __construct(/*string*/$message = null, $code = 0 )
	{
		parent::__construct($message, $code);
	}
}
?>