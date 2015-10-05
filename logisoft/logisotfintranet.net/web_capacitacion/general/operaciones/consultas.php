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
		$s = "SELECT bs.folio FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN programacionrecepciondiaria prd ON bs.folio=prd.idbitacora
		LEFT JOIN liquidaciongastos lg ON bs.folio=lg.foliobitacora
		WHERE bs.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY bs.folio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(bs.gastos,0)),2) AS viaticos,FORMAT(SUM(IFNULL(lg.gastos,0)),2) AS gastos,
		FORMAT(SUM(IFNULL(bs.gastos,0)-IFNULL(lg.gastos,0)),2) AS utilidad
		FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN programacionrecepciondiaria prd ON bs.folio=prd.idbitacora
		LEFT JOIN liquidaciongastos lg ON bs.folio=lg.foliobitacora
		WHERE bs.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT bs.fecha AS fecharuta,bs.folio AS bitacora,cr.descripcion AS ruta,0 AS gastoruta,
		IFNULL(lg.gastos,0) AS gastotranscurso,IFNULL(bs.gastos,0)-IFNULL(lg.gastos,0) AS utilidad
		FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN programacionrecepciondiaria prd ON bs.folio=prd.idbitacora
		LEFT JOIN liquidaciongastos lg ON bs.folio=lg.foliobitacora
		WHERE bs.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'	
		GROUP BY bs.folio $limite";
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
		//se crea una tabla temporal
		$s = "CREATE TEMPORARY TABLE `tmp_operaciones` (                                                  
		`id` DOUBLE NOT NULL AUTO_INCREMENT,             
		`ruta` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,                     
		`guia` VARCHAR(15) COLLATE utf8_unicode_ci DEFAULT NULL,                                           
		`orden` DOUBLE DEFAULT NULL,
		`sucursal` DOUBLE DEFAULT NULL,                                         
		`importe` DOUBLE DEFAULT NULL,                                         
		`tipo` DOUBLE DEFAULT NULL,                                          
		PRIMARY KEY  (`id`),
		KEY  `guia` (`guia`)                                                   
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//consulta para sacar las guias y insertarlas
		$s = "INSERT INTO tmp_operaciones
		SELECT NULL,cr.descripcion AS ruta,emd.guia,crd.id AS orden,emd.sucursal,0 AS importe,1 AS tipo
		FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN catalogorutadetalle crd ON bs.ruta=crd.ruta
		LEFT JOIN embarquedemercancia em ON bs.folio=em.foliobitacora AND crd.sucursal=em.idsucursal
		LEFT JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque AND crd.sucursal=emd.sucursal
		WHERE bs.folio=".$_GET[bitacora]." AND NOT ISNULL(emd.guia) GROUP BY emd.guia
		UNION
		SELECT NULL,cr.descripcion AS ruta,rmd.guia,crd.id AS orden,rmd.sucursal,0 AS importe,2 AS tipo
		FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN catalogorutadetalle crd ON bs.ruta=crd.ruta
		LEFT JOIN recepcionmercancia rm ON bs.folio=rm.foliobitacora AND crd.sucursal=rm.idsucursal
		LEFT JOIN recepcionmercanciadetalle rmd ON rm.folio=rmd.recepcion AND crd.sucursal=rmd.sucursal
		WHERE bs.folio=".$_GET[bitacora]." AND NOT ISNULL(rmd.guia) GROUP BY rmd.guia;";
		mysql_query($s,$l) or die($s);
		//agregar el importe
		$s = "UPDATE tmp_operaciones temp INNER JOIN guiasventanilla gv ON temp.guia=gv.id
		SET importe=gv.total;";
		mysql_query($s,$l) or die($s);
		$s = "UPDATE tmp_operaciones temp INNER JOIN guiasempresariales ge ON temp.guia=ge.id
		SET importe=ge.total;";
		mysql_query($s,$l) or die($s);
		//fin datos tmp_operaciones
		
		$s = "SELECT COUNT(DISTINCT sucursal) FROM tmp_operaciones";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT SUM(IF(tipo=1,1,0)) AS total1,SUM(IF(tipo=2,1,0)) AS total2,FORMAT(SUM(importe),2) AS total3 FROM tmp_operaciones";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t.ruta,cs.prefijo AS descripcionsucursal,SUM(IF(tipo=1,1,0)) AS guiasembarcadas,
		SUM(IF(tipo=2,1,0)) AS guiasrecibidas, SUM(t.importe) AS importeembarcadas
		FROM tmp_operaciones t INNER JOIN catalogosucursal cs ON t.sucursal=cs.id
		GROUP BY t.sucursal ORDER BY t.orden ";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->ruta = cambio_texto($f->ruta);
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
		$s = "SELECT bs.unidad,bs.ruta,bs.folio AS bitacora,bs.gastos AS viaticos,IFNULL(cl.folio,0) AS folioliquidacion,bs.sucursal
		FROM bitacorasalida bs LEFT JOIN comprobantedeliquidaciondebitacora cl ON bs.folio=cl.foliobitacora AND bs.sucursal=cl.sucursal
		WHERE bs.folio=".$_GET[bitacora]."";
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
