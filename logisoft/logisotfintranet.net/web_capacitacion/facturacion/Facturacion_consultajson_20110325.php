<?
	session_start();
	require_once("../clases/FacturacionElectronica.php");
	require_once("../Conectar.php");
	require_once("../fn-error.php");
	$l = Conectarse("webpmm");
	$_POST[sucorigen] = $_SESSION[IDSUCURSAL];

    if($_POST[accion]==3){
		
		$_POST[cliente] = preg_replace("/[a-zA-Z]/","",$_POST[cliente]);
		
		$foliotipo = $_POST[foliotipo];
		
		$losfolios = "'".str_replace(",","','",$_POST[foliosguias])."'";
		
		$foliosguias = "";
		$foliosolici = "";
		$paratipo = split(",",$foliotipo);
		$lfolios  = split(",",$losfolios);
		
		for($i=0; $i<count(split(",",$foliotipo)); $i++){
			if($paratipo[$i]=="G"){
				$foliosguias .= (($foliosguias!="")?",":"").$lfolios[$i];
			}else{
				$foliosolici .= (($foliosolici!="")?",":"").$lfolios[$i];
			}
		}
		
		$foliosguias = (($foliosguias=="")?"0":$foliosguias);
		$foliosolici = (($foliosolici=="")?"0":$foliosolici);
		$losfolios2 = "'".str_replace(",","','",$_POST[foliosguias2])."'";
		
		
		$sx = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = $_POST[sucorigen]) AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
		$rx = mysql_query($sx,$l) or postError($sx);
		$fxpr = mysql_fetch_object($rx);
		$iva = $fxpr->iva/100;
		$ivar = $fxpr->ivar/100;
		
		$sy = "select personamoral from catalogocliente where id = $_POST[cliente]";
		$ry = mysql_query($sy,$l) or postError($sy);
		$fpm = mysql_fetch_object($ry);
		$personamoral = $fpm->personamoral;
		if($personamoral!='SI')
			$ivar=0;
			
		$_POST[data] = str_replace("xAMx","&",$_POST[data]);
		$_POST[data] = str_replace("xIQx","=",$_POST[data]);
		
		
		$s = "SELECT * FROM catalogofoliosfacturacion
		WHERE (SELECT MAX(folio) folio FROM facturacion) <= foliofinal";
		$r = mysql_query($s,$l) or postError($s);
		if(mysql_num_rows($r)<1){
			die("Se han agotado los folios para facturacion digital");
		}
		
		/*$_POST[nombrecliente] = utf8_decode($_POST[nombrecliente]);
		$_POST[apellidopaternocliente] = utf8_decode($_POST[apellidopaternocliente]);
		$_POST[apellidomaternocliente] = utf8_decode($_POST[apellidomaternocliente]);
		$_POST[crucecalles] = utf8_decode($_POST[crucecalles]);
		$_POST[colonia] = utf8_decode($_POST[colonia]);
		$_POST[poblacion] = utf8_decode($_POST[poblacion]);
		$_POST[municipio] = utf8_decode($_POST[municipio]);
		$_POST[pais] = utf8_decode($_POST[pais]);*/
		
		
		$s = "INSERT INTO facturacion SET facturaestado = 'GUARDADO', tipofactura='NORMAL', 
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
		total = '$_POST[total]',sobseguro = '$_POST[sobseguro]',sobexcedente = '$_POST[sobexcedente]',
		sobsubtotal = '$_POST[sobsubtotal]',sobiva = '$_POST[sobiva]',sobivaretenido = '$_POST[sobivaretenido]',
		sobmontoafacturar = '$_POST[sobmontoafacturar]',otroscantidad = '$_POST[otroscantidad]',
		otrosdescripcion = '$_POST[otrosdescripcion]',otrosimporte = '$_POST[otrosimporte]',otrossubtotal = '$_POST[otrossubtotal]',
		otrosiva = '$_POST[otrosiva]',otrosivaretenido = '$_POST[otrosivaretenido]',otrosmontofacturar = '$_POST[otrosmontofacturar]',
		idusuario='".$_SESSION[IDUSUARIO]."', usuario = '".$_SESSION[NOMBREUSUARIO]."',fecha = CURRENT_TIMESTAMP(),
		ivacobrado='$iva', ivarcobrado='$ivar', personamoral='$personamoral'".(($_POST[credito]=='SI')?"":", estadocobranza='C'");
		mysql_query($s,$l) or postError($s);
		$nfact = mysql_insert_id($l);
		
		$s = "SELECT * FROM catalogofoliosfacturacion ORDER BY id DESC LIMIT 1";
		$r = mysql_query($s,$l) or postError("$s");
		$f = mysql_fetch_object($r);
		$fe_anoaprobacion 		= $f->anoaprobacion;
		$fe_numeroaprobacion 	= $f->numeroaprobacion;
		$fe_serie 					= $f->serie;
		
		$s = "SELECT * FROM certificates_empresa";
		$r = mysql_query($s,$l) or postError("$s");
		$f = mysql_fetch_object($r);
		$fe_nombre		= $f->nombre;
		$fe_rfc			= $f->rfc;
		$fe_calle		= $f->calle;
		$fe_numeroint	= $f->numeroint;
		$fe_numeroext	= $f->numeroext;
		$fe_colonia		= $f->colonia;
		$fe_cp			= $f->cp;
		
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
		
		$arre_cliente['informacion']['name'] = "$_POST[nombrecliente] $_POST[apellidopaternocliente] $_POST[apellidomaternocliente]";
		$arre_cliente['informacion']['rfc'] = $_POST[rfc];
		$arre_cliente['informacion']['street'] = $_POST[calle];
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
		FROM guiasventanilla AS gv WHERE gv.id IN($foliosguias);";
		$r = mysql_query($s,$l) or postError($s);
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
		
		$s = "SELECT CONCAT('GUIA ',ge.id) AS descripcion,
		ge.subtotal, ge.tiva iva, ge.ivaretenido, ge.total
		FROM guiasempresariales AS ge WHERE ge.id IN($foliosguias);";
		$r = mysql_query($s,$l) or postError($s);
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
		WHERE sge.id IN($foliosolici)";
		$r = mysql_query($s,$l) or postError($s);
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
		WHERE ge.id IN($losfolios2)";
		$r = mysql_query($s,$l) or postError($s);
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
		
		if($_POST[otrosmontofacturar]>0){
			$var_subtotal 			+= $_POST[otrossubtotal];
			$var_iva 				+= $_POST[otrosiva];
			$var_ivaretenido		+= $_POST[otrosivaretenido];
			$var_total				+= $_POST[otrosmontofacturar];
			
			$arre_cliente['producto'][$var_contadordetalle]['preciounitario'] = $_POST[otrosimporte];
		   	$arre_cliente['producto'][$var_contadordetalle]['descripcion'] = "$_POST[otrosdescripcion]";
		   	$arre_cliente['producto'][$var_contadordetalle]['cantidad'] = $_POST[otroscantidad];
		   	$arre_cliente['producto'][$var_contadordetalle]['importe'] = $_POST[otrosmontofacturar];
		}
		
		#---------------------
		$arre_cliente['Impuestos']['totalImpuestosTrasladados'] = 0;
		$arre_cliente['Impuestos']['tasa'] = "".round($var_iva/$var_subtotal,2)."";
		$arre_cliente['Impuestos']['iva'] = "".$var_iva."";
		$arre_cliente['Impuestos']['ivaRetenido'] = "".$var_ivaretenido."";
		$arre_cliente['Impuestos']['subtotal'] = "".$var_subtotal."";
		$arre_cliente['Impuestos']['total'] = "".$var_total."";
		
		$miClase = new FacturacionElectronica();
		$miClase->setDatos($arre_cliente,$empresa);
		$arre = $miClase->crearFactura();
		
		$s = "update facturacion set xml='".$arre[1]."', cadenaoriginal='".$arre[2]."' where folio = $nfact";
		$r = mysql_query($s,$l) or postError("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		
		$s = "insert into historialmovimientos(modulo,folio,estado,idusuario,fechamodificacion) 
			values ('facturacion',$nfact,'GUARDADO',$_SESSION[IDUSUARIO],CURRENT_TIMESTAMP);";
			mysql_query($s,$l) or postError(mysql_error($l).$s);
		
		if($_POST[credito]=="NO"){
			$s = "INSERT INTO facturacion_fechapago (factura,fechapago)
			SELECT '$nfact', CURRENT_DATE;";
			mysql_query(str_replace("''","null",$s),$l) or postError($s);
		}
		
		if($_POST[valorcontado]!="0" && $_POST[valorcontado]!=""){
			
			if($_POST[credito]=="SI"){
				$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$_POST[valorcontado]', 
				fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
				cliente = '$_POST[cliente]', credito='SI',
				sucursalacobrar = '$_SESSION[IDSUCURSAL]', pagado='N'";
				$r = @mysql_query(str_replace("''","null",$s),$l) or postError($s);
			}else{
				$s = "INSERT INTO formapago SET guia='$nfact',procedencia='F',tipo='O',
				total='$_POST[valorcontado]',efectivo='$_POST[efectivo]',
				tarjeta='$_POST[tarjeta]',transferencia='$_POST[trasferencia]',cheque='$_POST[cheque]',
				ncheque='$_POST[ncheque]',banco='$_POST[banco]',notacredito='$_POST[nc]', cliente = '$_POST[cliente]',
				nnotacredito='$_POST[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',usuario='$_SESSION[IDUSUARIO]',fecha=current_date";
				mysql_query(str_replace("''","null",$s),$l) or postError($s);
				
				
				$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$_POST[valorcontado]', 
				fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
				cliente = '$_POST[cliente]',	sucursalacobrar = '$_SESSION[IDSUCURSAL]', pagado='S', 
				fechapago = CURRENT_DATE, usuariocobro = $_SESSION[IDUSUARIO], sucursalcobro = $_SESSION[IDSUCURSAL]";
				$r = @mysql_query(str_replace("''","null",$s),$l) or postError($s);
			}
		}
			
		
		$s = "UPDATE guiasventanilla SET factura = $nfact WHERE id IN($foliosguias)";
		mysql_query($s,$l) or postError($s);
		//echo $s;
		
		$s = "UPDATE guiasempresariales SET factura = $nfact WHERE id IN($foliosguias)";
		mysql_query($s,$l) or postError($s);
		//echo $s;
		$s = "update solicitudguiasempresariales set factura = $nfact, foliosactivados='SI' where id in($foliosolici)";
		mysql_query($s,$l) or postError($s);
		//echo $s;
		$s = "UPDATE guiasempresariales SET factura = $nfact WHERE id IN($losfolios2)";
		mysql_query($s,$l) or postError($s);
		
		
		#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
		$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$nfact'";
			$r = mysql_query(str_replace("''",'NULL',$sql),$l) or postError($sql);
			if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					#SE REGISTRA LA VENTA
					$s = "CALL proc_RegistroVendedores('REG_VEN_GEC', '$f->guias');";
					mysql_query($s,$l) or postError($s);
					
					if($_POST[credito]!="SI"){
						#en caso de ke sea contado se registran la comision
						$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$f->guias');";
						mysql_query($s,$l) or postError($s);
					}
				}
			}
			
			#para el registro de folios prepagados en reporte vendedores
			$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$nfact'";
			$r = mysql_query($sql,$l) or postError($sql);
			if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					#SE REGISTRA LA VENTA
					$s = "CALL proc_RegistroVendedores('REG_VEN_PRE', '$f->id');";
					mysql_query($s,$l) or postError($s);
						
					#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
					if($_POST[credito]!="SI"){
						$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$f->id');";
						mysql_query($s,$l) or postError($s);
					}
				}
			}
		#*********************************************************************************************
		
		$s = "INSERT INTO facturadetalle 
		SELECT 0 AS id, ".$nfact." AS factura, gv.id AS folio, 'NORMAL' AS tipoguia, gv.fecha,
		gv.tflete, gv.ttotaldescuento, gv.texcedente, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.tcombustible, gv.totros, 
		gv.subtotal, gv.tiva, gv.ivaretenido, gv.total, 'G' AS tipo,
		".$_SESSION[IDUSUARIO]." AS idusuario, CURRENT_TIMESTAMP
		FROM guiasventanilla AS gv WHERE gv.id IN($foliosguias)";
		mysql_query($s,$l) or postError($s);

		$s = "INSERT INTO facturadetalle
		SELECT 0 AS id, ".$nfact." AS factura, ge.id AS folio, 'CONSIGNACION' AS tipoguia, ge.fecha,
		ge.tflete, ge.ttotaldescuento, ge.texcedente, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.tcombustible, ge.totros, 
		ge.subtotal, ge.tiva, ge.ivaretenido, ge.total, 'G' AS tipo, 
		".$_SESSION[IDUSUARIO]." AS idusuario, CURRENT_TIMESTAMP
		FROM guiasempresariales AS ge WHERE ge.id IN($foliosguias)";
		mysql_query($s,$l) or postError($s);
		
		$s = "INSERT INTO facturadetalle
		SELECT 0 AS id, ".$nfact." AS factura,
