<?php
/*
 * Created on Dec 10, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
     interface ITemplateNodeContainer
    {
        /// <summary>
        /// Returns the type of the contained component elements.
        /// </summary>
        /// <returns>Component type in the aggregated collection of child elements</returns>
        /*Type*/ public function getItemClass();

        /// <summary>
        /// Returns the name of the element containing children of the container.
        /// </summary>
        /// <returns>Property name</returns>
        /*String*/ public function getFieldName();
    }
?>
