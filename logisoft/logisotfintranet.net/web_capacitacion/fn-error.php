<?
function postError($e){
	$fecha_hora=date("d-m-Y (h:i a)");
	echo $e;
	$e=ereg_replace( "<br>", "\r\n", $e );
	
	echo "<br><br>Valores enviados con POST:<br><br>";
	reset ($_POST);
	$postv="";
	while (list ($clave, $val) = each ($_POST)) {
		echo "$clave => $val<br>";
		$postv.="$clave => $val\r\n";
	} 
	echo "<br><br>Valores enviados con GET:<br><br>";
	reset ($_GET);
	while (list ($clave, $val) = each ($_GET)) {
		echo "$clave => $val<br>";
		$getv.="$clave => $val\r\n";
	} 
	
	mail("ipartida@tecnika.com.mx", "ERROR en modulo ${_SERVER['SCRIPT_NAME']}", 
			"\r\n{$_SERVER['SERVER_NAME']}\r\n\r\nFecha (Hora): $fecha_hora \r\n\r\nUsuario: ".$_SESSION[NOMBREUSUARIO]."\r\n\r\n\r\n Consulta:\r\n $e \r\n\r\n POST:\r\n $postv GET \r\n $getv","From: pmmintranet.net\r\n");
	
	mail("lortega@tecnika.com.mx", "ERROR en modulo ${_SERVER['SCRIPT_NAME']}", 
			"\r\n{$_SERVER['SERVER_NAME']}\r\n\r\nFecha (Hora): $fecha_hora \r\n\r\nUsuario: ".$_SESSION[NOMBREUSUARIO]."\r\n\r\n\r\n Consulta:\r\n $e \r\n\r\n POST:\r\n $postv GET: \r\n $getv","From: pmmintranet.net\r\n");
	
	exit;
}
?>