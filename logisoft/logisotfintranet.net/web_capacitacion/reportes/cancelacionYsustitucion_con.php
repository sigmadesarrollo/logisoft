<?	
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$farr1 = split("/",$_GET[fecha1]);
		$farr2 = split("/",$_GET[fecha2]);
		
		$fecha1 = $farr1[2]."/".$farr1[1]."/".$farr1[0];
		$fecha2 = $farr2[2]."/".$farr2[1]."/".$farr2[0];
		
		$s = "SELECT hcs.guia, CONCAT_WS(' ', cc1.nombre, cc1.paterno, cc1.materno) AS cliente, 
		DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha, 
		DATE_FORMAT(hcs.fecha, '%d/%m/%Y') AS modifico, 
		IF(gv.condicionpago=1,'Contado','Credito') AS tipopago,
		CONCAT(hcs.accion,' ',IFNULL(hcs.sustitucion,'')) as descripcion, hcs.tipo,
		CONCAT_WS(' ', ce.nombre, ce.apellidopaterno, ce.apellidomaterno) AS usuario, 
		cs.descripcion AS sucursal
		FROM historial_cancelacionysustitucion hcs
		INNER JOIN guiasventanilla gv ON hcs.guia = gv.id
		LEFT JOIN catalogocliente cc1 ON cc1.id = IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
		LEFT JOIN catalogoempleado ce ON hcs.usuario = ce.id
		LEFT JOIN catalogosucursal cs ON hcs.sucursal = cs.id
		WHERE (hcs.accion = 'CANCELADO' OR hcs.accion = 'SUSTITUCION REALIZADA')
		AND hcs.fecha BETWEEN '$fecha1' AND '$fecha2'";
		$r = mysql_query($s,$l) or die($s);
		$datos = array();
		while($f = mysql_fetch_object($r)){
			$datos[] = $f;
		}
		echo json_encode($datos);
		
	}
?>