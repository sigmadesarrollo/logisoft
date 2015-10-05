<?php 
/**
 * <tt>MethodDescriptor</tt>
 * MethodDescriptor class describes a method in a service inspection response. A method
 * descriptor contains a collection of argument descriptors. A method descriptor must be
 * added to an instance of ServiceDescriptor using ServiceDescriptor.addMethod()
 *
 * @author <a href="http://www.themidnightcoders.com">Midnight Coders, LLC</a>
 */


class MethodDescriptor
{
  public /*String*/ $name;
  public /*String*/ $version = "1.0";
  public /*ArgumentDescriptor[]*/ $arguments = array();
  public /*String*/ $returns;
  public /*String*/ $description;

  /**
   * Set method name
   *
   * @param name
   */
  public function setMethodName( /*String*/ $name )
  {
    $this->name = $name;
  }

  /**
   * Set method description
   *
   * @param description
   */
  public function setDescription( /*String*/ $description )
  {
    $this->description = $description;
  }

  /**
   * Set return type
   *
   * @param returnType
   */
  public function setReturnType( /*String*/ $returnType )
  {
    $this->returns = $returnType;
  }

  /**
   * Add an argument for this method
   *
   * @param argument
   */
  public function addArgument( ArgumentDescriptor $argument )
  {
  	$this->arguments[] = $argument;
//    /*ArgumentDescriptor[]*/ $newArray = new ArgumentDescriptor[ arguments.length + 1 ];
//    System.arraycopy( arguments, 0, newArray, 0, arguments.length );
//    arguments = newArray;
//    arguments[ arguments.length - 1 ] = argument;
  }

  /**
   * This method is not intended for public use. It is required in
   * this class for the implementation of INamedType
   * @return String name of the type
   */
  public /*String*/function getTypeName()
  {
    return InspectionConstants::FUNCTIONMETADATA;
  }
  }
?>
