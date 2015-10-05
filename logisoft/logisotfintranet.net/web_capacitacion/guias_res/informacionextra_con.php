<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT DATE_FORMAT(cg.fecha, '%d/%m/%Y') AS fecha, cg.comentario, 
		CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) AS empleado,
		hora
		FROM comentarios_guias AS cg
		INNER JOIN catalogoempleado AS ce ON cg.usuario = ce.id
		WHERE folioguia = '$_GET[folio]'
		order by cg.fecha desc, hora desc";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->comentario = cambio_texto($f->comentario);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==2){
		$s = "INSERT INTO comentarios_guias SET folioguia='$_GET[folio]', comentario='$_GET[comentario]', 
		usuario=$_SESSION[IDUSUARIO], fecha = CURRENT_DATE, hora=current_time";
		mysql_query($s,$l) or die($s);
		
		echo "guardo";
	}
?>