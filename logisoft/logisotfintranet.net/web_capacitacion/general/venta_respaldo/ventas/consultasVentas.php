<?
	require_once('../../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == 1){//STD-ProcedimientoReporteVentas_PM
		$row = split(",",$_GET[arre]);
		$s = "SELECT sucursal, SUM(convenio) AS convenio, SUM(sinconvenio) AS sinconvenio,
		SUM((convenio + sinconvenio)) AS total FROM 
		(SELECT cs.prefijo AS sucursal, IFNULL(SUM(IF(gv.convenioaplicado<>'',gv.total,0)),0) AS convenio,
		IFNULL(SUM(IF(gv.convenioaplicado='' OR gv.convenioaplicado IS NULL,gv.total,0)),0) AS sinconvenio
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.estado<>'CANCELADO' 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($row[0])."' AND '".cambiaf_a_mysql($row[1])."'		
		GROUP BY gv.idsucursalorigen
		UNION
		SELECT cs.prefijo AS sucursal, IFNULL(SUM(IF(ge.convenioaplicado<>'',ge.total,0)),0) AS convenio,
		IFNULL(SUM(IF(ge.convenioaplicado='' OR ge.convenioaplicado IS NULL,ge.total,0)),0) AS sinconvenio
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE ge.estado<>'CANCELADO' AND
		ge.fecha BETWEEN '".cambiaf_a_mysql($row[0])."' AND '".cambiaf_a_mysql($row[1])."'
		GROUP BY ge.idsucursalorigen) tabla
		GROUP BY sucursal";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->sucursal);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion] == 2){//STD-ProcedimientoReporteTipodeVentas_PM
		$s = "SELECT sucursal, SUM(normales) AS normales, SUM(prepagadas) AS prepagadas,
		SUM(consignacion) AS consignacion, 
		SUM((normales + prepagadas + consignacion)) AS total FROM 
		(SELECT cs.prefijo AS sucursal,
		IFNULL(SUM(IF(gv.convenioaplicado<>'',gv.total,0)),0) AS normales,0 AS prepagadas,
		0 AS consignacion
		FROM guiasventanilla gv 
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND gv.estado<>'CANCELADO' 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY gv.idsucursalorigen
		UNION
		SELECT cs.prefijo AS sucursal, 0 AS normales,
		IFNULL(SUM(IF(ge.tipoguia='PREPAGADA',ge.total,0)),0) AS prepagadas,
		IFNULL(SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)),0) AS consignacion
		FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND ge.estado<>'CANCELADO' 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'		
		GROUP BY ge.idsucursalorigen) tabla";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->sucursal);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 3){
		
		if ($_GET[tipo]=="1"){
			$s="SELECT * FROM (
		SELECT cs.prefijo AS sucursal,
		SUM(IF(gv.condicionpago=0 AND gv.tipoflete=0,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,0,gv.total),0),0)) AS contados,
		SUM(IF(gv.condicionpago=1 AND gv.tipoflete=0,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,0,gv.total),0),0)) AS credito,
		SUM(IF(gv.condicionpago=0 AND gv.tipoflete=1,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,0,gv.total),0),0)) AS cobcontado,
		SUM(IF(gv.condicionpago=1 AND gv.tipoflete=1,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,0,gv.total),0),0)) AS cobcredito
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE cs.prefijo =  '".$_GET[sucursal]."' 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.estado <> 'CANCELADO'
		GROUP BY cs.prefijo
		UNION
		SELECT cs.prefijo AS sucursal,
		SUM(IF(ge.tipopago='CONTADO' AND ge.tipoflete='PAGADO',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,0,ge.total),0),0)) AS contados,
		SUM(IF(ge.tipopago='CREDITO' AND ge.tipoflete='PAGADO',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,0,ge.total),0),0)) AS credito,
		SUM(IF(ge.tipopago='CONTADO' AND ge.tipoflete='POR COBRAR',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,0,ge.total),0),0)) AS cobcontado,
		SUM(IF(ge.tipopago='CREDITO' AND ge.tipoflete='POR COBRAR',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,0,ge.total),0),0)) AS cobcredito
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' 
		AND ge.fecha BETWEEN  '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND ge.estado <> 'CANCELADO'
		GROUP BY cs.prefijo) t
		WHERE contados>0 OR credito>0 OR cobcontado>0 OR cobcredito>0";		
	}else if ($_GET[tipo]=="2"){
	
		$s="SELECT * FROM (
		SELECT cs.prefijo AS sucursal,
		SUM(IF(gv.condicionpago=0 AND gv.tipoflete=0,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,gv.total,0),0),0)) AS contados,
		SUM(IF(gv.condicionpago=1 AND gv.tipoflete=0,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,gv.total,0),0),0)) AS credito,
		SUM(IF(gv.condicionpago=0 AND gv.tipoflete=1,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,gv.total,0),0),0)) AS cobcontado,
		SUM(IF(gv.condicionpago=1 AND gv.tipoflete=1,IFNULL(IF(gv.convenioaplicado='0' OR gv.convenioaplicado IS NULL,gv.total,0),0),0)) AS cobcredito
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' 
		AND gv.fecha BETWEEN  '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.estado <> 'CANCELADO'
		GROUP BY cs.prefijo
		UNION
		SELECT cs.prefijo AS sucursal,
		SUM(IF(ge.tipopago='CONTADO' AND ge.tipoflete='PAGADO',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,ge.total,0),0),0)) AS contados,
		SUM(IF(ge.tipopago='CREDITO' AND ge.tipoflete='PAGADO',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,ge.total,0),0),0)) AS credito,
		SUM(IF(ge.tipopago='CONTADO' AND ge.tipoflete='POR COBRAR',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,ge.total,0),0),0)) AS cobcontado,
		SUM(IF(ge.tipopago='CREDITO' AND ge.tipoflete='POR COBRAR',IFNULL(IF(ge.convenioaplicado='0' OR ge.convenioaplicado IS NULL,ge.total,0),0),0)) AS cobcredito
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' 
		AND ge.fecha BETWEEN   '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND ge.estado <> 'CANCELADO'
		GROUP BY cs.prefijo) t
		WHERE contados>0 OR credito>0 OR cobcontado>0 OR cobcredito>0";
		
	}
		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->sucursal);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion] == 4){//STD-ProcedimientoReporteVentasalContado_PM
		if ($_GET[tipo]=="1"){
			$s = "Select cliente,nombre,destino,guia,importe FROM (
			SELECT gv.idremitente AS cliente,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destino,
			gv.id AS guia, IFNULL(gv.total,0) AS importe
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.estado<>'CANCELADO' AND cs.prefijo = '".$_GET[sucursal]."' AND gv.condicionpago=0
			AND gv.tipoflete=0 AND gv.convenioaplicado<>0
			AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')Tabla 
			LIMIT ".$_GET[inicio].",30";
			
		}else if ($_GET[tipo]=="2"){
			$s = "Select cliente,nombre,destino,guia,importe FROM (
			SELECT gv.idremitente AS cliente,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destino,
			gv.id AS guia, IFNULL(gv.total,0) AS importe
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.estado<>'CANCELADO' AND cs.prefijo = '".$_GET[sucursal]."' AND gv.condicionpago=0 
			AND gv.tipoflete=0 AND gv.convenioaplicado=0
			AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')Tabla 
			LIMIT ".$_GET[inicio].",30";
			
		}
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$f->destino = cambio_texto($f->destino);
				//$f->sucursal = cambio_texto($f->sucursal);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 5){//STD-ProcedimientoReporteVentasxsucursal_PM
		if ($_GET[tipo]=="1"){
			$s = "SELECT gv.idremitente AS cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
			CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destino, gv.id AS guia, 
			IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condicionpago, IFNULL(gv.total,0) AS importe 
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
			INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE cs.prefijo = '".$_GET[sucursal]."' AND gv.convenioaplicado<>0 AND gv.estado<>'CANCELADO' 
			AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."'
			AND '".cambiaf_a_mysql($_GET[fechafin])."' LIMIT ".$_GET[inicio].",30";		
		}else if ($_GET[tipo]=="2"){
			$s = "SELECT gv.idremitente AS cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
			CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destino, gv.id AS guia, 
			IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condicionpago, IFNULL(gv.total,0) AS importe 
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
			INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE cs.prefijo = '".$_GET[sucursal]."' AND gv.convenioaplicado=0 AND gv.estado<>'CANCELADO' 
			AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."'
			AND '".cambiaf_a_mysql($_GET[fechafin])."' LIMIT ".$_GET[inicio].",30";		
		
		}
		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$f->destino = cambio_texto($f->destino);
				//$f->sucursal = cambio_texto($f->sucursal);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 6){//REPORTE PEDIENTES DE FACTURAR
		$s = "SELECT DATE_FORMAT(ge.fecha,'%d/%m/%Y')AS fecha, ori.prefijo AS origen,
		des.prefijo AS destino, ge.id AS guia,
		IFNULL(ge.valordeclarado,0) AS valordeclarado, IFNULL(ge.tflete,0)AS tflete, 
		SUM(de.kgexcedente) AS excedente, IFNULL(sub.costoead,0) AS subdestino,
		IFNULL(ge.tseguro,0) AS costoseg, IFNULL(ge.tcombustible,0) AS cargocombustible, 
		IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
		IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total,ge.factura,
		SUM(de.cantidad) AS cantidad, SUM(de.peso) AS kilogramos
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN guiasempresariales_detalle de ON ge.id = de.id
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
		LEFT JOIN catalogodestino sub ON ge.idsucursaldestino = sub.id
		WHERE ge.estado<>'CANCELADO' AND ge.tipoguia = 'PREPAGADA' AND 
		ge.idremitente = ".(($_GET[cliente]!='')?$_GET[cliente]:0)."
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY ge.fecha";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 7){//consignacion
		$s="SELECT cc.id AS cliente,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		IFNULL(s.cantidad,0) AS cantidadfolios, IFNULL(f.folio,0) AS factura,
		IFNULL((f.total + f.sobmontoafacturar),0) AS importefactura,
		IFNULL(f.otrosmontofacturar,0) AS serviciosadicionales,
		ge.total
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		LEFT JOIN solicitudguiasempresariales s ON ge.idsolicitudguia	= s.id
		LEFT JOIN facturacion f ON ge.factura=f.folio
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND ge.tipoguia='CONSIGNACION' AND ge.estado<>'CANCELADO' 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."' LIMIT ".$_GET[inicio].",30";
		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo 0;
		}
	}else if($_GET[accion] == 8){//STD-ProcedimientoReporteVentaxPrepagadas_PM
		$s="SELECT cs.prefijo as sucursal,cc.id AS cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,
		s.foliotipo AS venta, IFNULL(CONCAT_WS(' - ',s.desdefolio,s.hastafolio),0) AS folios, ifnull(f.folio,0)as folio,
		IFNULL(IF(ge.factura IS NOT NULL OR ge.factura<>0,ge.total,0),0) AS importe,
		IFNULL(s.total,0) AS porfacturar
		FROM guiasempresariales ge
		LEFT JOIN facturacion f ON ge.factura=f.folio		
		INNER JOIN catalogocliente cc ON cc.id=ge.idremitente		
		LEFT JOIN solicitudguiasempresariales s ON ge.idsolicitudguia = s.id		
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND ge.tipoguia = 'PREPAGADA' AND ge.estado<>'CANCELADO' 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY s.id LIMIT 0,30";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 9){//Reporte de Relación de conceptos por consignación		
		$s = "SELECT DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, ori.prefijo AS origen,
		des.prefijo AS destino, ge.id AS guia,
		IFNULL(ge.valordeclarado,0) AS valordeclarado, IFNULL(ge.tflete,0) AS tflete,
		SUM(de.kgexcedente) AS excedente, IFNULL(sub.costoead,0) AS subdestino,
		IFNULL(ge.tseguro,0) AS costoseg, IFNULL(ge.tcombustible,0) AS cargocombustible, 
		IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
		IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total, ge.factura,
		SUM(de.cantidad) AS cantidad, SUM(de.peso) AS kilogramos
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN guiasempresariales_detalle de ON ge.id = de.id
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
		LEFT JOIN catalogodestino sub ON ge.idsucursaldestino = sub.id
		WHERE ge.tipoguia = 'CONSIGNACION' AND cc.id = ".$_GET[cliente]." AND ge.estado<>'CANCELADO' 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY ge.fecha";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){				
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 10){//Reporte de Ventas por Cliente tabla 1
		$s = "SELECT DATE_FORMAT(gv.fecha,'%d/%m/%Y')AS fecha, gv.id AS guia,
		cs.prefijo AS sucursal, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		IF(gv.tipoflete=0 AND gv.condicionpago=0,'PAG-CONTADO',
		IF(gv.tipoflete=0 AND gv.condicionpago=1,'PAG-CREDITO',
		IF(gv.tipoflete=1 AND gv.condicionpago=0,'COB-CONTADO',
		IF(gv.tipoflete=1 AND gv.condicionpago=1,'COB-CREDITO','')))) AS flete,
		IF(gv.ocurre=1,'OCURRE','EAD') AS envio,
		SUM(d.cantidad) AS paquete, SUM(d.peso) AS kilogramos, gv.total,
		gv.estado, (SELECT eo.personaquerecibe FROM entregasocurre eo
		INNER JOIN entregasocurre_detalle eod ON eo.folio = eod.entregaocurre
		WHERE eod.guia=gv.id AND eod.entregada = 1) AS recibio,
		IF(gv.ocurre=1,gv.entregaocurre,gv.entregaead) AS diasentrega
		FROM guiasventanilla gv
		INNER JOIN guiaventanilla_detalle d ON gv.id = d.idguia
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idremitente = ".$_GET[cliente]." AND gv.estado<>'CANCELADO' 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		
		GROUP BY gv.id
		HAVING gv.id IS NOT NULL AND cs.prefijo IS NOT NULL";		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){				
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion] == 11){//Reporte de Ventas por Cliente tabla 2
		$s = "SELECT DATE_FORMAT(gv.fecha,'%d/%m/%Y')AS fecha, gv.id AS guia,
		cs.prefijo AS sucursal, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		IF(gv.tipoflete=0 AND gv.condicionpago=0,'PAG-CONTADO',
		IF(gv.tipoflete=0 AND gv.condicionpago=1,'PAG-CREDITO',
		IF(gv.tipoflete=1 AND gv.condicionpago=0,'COB-CONTADO',
		IF(gv.tipoflete=1 AND gv.condicionpago=1,'COB-CREDITO','')))) AS flete,
		IF(gv.ocurre=1,'OCURRE','EAD') AS envio,
		SUM(d.cantidad) AS paquete, SUM(d.peso) AS kilogramos, gv.total,
		gv.estado, (SELECT eo.personaquerecibe FROM entregasocurre eo
		INNER JOIN entregasocurre_detalle eod ON eo.folio = eod.entregaocurre
		WHERE eod.guia=gv.id AND eod.entregada = 1) AS recibio,
		/*IF(gv.ocurre=1,gv.entregaocurre,gv.entregaead)*/0 AS diasentrega
		FROM guiasventanilla gv
		INNER JOIN guiaventanilla_detalle d ON gv.id = d.idguia
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.iddestinatario=".$_GET[cliente]." AND gv.estado<>'CANCELADO' 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		
		GROUP BY gv.id
		
		HAVING gv.id IS NOT NULL AND cs.prefijo IS NOT NULL";		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}
?>