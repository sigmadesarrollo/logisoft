<?
	require_once("../web/Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "select * from catalogosucursal where id > 1";
	$r = mysql_query($s,$l);
	while($f = mysql_fetch_object($r)){
		echo "<option value='$f->id'>$f->descripcion</option>";
	}
?>