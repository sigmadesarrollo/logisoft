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
		$s = "SELECT * FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."" : '' )."
		GROUP BY idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT IFNULL(SUM(IF(CURDATE()<=vigencia,1,0)),0) AS vigentes,
		IFNULL(SUM(IF(CURDATE() > vigencia,1,0)),0) AS vencidos,
		IFNULL(SUM(IF(CURDATE()<=vigencia,1,0) + IF(CURDATE() > vigencia,1,0)),0) AS total,
		FORMAT(IFNULL(SUM((IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasfacturaconsignacion,0) +
		IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + 
		IFNULL(ventasnofacturaconsignacion,0))),0),2) AS importe
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."" : '' )."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT idsucursal, prefijosucursal AS sucursal, SUM(IF(CURDATE()<=vigencia,1,0)) AS vigentes,
		SUM(IF(CURDATE() > vigencia,1,0)) AS vencidos,
		SUM(IF(CURDATE()<=vigencia,1,0) + IF(CURDATE() > vigencia,1,0)) AS total, 
		SUM((IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasfacturaconsignacion,0) +
		IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + IFNULL(ventasnofacturaconsignacion,0)))AS importe 
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."" : '' )."
		GROUP BY idsucursal ORDER BY prefijosucursal $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->sucursal = cambio_texto($f->sucursal);
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
		
		
	}else if($_GET[accion]==2){//REPORTE DOBLE CLICK IMPORTE
		$s = "SELECT * FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) +
		IFNULL(ventasfacturaconsignacion,0)),2) AS facturado,
		FORMAT(SUM(IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + 
		IFNULL(ventasnofacturaconsignacion,0)),2) AS nofacturado,
		FORMAT(SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasfacturaconsignacion,0) +
		IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + IFNULL(ventasnofacturaconsignacion,0)),2) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT idsucursal, prefijosucursal, SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) +
		IFNULL(ventasfacturaconsignacion,0)) AS facturado, 
		SUM(IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + 
		IFNULL(ventasnofacturaconsignacion,0)) AS nofacturado,
		SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasfacturaconsignacion,0) +
		IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + IFNULL(ventasnofacturaconsignacion,0)) AS totales
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal ORDER BY prefijosucursal $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
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
		
	}else if($_GET[accion]==3){//REPORTE FACTURADO
		$s = "SELECT * FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(ventasfacturanormal,0)),2) AS normales, 
		FORMAT(SUM(IFNULL(ventasfacturaprepagada,0)),2) AS prepagadas,
		FORMAT(SUM(IFNULL(ventasfacturaconsignacion,0)),2) AS consignacion,
		FORMAT(SUM(IFNULL(ventasfacturanormal,0)) + SUM(IFNULL(ventasfacturaprepagada,0)) + 
		SUM(IFNULL(ventasfacturaconsignacion,0)),2) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		/*$s = "SELECT FORMAT(SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasnofacturanormal,0)),2) AS normales,
		FORMAT(SUM(IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasnofacturaprepagada,0)),2) AS prepagadas,
		FORMAT(SUM(IFNULL(ventasfacturaconsignacion,0) + IFNULL(ventasnofacturaconsignacion,0)),2) AS consignacion,
		FORMAT(SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasnofacturanormal,0)) + SUM(IFNULL(ventasfacturaprepagada,0) 
		+ IFNULL(ventasnofacturaprepagada,0)) + SUM(IFNULL(ventasfacturaconsignacion,0) 
		+ IFNULL(ventasnofacturaconsignacion,0)),2) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT idsucursal, prefijosucursal, SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasnofacturanormal,0)) AS normales,
		SUM(IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasnofacturaprepagada,0)) AS prepagadas,
		SUM(IFNULL(ventasfacturaconsignacion,0) + IFNULL(ventasnofacturaconsignacion,0)) AS consignacion,
		SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasnofacturanormal,0)) + SUM(IFNULL(ventasfacturaprepagada,0) +
		IFNULL(ventasnofacturaprepagada,0)) + SUM(IFNULL(ventasfacturaconsignacion,0) 
		+ IFNULL(ventasnofacturaconsignacion,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal ORDER BY prefijosucursal $limite";
		$r = mysql_query($s,$l) or die($s);*/
		$s = "SELECT idsucursal, prefijosucursal, SUM(IFNULL(ventasfacturanormal,0)) AS normales, 
		SUM(IFNULL(ventasfacturaprepagada,0)) AS prepagadas,
		SUM(IFNULL(ventasfacturaconsignacion,0)) AS consignacion,
		SUM(IFNULL(ventasfacturanormal,0)) + SUM(IFNULL(ventasfacturaprepagada,0)) + 
		SUM(IFNULL(ventasfacturaconsignacion,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal ORDER BY prefijosucursal $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
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
		
	}else if($_GET[accion]==4){//REPORTE TOTAL FACTURADO
		$s = "SELECT t4.id FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."'  AND t4.activo = 0
		".(($_GET[sucursal]!="1")?" AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(t4.facturadasnormales,0)),2) AS normales,
		FORMAT(SUM(IFNULL(t4.facturadasprepagadas,0)),2) AS prepagadas,
		FORMAT(SUM(IFNULL(t4.facturadasconsignacion,0)),2) AS consignacion,
		FORMAT(SUM(IFNULL(t4.facturadasnormales,0)) + SUM(IFNULL(t4.facturadasprepagadas,0)) 
		+ SUM(IFNULL(t4.facturadasconsignacion,0)),2) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."'  AND t4.activo = 0
		".(($_GET[sucursal]!="1")?" AND t4.idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t4.prefijosucursal, t4.idcliente, t4.cliente, t4.tipoconvenio, 
		SUM(IFNULL(t4.facturadasnormales,0)) AS normales,
		SUM(IFNULL(t4.facturadasprepagadas,0)) AS prepagadas, 
		SUM(IFNULL(t4.facturadasconsignacion,0)) AS consignacion,
		SUM(IFNULL(t4.facturadasnormales,0) + IFNULL(t4.facturadasprepagadas,0) + IFNULL(t4.facturadasconsignacion,0)) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t4.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idcliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
			$f->tipoconvenio = cambio_texto($f->tipoconvenio);
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
		
	}else if($_GET[accion]==5){//REPORTE TOTAL CLIENTE
		$s = "SELECT * FROM reportecliente5
		WHERE YEAR(fecha) = '".$_GET[fecha]."' AND idcliente = ".$_GET[cliente]." 
		AND factura IS NOT NULL AND estadofactura = 'A'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(IFNULL(SUM(importe),0),2) AS importe FROM reportecliente5
		WHERE YEAR(fecha) = '".$_GET[fecha]."' AND idcliente = ".$_GET[cliente]." 
		AND factura IS NOT NULL AND estadofactura = 'A'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, destinatario, guia, prefijodestino, 
		importe, IFNULL(factura,'') AS factura  FROM reportecliente5
		WHERE YEAR(fecha) = '".$_GET[fecha]."' AND idcliente = ".$_GET[cliente]." 
		AND factura IS NOT NULL AND estadofactura = 'A'";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->destinatario = cambio_texto($f->destinatario);
			$arr[] = $f;
		}
			$registros = str_replace('null','""',json_encode($arr));
		
		$s = "SELECT (SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM catalogocliente WHERE id = ".$_GET[cliente].") AS cliente,
		(SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal].") AS sucursal ";
		$r = mysql_query($s,$l) or die($s);
		$cc= mysql_fetch_object($r);
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"cliente":"'.cambio_texto($cc->cliente).'",
		"sucursal":"'.cambio_texto($cc->sucursal).'",
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==6){//VENTAS CONVENIOS SIN FACTURAR
		/*$s = "SELECT t4.id FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."'
		".(($_GET[sucursal]!="1")?" AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(t4.nofacturadasnormales,0)),2) AS normales,
		FORMAT(SUM(IFNULL(t4.nofacturadasprepagadas,0)),2) AS prepagadas,
		FORMAT(SUM(IFNULL(t4.nofacturadasconsignacion,0)),2) AS consignacion,
		FORMAT(SUM(IFNULL(t4.nofacturadasnormales,0)) + SUM(IFNULL(t4.nofacturadasprepagadas,0)) 
		+ SUM(IFNULL(t4.nofacturadasconsignacion,0)),2) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."'
		".(($_GET[sucursal]!="1")?" AND t4.idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t4.idsucursal, t4.prefijosucursal, t4.idcliente, t4.cliente, t4.tipoconvenio, 
		SUM(t4.nofacturadasnormales) AS normales,
		SUM(t4.nofacturadasprepagadas) AS prepagadas, SUM(t4.nofacturadasconsignacion) AS consignacion,
		SUM(t4.nofacturadasnormales) + SUM(t4.nofacturadasprepagadas) + SUM(t4.nofacturadasconsignacion) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."'
		".(($_GET[sucursal]!="1") ? " AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idcliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
			$arr[] = $f;
		}
			$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';*/
		
		$s = "SELECT * FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(ventasnofacturanormal,0)),2) AS normales, 
		FORMAT(SUM(IFNULL(ventasnofacturaprepagada,0)),2) AS prepagadas,
		FORMAT(SUM(IFNULL(ventasnofacturaconsignacion,0)),2) AS consignacion,
		FORMAT(SUM(IFNULL(ventasnofacturanormal,0)) + SUM(IFNULL(ventasnofacturaprepagada,0)) + 
		SUM(IFNULL(ventasnofacturaconsignacion,0)),2) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT idsucursal, prefijosucursal, SUM(IFNULL(ventasnofacturanormal,0)) AS normales, 
		SUM(IFNULL(ventasnofacturaprepagada,0)) AS prepagadas,
		SUM(IFNULL(ventasnofacturaconsignacion,0)) AS consignacion,
		SUM(IFNULL(ventasnofacturanormal,0)) + SUM(IFNULL(ventasnofacturaprepagada,0)) + 
		SUM(IFNULL(ventasnofacturaconsignacion,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal ORDER BY prefijosucursal $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
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
		
	}else if($_GET[accion]==7){
		$s = "SELECT t1.id FROM reportecliente1 t1
		INNER JOIN solicitudguiasempresariales s ON t1.idcliente = s.idcliente
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND s.factura = 0 AND s.prepagada = 'SI' AND t1.activo = 0
		".(($_GET[sucursal]!="1")?" AND t1.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t1.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(t1.ptotal),2) AS total
		FROM reportecliente1 t1
		INNER JOIN solicitudguiasempresariales s ON t1.idcliente = s.idcliente
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND s.factura = 0 AND s.prepagada = 'SI' AND t1.activo = 0
		".(($_GET[sucursal]!="1")?" AND t1.idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t1.prefijosucursal, t1.idcliente, t1.cliente, IFNULL(t1.pcantidadguia,0) AS cantidad, t1.pfolios,
		IFNULL(t1.pflete,0) AS flete, IFNULL(t1.psobrepeso,0) AS sobrepeso, 
		IFNULL(t1.pcostoseguro,0) AS costoseguro, IFNULL(t1.psubdestinos,0) AS costoead, 
		t1.ptotal AS total
		FROM reportecliente1 t1
		INNER JOIN solicitudguiasempresariales s ON t1.idcliente = s.idcliente
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND s.factura = 0 AND s.prepagada = 'SI' AND t1.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t1.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t1.idcliente $limite";
		/*$s = "SELECT prefijosucursal, idcliente, cliente, IFNULL(pcantidadguia,0) AS cantidad, pfolios,
		IFNULL(pflete,0) AS flete, IFNULL(psobrepeso,0) AS sobrepeso, 
		IFNULL(pcostoseguro,0) AS costoseguro, IFNULL(psubdestinos,0) AS costoead, 
		SUM(IFNULL(pflete,0) + IFNULL(psobrepeso,0) + IFNULL(pcostoseguro,0) + IFNULL(psubdestinos,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idcliente $limite";*/
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
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
		
	}else if($_GET[accion]==8){
		$s = "SELECT id FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(ventasnofacturaconsignacion,0) + IFNULL(pflete,0) + 
		IFNULL(psobrepeso,0) + IFNULL(pcostoseguro,0) + 0),2) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1")?" AND idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT prefijosucursal, idcliente, cliente, IFNULL(ventasnofacturaconsignacion,0) AS porfacturar,
		IFNULL(csobrepeso,0) AS sobrepeso, IFNULL(cvalordeclarado,0) AS valordeclarado,
		IFNULL(csubdestino,0) AS costoead,
		SUM(IFNULL(ventasnofacturaconsignacion,0) + IFNULL(csobrepeso,0) + 
		IFNULL(cvalordeclarado,0) + IFNULL(csubdestino,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1")? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idcliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();		
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
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
	
	}else if($_GET[accion]==9){//REPORTE TOTAL FACTURADO POR SUCURSAL
		$s = "SELECT t4.id FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t4.activo = 0
		".(($_GET[sucursal]!="1")?" AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IFNULL(t4.nofacturadasnormales,0)),2) AS normales,
		FORMAT(SUM(IFNULL(t4.nofacturadasprepagadas,0)),2) AS prepagadas,
		FORMAT(SUM(IFNULL(t4.nofacturadasconsignacion,0)),2) AS consignacion,
		FORMAT(SUM(IFNULL(t4.nofacturadasnormales,0)) + SUM(IFNULL(t4.nofacturadasprepagadas,0)) 
		+ SUM(IFNULL(t4.nofacturadasconsignacion,0)),2) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t4.activo = 0
		".(($_GET[sucursal]!="1")?" AND t4.idsucursal = ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t4.prefijosucursal, t4.idcliente, t4.cliente, t4.tipoconvenio, 
		SUM(IFNULL(t4.nofacturadasnormales,0)) AS normales,
		SUM(IFNULL(t4.nofacturadasprepagadas,0)) AS prepagadas, 
		SUM(IFNULL(t4.nofacturadasconsignacion,0)) AS consignacion,
		SUM(IFNULL(t4.nofacturadasnormales,0) + IFNULL(t4.nofacturadasprepagadas,0) + 
		IFNULL(t4.nofacturadasconsignacion,0)) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t4.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idcliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
			$f->tipoconvenio = cambio_texto($f->tipoconvenio);
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
		
	}else if($_GET[accion]==10){//REPORTE CONVENIOS VIGENTES
		$s = "SELECT t3.* FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE CURDATE() < t3.vencimiento AND YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1")?" AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT COUNT(t3.id) AS total FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE CURDATE() < t3.vencimiento AND YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1")?" AND t1.idsucursal= ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t3.idsucursal, t3.prefijosucursal, t3.idcliente, t3.cliente, t3.tipo, 0 AS precio, 
		DATE_FORMAT(t3.vencimiento,'%d/%m/%Y') AS vencimiento FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE CURDATE() < t3.vencimiento AND YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',		
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'",
		"registros":'.$registros.'})';
		
	}else if($_GET[accion]==11){//REPORTE CONVENIOS VENCIDOS
		$s = "SELECT t3.* FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE CURDATE() > t3.vencimiento AND YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1")?" AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT COUNT(t3.id) AS total FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE CURDATE() > t3.vencimiento AND YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1")?" AND t1.idsucursal= ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t3.idsucursal, t3.prefijosucursal, t3.idcliente, t3.cliente, t3.tipo, 0 AS precio, 
		DATE_FORMAT(t3.vencimiento,'%d/%m/%Y') AS vencimiento FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE CURDATE() > t3.vencimiento AND YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();		
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
			$f->tipo = cambio_texto($f->tipo);
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
		
	}else if($_GET[accion]==12){//REPORTE TIPO CONVENIOS
		$s = "SELECT t3.* FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1")?" AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT COUNT(t3.id) AS total FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1")?" AND t1.idsucursal= ".$_GET[sucursal]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t3.idsucursal, t3.prefijosucursal, t3.idcliente, t3.cliente, t3.tipo, 0 AS precio,
		IF(CURDATE() < t3.vencimiento,'VIGENTE','VENCIDO') AS estatus,
		DATE_FORMAT(t3.vencimiento,'%d/%m/%Y') AS vencimiento FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();		
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->cliente = cambio_texto($f->cliente);
			$f->tipo = cambio_texto($f->tipo);
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
		
	}else if($_GET[accion]==13){//REPORTE HISTORIAL DE CLIENTE
		$s = "SELECT * FROM reportecliente2	t2
		WHERE (YEAR(t2.fechaalta) = '".$_GET[fecha]."' OR YEAR(t2.fechamodificacion) = '".$_GET[fecha]."') 
		AND t2.idcliente = ".$_GET[cliente]." AND t2.activo = 0";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);

		$totales = json_encode(0);
		
		$s = "SELECT DATE_FORMAT(t2.fechaalta,'%d/%m/%Y') AS fechaalta, 
		DATE_FORMAT(t2.fechamodificacion,'%d/%m/%Y') AS fechamodificacion,
		DATE_FORMAT(t2.fechavencimiento,'%d/%m/%Y') AS fechavencimiento, 
		t2.estadocredito, t2.limitecredito, t2.tipoconvenio, 0 AS valorconvenio,
		t2.pesomaximo, t2.preciosobrepeso, t2.idcliente FROM reportecliente2 t2 
		WHERE (YEAR(t2.fechaalta) = '".$_GET[fecha]."' OR YEAR(t2.fechamodificacion) = '".$_GET[fecha]."') 
		AND t2.idcliente = ".$_GET[cliente]." AND t2.activo = 0";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();		
		while($f = mysql_fetch_object($r)){
			$f->estadocredito = cambio_texto($f->estadocredito);
			$f->tipoconvenio = cambio_texto($f->tipoconvenio);
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
		
	}else if($_GET[accion]==14){//SCRIPT INSERTAR CONVENIOS
		$s = "SELECT * FROM generacionconvenio WHERE estadoconvenio ='ACTIVADO' AND YEAR(fecha) = YEAR(CURDATE())";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "CALL proc_RegistroClientes('convenio',0,".$f->folio.",0,0)";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "SELECT cliente, estado, montoautorizado FROM solicitudcredito";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "SELECT IFNULL(MAX(id),0) AS id FROM reportecliente2 WHERE idcliente = ".$f->cliente."";
			$r = mysql_query($s,$l) or die($s); $cc = mysql_fetch_object($r);
			
			if($cc->id==0){
				$s = "INSERT INTO reportecliente2 SET estadocredito = '".$f->estado."', 
				limitecredito = '".$f->montoautorizado."', idcliente = ".$f->cliente."";
				mysql_query($s,$l) or die($s);
			}else{		
				$s = "UPDATE reportecliente2 SET estadocredito = '".$f->estado."', limitecredito = '".$f->montoautorizado."'
				WHERE id = ".$cc->id."";
				mysql_query($s,$l) or die($s);
			}
		}
		
		echo "OK";
	}
?>