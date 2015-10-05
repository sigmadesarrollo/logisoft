<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET[accion]==1){//OBTENER DATOS GENERALES -MODULOQUEJASDAÑOSFALTANTES.PHP
		$s = "SELECT mq.folio,cs.prefijo AS sucursal,DATE_FORMAT(mq.fecharegistro,'%d/%m/%Y') AS fecha,
		CONCAT( ce.nombre, ' ', ce.apellidopaterno, ' ', ce.apellidomaterno ) AS nombre,
		mq.observaciones, DATE_FORMAT(mq.fechaposible,'%d/%m/%Y') AS fechaposible,
		mq.observacionfechaposible, DATE_FORMAT(mq.fechaposible,'%m/%d/%Y') AS fechaparacomparar
		FROM moduloquejasdanosfaltantes mq
		INNER JOIN catalogosucursal cs ON cs.id=mq.idsucursal
		INNER JOIN catalogoempleado ce ON ce.id=mq.idresponsable
		WHERE mq.estado <> 'SOLUCIONADO'";
		$r = mysql_query($s, $l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$f->sucursal=cambio_texto($f->sucursal);
			$f->quejas=cambio_texto($f->quejas);
			$f->observaciones=cambio_texto($f->observaciones);
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==2){//FECHA POSIBLE Y OBSERVACIONES bitacoraQuejasDañosYfaltantes
		$row = split(",",$_GET[arre]);
		$s = "UPDATE moduloquejasdanosfaltantes SET
			observacionfechaposible='".$row[1]."',
			fechaposible='".cambiaf_a_mysql($row[0])."',
			idusuario = '".$_SESSION[IDUSUARIO]."' , 
			usuario = '".$_SESSION[NOMBREUSUARIO]."' , 
			fecha = CURRENT_DATE
			WHERE folio = '".$_GET[folio]."'";
		$r = mysql_query($s,$l) or die($s);
		echo "ok";
	}
	
?>
