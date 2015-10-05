<?
	function postError($e){
		$fecha_hora=date("d-m-Y (h:i a)");
		echo $e;
		$e=ereg_replace( "<br>", "\r\n", $e );
		
		reset ($_POST);
		$postv="";
		
		while (list ($clave, $val) = each ($_POST)) {
			$postv.="$clave => $val\r\n";
		}
		
		reset ($_GET);
		$getv="";
		while (list ($clave, $val) = each ($_GET)) {
			$getv.="$clave => $val\r\n";
		} 
		
		mail("ipartida@tecnika.com.mx", "ERROR en modulo ${_SERVER['SCRIPT_NAME']}", 
				"\r\n{$_SERVER['SERVER_NAME']}\r\n\r\nFecha (Hora): $fecha_hora \r\n\r\nUsuario: ".$_SESSION[NOMBREUSUARIO]."\r\n\r\n\r\n Consulta:\r\n $e \r\n\r\n POST:\r\n $postv GET \r\n $getv","From: pmmintranet.net\r\n");
		
		mail("lortega@tecnika.com.mx", "ERROR en modulo ${_SERVER['SCRIPT_NAME']}", 
				"\r\n{$_SERVER['SERVER_NAME']}\r\n\r\nFecha (Hora): $fecha_hora \r\n\r\nUsuario: ".$_SESSION[NOMBREUSUARIO]."\r\n\r\n\r\n Consulta:\r\n $e \r\n\r\n POST:\r\n $postv GET: \r\n $getv","From: pmmintranet.net\r\n");
		
		exit;
	}
?>