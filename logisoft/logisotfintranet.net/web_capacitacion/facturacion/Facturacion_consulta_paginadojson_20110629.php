<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
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

		$s = "select cc.id, cc.nombre, cc.paterno, cc.materno, cc.rfc, cc.celular,
		cc.personamoral, IFNULL(sc.folio,'NO') as foliocredrito
		from catalogocliente as cc 
		left join solicitudcredito as sc on cc.id = sc.cliente
		where id = $_GET[idcliente]";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant0 = mysql_num_rows($r);
			$f = mysql_fetch_object($r);
			
			$cliente = "{
				'nombre':'".strtoupper($f->nombre)."',
				'paterno':'".strtoupper($f->paterno)."',
				'materno':'".strtoupper($f->materno)."',
				'rfc':'".strtoupper($f->rfc)."',
				'celular':'".$f->celular."',
				'foliocredrito':'".$f->foliocredrito."',
				'personamoral':'".strtoupper($f->personamoral)."',
				'encontro':'$cant0',
				'idcliente':'$f->id',
				'vencido':'$vencido',
				'creditoactivado':'$creditoactivado',
				'disponible':$disponible
			}
			";
			
			
			$s = "select d.id, d.calle, d.numero, d.cp codigopostal, d.colonia, d.poblacion, d.telefono,
			d.crucecalles, d.municipio, d.pais, d.estado, d.fax, d.facturacion
			from direccion as d
			where origen = 'cl' and codigo = $_GET[idcliente] ";
			//echo "<br>$s<br>";
			$rx = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rx)>0){
				$cant = mysql_num_rows($rx);
				while($fx = mysql_fetch_object($rx)){
					$fx->id = strtoupper($fx->id);
					$fx->calle = strtoupper($fx->calle);
					$fx->numero = strtoupper($fx->numero);
					$fx->codigopostal = strtoupper($fx->codigopostal);
					$fx->colonia = strtoupper($fx->colonia);
					$fx->poblacion = strtoupper($fx->poblacion);
					$fx->crucecalles = strtoupper($fx->crucecalles);
					$fx->municipio = strtoupper($fx->municipio);
					$fx->pais = strtoupper($fx->pais);
					$fx->estado = strtoupper($fx->estado);
					$fx->fax = strtoupper($fx->fax);
					$fx->facturacion = strtoupper($fx->facturacion);
					$fx->municipio = strtoupper($fx->municipio);
					$fx->telefono = strtoupper($fx->telefono);
					$datos[] = $fx;
				}	
				$direcciones = json_encode($datos);
			}else{
				$direcciones = "[]";
			}
			echo "({
				'cliente':".str_replace('&#32;','',$cliente).",
				'direcciones':".str_replace('&#32;','',$direcciones)."
			})";
		}else{
			echo "({
				'cliente':0
			})";
		}
	}
	
	//solicitar guias
	if($_GET[accion] == 2){
		if($_GET[guia] == "vent"){
		
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
		AND (gv.estado <> 'CANCELADA' and gv.estado <> 'CANCELADO')
		AND pg.sucursalacobrar=$_GET[sucorigen] and (pg.pagado <> 'C' or ISNULL(pg.pagado)))";
		
		}else if($_GET[guia] == "emp"){
		
		if($_GET[idcliente]==159){
			//$condicionx = " AND ge.id < '999000010000A' ";
		}
		
		$s = "(SELECT ge.id, 'CONSIGNACION' as tipoguia, ge.evaluacion, 
		DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
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
		inner join generacionconvenio gc on ge.idremitente = gc.idcliente AND gc.sucursal=$_GET[sucorigen]
		inner join pagoguias as pg on ge.id = pg.guia
		WHERE (ge.tipoguia <> 'PREPAGADA') and ge.estado <> 'ENT SIST ANTERIOR' and
		IF(ge.tipoflete='PAGADA', ge.idremitente =  $_GET[idcliente], ge.iddestinatario =  $_GET[idcliente])
		AND ISNULL(factura) AND ge.tipopago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'")." and (pg.pagado <> 'C' OR ISNULL(pg.pagado))
		$condicionx
		group by ge.id
		) 
		UNION
		(SELECT sge.id, 'PREPAGADA' AS tipoguia, 0 AS evaluacion, DATE_FORMAT(sge.fecha, '%d/%m/%Y') AS fecha, '' AS fechaentrega, sge.factura, 
		'SOLICIDADA' AS estado, '' AS tipoflete, '' AS ocurre, '' AS idsucursalorigen, 
		'' AS idsucursalorigen, '' AS idsucursaldestino, 0 AS entregaocurre, 
		0 AS entregaead, 0 AS restrinccion, 0 AS totalpaquetes, 0 AS totalpeso, 
		0 AS totalvolumen, 0 AS emplaye, 0 AS bolsaempaque, 0 AS totalbolsaempaque, 
		0 AS avisocelular, 0 AS celular, 0 AS valordeclarado, 0 AS acuserecibo, 
		0 AS cod, 0 AS recoleccion, 0 AS observaciones, 
		
		IF(cp.descuento>0 AND gc.limitekg=cp.valpeso,
		cp.descuento*sge.cantidad,
		sge.subtotal-IFNULL(sge.combustible,0)) AS tflete, 
		
		0 AS tdescuento, 
		0 AS ttotaldescuento, 0 AS tcostoead, 0 AS trecoleccion, 0 AS tseguro, 0 AS totros, 
		0 AS texcedente, 
		
		IF(cp.descuento>0 AND gc.limitekg<=cp.valpeso,
		(cp.descuento*sge.cantidad)*(cg.cargocombustible/100),
		sge.combustible
		) AS tcombustible, 
		
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
		0 AS efectivo, 0 AS cheque, 0 AS banco, 0 AS ncheque, 0 AS tarjeta, 0 AS trasferencia, 
		sge.usuario, sge.fecha, '' AS hora_registro, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual,
		'S' AS tipo
		FROM solicitudguiasempresariales AS sge
		INNER JOIN generacionconvenio gc ON sge.idconvenio = gc.folio
		INNER JOIN catalogosucursal cs ON sge.sucursalacobrar = cs.id
		INNER JOIN configuradorgeneral cg 
		LEFT JOIN configuracion_promociones cp ON cp.tipo='EMPRESARIAL' AND 
			CURRENT_DATE BETWEEN cp.desde AND cp.hasta 
		WHERE sge.idcliente = $_GET[idcliente] AND sge.prepagada = 'SI' AND  (ISNULL(sge.factura) OR sge.factura=0)
		AND sge.estado = 'GUARDADA' 
		and sge.condicionpago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'").")";
		}
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$arre = array();
			while($f = mysql_fetch_object($r)){
				$f->seleccion = 'S';
				
				$arre[] = $f;
			}
			$grid1 = json_encode($arre);
			echo '({
				   "grid1":'.$grid1.'
			})';
		}else{
			echo '({
				   "grid1":[]
			})';
		}
	}
	
	//guardar factura
	if($_POST[accion] == 3){
		
		$sx = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = $_POST[sucorigen]) AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
		$rx = mysql_query($sx,$l) or die($sx);
		$fxpr = mysql_fetch_object($rx);
		$iva = $fxpr->iva;
		$ivar = $fxpr->iva;
		
		$sy = "select personamoral from catalogocliente where id = $_POST[cliente]";
		$ry = mysql_query($sy,$l) or die($sy);
		$fpm = mysql_fetch_object($rx);
		$personamoral = $fpm->personamoral;
		
		$_POST[data] = str_replace("xAMx","&",$_POST[data]);
		$_POST[data] = str_replace("xIQx","=",$_POST[data]);
		
		//echo $_POST[data];
		
       	$ch = curl_init("http://pmm.impresordigital.com/invoices/remote/136f43d234b5c17c34cbd7c7367cd93a");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST[data]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);       
        curl_close($ch);
		
		$arre = split("~",$output);
		
		/*$arre[0] = "";
		$arre[1] = "";*/
		
		
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
		total = '$_POST[total]',sobseguro = '$_POST[sobseguro]',sobexcedente = '0.00',
		sobsubtotal = '$_POST[sobsubtotal]',sobiva = '$_POST[sobiva]',sobivaretenido = '$_POST[sobivaretenido]',
		sobmontoafacturar = '$_POST[sobmontoafacturar]',otroscantidad = '$_POST[otroscantidad]',
		otrosdescripcion = '$_POST[otrosdescripcion]',otrosimporte = '$_POST[otrosimporte]',otrossubtotal = '$_POST[otrossubtotal]',
		otrosiva = '$_POST[otrosiva]',otrosivaretenido = '$_POST[otrosivaretenido]',otrosmontofacturar = '$_POST[otrosmontofacturar]',
		idusuario='".$_SESSION[IDUSUARIO]."', usuario = '".$_SESSION[NOMBREUSUARIO]."',fecha = CURRENT_DATE,
		ivacobrado='$iva', ivarcobrado='$ivar', personamoral='$personamoral'".(($_POST[credito]=='SI')?"":", estadocobranza='C'");
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		$nfact = mysql_insert_id($l);
		
		$s = "SELECT * FROM catalogofoliosfacturacion ORDER BY id DESC LIMIT 1";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$_POST[data] .= "&data[informacion][folio]=$nfact&data[informacion][anoaprobacion]=$f->anoaprobacion&data[informacion][numeroaprobacion]=$numeroaprobacion";
		
		$ch = curl_init("http://pmm.impresordigital.com/invoices/remote/136f43d234b5c17c34cbd7c7367cd93a");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST[data]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);       
        curl_close($ch);
		
		$arre = split("~",$output);
		
		$s = "update facturacion set xml='".html_entity_decode($arre[0])."', cadenaoriginal='".html_entity_decode($arre[1])."' 
		where folio = $nfact";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		
		$s = "insert into historialmovimientos(modulo,folio,estado,idusuario,fechamodificacion) 
			values ('facturacion',$nfact,'GUARDADO',$_SESSION[IDUSUARIO],CURRENT_TIMESTAMP);";
			mysql_query($s,$l) or die(mysql_error($l).$s);
		
		if($_POST[credito]=="NO"){
			$s = "INSERT INTO facturacion_fechapago (factura,fechapago)
			SELECT '$nfact', CURRENT_DATE;";
			mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<guardado>0</guardado>
					<consulta>$s</consulta>
					</datos>
					</xml>");
		}
		
		if($_POST[valorcontado]!="0" && $_POST[valorcontado]!=""){
			
			if($_POST[credito]=="SI"){
				$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$_POST[valorcontado]', 
				fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
				cliente = '$_POST[cliente]', credito='SI',
				sucursalacobrar = '$_SESSION[IDSUCURSAL]', pagado='N'";
				$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<guardado>0</guardado>
						<consulta>$s</consulta>
						</datos>
						</xml>");
			}else{
				$s = "INSERT INTO formapago SET guia='$nfact',procedencia='F',tipo='O',
				total='$_POST[valorcontado]',efectivo='$_POST[efectivo]',
				tarjeta='$_POST[tarjeta]',transferencia='$_POST[trasferencia]',cheque='$_POST[cheque]',
				ncheque='$_POST[ncheque]',banco='$_POST[banco]',notacredito='$_POST[nc]', cliente = '$_POST[cliente]',
				nnotacredito='$_POST[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',usuario='$_SESSION[IDUSUARIO]',fecha=current_date";
				mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<guardado>0</guardado>
						<consulta>$s</consulta>
						</datos>
						</xml>");
				
				
				$s = "INSERT INTO pagoguias SET guia = '$nfact', tipo='FACT', total='$_POST[valorcontado]', 
				fechacreo = CURRENT_DATE, usuariocreo = $_SESSION[IDUSUARIO], sucursalcreo = $_SESSION[IDSUCURSAL], 
				cliente = '$_POST[cliente]',	sucursalacobrar = '$_SESSION[IDSUCURSAL]', pagado='S', 
				fechapago = CURRENT_DATE, usuariocobro = $_SESSION[IDUSUARIO], sucursalcobro = $_SESSION[IDSUCURSAL]";
				$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<guardado>0</guardado>
						<consulta>$s</consulta>
						</datos>
						</xml>");
			}
		}
		
		
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
		$losfolios2 = "'".str_replace(",","','",$_POST[foliosguias2])."'";
		$s = "UPDATE guiasempresariales SET factura = $nfact WHERE id IN($losfolios2)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		
		#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
		$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$nfact'";
			$r = mysql_query(str_replace("''",'NULL',$sql),$l) or die($sql);
			if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					#SE REGISTRA LA VENTA
					$s = "CALL proc_RegistroVendedores('REG_VEN_GEC', '$f->guias');";
					mysql_query($s,$l) or die($s);
					
					if($_POST[credito]!="SI"){
						#en caso de ke sea contado se registran la comision
						$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$f->guias');";
						mysql_query($s,$l) or die($s);
					}
				}
			}
			
			#para el registro de folios prepagados en reporte vendedores
			$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$nfact'";
			$r = mysql_query($sql,$l) or die($sql);
			if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					#SE REGISTRA LA VENTA
					$s = "CALL proc_RegistroVendedores('REG_VEN_PRE', '$f->id');";
					mysql_query($s,$l) or die($s);
						
					#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
					if($_POST[credito]!="SI"){
						$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$f->id');";
						mysql_query($s,$l) or die($s);
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
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");

		$s = "INSERT INTO facturadetalle
		SELECT 0 AS id, ".$nfact." AS factura, ge.id AS folio, 'CONSIGNACION' AS tipoguia, ge.fecha,
		ge.tflete, ge.ttotaldescuento, ge.texcedente, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.tcombustible, ge.totros, 
		ge.subtotal, ge.tiva, ge.ivaretenido, ge.total, 'G' AS tipo, 
		".$_SESSION[IDUSUARIO]." AS idusuario, CURRENT_TIMESTAMP
		FROM guiasempresariales AS ge WHERE ge.id IN($foliosguias)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "INSERT INTO facturadetalle
		SELECT 0 AS id, ".$nfact." AS factura, sge.id AS folio, 'PREPAGADA' AS tipoguia,sge.fecha,
		sge.subtotal-sge.combustible AS tflete,0 as descuento, 0 AS texcedente, 0 AS tcostoead, 0 AS trecoleccion, 0 AS costoseguro, 
		sge.combustible AS tcombustible, 0 AS totros, 
		sge.subtotal, sge.iva AS tiva, sge.ivar AS ivaretenido, 
		sge.total AS total, 'S' AS tipo, 
		".$_SESSION[IDUSUARIO]." AS idusuario, CURRENT_TIMESTAMP
		FROM solicitudguiasempresariales AS sge WHERE sge.id IN($foliosolici)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
				
		$s = "INSERT INTO reportecliente5(guia,fecha,idcliente,remitente,importe,factura)
		SELECT fd.folio, f.fecha, f.cliente,
		CONCAT_WS(' ',f.nombrecliente,f.apellidopaternocliente,f.apellidomaternocliente) AS remitente,
		fd.total, ".$nfact." AS factura FROM facturacion f
		INNER JOIN facturadetalle fd ON f.folio = fd.factura
		WHERE fd.factura = ".$nfact." AND fd.tipoguia = 'PREPAGADA'";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "INSERT INTO facturadetalleguias
		SELECT 0 AS id, ".$nfact." AS factura, ge.id AS guia, ge.fecha AS fechaguia, 'PREPAGADA' AS tipoguia,
		IF(NOT ISNULL(ge.valordeclarado) AND ge.texcedente>0,'EXCEDENTE/VALOR DECLARADO',
		IF(ge.texcedente>0,'EXCEDENTE','VALOR DECLARADO')) AS concepto, 
		IFNULL(ge.tseguro,0) AS tseguro,
		IFNULL(ge.texcedente,0) AS texcedente, ge.subtotal, ge.tiva, ge.ivaretenido, 
		ge.total,'' AS idusuario, CURRENT_TIMESTAMP FROM guiasempresariales AS ge
		WHERE ge.id IN($losfolios2)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		#se registra la venta de otros en caso de que haya
		if($_POST[otrosmontofacturar]!="" && $_POST[otrosmontofacturar]!="0"){
			$s = "call proc_RegistroVentas('VENTA_OTROS','$nfact',0)";
			$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		}
		
		#se registra la venta de solicitud de folios en caso de que haya
		$paraciclo = split(',',$foliosolici);
		for($i=0; $i<count($paraciclo); $i++){
			$s = "call proc_RegistroVentas('VENTA_FOLIOS',".$paraciclo[$i].",0)";
			$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s =Z> ".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		}
		#se registra las guias empresariales con excedente si es ke hay
		$paraciclo = split(',',$_POST[foliosguias2]);
		if($_POST[foliosguias2]!=""){
			for($i=0; $i<count($paraciclo); $i++){
				$s = "call proc_RegistroVentas('VENTA_EXCEDENTE','".$paraciclo[$i]."',0)";
				$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<guardado>0</guardado>
					<consulta>".str_replace("''","null",$s)."</consulta>
					</datos>
					</xml>");
			}
		}
		
		#facturar guias en caso de que se facture alguna guia normal
		$s = "CALL proc_RegistroVentas('FACTURAR_GUIAS','$nfact',0);";
		$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<guardado>0</guardado>
			<consulta>".str_replace("''","null",$s)."</consulta>
			</datos>
			</xml>");
		
		$s = "CALL proc_RegistroClientes('facturacion',".$_POST[cliente].",0,".$nfact.",0)";
		$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<guardado>0</guardado>
			<consulta>".str_replace("''","null",$s)."</consulta>
			</datos>
			</xml>");
		if($_POST[credito]=="SI"){
			$s = "CALL proc_RegistroCobranza('FACTURA', $nfact, '', '', 0, 0);";
			$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		}
		
		if($_POST[tipoguiac]=='empresarial'){
			$s = "call proc_RegistroAuditorias('LF','$nfact',$_SESSION[IDSUCURSAL])";
			$d = mysql_query($s, $l);
		}
		
		/*$s = "SELECT f.cliente, d.folio FROM facturacion f
		INNER JOIN facturadetalle d ON f.folio = d.factura
		WHERE d.factura = ".$nfact." AND d.tipoguia='PREPAGADA'";
		$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$s = "CALL proc_RegistroClientes('ventas',".$f->cliente.",0,0,".$f->folio.")";
				$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<guardado>0</guardado>
					<consulta>".str_replace("''","null",$s)."</consulta>
					</datos>
					</xml>");
			}*/
		
		$s = "CALL proc_VentasVsPresupuesto('FACTURA','$nfact',$_SESSION[IDSUCURSAL]);";
		$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<guardado>0</guardado>
			<consulta>".str_replace("''","null",$s)."</consulta>
			</datos>
			</xml>");
		
		if($_POST[tipoguiac]=='empresarial'){
			$s = "CALL proc_RegistroFranquiciasConceciones('facturacion', $nfact, ".$_SESSION[IDSUCURSAL].", 0);";
			$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		}
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<foliofactura>$nfact</foliofactura>
				<guardado>1</guardado>
				<fechapago>".date('d/m/Y')."</fechapago>
				</datos>
				</xml>";
		
	}
	
	//cancelar factura
	if($_GET[accion] == 4){
		
		#CANCELAR FACTURA VENTAS CONTRA PRESUPUESTO
		$s = "CALL proc_VentasVsPresupuesto('CAN_FACTURA','$_GET[foliofactura]',$_SESSION[IDSUCURSAL]);";
		$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<guardado>0</guardado>
			<consulta>".str_replace("''","null",$s)."</consulta>
			</datos>
			</xml>");
		
		/* cambiar la guia a ocurre si es cargo ead*/
		$s = "SELECT SUBSTRING(otrosdescripcion,31) guia, cliente FROM facturacion 
		WHERE otrosdescripcion LIKE '%TRASPASO OCURRE A EAD DE GUIA%' AND folio = $_GET[foliofactura]";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$guia = $f->guia;
			
			$s = "UPDATE guiasventanilla SET ocurre = 1 WHERE id = '$guia'";
			mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
			
			$s = "UPDATE guiasempresariales SET ocurre = 1 WHERE id = '$guia'";
			mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		}
		/***********************************************/
		$s = "select cliente from facturacion where folio = $_GET[foliofactura]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "CALL proc_RegistroClientes('cancelacionFactura',".$f->cliente.",0,".$_GET[foliofactura].",0)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "CALL proc_RegistroVendedores('CAN_FACTURA', '$_GET[foliofactura]');";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
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
		
		
		$s = "UPDATE facturacion SET facturaestado = 'CANCELADO', fechacancelacion=current_date WHERE folio = $_GET[foliofactura]";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "UPDATE pagoguias SET pagado = 'C', fechacancelacion=current_date WHERE guia = '$_GET[foliofactura]' and tipo = 'FACT'";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "CALL proc_RegistroVentas('CANCELAR_FACTURA','$_GET[foliofactura]',0)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");		
		
		$s = "SELECT cliente FROM facturacion WHERE folio = ".$_GET[foliofactura]."";
		$r = mysql_query($s) or die($l); $f = mysql_fetch_object($r);		
		
		$s = "UPDATE solicitudguiasempresariales SET factura = NULL, foliosactivados='NO' WHERE factura = $_GET[foliofactura]";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "CALL proc_RegistroCobranza('CANCELARFACTURA', '$_GET[foliofactura]', '', '', 0, 0)";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>0</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "call proc_RegistroAuditorias('FC','$_GET[foliofactura]',NULL)";
		$d = mysql_query($s, $l);
		
		//proc_RegistroCobranza()
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<cancelada>1</cancelada>
				<consulta>$s</consulta>
				</datos>
				</xml>";
	}
	
	//solicitar maxid
	if($_GET[accion] == 5){
		$s = "select max(folio)+1 as foliom, DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual
		from facturacion where folio > 0";
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
				<fecha>$f->fechaactual</fecha>
				</datos>
				</xml>";
	}
	
	//solicitar factura
	if($_GET[accion] == 6){
		
		/*Sacar el estado de la guia si es diferente almacen destino o no*/
		
		$sepuede = "SI";
		
		$s = "SELECT SUBSTRING(otrosdescripcion,31) guia FROM facturacion 
		WHERE otrosdescripcion LIKE '%TRASPASO OCURRE A EAD DE GUIA%' AND folio='$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$s = "SELECT estado FROM guiasventanilla WHERE id = '$f->guia'
			UNION 
			SELECT estado FROM guiasempresariales WHERE id = '$f->guia'";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			if($f->estado == 'ALMACEN DESTINO')
				$sepuede = "SI";
			else
				$sepuede = "NO";
		}
		
		
		
		$s = "SELECT DATE_FORMAT(fechapago, '%d/%m/%Y') fechapago FROM facturacion_fechapago WHERE factura = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<resultado>0</resultado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$fechapago = $f->fechapago;
		}else{
			$fechapago = "NO PAGADA";
		}
		
		$s = "SELECT facturacion.*, date_format(facturacion.fechacancelacion,'%d/%m/%Y') fechacancelacion, catalogosucursal.descripcion as nsucursal,
		DATE_FORMAT(facturacion.fecha, '%d/%m/%Y') AS fechafactura 
		FROM facturacion 
		INNER JOIN catalogosucursal on facturacion.idsucursal = catalogosucursal.id
		WHERE facturacion.folio = '$_GET[folio]'
		".(($_SESSION[IDSUCURSAL]==1)?"":" AND facturacion.idsucursal = $_SESSION[IDSUCURSAL]");
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
				<tipodefactura>".cambio_texto($f->tipoguia)."</tipodefactura>
				<sepuede>$sepuede</sepuede>
				<fechapago>".cambio_texto($fechapago)."</fechapago>
				<fechacancelacion>".cambio_texto($f->fechacancelacion)."</fechacancelacion>
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
				<totaldescuento>".cambio_texto($f->totaldescuento)."</totaldescuento>
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
		$s = "select max(folio)folio from generacionconvenio where idcliente = $_GET[idcliente]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$folioconvenio = $f->folio;
		
		$s = "SELECT ge.id, ge.evaluacion, DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha, ge.fechaentrega, ge.factura, 
		ge.estado, ge.tipoflete, ge.ocurre, ge.idsucursalorigen, 
		ge.idsucursalorigen,ge.idsucursaldestino,ge.entregaocurre, 'PREPAGADA' tipoguia,
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
		INNER JOIN generacionconvenio gc ON ge.idremitente = gc.idcliente and gc.folio = $folioconvenio
		WHERE (NOT ISNULL(ge.valordeclarado) OR ge.texcedente>0) AND ge.tipoguia='PREPAGADA' AND
		ge.idremitente =  $_GET[idcliente]
		AND ISNULL(factura) AND ge.tipopago = ".(($_GET[tipoguia]==1)?"'CREDITO'":"'CONTADO'")."
		AND gc.sucursal=$_GET[sucorigen]";
		
		//echo $s;
		$sx = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = $_GET[sucorigen]) AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
		$rx = mysql_query($sx,$l) or die($sx);
		$fxpr = mysql_fetch_object($rx);
		
		$sy = "select personamoral from catalogocliente where id = $_GET[idcliente]";
		$ry = mysql_query($sy,$l) or die($sy);
		$fpm = mysql_fetch_object($ry);
		
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$arre = array();
			while($f = mysql_fetch_object($r)){
				$f->subtotal = $f->texcedente+$f->tseguro;
				$f->tiva = ($fxpr->iva/100)*$f->subtotal;
				if($fpm->personamoral=="SI"){
					$f->ivaretenido = ($fxpr->ivaretenido/100)*$f->subtotal;
				}else{
					$f->ivaretenido = "0.00";
				}
				$f->total = $f->subtotal+$f->tiva-$f->ivaretenido;
				
				$f->seleccion = 'S';
				
				$arre[] = $f;
			}
			$grid1 = json_encode($arre);
			echo '({
				   "grid2":'.$grid1.'
			})';
		}else{
			echo '({
				   "grid2":[]
			})';
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
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON  
		IF(gv.tipoflete=0, gv.idremitente=cc.id,gv.iddestinatario =cc.id)
		WHERE gv.id='".$_GET[folio]."'
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
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON  IF(ge.tipoflete='PAGADA', ge.idremitente=cc.id,ge.iddestinatario =cc.id)
		WHERE ge.id='".$_GET[folio]."'";
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
