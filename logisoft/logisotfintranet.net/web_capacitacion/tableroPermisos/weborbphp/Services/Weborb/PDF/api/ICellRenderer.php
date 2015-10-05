<?php
/*
 * Created on Dec 10, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
    interface ICellRenderer
    {
        /// <summary>
        /// Returns a component to be rendered in a cell identified by the row and column 
        /// parameters. The value argument comes from the data provider of the table that uses
        /// the cell renderer.
        /// </summary>
        /// <param name="value">Value to be rendered.</param>
        /// <param name="cellFont">Optional font to be used for text rendering</param>
        /// <param name="row">Row of the cell the renderer should fill with data</param>
        /// <param name="column">Column of the cell the renderer should fill with data</param>
        /// <returns></returns>
        /*IDataCellComponent*/public function getComponent( /*String*/ $value, /*Font*/ $cellFont, /*int*/ $row, /*int*/ $column );
    } 
?>