sge.id, 'PREPAGADA' AS tipoguia, DATE_FORMAT(sge.fecha, '%d/%m/%Y') AS fecha, 
		
		IF(cp.descuento>0 AND gc.limitekg=cp.valpeso,
		cp.descuento*sge.cantidad,
		sge.subtotal-sge.combustible) AS tflete, 
		
		0 AS ttotaldescuento, 
		0 AS texcedente, 
		0 AS costoead,
		0 AS costorecoleccion,
		0 AS costoseguro,
		
		
		IF(cp.descuento>0 AND gc.limitekg<=cp.valpeso,
		(cp.descuento*sge.cantidad)*(cg.cargocombustible/100),
		sge.combustible
		) AS tcombustible, 
		0 AS otros,
		
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
		) AS tiva, 
		
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
		) AS total, 
		'S' AS tipo,
		NULL, CURRENT_DATE
		FROM solicitudguiasempresariales AS sge
		INNER JOIN generacionconvenio gc ON sge.idconvenio = gc.folio
		INNER JOIN catalogosucursal cs ON sge.sucursalacobrar = cs.id
		INNER JOIN configuradorgeneral cg 
		LEFT JOIN configuracion_promociones cp ON cp.tipo='EMPRESARIAL' AND 
			CURRENT_DATE BETWEEN cp.desde AND cp.hasta 
		WHERE sge.id IN($foliosolici)";
		mysql_query($s,$l) or postError($s);
				
		$s = "INSERT INTO reportecliente5(guia,fecha,idcliente,remitente,importe,factura)
		SELECT fd.folio, f.fecha, f.cliente,
		CONCAT_WS(' ',f.nombrecliente,f.apellidopaternocliente,f.apellidomaternocliente) AS remitente,
		fd.total, ".$nfact." AS factura FROM facturacion f
		INNER JOIN facturadetalle fd ON f.folio = fd.factura
		WHERE fd.factura = ".$nfact." AND fd.tipoguia = 'PREPAGADA'";
		//mysql_query($s,$l) or postError($s);
		
		$s = "INSERT INTO facturadetalleguias
		SELECT 0 AS id, ".$nfact." AS factura, ge.id AS guia, ge.fecha AS fechaguia, 'PREPAGADA' AS tipoguia,
		IF(NOT ISNULL(ge.valordeclarado) AND ge.texcedente>0,'EXCEDENTE/VALOR DECLARADO',
		IF(ge.texcedente>0,'EXCEDENTE','VALOR DECLARADO')) AS concepto, 
		IFNULL(ge.tseguro,0) AS tseguro,
		IFNULL(ge.texcedente,0) AS texcedente, 
		IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0) subtotal, 
		(IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $iva,
		(IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $ivar,
		(IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0))+((IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $iva)-
		((IFNULL(ge.tseguro,0)+IFNULL(ge.texcedente,0)) * $ivar),
		'' AS idusuario, CURRENT_TIMESTAMP 
		FROM guiasempresariales AS ge
		WHERE ge.id IN($losfolios2)";
		mysql_query($s,$l) or postError($s);
		
		#se registra la venta de otros en caso de que haya
		if($_POST[otrosmontofacturar]!="" && $_POST[otrosmontofacturar]!="0"){
			$s = "call proc_RegistroVentas('VENTA_OTROS','$nfact',0)";
			$r = @mysql_query($s,$l) or postError($s);
		}
		
		#se registra la venta de solicitud de folios en caso de que haya
		$paraciclo = split(',',$foliosolici);
		for($i=0; $i<count($paraciclo); $i++){
			$s = "call proc_RegistroVentas('VENTA_FOLIOS',".$paraciclo[$i].",0)";
			$r = @mysql_query($s,$l) or postError("$s");
		}
		#se registra las guias empresariales con excedente si es ke hay
		$paraciclo = split(',',$_POST[foliosguias2]);
		if($_POST[foliosguias2]!=""){
			for($i=0; $i<count($paraciclo); $i++){
				$s = "call proc_RegistroVentas('VENTA_EXCEDENTE','".$paraciclo[$i]."',0)";
				$r = @mysql_query($s,$l) or postError($s);
			}
		}
		
		#facturar guias en caso de que se facture alguna guia normal
		$s = "CALL proc_RegistroVentas('FACTURAR_GUIAS','$nfact',0);";
		$r = @mysql_query($s,$l) or postError($s);
		
		$s = "CALL proc_RegistroClientes('facturacion',".$_POST[cliente].",0,".$nfact.",0)";
		$r = @mysql_query($s,$l) or postError($s);
		if($_POST[credito]=="SI"){
			$s = "CALL proc_RegistroCobranza('FACTURA', $nfact, '', '', 0, 0);";
			$r = @mysql_query($s,$l) or postError($s);
		}
		
		if($_POST[tipoguiac]=='empresarial'){
			$s = "call proc_RegistroAuditorias('LF','$nfact',$_SESSION[IDSUCURSAL])";
			$d = mysql_query($s, $l);
		}
		
		/*$s = "SELECT f.cliente, d.folio FROM facturacion f
		INNER JOIN facturadetalle d ON f.folio = d.factura
		WHERE d.factura = ".$nfact." AND d.tipoguia='PREPAGADA'";
		$r = mysql_query($s,$l) or postError($s);
			while($f = mysql_fetch_object($r)){
				$s = "CALL proc_RegistroClientes('ventas',".$f->cliente.",0,0,".$f->folio.")";
				$r = @mysql_query($s,$l) or postError($s);
			}*/
		
		$s = "CALL proc_VentasVsPresupuesto('FACTURA','$nfact',$_SESSION[IDSUCURSAL]);";
		$r = @mysql_query($s,$l) or postError($s);
		
		if($_POST[tipoguiac]=='empresarial'){
			$s = "CALL proc_RegistroFranquiciasConceciones('facturacion', $nfact, ".$_SESSION[IDSUCURSAL].", 0);";
			$r = @mysql_query($s,$l) or postError($s);
		}
		
		echo "ok,$nfact";
		
	}
	
?>