<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	//solicitar clientes
	if($_GET[accion] == 1){
		$s = "select cc.nombre, cc.paterno, cc.materno, cc.rfc, cc.celular,
		cc.personamoral
		from catalogocliente as cc where id = $_GET[idcliente]";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant0 = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
				<encontro>$cant0</encontro>
				<nombre>".strtoupper(cambio_texto($f->nombre))."</nombre>
				<paterno>".strtoupper(cambio_texto($f->paterno))."</paterno>
				<materno>".strtoupper(cambio_texto($f->materno))."</materno>
				<personamoral>".strtoupper(cambio_texto($f->personamoral))."</personamoral>
				<rfc>".strtoupper(cambio_texto($f->rfc))."</rfc>
				<celular>".cambio_texto($f->celular)."</celular>
			";
			
			//checar guias si son credito o contado o ambas			
			
			$s = "select d.facturacion
			from direccion as d
			where origen = 'cl' and codigo = $_GET[idcliente] 
			and facturacion = 'SI'";
			$r = mysql_query($s) or die($s);
			$encfac = mysql_num_rows($r);
			
			$s = "select d.id, d.calle, d.numero, d.cp, d.colonia, d.poblacion, d.telefono,
			d.crucecalles, d.municipio, d.pais, d.estado, d.fax, d.facturacion
			from direccion as d
			where origen = 'cl' and codigo = $_GET[idcliente] 
			order by facturacion desc";
			//echo "<br>$s<br>";
			$rx = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rx)>0){
				$cant = mysql_num_rows($rx);
				while($fx = mysql_fetch_object($rx)){
					$xml .= "<idcalle>".strtoupper(cambio_texto($fx->id))."</idcalle>
					";
					$xml .= "<calle>".strtoupper(cambio_texto($fx->calle))."</calle>
					";
					$xml .= "<crucecalles>".strtoupper(cambio_texto($fx->crucecalles))."</crucecalles>
					";
					$xml .= "<numero>".cambio_texto($fx->numero)."</numero>
					";
					$xml .= "<cp>".cambio_texto($fx->cp)."</cp>
					";
					$xml .= "<colonia>".strtoupper(cambio_texto($fx->colonia))."</colonia>
					";
					$xml .= "<poblacion>".strtoupper(cambio_texto($fx->poblacion))."</poblacion>
					";
					$xml .= "<municipio>".strtoupper(cambio_texto($fx->municipio))."</municipio>
					";
					$xml .= "<estado>".strtoupper(cambio_texto($fx->estado))."</estado>
					";
					$xml .= "<pais>".strtoupper(cambio_texto($fx->pais))."</pais>
					";
					$xml .= "<fax>".strtoupper(cambio_texto($fx->fax))."</fax>
					";
					$xml .= "<telefono>".cambio_texto($fx->telefono)."</telefono>
					";
					$xml .= "<facturacion>".cambio_texto($fx->facturacion)."</facturacion>
					";
				}
				$xml .= "<encontrodirecciones>$cant</encontrodirecciones>
				<direccionesf>$encfac</direccionesf>
				</datos>
				</xml>";	
			}else{
				$xml .= "<encontrodirecciones>0</encontrodirecciones>
				</datos>
				</xml>";
			}
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
	}
	
	//solicitar guias
	if($_GET[accion] == 2){
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
		where IF(gv.tipoflete=0, gv.idremitente =  $_GET[idcliente], gv.iddestinatario =  $_GET[idcliente])
		AND isnull(factura) AND gv.condicionpago = $_GET[tipoguia]
		AND IF(gv.tipoflete=0, gv.idsucursalorigen=$_GET[sucorigen],  gv.idsucursaldestino=$_GET[sucorigen])
		union
		SELECT ge.id, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso, 
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.totros, 
		ge.texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual
		FROM guiasempresariales AS ge
		WHERE ((ge.tipoguia = 'PREPAGADA' && isnull(ge.valordeclarado)) or ge.tipoguia <> 'PREPAGADA') and
		IF(ge.tipoflete='PAGADA', ge.idremitente =  $_GET[idcliente], ge.iddestinatario =  $_GET[idcliente])
		AND ISNULL(factura) AND ge.tipopago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'")."
		AND IF(ge.tipoflete='PAGADA', ge.idsucursalorigen=$_GET[sucorigen],  ge.idsucursaldestino=$_GET[sucorigen])";
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
				<total>".cambio_texto($f->total)."</total>";
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
		$s = "INSERT INTO facturacion SET facturaestado = 'GUARDADO', idsucursal='$_SESSION[IDSUCURSAL]', cliente = '$_GET[cliente]',
		nombrecliente = '$_GET[nombrecliente]',apellidopaternocliente = '$_GET[apellidopaternocliente]',
		apellidomaternocliente = '$_GET[apellidomaternocliente]', rfc = '$_GET[rfc]',
		calle = '$_GET[calle]',numero = '$_GET[numero]', sustitucion='$_GET[sustitutode]',
		codigopostal = '$_GET[codigopostal]',colonia = '$_GET[colonia]',crucecalles = '$_GET[crucecalles]',
		poblacion = '$_GET[poblacion]',municipio = '$_GET[municipio]',
		estado = '$_GET[estado]',pais = '$_GET[pais]',telefono = '$_GET[telefono]',fax = '$_GET[fax]',
		guiasempresa = '$_GET[guiasempresa]',guiasnormales = '$_GET[guiasnormales]',flete = '$_GET[flete]',
		excedente = '$_GET[excedente]',ead = '$_GET[ead]',recoleccion = '$_GET[recoleccion]',
		seguro = '$_GET[seguro]',combustible = '$_GET[combustible]',otros = '$_GET[otros]',
		subtotal = '$_GET[subtotal]',iva = '$_GET[iva]',ivaretenido = '$_GET[ivaretenido]',
		total = '$_GET[total]',sobseguro = '$_GET[sobseguro]',sobexcedente = '0.00',
		sobsubtotal = '$_GET[sobsubtotal]',sobiva = '$_GET[sobiva]',sobivaretenido = '$_GET[sobivaretenido]',
		sobmontoafacturar = '$_GET[sobmontoafacturar]',otroscantidad = '$_GET[otroscantidad]',
		otrosdescripcion = '$_GET[otrosdescripcion]',otrosimporte = '$_GET[otrosimporte]',otrossubtotal = '$_GET[otrossubtotal]',
		otrosiva = '$_GET[otrosiva]',otrosivaretenido = '$_GET[otrosivaretenido]',otrosmontofacturar = '$_GET[otrosmontofacturar]',
		idusuario='".$_SESSION[IDUSUARIO]."', usuario = '".$_SESSION[NOMBREUSUARIO]."',fecha = CURRENT_DATE";
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
		
		$s = "UPDATE guiasempresariales SET factura = $nfact WHERE id IN($losfolios)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$losfolios2 = "'".str_replace(",","','",$_GET[foliosguias2])."'";
		$s = "UPDATE guiasempresariales SET factura = $nfact WHERE id IN($losfolios2)";
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
		
		$s = "UPDATE facturacion SET facturaestado = 'CANCELADO' WHERE folio = $_GET[foliofactura]";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>1</cancelada>
				</datos>
				</xml>";
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
		$s = "SELECT facturacion.*, DATE_FORMAT(fecha, '%d/%m/%Y') AS fechafactura 
		FROM facturacion WHERE folio = '$_GET[folio]'";
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
				<xtotal>".cambio_texto($f->total)."</xtotal>
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
				<xfecha>".cambio_texto($f->fechafactura)."</xfecha>";
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
			
			$r = mysql_query($s,$l) or die($s);
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
			}
			
			$xml .= "<guiasencontradas>$guiasencontradas</guiasencontradas>
			</datos>
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
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 'VALOR DECLARADO' AS concepto, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso,
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.totros, 
		ge.texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual
		FROM guiasempresariales AS ge
		WHERE ge.estado = 'ALMACEN ORIGEN'  AND ge.tipoguia = 'PREPAGADA' && ge.valordeclarado>0 and
		IF(ge.tipoflete='PAGADA', ge.idremitente =  $_GET[idcliente], ge.iddestinatario =  $_GET[idcliente])
		AND ISNULL(factura) AND ge.tipopago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'")."
		AND IF(ge.tipoflete='PAGADA', ge.idsucursalorigen=$_GET[sucorigen],  ge.idsucursaldestino=$_GET[sucorigen])";
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
		gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, 
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
		date_format(current_date, '%d/%m/%Y') as fechaactual
		FROM liquidacion_detalleead ld 
		INNER JOIN guiasventanilla gv ON ld.guia=gv.id
		INNER JOIN catalogocliente cc ON  
		IF(gv.tipoflete=0, gv.idremitente=cc.id,gv.iddestinatario =cc.id)
		WHERE ld.tipoflete!='PAGADO' AND ld.condicionpago='CREDITO' 
		AND ld.idliquidacion=".$_GET[folio]." AND cc.id=".$_GET[cliente]."	
	UNION
		SELECT ge.id, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, 
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
