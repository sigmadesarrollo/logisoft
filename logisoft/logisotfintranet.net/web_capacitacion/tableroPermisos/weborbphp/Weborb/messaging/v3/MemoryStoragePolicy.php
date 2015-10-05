<?php


class MemoryStoragePolicy implements IMessageStoragePolicy
{
  private /*ArrayList*/ $messages = array();

  public function addMessage( /*PendingMessage*/ $message )
  {
      array_push($this->messages, $message );
  }

  public /*ArrayList*/ function getMessages( /*Subscriber*/ $subscriber )
  {
    /*LinkedList*/ $prefiltered = array();
    /*long*/ $lastTimeSubscriberWasHere = $subscriber->getLastRequestTime();


      for($i = count($this->messages) - 1; $i >= 0; $i-- )
      {
        /*PendingMessage*/$message = $this->messages[$i];
      	if( $message->getTimestamp() > $lastTimeSubscriberWasHere)
      	{
      		array_push($prefiltered, $message->getMessage() );
      	}
        else
        {
          break;
        }

      }
      $prefiltered = array_reverse($prefiltered);

    return $subscriber->filterMessages( $prefiltered );
  }

  public function cleanUp()
  {
    /*ArrayList*/ $notValidMessages = array();
    /*long*/ $currentTime = microtime(true);

    for( $i = 0, $max = count($this->messages); $i < $max; $i++ )
      if( $currentTime - $this->messages[$i]->timestamp > 1000 * 300 )
        array_push($notValidMessages, $this->messages[$i]);

    for( $i = 0, $max = count($notValidMessages); $i < $max; $i++ )
    {
      $k = array_search($notValidMessages[$i], $this->messages);
      unset($this->messages[$k]);
      $this->messages = array_values($this->messages);
    }
    unset($notValidMessages);
  }



}
?>