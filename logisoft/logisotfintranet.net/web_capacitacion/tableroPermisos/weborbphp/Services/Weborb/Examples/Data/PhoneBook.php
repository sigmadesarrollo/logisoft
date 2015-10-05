<?php
require_once WebOrbServicesPath . 'Weborb/Examples/Data/PhonebookEntry.php';
require_once WebOrbServicesPath . 'Weborb/Examples/Data/PhonebookException.php';

class PhoneBook
{
  
  private $phoneBookRecords = array();
	
  public function __construct()
  {
    if(is_array(Cache::get("phone_book_base")))
    {
    	$this->phoneBookRecords = Cache::get("phone_book_base");
    }
    
    
    
    if(count($this->phoneBookRecords) == 0)
    {
    	$this->createNewContact( "James Bond", "555-5555", "bond@themidnightcoders.com" );
    	$this->createNewContact( "Mickey Mouse", "555-5555", "mickey@disney.com" );
    }
  }
  
  public function __destruct()
  {
  	Cache::put("phone_book_base", $this->phoneBookRecords);
  }

  public function createNewContact( /*String*/ $name, /*String*/ $phoneNumber = null, /*String*/ $email = null )
  {
    if($name instanceof PhonebookEntry)
    {
    	$this->createNewContactFromObj($name);
    	return;
    }
    
    $this->phoneBookRecords[] = new PhonebookEntry($this->generateId(), $name, $phoneNumber, $email);
    
    
  }

  public function createNewContactFromObj( PhonebookEntry $entry )
  {
    $entry->ID = $this->generateId();
  	$this->phoneBookRecords[] = $entry;
  }
  
  public function generateId()
  {
  	$maxVal = 0;
  	foreach($this->phoneBookRecords as $record)
  	{
  		if($record->ID > $maxVal)
  			$maxVal = $record->ID;
  	}
  	$maxVal++;
  	return $maxVal;
  }

  public function editContact( /*int*/ $id, /*String*/ $newName, /*String*/ $newPhoneNumber, /*String*/ $newEmail )
  {
    $index = 'nill';
    for($i = 0; $i<count($this->phoneBookRecords); $i++)
    {
    	if($this->phoneBookRecords[$i]->ID == $id)
    	{
    		$index = $i;
    		break;
    	}
    }
//    var_dump($index);
//	var_dump($index === 'nill');
//     if(LOGGING)
//	 Log::log(LoggingConstants::MYDEBUG, ob_get_contents());
	 
    if($index === 'nill')
    	throw new PhonebookException("There is no record with ID = " . $id);
    	
   
    	
    $this->phoneBookRecords[$index]->setName($newName);
    $this->phoneBookRecords[$index]->setPhoneNumber($newPhoneNumber);
    $this->phoneBookRecords[$index]->setEmailAddress($newEmail);
  }

  /**
   * Delete a contact from the phone book
   *
   * @param contactID
   * @throws PhonebookException
   */
  public function deleteContact( /*int*/ $contactID )
  {
    $index = null;
    for($i = 0; $i<count($this->phoneBookRecords); $i++)
    {
    	if($this->phoneBookRecords[$i]->ID == $contactID)
    	{
    		$index = $i;
    		break;
    	}
    }
    
    if($index == null)
    	throw new PhonebookException("There is no record with ID = " . $id);
    
    unset($this->phoneBookRecords[$index]);
  }

  
  public /*array*/function getAllContacts()
  {
  	return $this->phoneBookRecords;
  }

//  public static void main( String[] args )
//    throws Exception
//  {
//    PhoneBook phoneBook = new PhoneBook();
//
//    phoneBook.createNewContact( "James Bond", "555-5555", "bond@themidnightcoders.com" );
//    phoneBook.createNewContact( "Mickey Mouse", "555-5555", "mickey@disney.com" );
//
//    System.out.println( "\n=============== after crreation ===================" );
//    dumpPhoneBook( phoneBook, true, false );
//
//    System.out.println( "\n=============== after changes =====================" );
//    dumpPhoneBook( phoneBook, false, true );
//
//    System.out.println( "\n=============== after deletion ====================" );
//    dumpPhoneBook( phoneBook, false, false );
//  }

//  private static void dumpPhoneBook( IPhoneBook phoneBook, boolean makeChanges, boolean deleteEntries )
//    throws Exception
//  {
//    ResultSet resultSet = phoneBook.getAllContacts();
//
//    while( resultSet.next() )
//    {
//      int id = resultSet.getInt( 1 );
//      String name = resultSet.getString( 2 );
//      String phone = resultSet.getString( 3 );
//      String email = resultSet.getString( 4 );
//
//      System.out.println( id + "\t\t" + name + "\t\t" + phone + "\t\t" + email );
//
//      if( makeChanges )
//        phoneBook.editContact( id, "Mr. " + name, phone, email );
//
//      if( deleteEntries )
//        phoneBook.deleteContact( id );
//    }
//  }
}

?>