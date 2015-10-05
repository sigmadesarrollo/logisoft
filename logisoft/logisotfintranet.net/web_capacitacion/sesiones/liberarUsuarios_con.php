<?
	session_start();
	require_once('../Conectar.php');
	$l=Conectarse('webpmm'); 
	
	if($_GET[accion]==1){
		$s = "SELECT se.idusuario, CONCAT_WS(' ', ce.nombre, ce.apellidopaterno, ce.apellidomaterno) AS empleado,
		ce.user AS usuario, DATE_FORMAT(se.fecha, '%d/%m/%Y  %H:%i') AS fecha, se.ip
		FROM catalogoempleado AS ce
		INNER JOIN sesiones AS se ON ce.id = se.idusuario";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->empleado = cambio_texto($f->empleado);
			$f->usuario = cambio_texto($f->usuario);
			$arre[] = $f;
		}
		echo str_replace("null", '""', json_encode($arre));
	}
	
	if($_GET[accion]==2){
		$s = "DELETE FROM sesiones WHERE idusuario = $_GET[idusuario]";
		mysql_query($s,$l) or die($s);
		
		echo "desbloqueado";
	}
?>