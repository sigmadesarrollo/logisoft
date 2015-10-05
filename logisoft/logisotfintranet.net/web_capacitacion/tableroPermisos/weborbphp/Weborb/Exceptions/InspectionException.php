<?php
/**
 * <tt>InspectionException</tt>
 *
 * @author <a href="http://www.themidnightcoders.com">Midnight Coders, LLC</a>
 */

class InspectionException extends Exception
{

  const /*long*/ serialVersionUID = "1L";

  public function InspectionException( /*String*/ $service )
  {
  	parent::__construct("Unable inspect service" . $service, 0);    
  }
}
?>