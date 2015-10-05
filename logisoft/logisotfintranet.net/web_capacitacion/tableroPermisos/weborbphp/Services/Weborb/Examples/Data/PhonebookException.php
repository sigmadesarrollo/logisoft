<?php
class PhonebookException extends Exception
{

  public function __construct( /*String*/ $message )
  {
    parent::__construct($message, 0);
  }
}
?>