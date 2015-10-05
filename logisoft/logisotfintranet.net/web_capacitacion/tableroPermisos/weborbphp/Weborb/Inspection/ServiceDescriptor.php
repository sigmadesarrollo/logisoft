<?php
/**
 * <tt>ServiceDescriptor</tt>
 * ServiceDescriptor contains an inspection summary of a service. It is used
 * for object inspection and contains a list of methods available in a service
 * as well as service description
 *
 * @author <a href="http://www.themidnightcoders.com">Midnight Coders, LLC</a>
 */


class ServiceDescriptor extends Value
{
  public /*String*/ $address;
  public /*String*/ $version = "1.0";
  public /*MethodDescriptor[]*/ $functions = array();
  public /*String*/ $description = "no description available";
  
  public function __construct(&$object)
  {
  	parent::__construct($object);
  }

  /**
   * Set address of the inspected service. This method should not be used
   * in the implementations. It is called by the framework upon successful
   * inspection
   *
   * @param address address of the inspected service
   */
  public function setAddress( /*String*/ $address )
  {
    $this->address = $address;
  }

  /**
   * Add a method for the service
   *
   * @param method
   */
  public function addMethod( MethodDescriptor $method )
  {
  	$this->functions[] = $method;
//    MethodDescriptor[] newArray = new MethodDescriptor[ functions.length + 1 ];
//    System.arraycopy( functions, 0, newArray, 0, functions.length );
//    functions = newArray;
//    functions[ functions.length - 1 ] = method;
  }

  /**
   * Set service description.
   *
   * @param description
   */
  public function setDescription( /*String*/ $description )
  {
    $this->description = $description;
  }

  /**
   * This is a method override from Value. It should not be used publicly
   *
   * @return
   */
  public /*Object*/function getObject()
  {
    return $this;
  }
  
  public function getName()
  {
  	parent::getObject()->getName();
  }

  /**
   * This method should nt be used publicly, it is here for proper object serialization
   * @return
   */
  public /*String*/function getTypeName()
  {
    return InspectionConstants::SERVICEMETADATA;
  }
  }
?>