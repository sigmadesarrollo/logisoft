<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$_GET[sucorigen] = $_SESSION[IDSUCURSAL];
	
	//solicitar clientes
	if($_GET[accion] == 1){}
	
	//solicitar guias
	if($_GET[accion] == 2){
		
		$arrefechainicio = split("/",$_GET[fechainicio]);
		$arrefechafin = split("/",$_GET[fechafin]);
		$nfecin = $arrefechainicio[2]."-".$arrefechainicio[1]."-".$arrefechainicio[0];
		$nfecfi = $arrefechafin[2]."-".$arrefechafin[1]."-".$arrefechafin[0];
			
		$s = "SELECT
			gv.id, 'NORMAL' as tipoguia, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
			gv.estado, gv.tipoflete, gv.ocurre, gv.idsucursalorigen, 
			gv.idsucursalorigen,gv.idsucursaldestino,gv.entregaocurre, 
			gv.entregaead, gv.restrinccion, gv.totalpaquetes, gv.totalpeso, 
			gv.totalvolumen, gv.emplaye, gv.bolsaempaque, gv.totalbolsaempaque, 
			gv.avisocelular, gv.celular, gv.valordeclarado, gv.acuserecibo, 
			gv.cod, gv.recoleccion, gv.observaciones, gv.tflete, gv.tdescuento, 
			gv.ttotaldescuento, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.totros, 
			gv.texcedente, gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
			gv.total, gv.efectivo, gv.cheque, gv.banco, gv.ncheque, gv.tarjeta, gv.trasferencia, 
			gv.usuario, gv.fecha_registro, gv.hora_registro, date_format(current_date, '%d/%m/%Y') as fechaactual,
			'G' as tipo
			FROM guiasventanilla as gv
			inner join pagoguias as pg on gv.id = pg.guia
			where isnull(factura) AND gv.condicionpago = 0			
			AND (gv.estado <> 'CANCELADA' and gv.estado <> 'CANCELADO')
			AND pg.sucursalacobrar=$_GET[sucorigen] and pg.pagado = 'S' and gv.fecha between '$nfecin' AND '$nfecfi'";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			while($f = mysql_fetch_object($r)){
				$xml .= "
				<seleccion>1</seleccion>
				<id>".cambio_texto($f->id)."</id>
				<tipoguia>".cambio_texto($f->tipoguia)."</tipoguia>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				<tflete>".cambio_texto($f->tflete)."</tflete>
				<tdescuento>".cambio_texto($f->tdescuento)."</tdescuento>
				<ttotaldescuento>".cambio_texto($f->ttotaldescuento)."</ttotaldescuento>
				<tcostoead>".cambio_texto($f->tcostoead)."</tcostoead>
				<trecoleccion>".cambio_texto($f->trecoleccion)."</trecoleccion>
				<tseguro>".cambio_texto($f->tseguro)."</tseguro>
				<totros>".cambio_texto($f->totros)."</totros>
				<texcedente>".cambio_texto($f->texcedente)."</texcedente>
				<tcombustible>".cambio_texto($f->tcombustible)."</tcombustible>
				<subtotal>".cambio_texto($f->subtotal)."</subtotal>
				<tiva>".cambio_texto($f->tiva)."</tiva>
				<ivaretenido>".cambio_texto($f->ivaretenido)."</ivaretenido>
				<total>".cambio_texto($f->total)."</total>
				<tipo>".cambio_texto($f->tipo)."</tipo>";
			}
			$xml .= "
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>0</encontrados>
				</datos>
				</xml>";
		}
	}
	
	//guardar factura
	if($_GET[accion] == 3){		
		$_GET[data] = str_replace("xAMx","&",$_GET[data]);
		$_GET[data] = str_replace("xIQx","=",$_GET[data]);
		
		//echo $_GET[data];
		
        $ch = curl_init("http://pmm.comprobantesdigitales.com.mx/invoices/remote/136f43d234b5c17c34cbd7c7367cd93a");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $_GET[data]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);       
        curl_close($ch);
		
		$arre = split("~",$output);
		
		$s = "INSERT INTO facturacion SET facturaestado = 'GUARDADO', tipofactura='GENERAL', 
		idsucursal='$_SESSION[IDSUCURSAL]',
		nombrecliente = '$_GET[nombrecliente]', rfc = '$_GET[rfc]', credito='NO',
		calle = '$_GET[calle]',numero = '$_GET[numero]', sustitucion='$_GET[sustitutode]',
		codigopostal = '$_GET[codigopostal]',colonia = '$_GET[colonia]',crucecalles = '$_GET[crucecalles]',
		poblacion = '$_GET[poblacion]',municipio = '$_GET[municipio]',
		estado = '$_GET[estado]',pais = '$_GET[pais]',telefono = '$_GET[telefono]',fax = '$_GET[fax]',
		guiasempresa = '$_GET[guiasempresa]',guiasnormales = '$_GET[guiasnormales]',flete = '$_GET[flete]',
		excedente = '$_GET[excedente]',ead = '$_GET[ead]',recoleccion = '$_GET[recoleccion]',
		seguro = '$_GET[seguro]',combustible = '$_GET[combustible]',otros = '$_GET[otros]',
		subtotal = '$_GET[subtotal]',iva = '$_GET[iva]',ivaretenido = '$_GET[ivaretenido]',
		total = '$_GET[total]',otroscantidad = '$_GET[otroscantidad]',
		otrosdescripcion = '$_GET[otrosdescripcion]',otrosimporte = '$_GET[otrosimporte]',otrossubtotal = '$_GET[otrossubtotal]',
		otrosiva = '$_GET[otrosiva]',otrosivaretenido = '$_GET[otrosivaretenido]',otrosmontofacturar = '$_GET[otrosmontofacturar]',
		idusuario='".$_SESSION[IDUSUARIO]."', usuario = '".$_SESSION[NOMBREUSUARIO]."',fecha = CURRENT_DATE,
		xml='".html_entity_decode($arre[0])."', cadenaoriginal='".html_entity_decode($arre[1])."',
		ivacobrado='$iva', ivarcobrado='$ivar', personamoral='$personamoral', estadocobranza='C'";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		$nfact = mysql_insert_id($l);		
		
		
		$losfolios = "'".str_replace(",","','",$_GET[foliosguias])."'";	
		
		$s = "UPDATE guiasventanilla SET factura = $nfact WHERE id IN($losfolios)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");		
		
		$s = "INSERT INTO facturadetalle 
		SELECT 0 AS id, ".$nfact." AS factura, gv.id AS folio, 'NORMAL' AS tipoguia, gv.fecha,
		gv.tflete, gv.ttotaldescuento, gv.texcedente, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.tcombustible, gv.totros, 
		gv.subtotal, gv.tiva, gv.ivaretenido, gv.total, 'G' AS tipo,
		".$_SESSION[IDUSUARIO]." AS idusuario, CURRENT_TIMESTAMP
		FROM guiasventanilla AS gv WHERE gv.id IN($losfolios)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<foliofactura>$nfact</foliofactura>
				<guardado>1</guardado>
				</datos>
				</xml>";
	}
	
	//cancelar factura
	if($_GET[accion] == 4){
		$s = "UPDATE guiasventanilla SET factura = NULL WHERE factura = $_GET[foliofactura]";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
	}
	
	//solicitar maxid
	if($_GET[accion] == 5){
		$s = "select max(folio)+1 as foliom from facturacion";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<resultado>0</resultado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		$f = mysql_fetch_object($r);
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<maximo>".(($f->foliom!="")?$f->foliom:"1")."</maximo>
				</datos>
				</xml>";
	}
	
	//solicitar factura
	if($_GET[accion] == 6){
		$s = "SELECT facturacion.*, catalogosucursal.descripcion as nsucursal,DATE_FORMAT(facturacion.fecha, '%d/%m/%Y') AS fechafactura 
		FROM facturacion 
		INNER JOIN catalogosucursal on facturacion.idsucursal = catalogosucursal.id
		WHERE facturacion.folio = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<resultado>0</resultado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
		
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontro>$enc</encontro>
				<folio>".cambio_texto($f->folio)."</folio> 
				<facturaestado>".cambio_texto($f->facturaestado)."</facturaestado>
				<nsucursal>".cambio_texto($f->nsucursal)."</nsucursal>
				<credito>".cambio_texto($f->credito)."</credito>
				<sustitucion>".cambio_texto($f->sustitucion)."</sustitucion> 
				<cliente>".cambio_texto($f->cliente)."</cliente> 
				<nombrecliente>".cambio_texto($f->nombrecliente)."</nombrecliente>  
				<apepat>".cambio_texto($f->apellidopaternocliente)."</apepat>  
				<apemat>".cambio_texto($f->apellidomaternocliente)."</apemat>  
				<rfc>".cambio_texto($f->rfc)."</rfc>  
				<calle>".cambio_texto($f->calle)."</calle> 
				<numero>".cambio_texto($f->numero)."</numero>
				<codigopostal>".cambio_texto($f->codigopostal)."</codigopostal> 
				<colonia>".cambio_texto($f->colonia)."</colonia>  
				<crucecalles>".cambio_texto($f->crucecalles)."</crucecalles> 
				<poblacion>".cambio_texto($f->poblacion)."</poblacion>
				<municipio>".cambio_texto($f->municipio)."</municipio>
				<estado>".cambio_texto($f->estado)."</estado>
				<pais>".cambio_texto($f->pais)."</pais>  
				<telefono>".cambio_texto($f->telefono)."</telefono> 
				<fax>".cambio_texto($f->fax)."</fax>
				<guiasempresa>".cambio_texto($f->guiasempresa)."</guiasempresa>  
				<guiasnormales>".cambio_texto($f->guiasnormales)."</guiasnormales> 
				<flete>".cambio_texto($f->flete)."</flete>
				<excedente>".cambio_texto($f->excedente)."</excedente>  
				<ead>".cambio_texto($f->ead)."</ead>  
				<recoleccion>".cambio_texto($f->recoleccion)."</recoleccion>
				<seguro>".cambio_texto($f->seguro)."</seguro>  
				<combustible>".cambio_texto($f->combustible)."</combustible>
				<otros>".cambio_texto($f->otros)."</otros>
				<subtotal>".cambio_texto($f->subtotal)."</subtotal>
				<iva>".cambio_texto($f->iva)."</iva>  
				<ivaretenido>".cambio_texto($f->ivaretenido)."</ivaretenido>
				<total>".cambio_texto($f->total)."</total>
				<sobseguro>".cambio_texto($f->sobseguro)."</sobseguro>  
				<sobexcedente>".cambio_texto($f->sobexcedente)."</sobexcedente>  
				<sobsubtotal>".cambio_texto($f->sobsubtotal)."</sobsubtotal>
				<sobiva>".cambio_texto($f->sobiva)."</sobiva>  
				<sobivaretenido>".cambio_texto($f->sobivaretenido)."</sobivaretenido>
				<sobmontoafacturar>".cambio_texto($f->sobmontoafacturar)."</sobmontoafacturar>
				<otroscantidad>".cambio_texto($f->otroscantidad)."</otroscantidad> 
				<otrosdescripcion>".cambio_texto($f->otrosdescripcion)."</otrosdescripcion>
				<otrosimporte>".cambio_texto($f->otrosimporte)."</otrosimporte>  
				<otrossubtotal>".cambio_texto($f->otrossubtotal)."</otrossubtotal> 
				<otrosiva>".cambio_texto($f->otrosiva)."</otrosiva>
				<otrosivaretenido>".cambio_texto($f->otrosivaretenido)."</otrosivaretenido> 
				<otrosmontofacturar>".cambio_texto($f->otrosmontofacturar)."</otrosmontofacturar>  
				<fecha>".cambio_texto($f->fechafactura)."</fecha>
				<ivacobrado>".cambio_texto($f->ivacobrado)."</ivacobrado>
				<ivarcobrado>".cambio_texto($f->ivarcobrado)."</ivarcobrado>
				<personamoral>".cambio_texto($f->personamoral)."</personamoral>
				<estadocobranza>".cambio_texto($f->estadocobranza)."</estadocobranza>";
				#mostrar las guias :)
			$s = "SELECT
			gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
			gv.estado, gv.tipoflete, gv.ocurre, gv.idsucursalorigen, 
			gv.idsucursalorigen,gv.idsucursaldestino,gv.entregaocurre, 
			gv.entregaead, gv.restrinccion, gv.totalpaquetes, gv.totalpeso, 
			gv.totalvolumen, gv.emplaye, gv.bolsaempaque, gv.totalbolsaempaque, 
			gv.avisocelular, gv.celular, gv.valordeclarado, gv.acuserecibo, 
			gv.cod, gv.recoleccion, gv.observaciones, gv.tflete, gv.tdescuento, 
			gv.ttotaldescuento, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.totros, 
			gv.texcedente, gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
			gv.total, gv.efectivo, gv.cheque, gv.banco, gv.ncheque, gv.tarjeta, gv.trasferencia, 
			gv.usuario, gv.fecha_registro, gv.hora_registro, date_format(current_date, '%d/%m/%Y') as fechaactual
			FROM guiasventanilla as gv
			where gv.factura = '$_GET[folio]'";
			
			/*$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$guiasencontradas = mysql_num_rows($r);
				//<fevaluacion>".cambio_texto($fx->evaluacion)."</fevaluacion>
				while($fx = mysql_fetch_object($r)){
					$xml .= "
					<id>".cambio_texto($fx->id)."</id>
					<seleccion>0</seleccion>
					<tipoguia>NORMAL</tipoguia>
					
					<fecha>".cambio_texto($fx->fecha)."</fecha>
					<estado>".cambio_texto($fx->estado)."</estado>
					<tflete>".cambio_texto($fx->tflete)."</tflete>
					<tdescuento>".cambio_texto($fx->tdescuento)."</tdescuento>
					<ttotaldescuento>".cambio_texto($fx->ttotaldescuento)."</ttotaldescuento>
					<tcostoead>".cambio_texto($fx->tcostoead)."</tcostoead>
					<trecoleccion>".cambio_texto($fx->trecoleccion)."</trecoleccion>
					<tseguro>".cambio_texto($fx->tseguro)."</tseguro>
					<totros>".cambio_texto($fx->totros)."</totros>
					<texcedente>".cambio_texto($fx->texcedente)."</texcedente>
					<tcombustible>".cambio_texto($fx->tcombustible)."</tcombustible>
					<subtotal>".cambio_texto($fx->subtotal)."</subtotal>
					<tiva>".cambio_texto($fx->tiva)."</tiva>
					<ivaretenido>".cambio_texto($fx->ivaretenido)."</ivaretenido>
					<total>".cambio_texto($fx->total)."</total>";
				}
			}<guiasencontradas>$guiasencontradas</guiasencontradas>*/
			
			$xml .= "</datos>
				</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
	}
	
	if($_GET[accion] == 7){
		$s = "SELECT ge.id, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 
		if(NOT ISNULL(ge.valordeclarado) and ge.texcedente>0,'EXCENDENTE/VALOR DECLARADO',
			if(ge.texcedente>0,'EXCEDENTE','VALOR DECLARADO')) AS concepto, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso,
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ifnull(ge.tseguro,0) tseguro, ge.totros, 
		ifnull(ge.texcedente,0) texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual
		FROM guiasempresariales AS ge
		WHERE (NOT ISNULL(ge.valordeclarado) OR ge.texcedente>0) AND ge.tipoguia='PREPAGADA' AND
		ge.idremitente =  $_GET[idcliente]
		AND ISNULL(factura) AND ge.tipopago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'")."
		AND ge.idsucursalorigen=$_GET[sucorigen]";
		
		//echo $s;
		$sx = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = $_GET[sucorigen]) AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
		$rx = mysql_query($sx,$l) or die($sx);
		$fxpr = mysql_fetch_object($rx);
		
		$sy = "select personamoral from catalogocliente where id = $_GET[idcliente]";
		$ry = mysql_query($sy,$l) or die($sy);
		$fpm = mysql_fetch_object($rx);
		
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			while($f = mysql_fetch_object($r)){
				$elsubtotal = $f->texcedente+$f->tseguro;
				$totiva = ($fxpr->iva/100)*$elsubtotal;
				if($fpm->personamoral=="SI"){
					$totivar = ($fxpr->ivar/100)*$elsubtotal;
				}else{
					$totivar = "0.00";
				}
				$eltotal = $elsubtotal+$totiva-$totivar;
				
				$xml .= "<seleccion>1</seleccion>
				<id>".cambio_texto($f->id)."</id>
				<tipoguia>PREPAGADA</tipoguia>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				<tflete>".cambio_texto($elsubtotal)."</tflete>
				<concepto>".cambio_texto($f->concepto)."</concepto>
				<tdescuento>".cambio_texto($f->tdescuento)."</tdescuento>
				<ttotaldescuento>".cambio_texto($f->ttotaldescuento)."</ttotaldescuento>
				<tcostoead>".cambio_texto($f->tcostoead)."</tcostoead>
				<trecoleccion>".cambio_texto($f->trecoleccion)."</trecoleccion>
				<tseguro>".cambio_texto($f->tseguro)."</tseguro>
				<totros>".cambio_texto($f->totros)."</totros>
				<texcedente>".cambio_texto($f->texcedente)."</texcedente>
				<tcombustible>".cambio_texto($f->tcombustible)."</tcombustible>
				<subtotal>".cambio_texto($elsubtotal)."</subtotal>
				<tiva>".cambio_texto($totiva)."</tiva>
				<ivaretenido>".cambio_texto($totivar)."</ivaretenido>
				<total>".cambio_texto($eltotal)."</total>";
			}
			$xml .= "
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>0</encontrados>
				</datos>
				</xml>";
		}
	}
	
	if($_GET[accion] == 8){
		$s = "SELECT
		gv.id, 'NORMAL' as tipoguia, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, 
		gv.fechaentrega, gv.factura, 
		gv.estado, gv.tipoflete, gv.ocurre, gv.idsucursalorigen, 
		gv.idsucursalorigen,gv.idsucursaldestino,gv.entregaocurre, 
		gv.entregaead, gv.restrinccion, gv.totalpaquetes, gv.totalpeso, 
		gv.totalvolumen, gv.emplaye, gv.bolsaempaque, gv.totalbolsaempaque, 
		gv.avisocelular, gv.celular, gv.valordeclarado, gv.acuserecibo, 
		gv.cod, gv.recoleccion, gv.observaciones, gv.tflete, gv.tdescuento, 
		gv.ttotaldescuento, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.totros, 
		gv.texcedente, gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
		gv.total, gv.efectivo, gv.cheque, gv.banco, gv.ncheque, gv.tarjeta, gv.trasferencia, 
		gv.usuario, gv.fecha_registro, gv.hora_registro, 
		date_format(current_date, '%d/%m/%Y') as fechaactual, 'G' as tipo
		FROM liquidacion_detalleead ld 
		INNER JOIN guiasventanilla gv ON ld.guia=gv.id
		INNER JOIN catalogocliente cc ON  
		IF(gv.tipoflete=0, gv.idremitente=cc.id,gv.iddestinatario =cc.id)
		WHERE ld.tipoflete!='PAGADO' AND ld.condicionpago='CREDITO' 
		AND ld.idliquidacion=".$_GET[folio]." AND cc.id=".$_GET[cliente]."	
	UNION
		SELECT ge.id, 'EMPRESARIAL' as tipoguia, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, 
		ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso, 
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.totros, 
		ge.texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, 
		DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual, 'G' as tipo
		FROM liquidacion_detalleead ld 
		INNER JOIN guiasempresariales ge ON ld.guia=ge.id
		INNER JOIN catalogocliente cc ON  IF(ge.tipoflete='PAGADA', ge.idremitente=cc.id,ge.iddestinatario =cc.id)
		WHERE ld.tipoflete!='PAGADO' AND ld.condicionpago='CREDITO' 
		AND ld.idliquidacion=".$_GET[folio]." AND cc.id=".$_GET[cliente]."";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			while($f = mysql_fetch_object($r)){
				
				$xml .= "
				<seleccion>1</seleccion>
				<id>".cambio_texto($f->id)."</id>
				<tipoguia>NORMAL</tipoguia>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				<tflete>".cambio_texto($f->tflete)."</tflete>
				<tdescuento>".cambio_texto($f->tdescuento)."</tdescuento>
				<ttotaldescuento>".cambio_texto($f->ttotaldescuento)."</ttotaldescuento>
				<tcostoead>".cambio_texto($f->tcostoead)."</tcostoead>
				<trecoleccion>".cambio_texto($f->trecoleccion)."</trecoleccion>
				<tseguro>".cambio_texto($f->tseguro)."</tseguro>
				<totros>".cambio_texto($f->totros)."</totros>
				<texcedente>".cambio_texto($f->texcedente)."</texcedente>
				<tcombustible>".cambio_texto($f->tcombustible)."</tcombustible>
				<subtotal>".cambio_texto($f->subtotal)."</subtotal>
				<tiva>".cambio_texto($f->tiva)."</tiva>
				<ivaretenido>".cambio_texto($f->ivaretenido)."</ivaretenido>
				<total>".cambio_texto($f->total)."</total>
				<tipo>G</tipo>";
			}
			$xml .= "
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>0</encontrados>
				</datos>
				</xml>";
		}
	}
	
		if($_GET[accion] == 9){
		$s = "SELECT ge.id, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 'VALOR DECLARADO' AS concepto, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso,
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.totros, 
		ge.texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, 
		DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual
		FROM liquidacion_detalleead ld 
		INNER JOIN guiasempresariales ge ON ld.guia=ge.id
		INNER JOIN catalogocliente cc ON  IF(ge.tipoflete='PAGADA', ge.idremitente=cc.id,ge.iddestinatario =cc.id)
		WHERE ld.tipoflete!='PAGADO' AND ld.condicionpago='CREDITO' 
		AND ld.idliquidacion=".$_GET[folio]." AND cc.id=".$_GET[cliente]."";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			while($f = mysql_fetch_object($r)){
				
				$xml .= "
				<seleccion>1</seleccion>
				<id>".cambio_texto($f->id)."</id>
				<tipoguia>NORMAL</tipoguia>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				<tflete>".cambio_texto($f->tflete)."</tflete>
				<concepto>".cambio_texto($f->concepto)."</concepto>
				<tdescuento>".cambio_texto($f->tdescuento)."</tdescuento>
				<ttotaldescuento>".cambio_texto($f->ttotaldescuento)."</ttotaldescuento>
				<tcostoead>".cambio_texto($f->tcostoead)."</tcostoead>
				<trecoleccion>".cambio_texto($f->trecoleccion)."</trecoleccion>
				<tseguro>".cambio_texto($f->tseguro)."</tseguro>
				<totros>".cambio_texto($f->totros)."</totros>
				<texcedente>".cambio_texto($f->texcedente)."</texcedente>
				<tcombustible>".cambio_texto($f->tcombustible)."</tcombustible>
				<subtotal>".cambio_texto($f->subtotal)."</subtotal>
				<tiva>".cambio_texto($f->tiva)."</tiva>
				<ivaretenido>".cambio_texto($f->ivaretenido)."</ivaretenido>
				<total>".cambio_texto($f->total)."</total>";
			}
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>0</encontrados>
				</datos>
				</xml>";
		}
	}
	
	//obtener guias de una factura -arriba
	if($_GET[accion] == 10){
		$s = "select * from facturadetalle where factura = $_GET[idfactura]
		/*(SELECT
		gv.id, 'NORMAL' as tipoguia, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
		gv.estado, gv.tipoflete, gv.ocurre, gv.idsucursalorigen, 
		gv.idsucursalorigen,gv.idsucursaldestino,gv.entregaocurre, 
		gv.entregaead, gv.restrinccion, gv.totalpaquetes, gv.totalpeso, 
		gv.totalvolumen, gv.emplaye, gv.bolsaempaque, gv.totalbolsaempaque, 
		gv.avisocelular, gv.celular, gv.valordeclarado, gv.acuserecibo, 
		gv.cod, gv.recoleccion, gv.observaciones, gv.tflete, gv.tdescuento, 
		gv.ttotaldescuento, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.totros, 
		gv.texcedente, gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
		gv.total, gv.efectivo, gv.cheque, gv.banco, gv.ncheque, gv.tarjeta, gv.trasferencia, 
		gv.usuario, gv.fecha_registro, gv.hora_registro, date_format(current_date, '%d/%m/%Y') as fechaactual,
		'G' as tipo
		FROM guiasventanilla as gv
		inner join pagoguias as pg on gv.id = pg.guia
		where factura=$_GET[idfactura])*/";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			while($f = mysql_fetch_object($r)){
				
				$xml .= "
				<seleccion>0</seleccion>
				<id>".cambio_texto($f->id)."</id>
				<tipoguia>".cambio_texto($f->tipoguia)."</tipoguia>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				<tflete>".cambio_texto($f->tflete)."</tflete>
				<tdescuento>".cambio_texto($f->tdescuento)."</tdescuento>
				<ttotaldescuento>".cambio_texto($f->ttotaldescuento)."</ttotaldescuento>
				<tcostoead>".cambio_texto($f->tcostoead)."</tcostoead>
				<trecoleccion>".cambio_texto($f->trecoleccion)."</trecoleccion>
				<tseguro>".cambio_texto($f->tseguro)."</tseguro>
				<totros>".cambio_texto($f->totros)."</totros>
				<texcedente>".cambio_texto($f->texcedente)."</texcedente>
				<tcombustible>".cambio_texto($f->tcombustible)."</tcombustible>
				<subtotal>".cambio_texto($f->subtotal)."</subtotal>
				<tiva>".cambio_texto($f->tiva)."</tiva>
				<ivaretenido>".cambio_texto($f->ivaretenido)."</ivaretenido>
				<total>".cambio_texto($f->total)."</total>
				<tipo>".cambio_texto($f->tipo)."</tipo>";
			}
			$xml .= "
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>0</encontrados>
				</datos>
				</xml>";
		}
	}
	
	if($_GET[accion] == 11){
		$s = "SELECT ge.id, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 
		if(NOT ISNULL(ge.valordeclarado) and ge.texcedente>0,'EXCEDENTE/VALOR DECLARADO',
			if(ge.texcedente>0,'EXCEDENTE','VALOR DECLARADO')) AS concepto, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso,
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ifnull(ge.tseguro,0) tseguro, ge.totros, 
		ifnull(ge.texcedente,0) texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual
		FROM guiasempresariales AS ge
		WHERE (NOT ISNULL(ge.valordeclarado) OR ge.texcedente>0)
		AND factura=$_GET[idfactura]";
		//echo $s;
		
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			while($f = mysql_fetch_object($r)){
				
				$elsubtotal = $f->texcedente+$f->tseguro;
				$totiva = ($_GET['ivacobrado']/100)*$elsubtotal;
				if($_GET['personamoral']=="SI"){
					$totivar = ($_GET['ivarcobrado']/100)*$elsubtotal;
				}else{
					$totivar = "0.00";
				}
				$eltotal = $elsubtotal+$totiva-$totivar;
				
				$xml .= "
				<seleccion>0</seleccion>
				<id>".cambio_texto($f->id)."</id>
				<tipoguia>PREPAGADA</tipoguia>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				<tflete>".cambio_texto($f->tflete)."</tflete>
				<concepto>".cambio_texto($f->concepto)."</concepto>
				<tdescuento>".cambio_texto($f->tdescuento)."</tdescuento>
				<ttotaldescuento>".cambio_texto($f->ttotaldescuento)."</ttotaldescuento>
				<tcostoead>".cambio_texto($f->tcostoead)."</tcostoead>
				<trecoleccion>".cambio_texto($f->trecoleccion)."</trecoleccion>
				<tseguro>".cambio_texto($f->tseguro)."</tseguro>
				<totros>".cambio_texto($f->totros)."</totros>
				<texcedente>".cambio_texto($f->texcedente)."</texcedente>
				<tcombustible>".cambio_texto($f->tcombustible)."</tcombustible>
				<subtotal>".cambio_texto($elsubtotal)."</subtotal>
				<tiva>".cambio_texto($totiva)."</tiva>
				<ivaretenido>".cambio_texto($totivar)."</ivaretenido>
				<total>".cambio_texto($eltotal)."</total>";
			}
			$xml .= "
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>0</encontrados>
				</datos>
				</xml>";
		}
	}
	
	echo $xml;
	
?>
