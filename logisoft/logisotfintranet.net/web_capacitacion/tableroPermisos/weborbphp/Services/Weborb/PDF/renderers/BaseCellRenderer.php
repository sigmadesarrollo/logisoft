<?php
/*
 * Created on Dec 10, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 if (!defined("BASE_PDF_SERVICE_PATH")) define("BASE_PDF_SERVICE_PATH","Weborb/");

 require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/api/ICellRenderer.php';
 require_once WebOrbServicesPath . BASE_PDF_SERVICE_PATH . 'PDF/model/Text.php';
 
 class BaseCellRenderer implements ICellRenderer
 {
 	public function getComponent( /*String*/ $value, /*Font*/ $cellFont, /*int*/ $row, /*int*/ $column )
 	{
 		$text = new Text();
 		$text->setFont($cellFont);
 		$text->value = $value;
 		return $text;
 	}
 }
?>
