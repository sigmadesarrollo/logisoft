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
		$s = "SELECT idsucorigen 
		FROM reportes_ventas 
		WHERE IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion)='".$_GET[fecha]."',YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) GROUP BY idsucorigen";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT IFNULL(SUM(IF(t1.estadoconvenio='ACTIVADO' AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0) AS vigentes,
		IFNULL(SUM(IF(t1.estadoconvenio='EXPIRADO' AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0) AS vencidos,
		IFNULL(SUM(IF((t1.estadoconvenio='EXPIRADO' OR t1.estadoconvenio='ACTIVADO') AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0) total,
		FORMAT(t2.timporte,2) importe, t2.sucursal, t1.sucursal as idsucursal 
		FROM generacionconvenio t1
		INNER JOIN (
		SELECT SUM(IF(tipoventa!='GUIA EMPRESARIAL',total,0) + IF(tipoventa='GUIA EMPRESARIAL',total,0)) timporte,prefijoorigen AS sucursal,idsucorigen 
		FROM reportes_ventas 
		WHERE IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion)='".$_GET[fecha]."',YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0)) AS t2 ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT IFNULL(SUM(IF(t1.estadoconvenio='ACTIVADO' AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0) AS vigentes,
		IFNULL(SUM(IF(t1.estadoconvenio='EXPIRADO' AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0) AS vencidos,
		IFNULL(SUM(IF((t1.estadoconvenio='EXPIRADO' OR t1.estadoconvenio='ACTIVADO') AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0) total,
		FORMAT(t2.timporte,2) importe, t2.sucursal, t1.sucursal as idsucursal 
		FROM generacionconvenio t1
		INNER JOIN (
		SELECT SUM(IF(tipoventa!='GUIA EMPRESARIAL',total,0) + IF(tipoventa='GUIA EMPRESARIAL',total,0)) timporte,prefijoorigen AS sucursal,idsucorigen 
		FROM reportes_ventas 
		WHERE IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion)='".$_GET[fecha]."',YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) GROUP BY sucursal) AS t2 ON t1.sucursal=t2.idsucorigen
		GROUP BY t2.sucursal ORDER BY t2.sucursal $limite";
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
		
		
	}else if($_GET[accion]==2){//VENTAS POR CONVENIO
		$s = "SELECT t0.id,SUM(t1.total) FROM catalogosucursal t0
		LEFT JOIN(SELECT idsucorigen,total FROM reportes_ventas WHERE IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0)
		GROUP BY idsucorigen) t1 ON t0.id=t1.idsucorigen WHERE t1.total>0 GROUP BY idsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT (t0.id) AS idsucursal,(t0.prefijo) AS prefijosucursal,FORMAT(SUM(IFNULL(t1.tfacturado,0)),2) AS facturado,
		FORMAT(SUM(IFNULL(t1.tnofacturado,0)),2) AS nofacturado,
		FORMAT(SUM(IFNULL(t1.tfacturado,0)) + SUM(IFNULL(t1.tnofacturado,0)),2) AS total
		FROM(SELECT id,prefijo FROM catalogosucursal) t0
		LEFT JOIN(SELECT SUM(IF(NOT ISNULL(factura),total,0)) AS tfacturado, idsucorigen,
		SUM(IF(ISNULL(factura),total,0)) AS tnofacturado FROM reportes_ventas WHERE IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0)) t1 ON t0.id=t1.idsucorigen";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT (t0.id) AS idsucursal,(t0.prefijo) AS prefijosucursal,FORMAT(SUM(IFNULL(t1.tfacturado,0)),2) AS facturado,
		FORMAT(SUM(IFNULL(t1.tnofacturado,0)),2) nofacturado,FORMAT(SUM(IFNULL(t1.tfacturado,0)) + SUM(IFNULL(t1.tnofacturado,0)),2) totales
		FROM catalogosucursal t0
		LEFT JOIN(SELECT SUM(IF(NOT ISNULL(factura),total,0)) AS tfacturado, idsucorigen,
		SUM(IF(ISNULL(factura),total,0)) AS tnofacturado FROM reportes_ventas WHERE IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0)
		GROUP BY idsucorigen) t1 ON t0.id=t1.idsucorigen WHERE t1.tfacturado>0 OR t1.tnofacturado>0 GROUP BY idsucursal ORDER BY prefijosucursal $limite";
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
		
	}else if($_GET[accion]==3){//VENTAS CON COVENIO FACTURADAS
		$s = "SELECT id FROM reportes_ventas	WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) GROUP BY idsucorigen";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IF(tipoventa='GUIA VENTANILLA',total,0)),2) AS normales, 
		FORMAT(SUM(IF(tipoventa='SOLICITUD DE FOLIOS',total,0)),2) AS prepagadas,
		FORMAT(SUM(IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0)),2) AS consignacion,
		FORMAT(SUM(IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)),2) AS otros,
		FORMAT(SUM(IF(tipoventa='GUIA VENTANILLA',total,0) + IF(tipoventa='SOLICITUD DE FOLIOS',total,0) +
		IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0) + 
		IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)),2) AS total		
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura))";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT FORMAT(SUM(IF(tipoventa='GUIA VENTANILLA',total,0)),2) AS normales, (idsucorigen) AS idsucursal,
		FORMAT(SUM(IF(tipoventa='SOLICITUD DE FOLIOS',total,0)),2) AS prepagadas, (prefijoorigen) AS prefijosucursal,
		FORMAT(SUM(IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0)),2) AS consignacion,
		FORMAT(SUM(IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)),2) AS otros,
		FORMAT(SUM(IF(tipoventa='GUIA VENTANILLA',total,0) + IF(tipoventa='SOLICITUD DE FOLIOS',total,0) +
		IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0) + 
		IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)),2) AS total		
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) GROUP BY idsucursal ORDER BY prefijosucursal $limite";
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
		
	}else if($_GET[accion]==4){//VENTAS CON COVENIO FACTURADAS POR CLIENTE
		$s = "SELECT t1.idsucorigen, t2.idcliente FROM reportes_ventas t1 INNER JOIN generacionconvenio t2 
		ON t1.idsucorigen=t2.sucursal AND t1.idcliente=t2.idcliente
		WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) AND idsucorigen=".$_GET[sucursal]." GROUP BY idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(IF(tipoventa='GUIA VENTANILLA',total,0)),2) AS normales, 
		FORMAT(SUM(IF(tipoventa='SOLICITUD DE FOLIOS',total,0)),2) AS prepagadas,
		FORMAT(SUM(IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0)),2) AS consignacion,
		FORMAT(SUM(IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)),2) AS otros,
		FORMAT(SUM(IF(tipoventa='GUIA VENTANILLA',total,0) + IF(tipoventa='SOLICITUD DE FOLIOS',total,0) +
		IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0) + 
		IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)),2) AS total		
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) AND idsucorigen=".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT FORMAT(SUM(IF(t1.tipoventa='GUIA VENTANILLA',total,0)),2) AS normales, t1.idsucorigen,
		FORMAT(SUM(IF(t1.tipoventa='SOLICITUD DE FOLIOS',total,0)),2) AS prepagadas, (prefijoorigen) AS prefijosucursal,
		FORMAT(SUM(IF(t1.tipoventa='GUIA EMPRESARIAL' AND t1.tipoempresarial='CONSIGNACION',total,0)),2) AS consignacion,
		FORMAT(SUM(IF(t1.tipoventa='FACTURA OTROS' OR t1.tipoventa='FACTURA EXCEDENTE',total,0)),2) AS otros,
		FORMAT(SUM(IF(t1.tipoventa='GUIA VENTANILLA',total,0) + IF(t1.tipoventa='SOLICITUD DE FOLIOS',total,0) +
		IF(t1.tipoventa='GUIA EMPRESARIAL' AND t1.tipoempresarial='CONSIGNACION',total,0) + 
		IF(t1.tipoventa='FACTURA OTROS' OR t1.tipoventa='FACTURA EXCEDENTE',total,0)),2) AS total,
		CONCAT(if(t2.precioporkg=1,'KG, ',''),if(t2.precioporcaja=1,'CAJA, ',''),if(t2.descuentosobreflete=1,'DESCUENTO, ',''),
		if(t2.prepagadas=1,'PREPAGADAS, ',''),if(t2.consignacionkg=1,'C. KG, ',''),if(t2.consignacioncaja=1,'C. CAJA, ',''),
		if(t2.consignaciondescuento=1,'C. DESC., ',''),if(t2.consignaciondescantidad=1,'C. DESC. CANT., ','')) AS tipoconvenio,	
		t2.idcliente, CONCAT(t2.nombre,' ',t2.apaterno,' ',t2.amaterno) as cliente
		FROM reportes_ventas t1 INNER JOIN generacionconvenio t2 ON t1.idsucorigen=t2.sucursal AND t1.idcliente=t2.idcliente
		WHERE YEAR(t1.fecharealizacion) = '".$_GET[fecha]."' AND IF(t1.tipoventa <> 'GUIA VENTANILLA',
		YEAR(t1.fechafacturacion)='".$_GET[fecha]."', YEAR(t1.fecharealizacion)='".$_GET[fecha]."') AND t1.activo='S' AND
		(NOT ISNULL(t1.convenio) AND t1.convenio!=0) AND (NOT ISNULL(t1.factura)) AND t1.idsucorigen=".$_GET[sucursal]."
		GROUP BY t1.idcliente ORDER BY prefijosucursal $limite";
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
		
	}else if($_GET[accion]==5){//RELACION DE ENVIOS FACTURADOS POR CLIENTE
		$s = "SELECT id FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND 
		IF(tipoventa <> 'GUIA VENTANILLA', YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) AND 
		idcliente = ".$_GET[cliente]." AND idsucorigen=".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(total),2) AS importe FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND 
		IF(tipoventa <> 'GUIA VENTANILLA', YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) AND 
		idcliente =".$_GET[cliente]." AND idsucorigen=".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(fecharealizacion,'%d/%m/%Y') AS fecha, (folio) as guia, (recibe) AS destinatario, prefijodestino, 
		FORMAT(total,2) AS importe, factura	FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND 
		IF(tipoventa <> 'GUIA VENTANILLA', YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) AND 
		idcliente =".$_GET[cliente]." AND idsucorigen=".$_GET[sucursal]." $limite";
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
		/* tabla temp */
		$s = "CREATE TEMPORARY TABLE `tmp_convenio` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`tipoconvenio` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente,NULL
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal,tipoconvenio= CONCAT(IF(precioporkg=1,'KG, ',''),IF(precioporcaja=1,'CAJA, ',''),
		IF(descuentosobreflete=1,'DESCUENTO, ',''),IF(prepagadas=1,'PREPAGADAS, ',''),IF(consignacionkg=1,'C. KG, ',''),
		IF(consignacioncaja=1,'C. CAJA, ',''),IF(consignaciondescuento=1,'C. DESC., ',''));";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT t.sucursalacobrar,cs.prefijo AS prefijosucursal
		FROM(
		SELECT idsucorigen AS sucursalacobrar FROM reportes_ventas 
		WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) AND 
		(ISNULL(factura) OR factura=0) GROUP BY idsucorigen
		UNION
		SELECT sucursalacobrar FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND 
		(ISNULL(factura) OR factura=0) AND YEAR(fecha)='".$_GET[fecha]."' GROUP BY sucursalacobrar
		UNION
		SELECT pg.sucursalacobrar FROM guiasempresariales ge INNER JOIN pagoguias AS pg ON ge.id = pg.guia
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' 
		AND NOT ISNULL(ge.total) AND ge.total!=0 GROUP BY pg.sucursalacobrar
		)t INNER JOIN catalogosucursal cs ON t.sucursalacobrar=cs.id GROUP BY sucursalacobrar";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT t.sucursalacobrar,FORMAT(SUM(t.tnormales),2) normales,FORMAT(SUM(t.tprepagadas),2) prepagadas,
		FORMAT(SUM(t.tconsignacion),2) consignacion,FORMAT(SUM(t.tnormales) + SUM(t.tprepagadas) + SUM(t.tconsignacion),2) total
		FROM(
		SELECT idsucorigen AS sucursalacobrar,SUM(IF(tipoventa='GUIA VENTANILLA',total,0)) tnormales,0 AS tprepagadas,0 AS tconsignacion
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (ISNULL(factura) OR factura=0) GROUP BY idsucorigen
		UNION
		SELECT sucursalacobrar,0 AS tnormales,SUM(IFNULL(total,0)) AS tprepagadas,0 AS tconsignacion
		FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND (ISNULL(factura) OR factura=0) 
		AND YEAR(fecha)='".$_GET[fecha]."' GROUP BY sucursalacobrar
		UNION
		SELECT pg.sucursalacobrar,0 AS tnormales,0 AS tprepagadas,SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)) AS tconsignacion
		FROM guiasempresariales ge INNER JOIN pagoguias pg ON ge.id = pg.guia
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' GROUP BY pg.sucursalacobrar )t ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t.sucursalacobrar AS idsucursal,cs.prefijo AS prefijosucursal,SUM(t.tnormales) normales,
		SUM(t.tprepagadas) prepagadas,SUM(t.tconsignacion) consignacion,SUM(t.tnormales) + SUM(t.tprepagadas) + SUM(t.tconsignacion) total
		FROM(
		SELECT idsucorigen AS sucursalacobrar,SUM(IF(tipoventa='GUIA VENTANILLA',total,0)) tnormales,0 AS tprepagadas,0 AS tconsignacion
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (ISNULL(factura) OR factura=0) GROUP BY idsucorigen
		UNION
		SELECT sucursalacobrar,0 AS tnormales,SUM(IFNULL(total,0)) AS tprepagadas,0 AS tconsignacion
		FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND (ISNULL(factura) OR factura=0) 
		AND YEAR(fecha)='".$_GET[fecha]."' GROUP BY sucursalacobrar
		UNION
		SELECT pg.sucursalacobrar,0 AS tnormales,0 AS tprepagadas,SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)) AS tconsignacion
		FROM guiasempresariales ge INNER JOIN pagoguias pg ON ge.id = pg.guia
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' 
		AND NOT ISNULL(ge.total) AND ge.total!=0 GROUP BY pg.sucursalacobrar
		)t INNER JOIN catalogosucursal cs ON t.sucursalacobrar=cs.id GROUP BY sucursalacobrar ORDER BY prefijo $limite";
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
		
	}else if($_GET[accion]==7){ //SIN FACTURAR PREPAGADAS
		$x = rand(1,1000); 	
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";
		/* tabla temp */
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`tipoconvenio` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente,NULL
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal,tipoconvenio= CONCAT(IF(precioporkg=1,'KG, ',''),IF(precioporcaja=1,'CAJA, ',''),
		IF(descuentosobreflete=1,'DESCUENTO, ',''),IF(prepagadas=1,'PREPAGADAS, ',''),IF(consignacionkg=1,'C. KG, ',''),
		IF(consignacioncaja=1,'C. CAJA, ',''),IF(consignaciondescuento=1,'C. DESC., ',''));";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT sge.id AS nventa FROM solicitudguiasempresariales sge WHERE sge.prepagada='SI' AND (ISNULL(sge.factura) OR 
		sge.factura=0) AND YEAR(sge.fecha) = '".$_GET[fecha]."' AND sge.estado!='CANCELADA' AND NOT ISNULL(sge.total) AND sge.total!=0
		GROUP BY sge.idcliente;";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT sge.id AS nventa,FORMAT(SUM(IFNULL(sge.total,0)),2) AS total	FROM solicitudguiasempresariales sge 
		WHERE sge.prepagada='SI' AND (ISNULL(sge.factura) OR sge.factura=0) AND YEAR(sge.fecha) = '".$_GET[fecha]."' 
		AND sge.estado!='CANCELADA'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT cs.prefijo AS prefijosucursal,temp.idcliente,temp.cliente,sge.id AS nventa,IFNULL(sge.cantidad,0) cantidad,
		CONCAT(sge.desdefolio,'-',sge.hastafolio) pfolios,sge.fecha,IFNULL(SUM(sge.subtotal),0) flete,t.excedente AS sobrepeso,
		t.seguro AS costoseguro,t.costoead AS costoead,SUM(sge.total) AS total
		FROM solicitudguiasempresariales sge 
		INNER JOIN tmp_convenio$x temp ON sge.idcliente=temp.idcliente
		INNER JOIN catalogosucursal cs ON temp.idsucursal=cs.id
		LEFT JOIN (
		SELECT SUM(IFNULL(ge.tseguro,0)) seguro,SUM(IFNULL(ge.texcedente,0)) excedente,SUM(IFNULL(ge.tcostoead,0)) costoead,
		tc.idsucursal,tc.idcliente 
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente=tc.idcliente 
		WHERE ISNULL(ge.factura) AND ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0) AND 
		YEAR(ge.fecha)='".$_GET[fecha]."' GROUP BY tc.idcliente)t ON sge.sucursalacobrar=t.idsucursal AND sge.idcliente=t.idcliente	
		WHERE sge.prepagada='SI' AND (ISNULL(sge.factura) OR sge.factura=0) AND YEAR(sge.fecha) = '".$_GET[fecha]."' AND 
		sge.estado!='CANCELADA' AND NOT ISNULL(sge.total) AND sge.total!=0
		GROUP BY temp.idcliente ORDER BY cs.prefijo $limite";
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
		
		$s = "DROP TABLE tmp_convenio$x;";
			  mysql_query($s,$l) or die($s);
		
	}else if($_GET[accion]==8){  //SIN FACTURAR CONSIGNACION
		/* tabla temp */
		$s = "CREATE TEMPORARY TABLE `tmp_convenio` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`tipoconvenio` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente,NULL
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal,tipoconvenio= CONCAT(IF(precioporkg=1,'KG, ',''),IF(precioporcaja=1,'CAJA, ',''),
		IF(descuentosobreflete=1,'DESCUENTO, ',''),IF(prepagadas=1,'PREPAGADAS, ',''),IF(consignacionkg=1,'C. KG, ',''),
		IF(consignacioncaja=1,'C. CAJA, ',''),IF(consignaciondescuento=1,'C. DESC., ',''));";
		mysql_query($s,$l) or die($s); 
		
		$s = "SELECT tc.idcliente FROM guiasempresariales ge 
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id 
		WHERE YEAR(ge.fecha) = '".$_GET[fecha]."' AND ge.tipoguia='CONSIGNACION' AND (ISNULL(ge.factura) OR ge.factura=0)
		AND NOT ISNULL(ge.idremitente) AND NOT ISNULL(ge.total) AND ge.total!=0 GROUP BY tc.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT tc.idcliente,FORMAT(SUM(ge.total),2) total FROM guiasempresariales ge
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id 
		WHERE YEAR(ge.fecha) = '".$_GET[fecha]."' AND ge.tipoguia='CONSIGNACION' AND (ISNULL(ge.factura) OR ge.factura=0)
		AND NOT ISNULL(ge.idremitente) ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
				
		$s="SELECT cs.prefijo AS prefijosucursal,tc.idcliente,tc.cliente,SUM(ge.tflete) porfacturar,
		SUM(IFNULL(ge.texcedente,0)) sobrepeso,SUM(IFNULL(ge.tseguro,0)) valordeclarado,SUM(IFNULL(ge.tcostoead,0)) costoead,
		SUM(ge.total) total FROM guiasempresariales ge INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente 
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id 
		WHERE YEAR(ge.fecha) = '".$_GET[fecha]."' AND ge.tipoguia='CONSIGNACION' AND (ISNULL(ge.factura) OR ge.factura=0)
		AND NOT ISNULL(ge.idremitente) AND NOT ISNULL(ge.total) AND ge.total!=0 GROUP BY tc.idcliente ORDER BY cliente $limite";
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
			
	}else if($_GET[accion]==9){//VENTAS SIN CONVENIO SIN FACTURAR
		/* tabla temp */
		$s = "CREATE TEMPORARY TABLE `tmp_convenio` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`tipoconvenio` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente,NULL
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal,tipoconvenio= CONCAT(IF(precioporkg=1,'KG, ',''),IF(precioporcaja=1,'CAJA, ',''),
		IF(descuentosobreflete=1,'DESCUENTO, ',''),IF(prepagadas=1,'PREPAGADAS, ',''),IF(consignacionkg=1,'C. KG, ',''),
		IF(consignacioncaja=1,'C. CAJA, ',''),IF(consignaciondescuento=1,'C. DESC., ',''));";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT sucursalacobrar FROM(
		SELECT idsucorigen AS sucursalacobrar
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND tipoventa='GUIA VENTANILLA' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (ISNULL(factura) OR factura=0) AND idsucorigen=".$_GET[sucursal]." 
		GROUP BY idcliente
		UNION
		SELECT sucursalacobrar FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND 
		(ISNULL(factura) OR factura=0) AND YEAR(fecha)='".$_GET[fecha]."' AND sucursalacobrar=".$_GET[sucursal]." GROUP BY idcliente
		UNION
		SELECT pg.sucursalacobrar FROM guiasempresariales ge INNER JOIN pagoguias pg ON ge.id = pg.guia
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' AND pg.sucursalacobrar=".$_GET[sucursal]."
		GROUP BY tc.idcliente
		)t GROUP BY sucursalacobrar";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT t.sucursalacobrar,FORMAT(SUM(t.tnormales),2) normales,FORMAT(SUM(t.tprepagadas),2) prepagadas,
		FORMAT(SUM(t.tconsignacion),2) consignacion,FORMAT(SUM(t.tnormales) + SUM(t.tprepagadas) + SUM(t.tconsignacion),2) total
		FROM(
		SELECT idsucorigen AS sucursalacobrar,idcliente,nombrecliente AS cliente,'' AS tipoconvenio,SUM(IFNULL(total,0)) tnormales,
		0 AS tprepagadas,0 AS tconsignacion
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND tipoventa='GUIA VENTANILLA' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (ISNULL(factura) OR factura=0) AND idsucorigen=".$_GET[sucursal]." 
		GROUP BY idcliente
		UNION
		SELECT sucursalacobrar,idcliente,CONCAT(nombre,' ',apepat,'',apemat) cliente,'' AS tipoconvenio,0 AS tnormales,
		SUM(IFNULL(total,0)) AS tprepagadas,0 AS tconsignacion
		FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND (ISNULL(factura) OR factura=0) 
		AND YEAR(fecha)='".$_GET[fecha]."' AND sucursalacobrar=".$_GET[sucursal]." GROUP BY idcliente
		UNION
		SELECT pg.sucursalacobrar,tc.idcliente,tc.cliente,tc.tipoconvenio,0 AS tnormales,0 AS tprepagadas,
		SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)) AS tconsignacion
		FROM guiasempresariales ge INNER JOIN pagoguias pg ON ge.id = pg.guia
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' AND pg.sucursalacobrar=".$_GET[sucursal]."
		GROUP BY tc.idcliente
		)t ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t.sucursalacobrar AS idsucursal,cs.prefijo AS prefijosucursal,t.idcliente,t.cliente,t.tipoconvenio,
		SUM(t.tnormales) normales,SUM(t.tprepagadas) prepagadas,SUM(t.tconsignacion) consignacion,SUM(t.tnormales) + 
		SUM(t.tprepagadas) + SUM(t.tconsignacion) total
		FROM(
		SELECT idsucorigen AS sucursalacobrar,idcliente,nombrecliente AS cliente,'' AS tipoconvenio,SUM(IFNULL(total,0)) tnormales,
		0 AS tprepagadas,0 AS tconsignacion
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND tipoventa='GUIA VENTANILLA' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (ISNULL(factura) OR factura=0) AND idsucorigen=".$_GET[sucursal]." 
		GROUP BY idcliente
		UNION
		SELECT sucursalacobrar,idcliente,CONCAT(nombre,' ',apepat,'',apemat) cliente,'' AS tipoconvenio,0 AS tnormales,
		SUM(IFNULL(total,0)) AS tprepagadas,0 AS tconsignacion
		FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND (ISNULL(factura) OR factura=0) 
		AND YEAR(fecha)='".$_GET[fecha]."' AND sucursalacobrar=".$_GET[sucursal]." GROUP BY idcliente
		UNION
		SELECT pg.sucursalacobrar,tc.idcliente,tc.cliente,tc.tipoconvenio,0 AS tnormales,0 AS tprepagadas,
		SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)) AS tconsignacion
		FROM guiasempresariales ge INNER JOIN pagoguias pg ON ge.id = pg.guia
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' AND pg.sucursalacobrar=".$_GET[sucursal]."
		GROUP BY tc.idcliente
		)t INNER JOIN catalogosucursal cs ON t.sucursalacobrar=cs.id GROUP BY t.idcliente ORDER BY t.idcliente $limite";
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
		if ($_GET[sucursal]!=''){
			$fsuc=" AND t2.sucursal='".$_GET[sucursal]."'";
		}
		
		$s = "SELECT COUNT(*) AS total FROM generacionconvenio t2 WHERE YEAR(fecha) = '".$_GET[fecha]."' AND estadoconvenio = 'ACTIVADO'
		$fsuc";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totalregistros = $f->total;
		
		$s = "SELECT COUNT(*) AS total FROM generacionconvenio t2 WHERE YEAR(fecha) = '".$_GET[fecha]."' AND estadoconvenio = 'ACTIVADO'
		$fsuc ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t2.idcliente, CONCAT(t2.nombre,' ',t2.apaterno,' ',t2.amaterno) as cliente, 0 as precio,
		DATE_FORMAT(t2.vigencia,'%d/%m/%Y') AS vencimiento, (t1.prefijo) as prefijosucursal,
		CONCAT(if(t2.precioporkg=1,'KG, ',''),if(t2.precioporcaja=1,'CAJA, ',''),if(t2.descuentosobreflete=1,'DESCUENTO, ',''),
		if(t2.prepagadas=1,'PREPAGADAS, ',''),if(t2.consignacionkg=1,'C. KG, ',''),if(t2.consignacioncaja=1,'C. CAJA, ',''),
		if(t2.consignaciondescuento=1,'C. DESC., ',''),if(t2.consignaciondescantidad=1,'C. DESC. CANT., ','')) AS tipo
		FROM generacionconvenio t2 LEFT JOIN catalogosucursal t1 ON t2.sucursal=t1.id
		WHERE YEAR(t2.fecha) = '".$_GET[fecha]."' AND t2.estadoconvenio = 'ACTIVADO' $fsuc ORDER BY prefijosucursal $limite";
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
		if ($_GET[sucursal]!=''){
			$fsuc=" AND t2.sucursal='".$_GET[sucursal]."'";
		}
		
		$s = "SELECT COUNT(*) AS total FROM generacionconvenio t2 WHERE YEAR(fecha) = '".$_GET[fecha]."' AND estadoconvenio = 'EXPIRADO'
		$fsuc";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totalregistros = $f->total;
		
		$s = "SELECT COUNT(*) AS total FROM generacionconvenio t2 WHERE YEAR(fecha) = '".$_GET[fecha]."' AND estadoconvenio = 'EXPIRADO'
		$fsuc";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t2.idcliente, CONCAT(t2.nombre,' ',t2.apaterno,' ',t2.amaterno) as cliente, 0 as precio,
		DATE_FORMAT(t2.vigencia,'%d/%m/%Y') AS vencimiento, (t1.prefijo) as sucursal, (t2.sucursal) AS idsucursal,
		CONCAT(if(t2.precioporkg=1,'KG, ',''),if(t2.precioporcaja=1,'CAJA, ',''),if(t2.descuentosobreflete=1,'DESCUENTO, ',''),
		if(t2.prepagadas=1,'PREPAGADAS, ',''),if(t2.consignacionkg=1,'C. KG, ',''),if(t2.consignacioncaja=1,'C. CAJA, ',''),
		if(t2.consignaciondescuento=1,'C. DESC., ',''),if(t2.consignaciondescantidad=1,'C. DESC. CANT., ','')) AS tipo
		FROM generacionconvenio t2 LEFT JOIN catalogosucursal t1 ON t2.sucursal=t1.id
		WHERE YEAR(t2.fecha) = '".$_GET[fecha]."' AND t2.estadoconvenio = 'EXPIRADO' $fsuc ORDER BY sucursal $limite";
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
		if ($_GET[sucursal]!=''){
			$fsuc=" AND t2.sucursal='".$_GET[sucursal]."'";
		}
		$s = "SELECT COUNT(*) AS total FROM generacionconvenio t2 WHERE YEAR(fecha) = '".$_GET[fecha]."' 
		AND (estadoconvenio = 'EXPIRADO' OR estadoconvenio = 'ACTIVADO') $fsuc";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totalregistros = $f->total;
		
		$s = "SELECT COUNT(*) AS total FROM generacionconvenio t2 WHERE YEAR(fecha) = '".$_GET[fecha]."' 
		AND (estadoconvenio = 'EXPIRADO' OR estadoconvenio = 'ACTIVADO') $fsuc";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT t2.idcliente, CONCAT(t2.nombre,' ',t2.apaterno,' ',t2.amaterno) as cliente, 0 as precio, 
		DATE_FORMAT(t2.vigencia,'%d/%m/%Y') AS vencimiento, (t1.prefijo) as prefijosucursal, (t2.sucursal) AS idsucursal,
		CONCAT(if(t2.precioporkg=1,'KG, ',''),if(t2.precioporcaja=1,'CAJA, ',''),if(t2.descuentosobreflete=1,'DESCUENTO, ',''),
		if(t2.prepagadas=1,'PREPAGADAS, ',''),if(t2.consignacionkg=1,'C. KG, ',''),if(t2.consignacioncaja=1,'C. CAJA, ',''),
		if(t2.consignaciondescuento=1,'C. DESC., ',''),if(t2.consignaciondescantidad=1,'C. DESC. CANT., ','')) AS tipo,
		(t2.estadoconvenio) as estatus FROM generacionconvenio t2 LEFT JOIN catalogosucursal t1 ON t2.sucursal=t1.id
		WHERE YEAR(t2.fecha) = '".$_GET[fecha]."' AND (t2.estadoconvenio = 'EXPIRADO' OR t2.estadoconvenio = 'ACTIVADO') $fsuc
		ORDER BY prefijosucursal $limite";
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
		$s = "SELECT * FROM generacionconvenio WHERE YEAR(fecha) = '".$_GET[fecha]."' AND idcliente = ".$_GET[cliente]."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);

		$totales = json_encode(0);
		
		$s = "SELECT gc.idcliente, 0 AS valorconvenio, DATE_FORMAT(gc.vigencia,'%d/%m/%Y') AS fechavencimiento, 
		DATE_FORMAT(gc.fecha,'%d/%m/%Y') AS fechaalta, CONCAT(IF(gc.precioporkg=1,'KG, ',''),
		IF(gc.precioporcaja=1,'CAJA, ',''),IF(gc.descuentosobreflete=1,'DESCUENTO, ',''),
		IF(gc.prepagadas=1,'PREPAGADAS, ',''),IF(gc.consignacionkg=1,'C. KG, ',''),IF(gc.consignacioncaja=1,'C. CAJA, ',''),
		IF(gc.consignaciondescuento=1,'C. DESC., ',''),IF(gc.consignaciondescantidad=1,'C. DESC. CANT., ','')) AS tipoconvenio,
		(gc.limitekg) AS pesomaximo, (gc.preciokgexcedente) AS preciosobrepeso, (t2.fechaactivacion) AS fechamodificacion,
		(t2.estado) AS estadocredito, (t2.montoautorizado) as limitecredito FROM generacionconvenio gc 
		INNER JOIN solicitudcredito t2 ON gc.idcliente=t2.cliente WHERE gc.idcliente=".$_GET[cliente]."";
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