<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		
		$s = "SELECT IFNULL(MAX(folio),0)+1 folio FROM reporte_auditoria_principal WHERE sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$foliosucursal = $f->folio;
		
		$s = "SELECT ifnull(saldoanterior,0) saldoanterior, ifnull(inventarioafecha,0) inventarioafecha, ifnull(carteraafecha,0) as carteraafecha, 
		date_format(fecha, '%d/%m/%Y') as fecha, date_format(current_date, '%d/%m/%Y') as factual, date_format(adddate(current_date, interval -1 day), '%d/%m/%Y') as fechaanterior
		FROM reporte_auditoria_principal
		WHERE sucursal = $_SESSION[IDSUCURSAL]
		ORDER BY folio DESC LIMIT 1";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$anterior = "{'saldoanterior':'$f->saldoanterior','inventarioal':'$f->inventarioafecha','carteraal':'$f->carteraafecha',
			fecha:'$f->fecha', 'factual':'$f->factual', 'folioauditoria':'$foliosucursal', 'fechaanterior':'$f->fechaanterior'}";
		}else{
			$anterior = "{'saldoanterior':'0','inventarioal':'0','carteraal':'0', 'fecha':'', 'factual':'".date("d/m/Y")."', 'folioauditoria':'$foliosucursal'}";
		}	
		
		# PARA ENTREGADAS Y PAGADAS
		
		//se limpian los estados
		$s = "UPDATE reporte_auditoria_liquidacion SET estado = '' WHERE sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditorias_fac SET estado = '' WHERE sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditorias_guias SET estado2 = '' WHERE sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s);	
		
		
		//verificar si las guias estan entregadas para eliminarlas
		$s = "UPDATE reporte_auditoria_liquidacion ral
		INNER JOIN guiasventanilla gv ON ral.guia = gv.id 
		SET ral.estado = IF(gv.estado = 'ENTREGADA','BP','')
		WHERE ral.sucursal = '$_SESSION[IDSUCURSAL]' AND ral.tipo <> 'F';";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_liquidacion ral
		INNER JOIN guiasempresariales ge ON ral.guia = ge.id 
		SET ral.estado = IF(ge.estado = 'ENTREGADA','BP','')
		WHERE ral.sucursal = '$_SESSION[IDSUCURSAL]' AND ral.tipo <> 'F';";
		$r = mysql_query($s,$l) or die($s);
		
		//verificar si las facturas estan pagadas
		$s = "UPDATE reporte_auditoria_liquidacion ral
		INNER JOIN facturacion f ON ral.guia = f.folio
		SET ral.estado = IF(f.facturaestado = 'GUARDADO' AND f.estadocobranza = 'C','BP','')
		WHERE ral.sucursal = '$_SESSION[IDSUCURSAL]' AND ral.tipo = 'F';";
		$r = mysql_query($s,$l) or die($s);
		
		# PARA CANCELACION **************************
		
		//verificar si las guias estan canceladas para eliminarlas al guardar
		$s = "UPDATE reporte_auditoria_liquidacion ral
		INNER JOIN guiasventanilla gv ON ral.guia = gv.id 
		SET ral.estado = IF(gv.estado = 'CANCELADA' OR gv.estado = 'CANCELADO','BF','')
		WHERE ral.sucursal = '$_SESSION[IDSUCURSAL]' AND ral.tipo <> 'F';";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_liquidacion ral
		INNER JOIN guiasempresariales ge ON ral.guia = ge.id 
		SET ral.estado = IF(ge.estado = 'CANCELADA' OR ge.estado = 'CANCELADO','BF','')
		WHERE ral.sucursal = '$_SESSION[IDSUCURSAL]' AND ral.tipo <> 'F';";
		$r = mysql_query($s,$l) or die($s);
		
		//verificar si las facturas estan canceladas para eliminarlas al guardar
		$s = "UPDATE reporte_auditoria_liquidacion ral
		INNER JOIN facturacion f ON ral.guia = f.folio
		SET ral.estado = IF(f.facturaestado = 'CANCELADO','BF','')
		WHERE ral.sucursal = '$_SESSION[IDSUCURSAL]' AND ral.tipo = 'F';";
		$r = mysql_query($s,$l) or die($s);
		
		
		# BORRANDO LOS PRINCIPALES 
		$s = "DELETE FROM reporte_auditoria_liquidacion WHERE estado = 'BP' AND sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		
		
		//LIQUIDACIONES
		$s = "SELECT IFNULL(SUM(total),0) AS total 
		FROM reporte_auditoria_liquidacion
		WHERE sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$liquidaciones = $f->total;
		}else{
			$liquidaciones = 0;
		}
		
		//depositos
		$s = "SELECT IFNULL(SUM(importe),0) as total FROM reporte_auditoria_depositos
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
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
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
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
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
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
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$facturascanceladas = $f->total;
		}else{
			$facturascanceladas = 0;
		}
		
		
		#METER INVENTARIO
		$s = "delete from reporte_auditoria_sistemainventario where sucursaldestino = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_sistemainventario
		(guia, fecha, cliente, origen, destino, importe, sucursaldestino)
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		cs1.prefijo, cs2.prefijo, gv.total, gv.idsucursaldestino
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN catalogosucursal cs1 ON gv.idsucursalorigen = cs1.id
		INNER JOIN catalogosucursal cs2 ON gv.idsucursaldestino = cs2.id
		WHERE gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]' AND gv.estado = 'ALMACEN DESTINO')
		UNION
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		cs1.prefijo, cs2.prefijo, gv.total, gv.idsucursaldestino
		FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN catalogosucursal cs1 ON gv.idsucursalorigen = cs1.id
		INNER JOIN catalogosucursal cs2 ON gv.idsucursaldestino = cs2.id
		WHERE gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]' AND gv.estado = 'ALMACEN DESTINO')";
		$r = mysql_query($s,$l) or die($s);
		
		#meter la cartera
		$s = "delete from reporte_auditoria_sistemacartera where sucursaldestino = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_sistemacartera
		(guia, fecha, cliente, origen, destino, importe, sucursaldestino)
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		cs1.prefijo, cs2.prefijo, gv.total, gv.idsucursaldestino
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN catalogosucursal cs1 ON gv.idsucursalorigen = cs1.id
		INNER JOIN catalogosucursal cs2 ON gv.idsucursaldestino = cs2.id
		WHERE gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]' AND gv.estado = 'ALMACEN DESTINO' AND gv.tipoflete=1 AND
		( gv.condicionpago = 1 AND (ISNULL(gv.factura) OR gv.factura = 0 ) OR  gv.condicionpago = 0))
		UNION
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		cs1.prefijo, cs2.prefijo, gv.total, gv.idsucursaldestino
		FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN catalogosucursal cs1 ON gv.idsucursalorigen = cs1.id
		INNER JOIN catalogosucursal cs2 ON gv.idsucursaldestino = cs2.id
		WHERE gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]' AND gv.estado = 'ALMACEN DESTINO' AND gv.tipoflete='POR COBRAR' AND
		( gv.tipopago = 'CREDITO' AND (ISNULL(gv.factura) OR gv.factura = 0 )  OR  gv.tipopago = 'CONTADO') )";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_sistemacartera
		(guia, fecha, cliente, origen, destino, 
		importe, sucursaldestino)
		SELECT f.folio, f.fecha, f.cliente, cs.prefijo, NULL, 
		IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0), f.idsucursal
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT
		(SELECT IFNULL(SUM(importe),0) 
		FROM reporte_auditoria_sistemacartera WHERE sucursaldestino = '$_SESSION[IDSUCURSAL]'
		AND ISNULL(folioauditoria)) carterasistema,
		(SELECT IFNULL(SUM(importe),0) 
		FROM reporte_auditoria_sistemainventario WHERE sucursaldestino = '$_SESSION[IDSUCURSAL]'
		AND ISNULL(folioauditoria)) inventariosistema";
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
		$s = "DELETE FROM reporte_auditoria_faltantesobrantes WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_auditoria_leido WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_auditorias_guias WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_auditorias_guias WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND idusuario = '$_SESSION[IDUSUARIO]';";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_auditoria_faltantesobrantes WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		#inserttar en guias...
		$s = "INSERT INTO reporte_auditorias_guias 
		SELECT SUBSTRING(rap.folio,1,13),rap.fecha,rap.hora,rap.sucursal,'$_SESSION[IDUSUARIO]',
		IF(COUNT(rap.id)=SUBSTRING(rap.folio,18,4),'C','I'), null
		FROM reporte_auditorias_paq rap
		WHERE sucursal = '$_SESSION[IDSUCURSAL]'
		GROUP BY SUBSTRING(rap.folio,1,13);";
		mysql_query($s,$l) or die($s);
		
		#meter en leido las guias leidas
		$s = "INSERT INTO reporte_auditoria_leido
		(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal)
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		gv.total, gv.tflete, IF(gv.condicionpago=0,'CONTADO', 'CREDITO'), gv.estado,
		'S', 'G','$_SESSION[IDSUCURSAL]'
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN reporte_auditorias_guias rp ON gv.id = rp.folio
		WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]' and rp.idusuario = '$_SESSION[IDUSUARIO]' and rp.estado='C')
		UNION
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		gv.total, gv.tflete, gv.tipopago, gv.estado,
		'S','G','$_SESSION[IDSUCURSAL]'
		FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN reporte_auditorias_guias rp ON gv.id = rp.folio
		WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]' and rp.idusuario = '$_SESSION[IDUSUARIO]' and rp.estado='C')";
		$r = mysql_query($s,$l) or die($s);
		
		#meter en leido las 
		$s = "INSERT INTO reporte_auditoria_leido
		(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal)
		SELECT f.folio, f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
		IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0),
		0,IF(f.credito='SI','CREDITO','CONTADO'), f.estado, 'S', 'F', '$_SESSION[IDSUCURSAL]'
		FROM facturacion AS f
		INNER JOIN reporte_auditorias_fac rf ON f.folio = rf.folio
		WHERE rf.sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);		
		
		# **************** Actualizando los estados
		$s = "UPDATE reporte_auditorias_fac raf
		INNER JOIN reporte_auditoria_liquidacion rlq ON raf.folio = rlq.guia
		SET rlq.estado = 'SI', raf.estado = 'SI'
		WHERE rlq.sucursal = '$_SESSION[IDSUCURSAL]' AND raf.sucursal = '$_SESSION[IDSUCURSAL]';";
		$r = mysql_query($s,$l) or die($s);	
		
		$s = "UPDATE reporte_auditorias_guias rag
		INNER JOIN reporte_auditoria_liquidacion rlq ON rag.folio = rlq.guia
		SET rlq.estado = 'SI', rag.estado = 'SI'
		WHERE rlq.sucursal = '$_SESSION[IDSUCURSAL]' AND rag.sucursal = '$_SESSION[IDSUCURSAL]';";
		$r = mysql_query($s,$l) or die($s);	
		# **************** *************************
		
		# **************** Actualizando los estados
			#checar facturas sobrantes ************
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico)
			SELECT rf.folio, f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
			IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0),
			0,IF(f.credito='SI','CREDITO','CONTADO'), f.facturaestado, 'S', 'F', '$_SESSION[IDSUCURSAL]','SOBRANTE'
			FROM reporte_auditorias_fac AS rf
			LEFT JOIN facturacion f ON f.folio = rf.folio
			WHERE rf.sucursal = '$_SESSION[IDSUCURSAL]'";
			$r = mysql_query($s,$l) or die($s);	
			
			#checar guias sobrantes ***************
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico, estadoguia)
			(SELECT rp.folio, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			ifnull(gv.total,0), ifnull(gv.tflete,0), IF(gv.condicionpago=0,'CONTADO', 'CREDITO'), gv.estado,
			'S', 'G','$_SESSION[IDSUCURSAL]','SOBRANTE', ifnull(rp.estado,'')
			FROM reporte_auditorias_guias rp
			LEFT JOIN guiasventanilla gv ON gv.id = rp.folio
			LEFT JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]' AND rp.idusuario = '$_SESSION[IDUSUARIO]' 
			and substring(rp.folio,1,3)<>'999' and rp.estado <> 'SI')
			UNION
			(SELECT rp.folio, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			ifnull(gv.total,0), ifnull(gv.tflete,0), gv.tipopago, gv.estado,
			'S','G','$_SESSION[IDSUCURSAL]','SOBRANTE', ifnull(rp.estado,'')
			FROM reporte_auditorias_guias rp
			LEFT JOIN guiasempresariales gv ON gv.id = rp.folio
			LEFT JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]' AND rp.idusuario = '$_SESSION[IDUSUARIO]'
			and substring(rp.folio,1,3)='999' and rp.estado <> 'SI')";
			$r = mysql_query($s,$l) or die($s);	
			
			#checar los faltantes facturas ****************
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico)
			SELECT ral.guia, f.fecha, CONCAT_WS(nombrecliente, apellidopaternocliente, apellidomaternocliente),
			IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0),
			0,IF(f.credito='SI','CREDITO','CONTADO'), f.facturaestado, 'S', 'F', '$_SESSION[IDSUCURSAL]','FALTANTE'
			FROM reporte_auditoria_liquidacion ral
			INNER JOIN facturacion f ON ral.guia = f.folio AND ral.tipo = 'F' AND ral.estado = ''
			WHERE ral.sucursal = '$_SESSION[IDSUCURSAL]'";
			$r = mysql_query($s,$l) or die($s);
			
			#checar los faltantes guias ****************
			$s = "INSERT INTO reporte_auditoria_faltantesobrantes
			(guia, fecha, cliente, importe, flete, pago, status, leida, tipo, sucursal, fisico)
			(SELECT rp.guia, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			gv.total, gv.tflete, IF(gv.condicionpago=0,'CONTADO', 'CREDITO'), gv.estado,
			'S', 'G','$_SESSION[IDSUCURSAL]','FALTANTE'
			FROM reporte_auditoria_liquidacion rp
			INNER JOIN guiasventanilla gv ON gv.id = rp.guia
			INNER JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]' AND rp.estado = '')
			UNION
			(SELECT rp.guia, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
			gv.total, gv.tflete, gv.tipopago, gv.estado,
			'S','G','$_SESSION[IDSUCURSAL]','FALTANTE'
			FROM reporte_auditoria_liquidacion rp
			INNER JOIN guiasempresariales gv ON gv.id = rp.guia
			INNER JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
			WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]' AND rp.estado = '')";
			$r = mysql_query($s,$l) or die($s);
			
		# **************** *************************
		
		# **************** PEDIR FALTANTESOSBRANTES
		$s = "SELECT 1 seleccion, guia, cliente, importe, fisico, ifnull(estadoguia,'') estadoguia
		FROM reporte_auditoria_faltantesobrantes WHERE sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
			$arre[] = $f;
		}
		
		$registros = json_encode($arre);
		# *****************************************
		
		$s = "SELECT IFNULL(SUM(IF(tipo='F',importe,0)),0) factura,  
		IFNULL(SUM(IF(tipo<>'F',importe,0)),0) guia
		FROM reporte_auditoria_leido WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND 
		ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cartera = $f->factura;
			$inventario = $f->guia;
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
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
			
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
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		
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
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
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
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
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
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
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
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
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
	
	if($_GET[accion]==9){
		$s = "INSERT INTO reporte_auditoria_principal SET folio=obtenerFolio('reporte_auditoria_principal',$_SESSION[IDSUCURSAL]),
		saldoanterior='$_GET[saldoanterior]', inventarioafecha='$_GET[inventarioal]', carteraafecha='$_GET[carteraal]', 
		liquidaciones='$_GET[liquidaciones]', depositos='$_GET[depositos]', facturascanceladas='$_GET[facturascanceladas]', 
		guiascanceladas='$_GET[guiascanceladas]', notascredito='$_GET[notasdecredito]',saldocontable='$_GET[saldocontable]', 
		inventariocierre='$_GET[inventarioactual]', carteracierre='$_GET[carteraalcierre]', saldofinal='$_GET[saldofinal]',
		ajustestotalseleccionado='$_GET[ajustestotalseleccionado]',ajustesrestante='$_GET[ajustesrestante]',
		fecha=CURRENT_DATE,hora=CURRENT_TIME,sucursal='$_SESSION[IDSUCURSAL]';";
		$r = mysql_query($s,$l) or die($s);
		$folioauditoria = mysql_insert_id($l);		
		
		$s = "UPDATE reporte_auditoria_depositos SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_facturascanceladas SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_guiascanceladas SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_liquidaciondetalle
		(sucursal, guia,flete,EAD, RAD, seguro, otros, excedente, combustible,ivaretenido,iva,total,tipo,fecha,folioauditoria)
		SELECT sucursal, guia,flete,EAD, RAD, seguro, otros, excedente, combustible,ivaretenido,iva,total,tipo,fecha,'$folioauditoria'
		FROM reporte_auditoria_liquidacion WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_faltantesobrantes_det
		(guia,fecha,cliente,importe,flete,pago,STATUS,leida,tipo,sucursal,estadoguia,fisico,folioauditoria)
		SELECT guia,fecha,cliente,importe,flete,pago,STATUS,leida,tipo,sucursal,estadoguia,fisico,'$folioauditoria'
		FROM reporte_auditoria_faltantesobrantes 
		WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$_GET[foliosajustes] = "'".str_replace(",","','",$_GET[foliosajustes])."'";
		
		$s = "UPDATE reporte_auditoria_faltantesobrantes_det
		SET seleccionado = 'S' 
		WHERE guia IN ($_GET[foliosajustes]) ";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_notacredito SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_leido SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_ajustes SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]' and usuario = $_SESSION[IDUSUARIO];";
		mysql_query($s,$l) or die($s);
		
		echo "_ok_";
	}
	
	if($_GET[accion]==10){		
		$s = "SELECT id, ifnull(saldoanterior,0) saldoanterior, ifnull(inventarioafecha,0) inventarioafecha, ifnull(carteraafecha,0) as carteraafecha, 
		date_format(fecha, '%d/%m/%Y') as fecha, date_format(current_date, '%d/%m/%Y') as factual,
		inventariocierre, carteracierre, saldofinal, ajustestotalseleccionado, ajustesrestante, date_format(adddate(fecha, interval -1 day), '%d/%m/%Y') as fechaanterior
		FROM reporte_auditoria_principal
		WHERE folio = '$_GET[folio]' and sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$anterior = "{'saldoanterior':'$f->saldoanterior','inventarioal':'$f->inventarioafecha','carteraal':'$f->carteraafecha',
		fecha:'$f->fecha', 'factual':'$f->factual', 'folioauditoria':'$_GET[folio]', 
		'inventariocierre':'$f->inventariocierre', 'carteracierre':'$f->carteracierre', 'saldofinal':'$f->saldofinal',
		'ajustestotalseleccionado':'$f->ajustestotalseleccionado', 'ajustesrestante':'$f->ajustesrestante', 'fechaanterior':'$f->fechaanterior'}";
		
		$folioauditoria = $f->id;
		
		//LIQUIDACIONES
		$s = "SELECT IFNULL(SUM(total),0) AS total 
		FROM reporte_auditoria_liquidaciondetalle
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
		
		# **************** PEDIR FALTANTESOSBRANTES
		$s = "SELECT 1 seleccion, guia, cliente, importe, fisico, ifnull(estadoguia,'') estadoguia, seleccionado
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
		sucursal = '$_SESSION[IDSUCURSAL]', usuario='$_SESSION[IDUSUARIO]', fecha = current_date";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "ok";
	}
	
	if($_GET[accion]==12){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursaldestino = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		$s = "SELECT * 
		FROM reporte_auditoria_sistemacartera
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_sistemacartera
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
	
	if($_GET[accion]==13){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursaldestino = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		$s = "SELECT * 
		FROM reporte_auditoria_sistemainventario
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_sistemainventario
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
