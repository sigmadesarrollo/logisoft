<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_SESSION[modulosextras]!="SI"){
		die("<script>document.location.href='index.php';</script>");
	}
	
	if($_POST[accion]=='guardar'){
		
		$s = "CREATE TEMPORARY TABLE `datosconvenio` (
		  `cliente` DOUBLE DEFAULT NULL,
		  `pesolimite` DOUBLE DEFAULT NULL,
		  `precio` DOUBLE DEFAULT NULL,
		  `preciokgexcedente` DOUBLE DEFAULT NULL,
		  `valordeclarado` DOUBLE DEFAULT NULL,
		  `limite` DOUBLE DEFAULT NULL,
		  `porcada` DOUBLE DEFAULT NULL,
		  `costoextra` DOUBLE DEFAULT NULL
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into datosconvenio
		SELECT '$_POST[txtnumCliente]', cco.pesolimite, cco.precio, cco.preciokgexcedente,
		valordeclarado,limite,porcada,costoextra
		FROM generacionconvenio gc
		INNER JOIN cconvenio_configurador_caja cco ON gc.folio = cco.idconvenio
		AND cco.tipo = 'CONSIGNACION' 
		WHERE gc.estadoconvenio = 'ACTIVADO' AND gc.idcliente = '$_POST[txtnumCliente]'
		GROUP BY gc.idcliente;";
		mysql_query($s,$l) or die($s);
		
		
		$s = "INSERT INTO tablasguiasclientes
			SELECT guiaempresarial id,'no' borrar
			FROM evaluacionmercancia 
			WHERE estado = 'GUARDADO' AND sucursal = '$_POST[sucursal]' AND guiaempresarial <> ''
			UNION
			SELECT id,'no' borrar 
			FROM guiasventanillaclientes 
			WHERE estado = 'GUARDADA' AND idremitente = '$_POST[txtnumCliente]' and impreso = 'S';";
		mysql_query($s,$l) or die($s);
			
		$s ="UPDATE tablasguiasclientes gc
			INNER JOIN guiasempresariales g ON gc.id = g.id
			SET gc.borrar = 'si';";
 		mysql_query($s,$l) or die($s);

		$s = "DELETE FROM tablasguiasclientes WHERE borrar = 'si';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE tablasguiasclientes set borrar = 'si';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE tablasguiasclientes gc
			INNER JOIN guiasventanillaclientes g ON gc.id = g.id 
			SET gc.borrar = 'no';";
		mysql_query($s,$l) or die($s);
			
		$s = "DELETE FROM tablasguiasclientes WHERE borrar = 'si';";
		mysql_query($s,$l) or die($s);
		
		/****** ANTERIOR ****
		$s = "INSERT INTO guiasempresariales 
			(id,estado,tipoflete,tipopago,tipoguia,
			fecha,iddireccionremitente,
			ocurre, idsucursalorigen, iddestino, 
			idsucursaldestino, idremitente,iddestinatario, iddirecciondestinatario, entregaocurre, entregaead,
					restrinccion, totalpaquetes, totalpeso, totalvolumen, emplaye, bolsaempaque, 
					totalbolsaempaque, avisocelular, celular, valordeclarado, acuserecibo, cod, recoleccion, 
					observaciones, tflete, tdescuento, ttotaldescuento, tcostoead, trecoleccion, 
					tseguro, totros, texcedente, tcombustible, subtotal, tiva, ivaretenido, total, 
					efectivo, cheque, banco, ncheque, clienteconvenio, sucursalconvenio, idvendedorconvenio, 
					nvendedorconvenio, convenioaplicado, idsolicitudguia, tarjeta, trasferencia, usuario,ubicacion,sector,
					idusuario, fecha_registro, hora_registro)
			SELECT gc.id,'ALMACEN ORIGEN',IF(gc.tipoflete=0,'PAGADA','POR COBRAR'), IF(gc.condicionpago=0,'CONTADO','CREDITO'),
			 		'CONSIGNACION', CURRENT_DATE,gc.iddireccionremitente, gc.ocurre, gc.idsucursalorigen, gc.iddestino, 
					gc.idsucursaldestino, gc.idremitente, gc.iddestinatario, gc.iddirecciondestinatario, gc.entregaocurre, 
					gc.entregaead, gc.restrinccion, gc.totalpaquetes, gc.totalpeso, gc.totalvolumen, gc.emplaye, gc.bolsaempaque, 
					gc.totalbolsaempaque, gc.avisocelular, gc.celular, gc.valordeclarado, gc.acuserecibo, gc.cod, gc.recoleccion, 
					gc.observaciones, 
					
					41, 0, 0, 0, 0, 0, 0, 0, 0, 41, 6.56, 1.64, 45.92, 
					
					gc.efectivo, gc.cheque, gc.banco, gc.ncheque,  
					gc.clienteconvenio, gc.sucursalconvenio, gc.idvendedorconvenio, gc.nvendedorconvenio, gc.convenioaplicado, 0,
					gc.tarjeta, gc.trasferencia, gc.usuario,gc.ubicacion,0, gc.idusuario, CURRENT_DATE, CURRENT_TIME 
			FROM guiasventanillaclientes gc
			INNER JOIN tablasguiasclientes pgc ON gc.id = pgc.id;";
		*/
		$s = "INSERT INTO guiasempresariales 
		(id,estado,tipoflete,tipopago,tipoguia,
		fecha,iddireccionremitente,
		ocurre, idsucursalorigen, iddestino, 
		idsucursaldestino, idremitente,iddestinatario, iddirecciondestinatario, entregaocurre, entregaead,
				restrinccion, totalpaquetes, totalpeso, totalvolumen, emplaye, bolsaempaque, 
				totalbolsaempaque, avisocelular, celular, valordeclarado, acuserecibo, cod, recoleccion, 
				observaciones, tflete, tdescuento, ttotaldescuento, tcostoead, trecoleccion, 
				tseguro, totros, texcedente, tcombustible, subtotal, tiva, ivaretenido, total, 
				efectivo, cheque, banco, ncheque, clienteconvenio, sucursalconvenio, idvendedorconvenio, 
				nvendedorconvenio, convenioaplicado, idsolicitudguia, tarjeta, trasferencia, usuario,ubicacion,sector,
				idusuario, fecha_registro, hora_registro)
		SELECT gc.id,'ALMACEN ORIGEN',IF(gc.tipoflete=0,'PAGADA','POR COBRAR'), IF(gc.condicionpago=0,'CONTADO','CREDITO'),
				'CONSIGNACION', CURRENT_DATE,gc.iddireccionremitente, gc.ocurre, gc.idsucursalorigen, gc.iddestino, 
				gc.idsucursaldestino, gc.idremitente, gc.iddestinatario, gc.iddirecciondestinatario, gc.entregaocurre, 
				gc.entregaead, gc.restrinccion, gc.totalpaquetes, gc.totalpeso, gc.totalvolumen, gc.emplaye, gc.bolsaempaque, 
				gc.totalbolsaempaque, gc.avisocelular, gc.celular, gc.valordeclarado, gc.acuserecibo, gc.cod, gc.recoleccion, 
				gc.observaciones, 
				gc.totalpaquetes * g2.precio, 
				0, 0, 
				0, 0, 
				IFNULL((gc.valordeclarado/g2.porcada)*g2.valordeclarado,0), 0,
				IF(gc.totalpaquetes * g2.pesolimite> IF(gc.totalpeso>gc.totalvolumen,
											gc.totalpeso,
											gc.totalvolumen
										),
				0,
				   IF(gc.totalpeso>gc.totalvolumen,
					  gc.totalpeso,
					  gc.totalvolumen
				   )-gc.totalpaquetes * g2.pesolimite
				)*g2.preciokgexcedente, 
				0, 
				(gc.totalpaquetes * g2.precio) + (IF(gc.totalpaquetes * g2.pesolimite> IF(gc.totalpeso>gc.totalvolumen,
												gc.totalpeso,
												gc.totalvolumen
											),
				   0,
					   IF(gc.totalpeso>gc.totalvolumen,
						  gc.totalpeso,
						  gc.totalvolumen
					   )-gc.totalpaquetes * g2.pesolimite
					)*g2.preciokgexcedente)+(gc.tcostoead+gc.trecoleccion+gc.tseguro+gc.totros+gc.tcombustible), 
				((gc.totalpaquetes * g2.precio) + (IF(gc.totalpaquetes * g2.pesolimite> IF(gc.totalpeso>gc.totalvolumen,
												gc.totalpeso,
												gc.totalvolumen
											),
				   0,
					   IF(gc.totalpeso>gc.totalvolumen,
						  gc.totalpeso,
						  gc.totalvolumen
					   )-gc.totalpaquetes * g2.pesolimite
					)*g2.preciokgexcedente)+(gc.tcostoead+gc.trecoleccion+gc.tseguro+gc.totros+gc.tcombustible))*0.16, 
				((gc.totalpaquetes * g2.precio) + (IF(gc.totalpaquetes * g2.pesolimite> IF(gc.totalpeso>gc.totalvolumen,
												gc.totalpeso,
												gc.totalvolumen
											),
				   0,
					   IF(gc.totalpeso>gc.totalvolumen,
						  gc.totalpeso,
						  gc.totalvolumen
					   )-gc.totalpaquetes * g2.pesolimite
					)*g2.preciokgexcedente)+(gc.tcostoead+gc.trecoleccion+gc.tseguro+gc.totros+gc.tcombustible))*0.04, 
				((gc.totalpaquetes * g2.precio) + (IF(gc.totalpaquetes * g2.pesolimite> IF(gc.totalpeso>gc.totalvolumen,
												gc.totalpeso,
												gc.totalvolumen
											),
				   0,
					   IF(gc.totalpeso>gc.totalvolumen,
						  gc.totalpeso,
						  gc.totalvolumen
					   )-gc.totalpaquetes * g2.pesolimite
					)*g2.preciokgexcedente)+(gc.tcostoead+gc.trecoleccion+gc.tseguro+gc.totros+gc.tcombustible))+(((gc.totalpaquetes * g2.precio) + (IF(gc.totalpaquetes * g2.pesolimite> IF(gc.totalpeso>gc.totalvolumen,
												gc.totalpeso,
												gc.totalvolumen
											),
				   0,
					   IF(gc.totalpeso>gc.totalvolumen,
						  gc.totalpeso,
						  gc.totalvolumen
					   )-gc.totalpaquetes * g2.pesolimite
					)*g2.preciokgexcedente)+(gc.tcostoead+gc.trecoleccion+gc.tseguro+gc.totros+gc.tcombustible))*0.16)-(((gc.totalpaquetes * g2.precio) + (IF(gc.totalpaquetes * g2.pesolimite> IF(gc.totalpeso>gc.totalvolumen,
												gc.totalpeso,
												gc.totalvolumen
											),
				   0,
					   IF(gc.totalpeso>gc.totalvolumen,
						  gc.totalpeso,
						  gc.totalvolumen
					   )-gc.totalpaquetes * g2.pesolimite
					)*g2.preciokgexcedente)+(gc.tcostoead+gc.trecoleccion+gc.tseguro+gc.totros+gc.tcombustible))*0.04), 
				
				gc.efectivo, gc.cheque, gc.banco, gc.ncheque,  
				gc.clienteconvenio, gc.sucursalconvenio, gc.idvendedorconvenio, gc.nvendedorconvenio, gc.convenioaplicado, 0,
				gc.tarjeta, gc.trasferencia, gc.usuario,gc.ubicacion,0, gc.idusuario, CURRENT_DATE, CURRENT_TIME 
		FROM guiasventanillaclientes gc
		INNER JOIN tablasguiasclientes pgc ON gc.id = pgc.id
		INNER JOIN datosconvenio g2 ON '$_POST[txtnumCliente]'= g2.cliente;";
		mysql_query($s,$l) or die(mysql_error($l)."<br>".$s);
		
		$s = "UPDATE guiasempresariales ge
		INNER JOIN tablasguiasclientes pgc ON ge.id = pgc.id
		SET subtotal = tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible,
		tiva = (tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*0.16,
		ivaretenido = (tflete-ttotaldescuento+tcostoead+trecoleccion+tseguro+totros+texcedente+tcombustible)*0.04;";
		mysql_query($s,$l) or die(mysql_error($l)."<br>".$s);
		
		$s = "UPDATE guiasempresariales ge
		INNER JOIN tablasguiasclientes pgc ON ge.id = pgc.id
		SET total = subtotal + tiva - ivaretenido";
		mysql_query($s,$l) or die(mysql_error($l)."<br>".$s);
		
		$s = "INSERT INTO guiasempresariales_detalle (id,cantidad,descripcion,contenido,peso,largo,ancho,alto,volumen,importe)
			SELECT gcd.idguia,gcd.cantidad,gcd.descripcion,gcd.contenido,gcd.peso,gcd.largo,gcd.ancho,gcd.alto,gcd.volumen,gcd.importe
			FROM guiasventanillaclientes_detalle gcd
			INNER JOIN tablasguiasclientes pgc ON gcd.idguia = pgc.id;";
		mysql_query($s,$l) or die($s);
			
		$s = "INSERT INTO pagoguias (guia,tipo,total, fechacreo,usuariocreo,sucursalcreo, cliente,credito, sucursalacobrar)
			SELECT g.id,'EMPRESARIAL',g.total, CURRENT_DATE,1,g.idsucursalorigen, g.idremitente,'SI', g.idsucursalorigen
			FROM guiasempresariales g
			INNER JOIN tablasguiasclientes pgc ON g.id = pgc.id;";
		mysql_query($s,$l) or die($s);

		$s = "CALL meterPaquetesClien();";
		mysql_query($s,$l) or die($s);

		$s = "UPDATE guiasventanillaclientes g
			INNER JOIN tablasguiasclientes pgc ON g.id = pgc.id
			SET estado = 'APLICADA';";
		mysql_query($s,$l) or die($s);	
		
		$s = "UPDATE evaluacionmercancia g
			INNER JOIN tablasguiasclientes pgc ON g.guiaempresarial = pgc.id
			SET estado = 'ENGUIA';";
		mysql_query($s,$l) or die($s);
		
		# insertar en reporte ventas
		$s = "INSERT INTO reportes_ventas 
		(tipoventa, idcliente, convenio,
		nombrecliente,
		idsucorigen,origen,prefijoorigen,idenvia,envia,
		idsucdestino,destino,prefijodestino,idrecibe,recibe,
		paquetes,totalkilogramos,folio,tipoflete,tipopago,
		tipoentrega,tipoempresarial,flete,valordeclarado,excedente,
		ead,subdestino,total,sucursalrealizo,prefijosucursal,activo,fecharealizacion,
		seguro,combustible,subtotal,iva,ivaretenido)
		SELECT 'GUIA EMPRESARIAL', IF(ge.tipoflete='PAGADA',ge.idremitente,ge.iddestinatario), ge.convenioaplicado, 
		IF(ge.tipoflete=0,CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno),CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno)),
		cs1.id, cs1.descripcion, cs1.prefijo, cc1.id,CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno),
		cs2.id, cs2.descripcion, cs2.prefijo, cc2.id,CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno),
		ge.totalpaquetes, ge.totalpeso, ge.id, ge.tipoflete,ge.tipopago,
		IF(ge.ocurre=0,'EAD','OCURRE'), ge.tipoguia, ge.tflete, ge.valordeclarado, ge.texcedente,
		ge.tcostoead, IF(cd1.subdestinos=1,0,1), ge.total, ge.idsucursalorigen,cs1.prefijo,'S',CURRENT_DATE,
		ge.tseguro, ge.tcombustible, ge.subtotal, ge.tiva, ge.ivaretenido
		FROM guiasempresariales ge
		inner join guiasventanillaclientes gc on ge.id = gc.id
		INNER JOIN tablasguiasclientes pgc ON gc.id = pgc.id AND ge.id = pgc.id
		LEFT JOIN catalogocliente cc1 ON ge.idremitente = cc1.id
		LEFT JOIN catalogocliente cc2 ON ge.iddestinatario = cc2.id
		LEFT JOIN catalogosucursal cs1 ON ge.idsucursalorigen = cs1.id
		LEFT JOIN catalogosucursal cs2 ON ge.idsucursaldestino = cs2.id
		LEFT JOIN catalogodestino cd1 ON ge.iddestino = cd1.id
		group by ge.id;";
		mysql_query($s,$l) or die($s);
		
		# insertar en cobranza
		$s = "INSERT INTO reporte_cobranza1 (idsucursal, prefijosucursal, idcliente, cliente, tipo, folio, fecha, fechavencimiento,total)
		SELECT cs.id, cs.prefijo, cc.id, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno) AS cliente,
		'E', ge.id, ge.fecha, ADDDATE(ge.fecha, INTERVAL sc.diascredito DAY), ge.total
		FROM guiasempresariales ge
		INNER JOIN guiasventanillaclientes gc on ge.id = gc.id
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		LEFT JOIN solicitudcredito sc ON cc.id = sc.cliente AND sc.estado = 'ACTIVADO'
		WHERE ge.tipopago = 'CREDITO' GROUP BY ge.id;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_cobranza4 (idcliente, fecha, idsucursal, prefijosucursal, folio, cargo)
		SELECT IF(ge.tipoflete<>'POR COBRAR',ge.idremitente,ge.iddestinatario), ge.fecha, cs.id, cs.prefijo, ge.id, ge.total
		FROM guiasempresariales ge
		inner join guiasventanillaclientes gc on ge.id = gc.id
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE ge.tipopago = 'CREDITO'";
		mysql_query($s,$l) or die($s);
		
		?><script>document.location.href='elaborarGuiasEmpresariales.php?accion=guardar'</script><?		
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PMM</title>
</head>

