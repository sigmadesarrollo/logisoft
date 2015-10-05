<?php
/**
 * <tt>ArgumentDescriptor</tt>
 * ArgumentDescriptor describes a method argument in an inspection response. Argument
 * descriptors should be added to method descriprors (see MethodDescriptor) using
 * MethodDescriptor.addArgument()
 *
 * @author <a href="http://www.themidnightcoders.com">Midnight Coders, LLC</a>
 */

class ArgumentDescriptor
{
  public /*String*/ $name;
  public /*String*/ $description = "none";
  public /*String*/ $type;
  public /*boolean*/ $required = true;


  /**
   * Set argument name
   *
   * @param name name of the argument
   */
  public /*void*/function setArgumentName( /*String*/ $name )
  {
    $this->name = $name;
  }

  /**
   * Set argument type. For example, for a java object, argument type is the class name
   *
   * @param type type of the argument
   */
  public function setArgumentType( /*String*/ $type )
  {
    $this->type = $type;
  }

  /**
   * Set argument description
   *
   * @param description description of the argument
   */
  public function setDescription( /*String*/ $description )
  {
    $this->description = $description;
  }

  /**
   * Set a flag indicating whether the argument is required. By default this value is true
   *
   * @param isRequired
   */
  public function setIsRequired( /*boolean*/ $isRequired )
  {
    $this->required = $isRequired;
  }

  /**
   * This method is not intended for public use. It is required in
   * this class for the implementation of INamedType
   * @return String name of the type
   */
  public /*String*/function getTypeName()
  {
    return InspectionConstants::ARGUMENTMETADATA;
  }
  }
?>