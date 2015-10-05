<? 
	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	if($_POST[accion]==1){
		if(!empty($_POST[checktodas])){
			$andsuc = "";
		}else{
			if($_POST[sucursalmovio]==0)
				$andsuc = " AND gv.idsucursalorigen = $_POST[sucursal_hidden] ";
			else
				$andsuc = " AND hc.sucursal = $_POST[sucursal_hidden] ";
		}
		
		if($_POST[tipofecha]==0){
			$andfec = " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_POST[inicio])."' AND '".cambiaf_a_mysql($_POST[fin])."' ";
		}else{
			$andfec = " AND hc.fecha BETWEEN '".cambiaf_a_mysql($_POST[inicio])."' AND '".cambiaf_a_mysql($_POST[fin])."' ";
		}
		
		$s = "SELECT gv.id guia, cso.prefijo origen, csd.prefijo destino, DATE_FORMAT(gv.fecha, '%d/%m/%Y') emision,
		DATE_FORMAT(hc.fecha, '%d/%m/%Y') cancelacion, gv.total importe, 
		IF(gv.tipoflete = 0,'PAGADA','POR COBRAR') tipoflete,
		csc.prefijo cancelo, IF(gv.tipoflete=0, cso.prefijo, csd.prefijo) afecta,
		CONCAT(ce.nombre, ' ', ce.apellidopaterno, ' ', ce.apellidomaterno) empleado
		FROM guiasventanilla gv
		INNER JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia
		INNER JOIN catalogosucursal cso ON gv.idsucursalorigen = cso.id
		INNER JOIN catalogosucursal csd ON gv.idsucursaldestino = csd.id
		INNER JOIN catalogosucursal csc ON hc.sucursal = csc.id
		INNER JOIN catalogoempleado ce ON hc.usuario = ce.id
		WHERE (hc.accion = 'SUSTITUCION REALIZADA' OR hc.accion = 'CANCELADO')
		$andsuc $andfec";
		$r = mysql_query($s,$l) or die($s);
		$importes=0;
		if(mysql_num_rows($r)>0){
			$total = mysql_num_rows($r);
			while($f = mysql_fetch_object($r)){
				$importes += $f->importe;
				$f->origen = utf8_encode($f->origen);
				$f->destino = utf8_encode($f->destino);
				$f->cancelo = utf8_encode($f->cancelo);
				$f->afecta = utf8_encode($f->afecta);
				$f->empleado = utf8_encode($f->empleado);
				$arre[] = $f;
			}
		}else{
			$total=0;
			$arre = array();
		}
		echo "({'registros':".json_encode($arre).", 'importes':'".number_format($importes,2,".",",")."', 'total':'$total'})";
	}
?>