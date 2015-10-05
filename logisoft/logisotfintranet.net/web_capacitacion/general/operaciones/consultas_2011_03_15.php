<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$paginado = 30;
	$contador = ($_GET[contador]!="")?$_GET[contador]:0;
	$desde	  = ($paginado*$contador);
	$limite = " limit $desde, $paginado ";
	
	function f_adelante($vdesde,$vpaginado,$total){
		if($vdesde+$vpaginado>($total-1))
			return false;
		else
			return true;
	}
	function f_atras($vdesde){
		if($vdesde==0)
			return false;
		else
			return true;
	}
	function f_paginado($vpaginado,$vtotal){
		if($vpaginado>=$vtotal)
			return false;
		else
			return true;
	}

	if($_GET[accion]==1){//PRINCIPAL CLIENTES
		$s = "SELECT * FROM reporteoperaciones1 
		WHERE fecharuta BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(gastoruta,0)),2) AS viaticos,
		FORMAT(SUM(IFNULL(gastotranscurso,0)),2) AS gastos,
		FORMAT(SUM(IFNULL(gastoruta,0) + IFNULL(gastotranscurso,0)),2) AS utilidad
		FROM reporteoperaciones1
		WHERE fecharuta BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(fecharuta,'%d/%m/%Y') AS fecharuta,
		bitacora, ruta, gastoruta, gastotranscurso, utilidad
		FROM reporteoperaciones1
		WHERE fecharuta BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
		
	}else if($_GET[accion]==2){//REPORTE INFORME DE RUTA
		$s = "SELECT IFNULL(COUNT(*),0) AS total FROM recepcionmercancia rm
		INNER JOIN reportedanosfaltante rd ON rm.folio = rd.recepcion
		WHERE rm.foliobitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT b.unidad, CONCAT_WS(' ',ce1.nombre,ce1.apellidopaterno,ce1.apellidomaterno) AS operador1,
		IF(b.conductor2=0,'',CONCAT_WS(' ',ce2.nombre,ce2.apellidopaterno,ce2.apellidomaterno)) AS operador2,
		IF(b.conductor3=0,'',CONCAT_WS(' ',ce3.nombre,ce3.apellidopaterno,ce3.apellidomaterno)) AS operador3,
		".$f->total." AS incidentes, ".$_GET[bitacora]." AS bitacora
		FROM bitacorasalida b
		INNER JOIN catalogoempleado ce1 ON b.conductor1 = ce1.id
		LEFT JOIN catalogoempleado ce2 ON b.conductor2 = ce2.id
		LEFT JOIN catalogoempleado ce3 ON b.conductor3 = ce3.id
		WHERE b.folio = ".$_GET[bitacora]."";		
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->unidad = cambio_texto($f->unidad);
				$f->conductor1 = cambio_texto($f->conductor1);
				$f->conductor2 = cambio_texto($f->conductor2);
				$f->conductor3 = cambio_texto($f->conductor3);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==3){//REPORTE DE INCIDENTES
		$s = "SELECT * FROM recepcionmercancia rm
		INNER JOIN reportedanosfaltante rd ON rm.folio = rd.recepcion
		WHERE rm.foliobitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT DATE_FORMAT(rd.fecha,'%d/%m/%Y') AS fecha,
		IF(rd.dano=1,'DAÑO',IF(rd.faltante=1,'FALTANTE',IF(rd.sobrante=1,'SOBRANTE',''))) AS tipoincidente,
		".$_GET[bitacora]." AS bitacora, cs.prefijo AS sucursal,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS operador
		FROM recepcionmercancia rm
		INNER JOIN reportedanosfaltante rd ON rm.folio = rd.recepcion
		INNER JOIN catalogosucursal cs ON rd.sucursal = cs.id
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		WHERE rm.foliobitacora = ".$_GET[bitacora]." $limit";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->incidencia = cambio_texto($f->incidencia);
			$f->prefijo = cambio_texto($f->prefijo);
			$f->operador = cambio_texto($f->operador);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	
	}else if($_GET[accion]==4){//PRODUCTIVIDAD POR RUTA	
		$s = "SELECT descripcionruta, descripcionsucursal,guiasembarcadas,
		guiasrecibidas,importeembarcadas,bitacora,folioembarque 
		FROM reporteoperaciones2
		WHERE bitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT SUM(IFNULL(guiasembarcadas,0)) AS total1,
		SUM(IFNULL(guiasrecibidas,0)) AS total2,
		FORMAT(SUM(IFNULL(importeembarcadas,0)),2) AS total3
		FROM reporteoperaciones2
		WHERE bitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT descripcionruta as ruta, descripcionsucursal,guiasembarcadas,
		guiasrecibidas,importeembarcadas,bitacora,folioembarque, sucursal as idsucursal FROM reporteoperaciones2
		WHERE bitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->descripcionruta = cambio_texto($f->descripcionruta);
			$f->descripcionsucursal = cambio_texto($f->descripcionsucursal);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	
	}else if($_GET[accion]==5){//GASTOS POR RUTA 
		$s = "SELECT unidad, ruta, bitacora, viaticos, IFNULL(folioliquidacion,0) AS folioliquidacion,
		IFNULL(sucursalfolioliquidacion,0) AS sucursal
		FROM reporteoperaciones3 WHERE bitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		$registros = "";
		while($f = mysql_fetch_object($r)){
			$f->unidad = cambio_texto($f->unidad);			
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		$s = "SELECT d.concepto, d.cantidad FROM comprobantedeliquidaciondebitacora c
		INNER JOIN comprobantedeliquidaciondebitacoradetalle d ON c.folio = d.comprobantedeliquida AND c.sucursal = d.sucursal
		WHERE c.foliobitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		$registros2 = "";
		while($f = mysql_fetch_object($r)){
			$f->concepto = cambio_texto($f->concepto);			
			$arre[] = $f;
		}
		
		$registros2 = str_replace('null','""',json_encode($arre));
		
		echo '({"registros":'.$registros.', "registros2":'.$registros2.'})';
	}

?>
