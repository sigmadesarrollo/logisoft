<?php
interface IInspectionHandler
{
  /**
   * Inspect a service with address and return its description. If inspection
   * is not possible by the inspector, the method should return null. If an inspection
   * error occurs due to a problem with the service, the method should throw ServiceException
   *
   * @param targetObject address of the service to inspect
   * @return ServiceDescriptor
   * @throws ServiceException if a service error occurs during inspection
   */
  public /*ServiceDescriptor*/ function inspect( /*String*/ $targetObject );

  
}
?>