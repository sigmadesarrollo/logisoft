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
	if($_GET[accion] == 1){
		$s = "SELECT sc.montoautorizado,cc.activado
		FROM solicitudcredito sc
		inner join catalogocliente as cc on sc.cliente = cc.id
		WHERE sc.cliente=$_GET[idcliente]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$montoautorizado = $f->montoautorizado;
			$creditoactivado = $f->activado;
		}else{
			$montoautorizado = 0;
			$creditoactivado = 'NO';
		}
		
		$s = "select $montoautorizado-sum(restar) as disponible,'a' as grupo from(
			SELECT IFNULL(SUM(IF(pagado='N', total,0)),0) AS restar
			FROM pagoguias 
			WHERE cliente = '$_GET[idcliente]' AND credito='SI'
		)as t
		group by grupo";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$disponible = $f->disponible;
		
		$s = "select cc.nombre, cc.paterno, cc.materno, cc.rfc, cc.celular,
		cc.personamoral, IFNULL(sc.folio,'NO') as foliocredrito
		from catalogocliente as cc 
		left join solicitudcredito as sc on cc.id = sc.cliente
		where id = $_GET[idcliente]";
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
				<creditoactivado>".$creditoactivado."</creditoactivado>
				<disponible>".$disponible."</disponible>
				<rfc>".strtoupper(cambio_texto($f->rfc))."</rfc>
				<celular>".cambio_texto($f->celular)."</celular>
				<foliocredito>$f->foliocredrito</foliocredito>";
			
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
		$s = "(SELECT
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
		where IF(gv.tipoflete=0, gv.idremitente =  $_GET[idcliente], gv.iddestinatario =  $_GET[idcliente])
		AND isnull(factura) AND gv.condicionpago = $_GET[tipoguia]
		AND pg.sucursalacobrar=$_GET[sucorigen])
		union
		(SELECT ge.id, 'CONSIGNACION' as tipoguia, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso, 
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.totros, 
		ge.texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual,
		'G' as tipo
		FROM guiasempresariales AS ge
		inner join pagoguias as pg on ge.id = pg.guia
		WHERE (ge.tipoguia <> 'PREPAGADA') and
		IF(ge.tipoflete='PAGADA', ge.idremitente =  $_GET[idcliente], ge.iddestinatario =  $_GET[idcliente])
		AND ISNULL(factura) AND ge.tipopago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'")."
		AND pg.sucursalacobrar=$_GET[sucorigen])
		UNION
		(SELECT sge.id, 'PREPAGADA' as tipoguia, 0 AS evaluacion, DATE_FORMAT(sge.fecha, '%d/%m/%Y') AS fecha, '' AS fechaentrega, sge.factura, 
		'SOLICIDADA' AS estado, '' AS tipoflete, '' AS ocurre, '' AS idsucursalorigen, 
		'' AS idsucursalorigen, '' AS idsucursaldestino, 0 AS entregaocurre, 
		0 AS entregaead, 0 AS restrinccion, 0 AS totalpaquetes, 0 AS totalpeso, 
		0 AS totalvolumen, 0 AS emplaye, 0 AS bolsaempaque, 0 AS totalbolsaempaque, 
		0 AS avisocelular, 0 AS celular, 0 AS valordeclarado, 0 AS acuserecibo, 
		0 AS cod, 0 AS recoleccion, 0 AS observaciones, sge.subtotal AS tflete, 0 AS tdescuento, 
		0 AS ttotaldescuento, 0 AS tcostoead, 0 AS trecoleccion, 0 AS tseguro, 0 AS totros, 
		0 AS texcedente, sge.combustible AS tcombustible, sge.subtotal, sge.iva AS tiva, sge.ivar AS ivaretenido, 
		sge.total AS total, 0 AS efectivo, 0 AS cheque, 0 AS banco, 0 AS ncheque, 0 AS tarjeta, 0 AS trasferencia, 
		sge.usuario, sge.fecha, '' AS hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual,
		'S' as tipo
		FROM solicitudguiasempresariales AS sge
		WHERE sge.idcliente = $_GET[idcliente] AND sge.prepagada = 'SI' and  (ISNULL(factura) or factura=0)
		and sge.estado = 'GUARDADA'
		and sge.condicionpago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'").")";
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
		
		$sx = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = $_GET[sucorigen]) AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
		$rx = mysql_query($sx,$l) or die($sx);
		$fxpr = mysql_fetch_object($rx);
		$iva = $fxpr->iva;
		$ivar = $fxpr->iva;
		
		$sy = "select personamoral from catalogocliente where id = $_GET[cliente]";
		$ry = mysql_query($sy,$l) or die($sy);
		$fpm = mysql_fetch_object($rx);
		$personamoral = $fpm->personamoral;
		
		$s = "INSERT INTO facturacion SET facturaestado = 'GUARDADO', idsucursal='$_SESSION[IDSUCURSAL]', cliente = '$_GET[cliente]',
		nombrecliente = '$_GET[nombrecliente]',apellidopaternocliente = '$_GET[apellidopaternocliente]',
		apellidomaternocliente = '$_GET[apellidomaternocliente]', rfc = '$_GET[rfc]', credito='$_GET[credito]',
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
		idusuario='".$_SESSION[IDUSUARIO]."', usuario = '".$_SESSION[NOMBREUSUARIO]."',fecha = CURRENT_DATE,
		ivacobrado='$iva', ivarcobrado='$ivar', personamoral='$personamoral'".(($_GET[credito]=='SI')?"":", estadocobranza='C'");
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		$nfact = mysql_insert_id($l);
		
		
		if($_GET[valorcontado]!="0" && $_GET[valorcontado]!=""){
			
			if($_GET[credito]=="SI"){
				$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$_GET[valorcontado]', 
				fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
				cliente = '$_GET[cliente]', credito='SI',
				sucursalacobrar = '$_SESSION[IDSUCURSAL]', pagado='N'";
				$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<guardado>0</guardado>
						<consulta>$s</consulta>
						</datos>
						</xml>");
			}else{
				$s = "INSERT INTO formapago SET guia='$nfact',procedencia='F',tipo='O',
				total='$_GET[valorcontado]',efectivo='$_GET[efectivo]',
				tarjeta='$_GET[tarjeta]',transferencia='$_GET[trasferencia]',cheque='$_GET[cheque]',
				ncheque='$_GET[ncheque]',banco='$_GET[banco]',notacredito='$_GET[nc]',
				nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',usuario='$_SESSION[IDUSUARIO]',fecha=current_date";
				mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<guardado>0</guardado>
						<consulta>$s</consulta>
						</datos>
						</xml>");
				
				$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$_GET[valorcontado]', 
				fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
				cliente = '$_GET[cliente]',	sucursalacobrar = '$_SESSION[IDSUCURSAL]', pagado='S', 
				fechapago = CURRENT_DATE, usuariocobro = $_SESSION[IDUSUARIO], sucursalcobro = $_SESSION[IDSUCURSAL]";
				$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<guardado>0</guardado>
						<consulta>$s</consulta>
						</datos>
						</xml>");
			}
		}
		
		
		$foliotipo = $_GET[foliotipo];
		
		$losfolios = "'".str_replace(",","','",$_GET[foliosguias])."'";
		
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
		
		$s = "UPDATE guiasventanilla SET factura = $nfact WHERE id IN($foliosguias)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		//echo $s;
		
		$s = "UPDATE guiasempresariales SET factura = $nfact WHERE id IN($foliosguias)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		//echo $s;
		$s = "update solicitudguiasempresariales set factura = $nfact, foliosactivados='SI' where id in($foliosolici)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		//echo $s;
		$losfolios2 = "'".str_replace(",","','",$_GET[foliosguias2])."'";
		$s = "UPDATE guiasempresariales SET factura = $nfact WHERE id IN($losfolios2)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		//echo $s;
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
		
		$s = "UPDATE guiasempresariales SET factura = NULL WHERE factura = $_GET[foliofactura]";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "UPDATE solicitudguiasempresariales SET factura = NULL, foliosactivados='NO' WHERE factura = $_GET[foliofactura]";
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
		
		$s = "UPDATE pagoguias SET pagado = 'C' WHERE guia = '$_GET[foliofactura]' and tipo = 'FACT'";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>1</cancelada>
				<consulta>$s</consulta>
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
		$s = "(SELECT
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
		where factura=$_GET[idfactura])
		union
		(SELECT ge.id, 'EMPRESARIAL' as tipoguia, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 
		ge.entregaead, ge.restrinccion, ge.totalpaquetes, ge.totalpeso, 
		ge.totalvolumen, ge.emplaye, ge.bolsaempaque, ge.totalbolsaempaque, 
		ge.avisocelular, ge.celular, ge.valordeclarado, ge.acuserecibo, 
		ge.cod, ge.recoleccion, ge.observaciones, ge.tflete, ge.tdescuento, 
		ge.ttotaldescuento, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.totros, 
		ge.texcedente, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total, ge.efectivo, ge.cheque, ge.banco, ge.ncheque, ge.tarjeta, ge.trasferencia, 
		ge.usuario, ge.fecha_registro, ge.hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual,
		'G' as tipo
		FROM guiasempresariales AS ge
		inner join pagoguias as pg on ge.id = pg.guia
		WHERE (isnull(ge.valordeclarado) && ge.tipoguia <> 'PREPAGADA' && ge.texcedente = 0)
		AND factura=$_GET[idfactura])
		UNION
		(SELECT sge.id, 'EMPRESARIAL' as tipoguia, 0 AS evaluacion, DATE_FORMAT(sge.fecha, '%d/%m/%Y') AS fecha, '' AS fechaentrega, sge.factura, 
		'SOLICIDADA' AS estado, '' AS tipoflete, '' AS ocurre, '' AS idsucursalorigen, 
		'' AS idsucursalorigen, '' AS idsucursaldestino, 0 AS entregaocurre, 
		0 AS entregaead, 0 AS restrinccion, 0 AS totalpaquetes, 0 AS totalpeso, 
		0 AS totalvolumen, 0 AS emplaye, 0 AS bolsaempaque, 0 AS totalbolsaempaque, 
		0 AS avisocelular, 0 AS celular, 0 AS valordeclarado, 0 AS acuserecibo, 
		0 AS cod, 0 AS recoleccion, 0 AS observaciones, sge.subtotal AS tflete, 0 AS tdescuento, 
		0 AS ttotaldescuento, 0 AS tcostoead, 0 AS trecoleccion, 0 AS tseguro, 0 AS totros, 
		0 AS texcedente, sge.combustible AS tcombustible, sge.subtotal, sge.iva AS tiva, sge.ivar AS ivaretenido, 
		sge.total AS total, 0 AS efectivo, 0 AS cheque, 0 AS banco, 0 AS ncheque, 0 AS tarjeta, 0 AS trasferencia, 
		sge.usuario, sge.fecha, '' AS hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual,
		'S' as tipo
		FROM solicitudguiasempresariales AS sge
		WHERE sge.prepagada = 'SI' AND factura=$_GET[idfactura])";
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
