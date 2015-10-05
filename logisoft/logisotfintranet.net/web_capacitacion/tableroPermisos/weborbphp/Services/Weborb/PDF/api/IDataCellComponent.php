<?php
/*
 * Created on Dec 10, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
    interface IDataCellComponent
    {
        /// <summary>
        /// Returns content to be rendered in a table cell. Can be an instance of the
        /// following types:
        /// <list type="bullet">
        ///     <item>
        ///         <term>iTextSharp.text.Image</term>
        ///         <description>An image to be included into the document</description>
        ///     </item>
        ///     <item>
        ///         <term>iTextSharp.text.pdf.PdfPTable</term>
        ///         <description>A table component</description>
        ///     </item>
        ///     <item>
        ///         <term><see cref="System.String"/></term>
        ///         <description>A string of text</description>
        ///     </item>
        /// </list>
        /// </summary>
        /// <returns></returns>
        /*Object*/ public function getContent();

        /// <summary>
        /// Returns a Font object to be used for text rendering. Used when <see cref="getContent"/> returns String.
        /// </summary>
        /// <returns>An instance of the iTextSharp.text.Font class</returns>
        /*Font*/ public function getFont();
    } 
?>
