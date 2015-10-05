<?php
class ShoppingCart
{
  private /*Vector*/ $items = array();

  public /*String*/function addItem( /*String*/ $itemName )
  {
    $_SESSION['shopping_cart_items'][] = $itemName; 
    
    var_dump($_SESSION['shopping_cart_items']);
    if(LOGGING)
    	Log::log(LoggingConstants::MYDEBUG, ob_get_contents());
    return $itemName;
  }

  public /*Vector*/function getItems()
  {
    return $_SESSION['shopping_cart_items'];
  }
}
?>