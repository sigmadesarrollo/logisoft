<?
	session_start();
	require_once("../clases/FacturacionElectronica2.php");
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
			
		if($_POST[foliofactura]==""){
			die("NO ESPECIFICO EL FOLIO DE FACTURACION.. foliofactura=numero");
		}
		
		
		
			
		/*$_POST[nombrecliente] = utf8_decode($_POST[nombrecliente]);
		$_POST[apellidopaternocliente] = utf8_decode($_POST[apellidopaternocliente]);
		$_POST[apellidomaternocliente] = utf8_decode($_POST[apellidomaternocliente]);
		$_POST[crucecalles] = utf8_decode($_POST[crucecalles]);
		$_POST[colonia] = utf8_decode($_POST[colonia]);
		$_POST[poblacion] = utf8_decode($_POST[poblacion]);
		$_POST[municipio] = utf8_decode($_POST[municipio]);
		$_POST[pais] = utf8_decode($_POST[pais]);*/
		
		
		/*$s = "INSERT INTO facturacion SET facturaestado = 'GUARDADO', tipofactura='NORMAL', 
		tipoguia='$_POST[tipoguiac]',idsucursal='$_SESSION[IDSUCURSAL]', cliente = '$_POST[cliente]',
		nombrecliente = '$_POST[nombrecliente]',apellidopaternocliente = '$_POST[apellidopaternocliente]',
		apellidomaternocliente = '$_POST[apellidomaternocliente]', rfc = '$_POST[rfc]', credito='$_POST[credito]',
		calle = '$_POST[calle]',numero = '$_POST[numero]', sustitucion='$_POST[sustitutode]',
		codigopostal = '$_POST[codigopostal]',colonia = '$_POST[colonia]',crucecalles = '$_POST[crucecalles]',
		poblacion = '$_POST[poblacion]',municipio = '$_POST[municipio]',
		estado = '$_POST[estado]',pais = '$_POST[pais]',telefono = '$_POST[telefono]',fax = '$_POST[fax]',
		guiasempresa = '$_POST[guiasempresa]',guiasnormales = '$_POST[guiasnormales]',flete = '$_POST[flete]',
		totaldescuento = '$_POST[totaldescuento]',excedente = '$_POST[excedente]',ead = '$_POST[ead]',recoleccion = '$_POST[recoleccion]',
		seguro = '$_POST[seguro]',combustible = '$_POST[combustible]',otros = '$_POST[otros]',
		subtotal = '$_POST[subtotal]',iva = '$_POST[iva]',ivaretenido = '$_POST[ivaretenido]',
		total = '$_POST[total]',sobseguro = '$_POST[sobseguro]',sobexcedente = '0.00',
		sobsubtotal = '$_POST[sobsubtotal]',sobiva = '$_POST[sobiva]',sobivaretenido = '$_POST[sobivaretenido]',
		sobmontoafacturar = '$_POST[sobmontoafacturar]',otroscantidad = '$_POST[otroscantidad]',
		otrosdescripcion = '$_POST[otrosdescripcion]',otrosimporte = '$_POST[otrosimporte]',otrossubtotal = '$_POST[otrossubtotal]',
		otrosiva = '$_POST[otrosiva]',otrosivaretenido = '$_POST[otrosivaretenido]',otrosmontofacturar = '$_POST[otrosmontofacturar]',
		idusuario='".$_SESSION[IDUSUARIO]."', usuario = '".$_SESSION[NOMBREUSUARIO]."',fecha = CURRENT_TIMESTAMP(),
		ivacobrado='$iva', ivarcobrado='$ivar', personamoral='$personamoral'".(($_POST[credito]=='SI')?"":", estadocobranza='C'");
		mysql_query($s,$l) or die($s);
		*/
		$s = "update facturacion SET
		nombrecliente = '$_POST[nombrecliente]',apellidopaternocliente = '$_POST[apellidopaternocliente]',
		apellidomaternocliente = '$_POST[apellidomaternocliente]', rfc = '$_POST[rfc]',
		calle = '$_POST[calle]',numero = '$_POST[numero]',
		codigopostal = '$_POST[codigopostal]',colonia = '$_POST[colonia]',crucecalles = '$_POST[crucecalles]',
		poblacion = '$_POST[poblacion]',municipio = '$_POST[municipio]',
		estado = '$_POST[estado]',pais = '$_POST[pais]',telefono = '$_POST[telefono]',fax = '$_POST[fax]'
		WHERE folio = '$_POST[foliofactura]'";
		mysql_query($s,$l) or die($s);
		
		$nfact = $_POST[foliofactura];
		
		$s = "SELECT * FROM catalogofoliosfacturacion WHERE '$nfact' BETWEEN folioinicial AND foliofinal";
		$r = mysql_query($s,$l) or die("$s");
		$f = mysql_fetch_object($r);
		$fe_anoaprobacion 		= $f->anoaprobacion;
		$fe_numeroaprobacion 	= $f->numeroaprobacion;
		$fe_serie 				= $f->serie;
		
		$s = "SELECT * FROM certificates_empresa";
		$r = mysql_query($s,$l) or die("$s");
		$f = mysql_fetch_object($r);
		$fe_nombre		= $f->nombre;
		$fe_rfc			= $f->rfc;
		$fe_calle		= $f->calle;
		$fe_numeroint	= $f->numeroint;
		$fe_numeroext	= $f->numeroext;
		$fe_colonia		= $f->colonia;
		$fe_cp			= $f->cp;
		
		#limpiar datos
		$arre_cliente = null;
		$empresa = null;
		
		#empresa
		$empresa['Business']['name'] = $fe_nombre;
		$empresa['Business']['rfc'] = $fe_rfc;
		$empresa['Business']['street'] = $fe_calle;
		//$empresa['Business']["inside_number"] = $fe_numeroint;
		$empresa['Business']["outside_number"] = $fe_numeroext;
		$empresa['Business']["col"] = $fe_colonia;
		$empresa['Business']["cp"] = $fe_cp;
		$empresa['Business']['city_name'] = "MAZATLAN";
		$empresa["Municipio"]['name'] = "MAZATLAN";
		$empresa["State"]['name'] = "SINALOA";
		$empresa["Country"]['name'] = "MEXICO";
		
		//el cliente
		$arre_cliente['informacion']['serie'] = $fe_serie;
		$arre_cliente['informacion']['folio'] = "".$nfact."";
		$arre_cliente['informacion']['numeroaprobacion'] = $fe_numeroaprobacion;
		$arre_cliente['informacion']['anoaprobacion'] = $fe_anoaprobacion;
		
		$s = "SELECT * FROM facturacion WHERE folio = $nfact;";
		$r = mysql_query($s,$l);
		$f = mysql_fetch_object($r);
		
		$sx = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = $f->idsucursal) AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
		$rx = mysql_query($sx,$l) or die($sx);
		$fxpr = mysql_fetch_object($rx);
		$iva = $fxpr->iva/100;
		$ivar = $fxpr->ivar/100;
		
		$sy = "select personamoral from catalogocliente where id = $f->cliente";
		$ry = mysql_query($sy,$l) or die($sy);
		$fpm = mysql_fetch_object($ry);
		$personamoral = $fpm->personamoral;
		if($personamoral!='SI')
			$ivar=0;
		
		$fzzz_otrossubtotal = $f->otrossubtotal;
		$fzzz_otrosiva = $f->otrosiva;
		$fzzz_otrosivaretenido = $f->otrosivaretenido;
		$fzzz_otrosmontofacturar = $f->otrosmontofacturar;
		
		$fzzz_otroscantidad = $f->otroscantidad;
		$fzzz_otrosdescripcion = $f->otrosdescripcion;
		$fzzz_otrosimporte = $f->otrosimporte;
		
		$arre_cliente['informacion']['name'] = $_POST[nombrecliente];
		$arre_cliente['informacion']['rfc'] = $_POST[rfc];
		$arre_cliente['informacion']['street'] = $_POST[calle];
		$arre_cliente['informacion']['fecha'] = $f->fecha;
		
		//$arre_cliente['informacion']["inside_number"] = "";
		$arre_cliente['informacion']["outside_number"] = $_POST[numero];
		$arre_cliente['informacion']["col"] = $_POST[colonia];
		$arre_cliente['informacion']["cp"] = $_POST[codigopostal];
		# de la facturacion
		$arre_cliente['informacion']["municipio"] = $_POST[municipio];
		$arre_cliente['informacion']["state"] = $_POST[estado];
		$arre_cliente['informacion']["country"] = "MEXICO";
		
		#llenar los detallados 
		$var_contadordetalle 	= 0;
		$var_subtotal 			= 0;
		$var_iva 				= 0;
		$var_ivaretenido		= 0;
		$var_total				= 0;
		
		$s = "SELECT CONCAT('GUIA ',gv.id) descripcion, gv.subtotal, 
		gv.tiva iva, gv.ivaretenido, gv.total
		FROM guiasventanilla AS gv WHERE factura = $nfact;";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$var_subtotal 			+= $f->subtotal;
			$var_iva 				+= $f->iva;
			$var_ivaretenido		+= $f->ivaretenido;
			$var_total				+= $f->total;
			
			$arre_cliente['producto'][$var_contadordetalle]['preciounitario'] = $f->subtotal;
		   	$arre_cliente['producto'][$var_contadordetalle]['descripcion'] = $f->descripcion;
		   	$arre_cliente['producto'][$var_contadordetalle]['cantidad'] = 1;
		   	$arre_cliente['producto'][$var_contadordetalle]['importe'] = $f->subtotal;
			$var_contadordetalle++;
		}	
		
		$s = "SELECT CONCAT('GUIA ',ge.id) AS descripcion, ge.subtotal, ge.tiva iva, ge.ivaretenido, ge.total
				FROM guiasempresariales AS ge 
				INNER JOIN facturadetalle fd ON fd.folio = ge.id
				WHERE fd.factura = $nfact AND fd.tipoguia = 'consignacion';";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$var_subtotal 			+= $f->subtotal;
			$var_iva 				+= $f->iva;
			$var_ivaretenido		+= $f->ivaretenido;
			$var_total				+= $f->total;
			
			$arre_cliente['producto'][$var_contadordetalle]['preciounitario'] = $f->subtotal;
		   	$arre_cliente['producto'][$var_contadordetalle]['descripcion'] = "$f->descripcion";
		   	$arre_cliente['producto'][$var_contadordetalle]['cantidad'] = 1;
		   	$arre_cliente['producto'][$var_contadordetalle]['importe'] = $f->subtotal;
			$var_contadordetalle++;
		}	
		
		$s = "SELECT CONCAT('SOLICITUD ', sge.id) AS descripcion, 
		IF(cp.descuento>0 AND gc.limitekg<=cp.valpeso,
			(cp.descuento*sge.cantidad)+
			((cp.descuento*sge.cantidad)*(cg.cargocombustible/100))
			,
			sge.subtotal
		) subtotal, 
		
		IF(cp.descuento>0 AND gc.limitekg<=cp.valpeso,
			((cp.descuento*sge.cantidad)+
			((cp.descuento*sge.cantidad)*(cg.cargocombustible/100)))*(cs.iva/100)
			,
			sge.iva
		) AS iva, 
		
		IF(cp.descuento>0 AND gc.limitekg<=cp.valpeso AND sge.ivar>0,
			((cp.descuento*sge.cantidad)+
			((cp.descuento*sge.cantidad)*(cg.cargocombustible/100)))*(cg.ivaretenido/100),
			sge.ivar
		) AS ivaretenido,
		
		IF(cp.descuento>0 AND gc.limitekg<=cp.valpeso,
			((cp.descuento*sge.cantidad)+
			((cp.descuento*sge.cantidad)*(cg.cargocombustible/100)))+
			(((cp.descuento*sge.cantidad)+
			((cp.descuento*sge.cantidad)*(cg.cargocombustible/100)))*(cs.iva/100))-
			IF(sge.ivar>0,
				(((cp.descuento*sge.cantidad)+
				((cp.descuento*sge.cantidad)*(cg.cargocombustible/100)))*(cg.ivaretenido/100))
			,0),
			sge.total
		) AS total
		FROM solicitudguiasempresariales AS sge
		INNER JOIN generacionconvenio gc ON sge.idconvenio = gc.folio
		INNER JOIN catalogosucursal cs ON sge.sucursalacobrar = cs.id
		INNER JOIN configuradorgeneral cg 
		LEFT JOIN configuracion_promociones cp ON cp.tipo='EMPRESARIAL' AND 
			CURRENT_DATE BETWEEN cp.desde AND cp.hasta 
		WHERE sge.factura = $nfact;";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$var_subtotal 			+= $f->subtotal;
			$var_iva 				+= $f->iva;
			$var_ivaretenido		+= $f->ivaretenido;
			$var_total				+= $f->total;
			
			$arre_cliente['producto'][$var_contadordetalle]['preciounitario'] = $f->subtotal;
		   	$arre_cliente['producto'][$var_contadordetalle]['descripcion'] = "$f->descripcion";
		   	$arre_cliente['producto'][$var_contadordetalle]['cantidad'] = 1;
		   	$arre_cliente['producto'][$var_contadordetalle]['importe'] = $f->subtotal;
			$var_contadordetalle++;
		}
		
		
		$s = "SELECT CONCAT(IF(NOT ISNULL(ge.valordeclarado) AND ge.texcedente>0,'EXC/VAL DEC',
		IF(ge.texcedente>0,'EXCEDENTE','VAL DEC')), ge.id) AS descripcion, 
		IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0) subtotal, 
		(IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $iva iva,
		(IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $ivar ivaretenido,
		(IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0))+((IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $iva)-
		((IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $ivar) total
		FROM guiasempresariales AS ge
		INNER JOIN facturadetalleguias fd ON ge.id = fd.guia
		WHERE ge.factura = $nfact;";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$var_subtotal 			+= $f->subtotal;
			$var_iva 				+= $f->iva;
			$var_ivaretenido		+= $f->ivaretenido;
			$var_total				+= $f->total;
			
			$arre_cliente['producto'][$var_contadordetalle]['preciounitario'] = $f->subtotal;
		   	$arre_cliente['producto'][$var_contadordetalle]['descripcion'] = "$f->descripcion";
		   	$arre_cliente['producto'][$var_contadordetalle]['cantidad'] = 1;
		   	$arre_cliente['producto'][$var_contadordetalle]['importe'] = $f->subtotal;
			$var_contadordetalle++;
		}
		
		if($fzzz_otrosmontofacturar>0){
			$var_subtotal 			+= $fzzz_otrossubtotal;
			$var_iva 				+= $fzzz_otrosiva;
			$var_ivaretenido		+= $fzzz_otrosivaretenido;
			$var_total				+= $fzzz_otrosmontofacturar;
			
			$arre_cliente['producto'][$var_contadordetalle]['preciounitario'] = $fzzz_otrosimporte;
		   	$arre_cliente['producto'][$var_contadordetalle]['descripcion'] = "$fzzz_otrosdescripcion";
		   	$arre_cliente['producto'][$var_contadordetalle]['cantidad'] = $fzzz_otroscantidad;
		   	$arre_cliente['producto'][$var_contadordetalle]['importe'] = $fzzz_otrossubtotal;
		}
		
		#---------------------
		$arre_cliente['Impuestos']['totalImpuestosTrasladados'] = 0;
		$arre_cliente['Impuestos']['tasa'] = "".round($var_iva/$var_subtotal,2)."";
		$arre_cliente['Impuestos']['iva'] = "".$var_iva."";
		$arre_cliente['Impuestos']['ivaRetenido'] = "".$var_ivaretenido."";
		$arre_cliente['Impuestos']['subtotal'] = "".$var_subtotal."";
		$arre_cliente['Impuestos']['total'] = "".$var_total."";
		
		
		
		//print_r($arre_cliente);
		
		
		$miClase = new FacturacionElectronica2();
		$miClase->setDatos($arre_cliente,$empresa);
		$arre = $miClase->crearFactura();
		
		$s = "update facturacion set xml='".$arre[1]."', cadenaoriginal='".$arre[2]."' where folio = $nfact";
		mysql_query($s,$l) or die($s);		
		
		echo "ok,$nfact<br>";
?>