<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<script src="../javascript/ClaseMensajes.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<script>
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript',false);

	function validacion()
	{  
		if(document.all.txtnumCliente.value.length == "")
		{
			alert("Debe capturar el Numero del Cliente");
			return false;
		}
		
		if(document.all.sucursal.value == "0")
		{
			alert("Debe seleccionar una sucursal");
			return false;
		}
					
		return true;
	}
	
</script>

<body>
	<form name="form1" action="" method="POST">
		<table style="margin:auto" align="center">
			<tr>
				<td align="center" colspan="2">
					<B>ELABORACION EMPRESARIALES</B>
				</td>
			</tr>
			<tr><td colspan="2"><br /></td></tr>
			<tr>
				<td align="right">
					<label id="lblnumCliente"><strong># Cliente:</strong></label>
				</td>
				<td>
					<input type="text" name="txtnumCliente" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label id="lblPrepagada"><strong>Sucursal Origen:</strong></label>
				</td>
				<td>
					<select name="sucursal" style="width:150px; font-family:Verdana, Geneva, sans-serif; font-size:12px">
						<option value="0">Seleccione una sucursal</option>
						<?
							$s = "select * from catalogosucursal order by descripcion";
							$r = mysql_query($s,$l) or die($s);
							while($f = mysql_fetch_object($r)){
						?>		
						<option value="<?=$f->id?>"><?=strtoupper(utf8_encode($f->descripcion))?>
						</option>		
						<?
							}
							
						?>
					</select>
				</td>				
			</tr>		
			<tr>			
				<td colspan="2" align="center">
					<br />
					<img src="../img/Boton_Guardar.gif" onClick="if(validacion()){enviarDatos()}" />
					<input type="hidden" name="accion" id="accion" />
				</td>
			</tr>
		</table>
	</form>
	<script>
		function enviarDatos(){
			mens.show("C","¿Desea documentar las guias Empresariales?","","","enviar()");
		}
		
		function enviar(){
			document.getElementById('accion').value = 'guardar';
			document.form1.submit();
		}
		
		<? 
			if($_GET[accion]=='guardar'){
		?>
				mens.show("I","Datos Guardados","ATENCION");
		<?
			}
		?>
	</script>
</body>
</html>
