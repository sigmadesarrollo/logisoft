<?php

/**
 * <tt>ClassInspector</tt>
 *
 * @author <a href="http://www.themidnightcoders.com">Midnight Coders, LLC</a>
 */


class ClassInspector
{
  public static /*ServiceDescriptor*/function inspectClass( ReflectionClass $clazz )
  {
    /*Method[]*/ $methods = $clazz->getMethods();
    /*ServiceDescriptor*/ $serviceDescriptor = new ServiceDescriptor($clazz);

    for( $i = 0, $max = count($methods); $i < $max; $i++ )
    {
      /*MethodDescriptor*/ $descriptor = new MethodDescriptor();
      $descriptor->setMethodName( $methods[ $i ]->getName() );
//      /*Class*/ $returnType = $methods[ $i ].getReturnType();
      $descriptor->setReturnType( "Object" );
      /*ReflectionParameter[]*/ $args = $methods[ $i ]->getParameters();

      for( $k = 0, $arglen = count($args); $k < $arglen; $k++ )
      {
        /*ArgumentDescriptor*/ $argDesc = new ArgumentDescriptor();
        $argDesc->setArgumentName( "arg" . $k );
        $argDesc->setArgumentType( $args[ $k ]->isArray() ? "Array of Object" : $args[ $k ]->getDeclaringClass()->getName() );
        $descriptor->addArgument( $argDesc );
      }

      $serviceDescriptor->addMethod( $descriptor );
    }
    
    return $serviceDescriptor;
  }
}
?>