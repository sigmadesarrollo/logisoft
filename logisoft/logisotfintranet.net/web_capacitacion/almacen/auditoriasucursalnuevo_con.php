<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	
	#verificar si existe en la sucursal una auditoria sin cerrar
	$s = "SELECT * 
	FROM reporte_auditoria_principal
	WHERE sucursal='$_GET[sucursalseleccionada]' AND cerrado='N'";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r);
		if($_GET[accion]==1)
			die("sin cerrar,$f->folio");
	}
	
	$s = "SELECT IFNULL(MAX(ADDDATE(fechafin, INTERVAL 1 DAY)),'2009-01-01') fechainicio, adddate(CURRENT_DATE, interval -1 day) fechafin
	FROM reporte_auditoria_principal WHERE sucursal = '$_GET[sucursalseleccionada]' AND cerrado='N';";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$fechainicio = $f->fechainicio;
	$fechafin = $f->fechafin;
	
	if($_GET[accion]==1){
		$s = "SELECT IFNULL(MAX(folio),0)+1 folio FROM reporte_auditoria_principal WHERE sucursal = $_GET[sucursalseleccionada]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$foliosucursal = $f->folio;
		
		$s = "SELECT ifnull(ajustesrestante,0) saldoanterior, ifnull(inventariocierre,0) inventarioafecha, ifnull(carteracierre,0) as carteraafecha, 
		ifnull(ajustestotalseleccionado,0) ajustesal,
		date_format(fechafin, '%d/%m/%Y') as fecha, date_format(current_date, '%d/%m/%Y') as factual, date_format(fechafin, '%d/%m/%Y') as fechaanterior
		FROM reporte_auditoria_principal
		WHERE sucursal = $_GET[sucursalseleccionada]
		ORDER BY folio DESC LIMIT 1";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$anterior = "{'saldoanterior':'$f->saldoanterior','inventarioal':'$f->inventarioafecha','carteraal':'$f->carteraafecha',
			fecha:'$f->fecha', 'factual':'$f->factual', 'folioauditoria':'$foliosucursal', 'fechaanterior':'$f->fechaanterior', 
			'ajustesal':'$f->ajustesal'}";
		}else{
			$anterior = "{'saldoanterior':'0','inventarioal':'0','carteraal':'0', 'ajustesal':'0',
			'fecha':'', 'factual':'".date("d/m/Y")."', 'folioauditoria':'$foliosucursal'}";
		}
		
		//LIQUIDACIONES
		$s = "SELECT IFNULL(SUM(total),0) AS total 
		FROM reporte_auditoria_liquidacion
		WHERE sucursal = $_GET[sucursalseleccionada] and isnull(folioauditoria)
		AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$liquidaciones = $f->total;
		}else{
			$liquidaciones = 0;
		}
		
		
		#meter las notas de credito de las fechas
		$s = "DELETE FROM reporte_auditoria_notacredito WHERE ISNULL(folioauditoria) AND sucursal = '$_GET[sucursalseleccionada]';";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_notacredito
		(sucursal, folionota, fecha, importe, cliente)
		(SELECT '$_GET[sucursalseleccionada]', f.nnotacredito,CURRENT_DATE, f.notacredito, CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno)
		FROM formapago f
		INNER JOIN notacredito n ON f.nnotacredito = n.folio
		INNER JOIN catalogocliente cc ON n.cliente = cc.id
		WHERE f.fecha BETWEEN '$fechainicio' AND '$fechafin' AND f.sucursal = '$_GET[sucursalseleccionada]'
		AND SUBSTRING(f.guia,1,3) <> '999' and (isnull(f.fechacancelacion) or f.fechacancelacion='0000-00-00'))
		UNION
		(SELECT '$_GET[sucursalseleccionada]', f.nnotacredito,CURRENT_DATE, f.notacredito, CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno)
		FROM formapago f
		INNER JOIN notacredito n ON f.nnotacredito = n.folio
		INNER JOIN catalogocliente cc ON n.cliente = cc.id
		INNER JOIN guiasempresariales ge ON f.guia = ge.id AND ge.factura <> 0 AND NOT ISNULL(ge.factura)
		WHERE f.fecha BETWEEN '$fechainicio' AND '$fechafin' AND f.sucursal = '$_GET[sucursalseleccionada]'
		AND SUBSTRING(f.guia,1,3) = '999' AND ge.tipoguia='CONSIGNACION' AND (isnull(f.fechacancelacion) or f.fechacancelacion='0000-00-00'))";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_notacredito
		WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$notacredito = $f->total;
		}else{
			$notacredito = 0;
		}
		
		
		#borrar los depositos sin foliwaoauditoria
		$s = "DELETE FROM reporte_auditoria_depositos
		WHERE ISNULL(folioauditoria) AND sucursal = '$_GET[sucursalseleccionada]'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_depositos
		(folio, procedencia, sucursal, fecha, importe, fechacorte)
		(SELECT f.guia, f.procedencia, f.sucursal, f.fecha, f.total-IFNULL(f.notacredito,0), '$fechainicio'
		FROM formapago f
		WHERE f.fecha BETWEEN '$fechainicio' AND '$fechafin' AND f.sucursal = '$_GET[sucursalseleccionada]'
		AND SUBSTRING(f.guia,1,3) <> '999'  AND (isnull(f.fechacancelacion) or f.fechacancelacion='0000-00-00'))
		UNION
		(SELECT f.guia, f.procedencia, f.sucursal, f.fecha, f.total-IFNULL(f.notacredito,0), '$fechainicio'
		FROM formapago f
		INNER JOIN guiasempresariales ge ON f.guia = ge.id AND ge.factura <> 0 AND NOT ISNULL(ge.factura)
		WHERE f.fecha BETWEEN '$fechainicio' AND '$fechafin' AND f.sucursal = '$_GET[sucursalseleccionada]'
		AND SUBSTRING(f.guia,1,3) = '999' AND ge.tipoguia='CONSIGNACION'  AND (isnull(f.fechacancelacion) or f.fechacancelacion='0000-00-00'))";
		mysql_query($s,$l) or die($s);
		
		#no borrar a lo mejor se usa este procedimiento
		#poner los importes de los pagos
		/*$s = "INSERT INTO reporte_auditoria_depositos
		(folio, sucursal, fecha, importe, fechacorte)
		(SELECT guia, '$_GET[sucursalseleccionada]', fechapago, total, '$fechainicio'
		FROM pagoguias
		WHERE fechapago between '$fechainicio' and '$fechafin' and sucursalacobrar = $_GET[sucursalseleccionada] and pagado = 'S'
		and tipo='NORMAL')
		union
		(SELECT folio, '$_GET[sucursalseleccionada]', fp.fechapago, 
		IFNULL(total,0)+IFNULL(sobmontoafacturar,0)+IFNULL(otrosmontofacturar,0), '$fechainicio' 
		FROM facturacion f
		INNER JOIN facturacion_fechapago fp ON f.folio = fp.factura
		WHERE f.idsucursal = '$_GET[sucursalseleccionada]' AND fp.fechapago BETWEEN '$fechainicio' AND '$fechafin' and f.tipoguia='empresarial')";
		mysql_query($s,$l) or die($s);*/
		//depositos
		$s = "SELECT IFNULL(SUM(importe),0) as total FROM reporte_auditoria_depositos
		WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$depositos = $f->total;
		}else{
			$depositos = 0;
		}
		
		//guias canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_guiascanceladas
		WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)
		AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$guiascanceladas = $f->total;
		}else{
			$guiascanceladas = 0;
		}
		
		//facturas canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total 
		FROM reporte_auditoria_facturascanceladas
		WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)
		AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$facturascanceladas = $f->total;
		}else{
			$facturascanceladas = 0;
		}
		
		
		#meter la cartera e inventario
		$s = "DELETE FROM reporte_auditoria_sistemainvcar WHERE sucursal = '$_GET[sucursalseleccionada]' and isnull(folioauditoria);";
		mysql_query($s,$l) or die($s);
		
		/*$s = "INSERT INTO reporte_auditoria_sistemainvcar 
		(folio, tipo, inventario, cartera, fecha, cliente, 
		importe, tipoflete, tipopago, sucursal)
		(SELECT gv.id, 'GUIA', IF(gv.tipoflete=0 AND NOT gv.idsucursalorigen = gv.idsucursaldestino,'N','S'),
		IF(gv.condicionpago=1 AND pg.pagado='N','S','N'),gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		IF(gv.idsucursalorigen = gv.idsucursaldestino AND gv.condicionpago=0,0,gv.total), 
		IF(gv.tipoflete=0,'PAGADA','POR COBRAR'), IF(gv.condicionpago=1,'CREDITO','CONTADO'), '$_GET[sucursalseleccionada]'
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		WHERE gv.estado <> 'CANCELADA' AND gv.estado <> 'CANCELADO'
		AND (
			(gv.idsucursalorigen = '$_GET[sucursalseleccionada]' AND gv.tipoflete=0 AND gv.condicionpago=1 AND pg.pagado = 'N') OR 
			(gv.idsucursalorigen = '$_GET[sucursalseleccionada]' AND gv.idsucursalorigen = gv.idsucursaldestino) OR 
			(gv.idsucursaldestino = '$_GET[sucursalseleccionada]' AND gv.tipoflete=1 AND ((gv.condicionpago=1 AND pg.pagado = 'N') OR (gv.condicionpago=0 AND gv.estado <> 'ENTREGADA')) )
		))
		UNION
		(SELECT gv.id, 'GUIA', 'S', 'N',gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		0, IF(gv.tipoflete=0,'PAGADA','POR COBRAR'), gv.tipopago, '$_GET[sucursalseleccionada]'
		FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		WHERE gv.estado <> 'CANCELADA' AND gv.estado <> 'CANCELADO' AND gv.estado <>'ENTREGADA'
		AND (
			(gv.idsucursalorigen = '$_GET[sucursalseleccionada]' AND gv.idsucursalorigen = gv.idsucursaldestino ) OR 
			(gv.idsucursaldestino = '$_GET[sucursalseleccionada]')
		))
		UNION
		(SELECT f.folio, 'FACT', 'N', 'S', f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
		IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0), '', 'CREDITO', '$_GET[sucursalseleccionada]'
		FROM facturacion f
		WHERE f.credito = 'SI' AND f.estadocobranza <> 'C' AND f.idsucursal = '$_GET[sucursalseleccionada]' and tipoguia = 'empresarial');";*/
		$s = "INSERT INTO reporte_auditoria_sistemainvcar 
		(folio, tipo, inventario, cartera, fecha, cliente, 
		importe, tipoflete, tipopago, sucursal)
		(SELECT gv.id, 'GUIA', 
		IF(gv.idsucursaldestino=$_GET[sucursalseleccionada],'S','N'),
		IF(gv.condicionpago=1 AND pg.pagado='N' AND pg.sucursalacobrar = '$_GET[sucursalseleccionada]','S','N'),
		gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		IF(pg.sucursalacobrar <> '$_GET[sucursalseleccionada]' OR pg.pagado='S' OR (gv.estado='ENTREGADA' AND gv.condicionpago=0),0,gv.total), 
		IF(gv.tipoflete=0,'PAGADA','POR COBRAR'), IF(gv.condicionpago=1,'CREDITO','CONTADO'), '$_GET[sucursalseleccionada]'
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		WHERE gv.estado <> 'CANCELADA' AND gv.estado <> 'CANCELADO'
		AND (
			(gv.idsucursaldestino = '$_GET[sucursalseleccionada]' AND gv.estado <> 'ENTREGADA') OR 
			(pg.sucursalacobrar = '$_GET[sucursalseleccionada]' AND pg.pagado = 'N')
		))
		UNION
		(SELECT gv.id, 'GUIA', 'S', 'N',gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		0, IF(gv.tipoflete=0,'PAGADA','POR COBRAR'), gv.tipopago, '$_GET[sucursalseleccionada]'
		FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		WHERE gv.estado <> 'CANCELADA' AND gv.estado <> 'CANCELADO' AND gv.estado <>'ENTREGADA'
		AND (
			(gv.idsucursalorigen = '$_GET[sucursalseleccionada]' AND gv.idsucursalorigen = gv.idsucursaldestino ) OR 
			(gv.idsucursaldestino = '$_GET[sucursalseleccionada]')
		))
		UNION
		(SELECT f.folio, 'FACT', 'N', 'S', f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
		IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0), '', 'CREDITO', '$_GET[sucursalseleccionada]'
		FROM facturacion f
		WHERE f.credito = 'SI' AND f.estadocobranza <> 'C' AND f.idsucursal = '$_GET[sucursalseleccionada]' and tipoguia = 'empresarial');";
		mysql_query($s,$l) or die($s);
		
		
		
		$s = "SELECT 
		(SELECT IFNULL(SUM(ras.importe),0) 
		FROM reporte_auditoria_sistemainvcar ras
		WHERE sucursal = '$_GET[sucursalseleccionada]' AND ras.cartera = 'S' and isnull(folioauditoria)) carterasistema,
		(SELECT IFNULL(SUM(IF(ras.cartera='S',0,ras.importe)),0) 
		FROM reporte_auditoria_sistemainvcar ras
		WHERE sucursal = '$_GET[sucursalseleccionada]' AND ras.inventario = 'S' and isnull(folioauditoria)) inventariosistema";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "({
				'anterior':$anterior,
				'liquidaciones':'$liquidaciones',
				'depositos':'$depositos',
				'notacredito':'$notacredito',
				'guiascanceladas':'$guiascanceladas',
				'facturascanceladas':'$facturascanceladas',
				'carterasistema':'$f->carterasistema',
				'inventariosistema':'$f->inventariosistema'
			})";
	}
	
	if($_GET[accion]==2){
		$s = "DELETE FROM reporte_auditoria_faltantesobrantes WHERE sucursal = '$_GET[sucursalseleccionada]'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_auditoria_leido WHERE ISNULL(folioauditoria) AND sucursal = '$_GET[sucursalseleccionada]'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_auditorias_guias WHERE sucursal = '$_GET[sucursalseleccionada]' AND idusuario = '$_SESSION[IDUSUARIO]';";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_auditoria_faltantesobrantes WHERE sucursal = '$_GET[sucursalseleccionada]'";
		mysql_query($s,$l) or die($s);
		
		#inserttar en guias...
		$s = "INSERT INTO reporte_auditorias_guias 
		SELECT SUBSTRING(rap.folio,1,13),rap.fecha,rap.hora,rap.sucursal,'$_SESSION[IDUSUARIO]',
		IF(COUNT(rap.id)=SUBSTRING(rap.folio,18,4),'C','I'), null
		FROM reporte_auditorias_paq rap
		WHERE sucursal = '$_GET[sucursalseleccionada]'
		GROUP BY SUBSTRING(rap.folio,1,13);";
		mysql_query($s,$l) or die($s);
		
		#meter en leido las guias leidas
		$s = "INSERT INTO reporte_auditoria_leido
		(guia, fecha, cliente, importe, flete, pago, status, encontrada, completa, tipo, sucursal)
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		gv.total, gv.tflete, IF(gv.condicionpago=0,'CONTADO', 'CREDITO'), gv.estado,
		'N',rp.estado, 'G','$_GET[sucursalseleccionada]'
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN reporte_auditorias_guias rp ON gv.id = rp.folio
		WHERE rp.sucursal = '$_GET[sucursalseleccionada]' and rp.idusuario = '$_SESSION[IDUSUARIO]')
		UNION
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		gv.total, gv.tflete, gv.tipopago, gv.estado,
		'N',rp.estado,'G','$_GET[sucursalseleccionada]'
		FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN reporte_auditorias_guias rp ON gv.id = rp.folio
		WHERE rp.sucursal = '$_GET[sucursalseleccionada]' and rp.idusuario = '$_SESSION[IDUSUARIO]')";
		$r = mysql_query($s,$l) or die($s);
		
		#meter en leido las facturas.
		$s = "INSERT INTO reporte_auditoria_leido
		(guia, fecha, cliente, importe, flete, pago, status, encontrada, completa, tipo, sucursal)
		SELECT f.folio, f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
		IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0),
		0,IF(f.credito='SI','CREDITO','CONTADO'), f.facturaestado, 'N', 'C', 'F', '$_GET[sucursalseleccionada]'
		FROM facturacion AS f
		INNER JOIN reporte_auditorias_fac rf ON f.folio = rf.folio
		WHERE rf.sucursal = '$_GET[sucursalseleccionada]'";
		$r = mysql_query($s,$l) or die($s);		
		# *************************************************************
		
		#actualizar los datos para saber si fueron encontrados
		$s = "UPDATE reporte_auditoria_leido rl
		INNER JOIN reporte_auditoria_sistemainvcar rsi ON rl.guia = rsi.folio
		SET rl.encontrada = 'S', rsi.encontrada = if(rl.completa='I','N','S'), rsi.completo=rl.completa
		WHERE rl.sucursal = '$_GET[sucursalseleccionada]' AND rsi.sucursal = '$_GET[sucursalseleccionada]'";
		mysql_query($s,$l) or die($s);
		
		#******************************* meter los sobrantes en las facturas
			#EL TOTAL DE LA FACTURA = IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0)
			#checar facturas sobrantes ************
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico, factura,tipoalmacen,tipopago)
			SELECT rl.guia, f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
			0,
			0,IF(f.credito='SI','CREDITO','CONTADO'), '', 'S', 'F', '$_GET[sucursalseleccionada]','SOBRANTE','','',if(f.credito='SI','CR','CO')
			FROM reporte_auditoria_leido AS rl
			LEFT JOIN facturacion f ON f.folio = rl.guia
			WHERE rl.sucursal = '$_GET[sucursalseleccionada]' AND tipo='F' AND encontrada = 'N'";
			$r = mysql_query($s,$l) or die($s);	
			#checar las guias sobrantes ***********
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico, estadoguia, factura,tipoalmacen,tipopago)
			(SELECT rp.guia, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			0, IFNULL(gv.tflete,0), IF(gv.condicionpago=0,'CONTADO', 'CREDITO'), rp.completa,
			'S', 'V','$_GET[sucursalseleccionada]','SOBRANTE', IFNULL(rp.status,''), ifnull(gv.factura,''),'',if(gv.condicionpago=0,'CO','CR')
			FROM reporte_auditoria_leido rp
			LEFT JOIN guiasventanilla gv ON gv.id = rp.guia
			LEFT JOIN pagoguias pg on gv.id = pg.guia
			LEFT JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_GET[sucursalseleccionada]' AND SUBSTRING(rp.guia,1,3)<>'999' AND rp.encontrada = 'N' AND rp.tipo='G')
			UNION
			(SELECT rp.guia, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			0, IFNULL(gv.tflete,0), gv.tipopago, rp.completa,
			'S','E','$_GET[sucursalseleccionada]','SOBRANTE', IFNULL(rp.status,''), ifnull(gv.factura,''),'',if(gv.tipopago='CONTADO','CO','CR')
			FROM reporte_auditoria_leido rp
			LEFT JOIN guiasempresariales gv ON gv.id = rp.guia
			LEFT JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_GET[sucursalseleccionada]' AND SUBSTRING(rp.guia,1,3)='999' AND rp.encontrada = 'N' AND rp.tipo='G')";
			$r = mysql_query($s,$l) or die($s);	
		
		#+++++++++++++++++++++++++++++++++meter los faltantes a la tabla
			#checar facturas faltantes ************
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico, factura,tipoalmacen,tipopago)
			SELECT rl.folio, f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
			IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0),
			0,IF(f.credito='SI','CREDITO','CONTADO'), '', 'S', 'F', '$_GET[sucursalseleccionada]','FALTANTE','','',if(f.credito='SI','CR','CO')
			FROM reporte_auditoria_sistemainvcar AS rl
			INNER JOIN facturacion f ON f.folio = rl.folio
			WHERE rl.sucursal = '$_GET[sucursalseleccionada]' AND isnull(rl.folioauditoria) AND encontrada = 'N'";
			$r = mysql_query($s,$l) or die($s);	
			
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico, estadoguia, factura,tipoalmacen,tipopago)
			(SELECT rp.folio, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			if(pg.pagado='S' OR pg.sucursalacobrar<>'$_GET[sucursalseleccionada]',0,IFNULL(gv.total,0)), 
			IFNULL(gv.tflete,0), IF(gv.condicionpago=0,'CONTADO', 'CREDITO'), rp.completo,
			'S', 'V','$_GET[sucursalseleccionada]','FALTANTE', '', ifnull(gv.factura,''),'',if(gv.condicionpago=0,'CO','CR')
			FROM reporte_auditoria_sistemainvcar rp
			INNER JOIN guiasventanilla gv ON gv.id = rp.folio
			INNER JOIN pagoguias pg on gv.id = pg.guia
			INNER JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_GET[sucursalseleccionada]' AND SUBSTRING(rp.folio,1,3)<>'999' AND rp.encontrada = 'N')
			UNION
			(SELECT rp.folio, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			0, IFNULL(gv.tflete,0), gv.tipopago, rp.completo,
			'S','E','$_GET[sucursalseleccionada]','FALTANTE', '', ifnull(gv.factura,''),'',if(gv.tipopago='CONTADO','CO','CR')
			FROM reporte_auditoria_sistemainvcar rp
			INNER JOIN guiasempresariales gv ON gv.id = rp.folio
			INNER JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_GET[sucursalseleccionada]' AND SUBSTRING(rp.folio,1,3)='999' AND rp.encontrada = 'N')";
			$r = mysql_query($s,$l) or die($s);	
		# **************** ACTUALIZAR TIPO DE ALMACEN
		$s = "UPDATE reporte_auditoria_faltantesobrantes f
		INNER JOIN reporte_auditoria_sistemainvcar i ON f.guia = i.folio 
		AND f.sucursal = i.sucursal AND f.sucursal = '$_GET[sucursalseleccionada]'
		SET f.tipoalmacen = IF(i.inventario='S' AND i.cartera='S','I,C', IF(i.inventario='S','I','C'))";
		mysql_query($s,$l) or die($s);
		# **************** PEDIR FALTANTESOSBRANTES
		$s = "SELECT 'S' seleccion, guia, cliente, importe, fisico, ifnull(status,'') estadoguia, tipo, tipoalmacen, factura, tipopago
		FROM reporte_auditoria_faltantesobrantes WHERE sucursal = $_GET[sucursalseleccionada]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
			$arre[] = $f;
		}
		
		$registros = json_encode($arre);
		# *****************************************
		
		$s = "UPDATE reporte_auditoria_leido le
		INNER JOIN reporte_auditoria_sistemainvcar ar ON le.guia = ar.folio
		SET le.tipoalmacen = IF(ar.inventario='S' AND ar.cartera='S','I,C', IF(ar.inventario='S','I','C')),
		le.importe = ar.importe
		WHERE le.sucursal = $_GET[sucursalseleccionada]";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_leido le
		INNER JOIN reporte_auditoria_faltantesobrantes fs ON le.guia = fs.guia
		SET le.importe = 0
		WHERE le.sucursal = '$_GET[sucursalseleccionada]' AND fs.fisico = 'SOBRANTE'";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL(SUM(IF(tipoalmacen<>'I',importe,0)),0) cartera,  
		IFNULL(SUM(IF(tipoalmacen='I',importe,0)),0) inventario
		FROM reporte_auditoria_leido WHERE sucursal = '$_GET[sucursalseleccionada]' AND 
		ISNULL(folioauditoria) AND completa = 'C'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cartera = $f->cartera;
			$inventario = $f->inventario;
		}else{
			$cartera = 0;
			$inventario = 0;
		}
		
		echo "({'inventario':'$inventario', 'cartera':'$cartera', 'registros':$registros})";
	}
	
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
	
	
	if($_GET[accion]==3){//liquidaciones
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)");
		
			
		$s = "SELECT * 
		FROM reporte_auditoria_liquidacion
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_liquidacion
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->nombre);
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
		
	}
	
	if($_GET[accion]==4){//depositos
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)");
		
		
		$s = "SELECT * FROM reporte_auditoria_depositos
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * FROM reporte_auditoria_depositos
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->nombre);
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
		
	}
	
	if($_GET[accion]==5){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)");
		
		$s = "SELECT *
		FROM reporte_auditoria_notacredito
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_notacredito
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
	}
	
	if($_GET[accion]==6){//
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)");
		
		$s = "SELECT *  
		FROM reporte_auditoria_facturascanceladas
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_facturascanceladas
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
		
	}
	
	if($_GET[accion]==7){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)");
		
		$s = "SELECT * 
		FROM reporte_auditoria_guiascanceladas
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_guiascanceladas
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
		
	}
	
	if($_GET[accion]==8){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE completa='C' and folioauditoria = $_GET[folio] ":"WHERE completa='C' AND sucursal = $_GET[sucursalseleccionada] and ISNULL(folioauditoria)");
		
		$s = "SELECT * 
		FROM reporte_auditoria_leido
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_leido
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
		
	}
	
	if($_POST[accion]==9){
		if($_POST['yaGuardado']==1){
			if($_POST[cerrar]==1)
				$cerrado = ",cerrado='S'";
				
			$s = "UPDATE reporte_auditoria_principal
			SET ajustestotalseleccionado='$_POST[ajustestotalseleccionado]',
			ajustesrestante='$_POST[ajustesrestante]' $cerrado
			WHERE folio = '$_POST[folioauditoria]' AND sucursal = '$_POST[sucursalseleccionada]'";
			mysql_query($s,$l) or die($s);
			
			$_POST[foliosajustes] = "'".str_replace(",","','",$_POST[foliosajustes])."'";
			
			$s = "UPDATE reporte_auditoria_faltantesobrantes_det SET seleccionado = 'N' 
			WHERE folioauditoria = 
			(select id from reporte_auditoria_principal where folio = '$_POST[folioauditoria]' and sucursal = $_POST[sucursalseleccionada]) ";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_faltantesobrantes_det SET seleccionado = 'S' 
			WHERE guia IN ($_POST[foliosajustes]) and folioauditoria = 
			(select id from reporte_auditoria_principal where folio = '$_POST[folioauditoria]' and sucursal = $_POST[sucursalseleccionada]) ";
			mysql_query($s,$l) or die($s);
			
			#Pagar las guias y facturas
			$total = 0;
			if($_POST[cerrar]==1){
				# guias de cartera 
				$s = "SELECT f.guia AS folio, f.tipopago, g.factura
				FROM reporte_auditoria_faltantesobrantes_det f
				INNER JOIN guiasventanilla g ON f.guia = g.id
				WHERE f.sucursal = '$_POST[sucursalseleccionada]' AND f.tipo <> 'F' 
				AND f.tipoalmacen LIKE ('%C%') AND f.seleccionado = 'S' 
				AND f.folioauditoria = (SELECT id FROM reporte_auditoria_principal WHERE folio = $_POST[folioauditoria] AND sucursal = $_POST[sucursalseleccionada]);";
				$r = mysql_query($s,$l);
				while($f = mysql_fetch_object($r)){
					$s = "SELECT f.folio, f.cliente, IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0) total
					FROM facturacion f WHERE f.folio = '$f->factura'";
					$rx = mysql_query($s,$l) or die($s);
					$fx = mysql_fetch_object($rx);
					
					$total += $fx->total;
					
					$s = "INSERT INTO formapago SET guia='$fx->folio',procedencia='F',tipo='X',
					total='$fx->total',efectivo='$fx->total',
					tarjeta='0',transferencia='0',
					cheque='0',ncheque='0',banco='0',notacredito='0',
					nnotacredito='0',sucursal='$_POST[sucursalseleccionada]',
					usuario='$_SESSION[IDUSUARIO]',fecha=CURRENT_DATE, cliente = '$f->folio'";
					mysql_query($s,$l) or die($s);
					$foliopago = mysql_insert_id($l);
					
					$s="call proc_RegistroCobranza('ABONOCLIENTE', '$foliopago', '', '', $foliopago, $fx->cliente);";
					mysql_query($s,$l) or die($s);
					
					$s = "UPDATE guiasventanilla SET recibio = 'AUDITORIA', estado = 'ENTREGADA' WHERE id = '$f->folio'";
					mysql_query($s,$l) or die($s);
					
					#se pone como pagada la guia
					$s="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='$_POST[sucursalseleccionada]' WHERE guia='$f->folio' and tipo<>'FACT'";		
					mysql_query($s,$l) or die($s);
					
					#se registra el pago en el reporte de cobranza tanto guia como factura
					$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$f->folio', '', '', 0, 0);";
					mysql_query($s,$l) or die($s);
					
					$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$f->factura', '', '', 0, 0);";
					mysql_query($s,$l) or die($s);
					
					#en caso de que haya un registro de pago de factura se pasa a pagado
					$s="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='$_POST[sucursalseleccionada]' WHERE guia='$f->factura' and tipo='FACT'";		
					mysql_query($s,$l) or die($s);
					
					#se le pone la C de cobrado a la factura
					$s="UPDATE facturacion SET estadocobranza='C' WHERE folio='$f->factura'";
					mysql_query($s,$l) or die($s);
				}
				
				# facturas de cartera 
				$s = "SELECT f.guia AS folio, f.tipopago
				FROM reporte_auditoria_faltantesobrantes_det f
				WHERE f.sucursal = '$_POST[sucursalseleccionada]' AND tipo = 'F' AND f.tipoalmacen LIKE ('%C%') AND f.seleccionado = 'S'
				AND f.folioauditoria = (SELECT id FROM reporte_auditoria_principal WHERE folio = $_POST[folioauditoria] AND sucursal = $_POST[sucursalseleccionada])";
				$r = mysql_query($s,$l);
				while($f = mysql_fetch_object($r)){
					$s = "SELECT f.folio, f.cliente, IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0) total
					FROM facturacion f WHERE f.folio = '$f->factura'";
					$rx = mysql_query($s,$l) or die($s);
					$fx = mysql_fetch_object($rx);
					
					$total += $fx->total;
					
					$s = "INSERT INTO formapago SET guia='$fx->folio',procedencia='F',tipo='X',
					total='$fx->total',efectivo='$fx->total',
					tarjeta='0',transferencia='0',
					cheque='0',ncheque='0',banco='0',notacredito='0',
					nnotacredito='0',sucursal='$_POST[sucursalseleccionada]',
					usuario='$_SESSION[IDUSUARIO]',fecha=CURRENT_DATE, cliente = '$f->folio'";
					mysql_query($s,$l) or die($s);
					$foliopago = mysql_insert_id($l);
					
					$s="call proc_RegistroCobranza('ABONOCLIENTE', '$foliopago', '', '', $foliopago, $fx->cliente);";
					mysql_query($s,$l) or die($s);
					
					$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$f->factura', '', '', 0, 0);";
					mysql_query($s,$l) or die($s);
					
					#en caso de que haya un registro de pago de factura se pasa a pagado
					$s="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='$_POST[sucursalseleccionada]' WHERE guia='$f->factura' and tipo='FACT'";		
					mysql_query($s,$l) or die($s);
					
					#se le pone la C de cobrado a la factura
					$s="UPDATE facturacion SET estadocobranza='C' WHERE folio='$f->factura'";
					mysql_query($s,$l) or die($s);
					
					#se ponen como pagadas todas las guias de la factura
					$s = "SELECT id FROM guiasventanilla WHERE factura = '$f->factura'
					UNION 
					SELECT id FROM guiasempresariales WHERE factura = '$f->factura'";
					$ry = mysql_query($s,$l) or die($s);
					while($fy = mysql_fetch_object($ry)){
						#se pone como pagada la guia
						$s="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
						sucursalcobro='$_POST[sucursalseleccionada]' WHERE guia='$fy->id' and tipo<>'FACT'";		
						mysql_query($s,$l) or die($s);
						
						#se registra el pago en el reporte de cobranza tanto guia como factura
						$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$fy->id', '', '', 0, 0);";
						mysql_query($s,$l) or die($s);
					}
				}
				
				# guias de inventario
				$s = "SELECT f.guia AS folio, f.tipopago
				FROM reporte_auditoria_faltantesobrantes_det f
				WHERE f.sucursal = '$_POST[sucursalseleccionada]' AND tipo = 'F' AND f.tipoalmacen LIKE ('%I%') AND f.seleccionado = 'S'
				AND f.folioauditoria = (SELECT id FROM reporte_auditoria_principal WHERE folio = $_POST[folioauditoria] AND sucursal = $_POST[sucursalseleccionada])";
				$r = mysql_query($s,$l);
				while($f = mysql_fetch_object($r)){
					$s = "UPDATE guiasventanilla SET recibio = 'AUDITORIA', estado = 'ENTREGADA' WHERE id = '$f->folio'";
					mysql_query($s,$l) or die($s);
				}
			
				if($total>0){
					$s = "INSERT INTO vales
					(idempleado, nombreempleado, idautorizo, nombreautorizo, importe, idsucursal, fechavale, fecha, impreso)
					SELECT ce.id, CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno),
					ce.id, CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno),
					'$total', '$_POST[sucursalseleccionada]', CURRENT_DATE, CURRENT_TIMESTAMP(),'N'
					FROM catalogoempleado ce
					INNER JOIN permisos_empleadospermisos pe ON ce.id = pe.idempleado AND idpermiso = 323
					AND ce.sucursal = '$_POST[sucursalseleccionada]'
					GROUP BY ce.id
					LIMIT 1";
					mysql_query($s,$l) or die($s);
				}
			}
			
		}else{
			
			$s = "INSERT INTO reporte_auditoria_principal SET folio=obtenerFolio('reporte_auditoria_principal',$_POST[sucursalseleccionada]),
			saldoanterior='$_POST[saldoanterior]', inventarioafecha='$_POST[inventarioal]', 
			carteraafecha='$_POST[carteraal]', ajustesal='$_POST[ajustesal]',
			liquidaciones='$_POST[liquidaciones]', depositos='$_POST[depositos]', facturascanceladas='$_POST[facturascanceladas]', 
			guiascanceladas='$_POST[guiascanceladas]', notascredito='$_POST[notasdecredito]',saldocontable='$_POST[saldocontable]', 
			inventariosistema='$_POST[inventariosistema]', carterasistema='$_POST[carterasistema]',
			inventariocierre='$_POST[inventarioactual]', carteracierre='$_POST[carteraalcierre]', saldofinal='$_POST[saldofinal]',
			ajustestotalseleccionado='$_POST[ajustestotalseleccionado]',ajustesrestante='$_POST[ajustesrestante]',
			fechainicio='$fechainicio', fechafin='$fechafin', hora=CURRENT_TIME,sucursal='$_POST[sucursalseleccionada]'";
			$r = mysql_query($s,$l) or die($s);
			$folioauditoria = mysql_insert_id($l);
			
			$s = "UPDATE reporte_auditoria_depositos SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]'
			AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_facturascanceladas SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]'
			AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_guiascanceladas SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]'
			AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_liquidacion SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]'
			AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_sistemainvcar SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]'
			AND fecha BETWEEN '$fechainicio' AND '$fechafin'";
			mysql_query($s,$l) or die($s);
			
			
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes_det
			(guia,fecha,cliente,importe,flete,pago,STATUS,leida,tipo,sucursal,estadoguia,fisico,folioauditoria,tipoalmacen,factura,tipopago)
			SELECT guia,fecha,cliente,importe,flete,pago,STATUS,leida,tipo,sucursal,estadoguia,fisico,'$folioauditoria',tipoalmacen,factura,tipopago
			FROM reporte_auditoria_faltantesobrantes 
			WHERE sucursal = '$_POST[sucursalseleccionada]'";
			mysql_query($s,$l) or die($s);
			
			$_POST[foliosajustes] = "'".str_replace(",","','",$_POST[foliosajustes])."'";
			
			$s = "UPDATE reporte_auditoria_sistemainvcar SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]'";
			
			$s = "UPDATE reporte_auditoria_faltantesobrantes_det SET seleccionado = 'S' 
			WHERE guia IN ($_POST[foliosajustes]) ";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_notacredito SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_leido SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]';";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE reporte_auditoria_ajustes SET folioauditoria = '$folioauditoria'
			WHERE ISNULL(folioauditoria) AND sucursal = '$_POST[sucursalseleccionada]' and usuario = $_SESSION[IDUSUARIO];";
			mysql_query($s,$l) or die($s);
		
		}
		
		echo "_ok_";
	}
	
	if($_GET[accion]==10){
		$s = "SELECT id, IFNULL(saldoanterior,0) saldoanterior, IFNULL(inventarioafecha,0) inventarioafecha,  IFNULL(ajustesal,0) ajustesal,
		IFNULL(carteraafecha,0) AS carteraafecha, DATE_FORMAT(fechainicio, '%d/%m/%Y') AS fecha, 
		DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS factual, inventariosistema, carterasistema, inventariocierre, carteracierre, saldofinal, 
		ajustestotalseleccionado, ajustesrestante, cerrado,
		DATE_FORMAT(adddate(fechainicio, interval -1 day), '%d/%m/%Y') AS fechaanterior,
		DATE_FORMAT(fechafin, '%d/%m/%Y') AS fechaauditoria 
		FROM reporte_auditoria_principal
		WHERE folio = '$_GET[folio]' and sucursal = $_GET[sucursalseleccionada]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$anterior = "{'saldoanterior':'$f->saldoanterior','inventarioal':'$f->inventarioafecha','carteraal':'$f->carteraafecha',
		ajustesal:'$f->ajustesal', fecha:'$f->fecha', 'factual':'$f->factual', 'folioauditoria':'$_GET[folio]', 
		'inventariocierre':'$f->inventariocierre', 'carteracierre':'$f->carteracierre', 'saldofinal':'$f->saldofinal',
		'ajustestotalseleccionado':'$f->ajustestotalseleccionado', 'ajustesrestante':'$f->ajustesrestante', 
		'fechaanterior':'$f->fechaanterior', 'fechaauditoria':'$f->fechaauditoria',
		'inventariosistema':'$f->inventariosistema', 'carterasistema':'$f->carterasistema', 'cerrado':'$f->cerrado'}";
		
		$folioauditoria = $f->id;
		
		//LIQUIDACIONES
		$s = "SELECT IFNULL(SUM(total),0) as total FROM reporte_auditoria_liquidacion
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$liquidaciones = $f->total;
		}else{
			$liquidaciones = 0;
		}
		
		//depositos
		$s = "SELECT IFNULL(SUM(importe),0) as total FROM reporte_auditoria_depositos
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$depositos = $f->total;
		}else{
			$depositos = 0;
		}
		
		//nota credito
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_notacredito
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$notacredito = $f->total;
		}else{
			$notacredito = 0;
		}
		
		//guias canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_guiascanceladas
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$guiascanceladas = $f->total;
		}else{
			$guiascanceladas = 0;
		}
		
		//facturas canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total 
		FROM reporte_auditoria_facturascanceladas
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$facturascanceladas = $f->total;
		}else{
			$facturascanceladas = 0;
		}
		
		# **************** ACTUALIZAR SI YA SE FACTURARON LOS SOBRANTES
		$s = "UPDATE reporte_auditoria_faltantesobrantes_det d
		INNER JOIN guiasventanilla g ON d.guia = g.id
		SET d.factura = ifnull(g.factura,0) WHERE d.folioauditoria = '$folioauditoria'";
		mysql_query($s,$l) or die($s);
		
		# **************** PEDIR FALTANTESOSBRANTES
		$s = "SELECT 1 seleccion, guia, cliente, importe, fisico, ifnull(estadoguia,'') estadoguia, seleccionado, tipoalmacen, factura, tipopago, tipo
		FROM reporte_auditoria_faltantesobrantes_det WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
			$arre[] = $f;
		}
		
		$registros = json_encode($arre);
		# *****************************************
		
		echo "({
				'anterior':$anterior,
				'liquidaciones':'$liquidaciones',
				'depositos':'$depositos',
				'notacredito':'$notacredito',
				'guiascanceladas':'$guiascanceladas',
				'facturascanceladas':'$facturascanceladas',
				'faltsob':$registros
			})";
	}
	
	if($_GET[accion]==11){		
		$s = "insert into reporte_auditoria_ajustes 
		set cantidad = '$_GET[cantidad]', concepto='$_GET[concepto]', tipoajuste = '$_GET[tipoajuste]',
		sucursal = '$_GET[sucursalseleccionada]', usuario='$_SESSION[IDUSUARIO]', fecha = current_date";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "ok";
	}
	
	if($_GET[accion]==12){//cartera
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio]  AND cartera = 'S' ":"WHERE sucursal = $_GET[sucursalseleccionada] AND cartera = 'S' and ISNULL(folioauditoria)");
		
		$s = "SELECT folio, CONCAT(IF(inventario='S','INV',''),',',IF(cartera='S','CART','')) AS tipo, fecha, cliente, importe, tipoflete, tipopago
		FROM reporte_auditoria_sistemainvcar 
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT folio, CONCAT(IF(inventario='S','INV',''),',',IF(cartera='S','CART','')) AS tipo, fecha, cliente, importe, tipoflete, tipopago
		FROM reporte_auditoria_sistemainvcar 
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
		
	}
	
	if($_GET[accion]==13){//inventario
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_GET[sucursalseleccionada] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio]  AND inventario = 'S' ":"WHERE sucursal = $_GET[sucursalseleccionada]  AND inventario = 'S' and ISNULL(folioauditoria)");
		
		$s = "SELECT folio, CONCAT(IF(inventario='S','INV',''),',',IF(cartera='S','CART','')) AS tipo, fecha, cliente, importe, tipoflete, tipopago
		FROM reporte_auditoria_sistemainvcar
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT folio, CONCAT(IF(inventario='S','INV',''),',',IF(cartera='S','CART','')) AS tipo, fecha, cliente, if(cartera='S',0,importe) importe, tipoflete, tipopago
		FROM reporte_auditoria_sistemainvcar
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
		
	}
?>
