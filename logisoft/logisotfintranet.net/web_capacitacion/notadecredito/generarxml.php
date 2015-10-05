<?
	header("Content-Disposition: attachment; filename=certificado.xml");
	
	require_once("../ConectarSolo.php");
	$l = Conectarse('webpmm');
	
	$s = "SELECT xml FROM notacredito WHERE folio = '$_GET[folio]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	echo $f->xml;
?>