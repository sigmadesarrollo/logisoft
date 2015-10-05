<?

require("php-barcode.php");

barcode_print($_GET[codigo],"128",$_GET[tamano],"PNG");

?>
