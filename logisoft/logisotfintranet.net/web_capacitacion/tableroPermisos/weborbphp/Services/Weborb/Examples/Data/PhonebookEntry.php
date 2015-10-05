<?php
class PhonebookEntry
{
  public /*int*/ $ID;
  public /*String*/ $name;
  public /*String*/ $phoneNumber;
  public /*String*/ $emailAddress;
  
  public function __construct($ID = null, $name = null, $phoneNumber = null, $emailAddress = null)
  {
  	$this->ID = $ID;
  	$this->name = $name;
  	$this->phoneNumber = $phoneNumber;
  	$this->emailAddress = $emailAddress;
  }

  public /*String*/ function getName()
  {
    return $this->name;
  }

  public function setName( /*String*/ $name )
  {
    $this->name = $name;
  }

  public /*String*/function getPhoneNumber()
  {
    return $this->phoneNumber;
  }

  public function setPhoneNumber( /*String*/ $phoneNumber )
  {
    $this->phoneNumber = $phoneNumber;
  }

  public /*String*/function getEmailAddress()
  {
    return $this->emailAddress;
  }

  public function setEmailAddress( /*String*/ $emailAddress )
  {
    $this->emailAddress = $emailAddress;
  }
}
?>