<?
	session_start();
	require_once("Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "select current_timestamp() dato";
	$r = mysql_query($s,$l);
	$f = mysql_fetch_object($r);
	
	echo $f->dato;
?>