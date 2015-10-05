<?
	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	$s = "SELECT pt.nombre
	FROM permisos_grupos AS pt
	INNER JOIN catalogoempleado AS ce ON pt.id = ce.grupo
	WHERE ce.id = $_SESSION[IDUSUARIO]";
	$r = mysql_query($s,$l) or die($s);
	$fx = mysql_fetch_object($r);
	
	$s = "select concat_ws(' ',nombre,apellidopaterno,apellidomaterno) as nombre from catalogoempleado where id = $_SESSION[IDUSUARIO]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
?>
<titulos>
    <titulo><?=$fx->nombre?></titulo>
    <usuario><?=ucwords(strtolower($f->nombre))?></usuario>
</titulos>