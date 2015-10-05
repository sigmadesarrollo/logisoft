<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion] == "X"){
		$s = "SELECT MAX(id) AS id FROM guiasventanilla_cs WHERE folioguia = '$_GET[folioguia]'";
		//echo $s."<br><br>";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$_GET[folio] = $f->id;
		$_GET[accion] = 1;
	}
	
	//solicitar guias
	if($_GET[accion] == 1){
		
		$s = "SELECT
		gv.id, gv.evaluacion, date_format(gv.fechacreacion, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
		gv.tipoflete, gv.ocurre, gv.idsucursalorigen, gv.estado,
		concat(cd.descripcion,' - ',csd.prefijo) as ndestino, csd.descripcion as nsucdestino,
		gv.idsucursalorigen,gv.idsucursaldestino,
		gv.condicionpago, 
		
		gv.idremitente, 
		concat_ws(' ', ccr.nombre, ccr.paterno, ccr.materno) as rncliente, ccr.rfc as rrfc, ccr.celular as rcelular,
		dr.calle as rcalle, dr.numero as rnumero, dr.cp as rcp, dr.colonia as rcolonia, 
		dr.poblacion as rpoblacion, dr.telefono as rtelefono,
		 
		gv.iddestinatario,
		concat_ws(' ', ccd.nombre, ccd.paterno, ccd.materno) as dncliente, ccd.rfc as drfc, ccd.celular as dcelular,
		dd.calle as dcalle, dd.numero as dnumero, dd.cp as dcp, dd.colonia as dcolonia, 
		dd.poblacion as dpoblacion, dd.telefono as dtelefono,
		
		gv.entregaocurre, 
		gv.entregaead, gv.restrinccion, gv.totalpaquetes, gv.totalpeso, 
		gv.totalvolumen, gv.emplaye, gv.bolsaempaque, gv.totalbolsaempaque, 
		gv.avisocelular, gv.celular, gv.valordeclarado, gv.acuserecibo, 
		gv.cod, gv.recoleccion, gv.observaciones, gv.tflete, gv.tdescuento, 
		gv.ttotaldescuento, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.totros, 
		gv.texcedente, gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
		gv.total, gv.efectivo, gv.cheque, gv.banco, gv.ncheque, gv.tarjeta, gv.trasferencia, 
		gv.usuario, gv.fecha_registro, gv.hora_registro, date_format(current_date, '%d/%m/%Y') as fechaactual
		FROM guiasventanilla_cs as gv
		inner join catalogosucursal as csd on gv.idsucursaldestino = csd.id
		inner join catalogodestino as cd on gv.iddestino = cd.id
		inner join catalogocliente as ccr on gv.idremitente = ccr.id
		left join direccion as dr on gv.iddireccionremitente = dr.id
		inner join catalogocliente as ccd on gv.iddestinatario = ccd.id
		left join direccion as dd on gv.iddirecciondestinatario = dd.id
		where gv.id= '$_GET[folio]'";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontrados>1</encontrados>
				<id>".cambio_texto($f->id)."</id>
				<fechaactual>".cambio_texto($f->fechaactual)."</fechaactual>
				<evaluacion>".cambio_texto($f->evaluacion)."</evaluacion>
				<fecha>".cambio_texto($f->fecha)."</fecha>
				<estado>".cambio_texto($f->estado)."</estado>
				<fechaentrega>".cambio_texto($f->fecha)."</fechaentrega>
				<factura>".cambio_texto($f->factura)."</factura>
				<tipoflete>".cambio_texto($f->tipoflete)."</tipoflete>
				<ocurre>".cambio_texto($f->ocurre)."</ocurre>
				<idsucursalorigen>".cambio_texto($f->idsucursalorigen)."</idsucursalorigen>
				<ndestino>".($f->ndestino)."</ndestino>
				<nsucdestino>".($f->nsucdestino)."</nsucdestino>
				<condicionpago>".cambio_texto($f->condicionpago)."</condicionpago>
				<idremitente>".cambio_texto($f->idremitente)."</idremitente>
				<rncliente>".cambio_texto($f->rncliente)."</rncliente>
				<rrfc>".cambio_texto($f->rrfc)."</rrfc>
				<rcelular>".cambio_texto($f->rcelular)."</rcelular>
				<rcalle>".cambio_texto($f->rcalle)."</rcalle>
				<rnumero>".cambio_texto($f->rnumero)."</rnumero>
				<rcp>".cambio_texto($f->rcp)."</rcp>
				<rpoblacion>".cambio_texto($f->rpoblacion)."</rpoblacion>
				<rtelefono>".cambio_texto($f->rtelefono)."</rtelefono>
				<rcolonia>".cambio_texto($f->rcolonia)."</rcolonia>
				<iddestinatario>".cambio_texto($f->iddestinatario)."</iddestinatario>
				<dncliente>".cambio_texto($f->dncliente)."</dncliente>
				<drfc>".cambio_texto($f->drfc)."</drfc>
				<dcelular>".cambio_texto($f->dcelular)."</dcelular>
				<dcalle>".cambio_texto($f->dcalle)."</dcalle>
				<dnumero>".cambio_texto($f->dnumero)."</dnumero>
				<dcp>".cambio_texto($f->dcp)."</dcp>
				<dpoblacion>".cambio_texto($f->dpoblacion)."</dpoblacion>
				<dtelefono>".cambio_texto($f->dtelefono)."</dtelefono>
				<dcolonia>".cambio_texto($f->dcolonia)."</dcolonia>	
				<entregaocurre>".cambio_texto($f->entregaocurre)."</entregaocurre>
				<entregaead>".cambio_texto($f->entregaead)."</entregaead>
				<restrinccion>".cambio_texto($f->restrinccion)."</restrinccion>
				<totalpaquetes>".cambio_texto($f->totalpaquetes)."</totalpaquetes>
				<totalpeso>".cambio_texto($f->totalpeso)."</totalpeso>
				<totalvolumen>".cambio_texto($f->totalvolumen)."</totalvolumen>
				<emplaye>".cambio_texto($f->emplaye)."</emplaye>
				<bolsaempaque>".cambio_texto($f->bolsaempaque)."</bolsaempaque>
				<totalbolsaempaque>".cambio_texto($f->totalbolsaempaque)."</totalbolsaempaque>
				<avisocelular>".cambio_texto($f->avisocelular)."</avisocelular>
				<celular>".cambio_texto($f->celular)."</celular>
				<valordeclarado>".cambio_texto($f->valordeclarado)."</valordeclarado>
				<acuserecibo>".cambio_texto($f->acuserecibo)."</acuserecibo>
				<cod>".cambio_texto($f->cod)."</cod>
				<recoleccion>".cambio_texto($f->recoleccion)."</recoleccion>
				<observaciones>".cambio_texto($f->observaciones)."</observaciones>
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
				<efectivo>".cambio_texto($f->efectivo)."</efectivo>
				<cheque>".cambio_texto($f->cheque)."</cheque>
				<banco>".cambio_texto($f->banco)."</banco>
				<ncheque>".cambio_texto($f->ncheque)."</ncheque>
				<tarjeta>".cambio_texto($f->tarjeta)."</tarjeta>
				<trasferencia>".cambio_texto($f->trasferencia)."</trasferencia>
				<usuario>".cambio_texto($f->usuario)."</usuario>
				<fecha_registro>".cambio_texto($f->fecha_registro)."</fecha_registro>
				<hora_registro>".cambio_texto($f->hora_registro)."</hora_registro>";
				
				$s = "SELECT * FROM guiaventanilla_detalle_cs where idguia='$_GET[folio]'";
				$rx = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rx)>0){
					$cant = mysql_num_rows($rx);
					while($fx = mysql_fetch_object($rx)){
						$xml .= "
						<idmercancia>0</idmercancia>
						<cantidad>$fx->cantidad</cantidad>
						<descripcion>".strtoupper(cambio_texto($fx->descripcion))."</descripcion>
						<contenido>".strtoupper(cambio_texto($fx->contenido))."</contenido>
						<peso>".round($fx->peso,2)."</peso>
						<volumen>".round($fx->volumen,2)."</volumen>
						<importe>".round($fx->importe,2)."</importe>
						";	
					}
					
				}else{
					$cant;
					$totalimporte = 0;
					$xml .= "<cantidad>$fx->cantidad</cantidad>
					<descripcion>0</descripcion>
					<contenido>0</contenido>
					<peso>0</peso>
					<volumen>0</volumen>
					<importe>0</importe>
					";
				}
				$xml .= "<valor_totalimporte>".cambio_texto($totalimporte+$tenvase)."</valor_totalimporte>
				<encontroevaluacion>$cant</encontroevaluacion>
			<tipototales>1</tipototales>
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
	// no autorizar
	if($_GET[accion] == 2){
		$s = "insert into historial_cancelacionysustitucion
		set guia = '$_GET[folio]', accion='SUSTITUCION NO AUTORIZAR', tipo='FORANEA', sucursal='$_SESSION[IDSUCURSAL]', fecha=current_date,
		hora=current_time, usuario = '$_SESSION[IDUSUARIO]';";		
		mysql_query($s,$l) or die($s);
		$s = "UPDATE guiasventanilla_cs SET estado = 'NO AUTORIZADA', motivosnoautorizacion='$_GET[motivo]'  WHERE id=$_GET[folio]; ";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "UPDATE guiasventanilla SET estado = 'ALMACEN DESTINO' where 
		id=(SELECT folioguia FROM guiasventanilla_cs WHERE id=$_GET[folio]);";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>1</guardado>
				</datos>
				</xml>";
	}
	//autorizar
	if($_GET[accion] == 3){
		
		$s = "insert into historial_cancelacionysustitucion
		set guia = '$_GET[folio]', accion='SUSTITUCION AUTORIZADA', tipo='FORANEA', sucursal='$_SESSION[IDSUCURSAL]', fecha=current_date,
		hora=current_time, usuario = '$_SESSION[IDUSUARIO]';";		
		mysql_query($s,$l) or die($s);
		$s = "UPDATE guiasventanilla_cs SET estado = 'AUTORIZADA PARA SUSTITUIR', motivosnoautorizacion='$_GET[motivo]'  WHERE id=$_GET[folio]; ";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>1</guardado>
				</datos>
				</xml>";
	}
	
	if($_GET[accion] == 4){
		
		$s = "select folioguia, idsucursaldestino from guiasventanilla_cs where id = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT id FROM guiasventanilla WHERE id = '$f->folioguia' AND estado = 'CANCELADO'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>-1</guardado>
				</datos>
				</xml>");
		}
		
		$s = "select idsucursaldestino from guiasventanilla_cs where id = '$f->folioguia'";
		$rx = mysql_query($s,$l) or die($s);
		$fx = mysql_fetch_object($rx);
		
		if($f->idsucursaldestino!=$_SESSION['IDSUCURSAL']){
			$estado = "ALMACEN ORIGEN";
		}else{
			$estado = "ALMACEN DESTINO";
		}
		
		$s = "SELECT CONCAT(cuentac,numerodesde,letra) AS newfolio FROM (
				SELECT 
					(SELECT idsucursal FROM catalogosucursal WHERE id = $_SESSION[IDSUCURSAL]) AS cuentac,
					
				LPAD(
					IFNULL(
						IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,SUBSTRING(MAX(id),4,9)+1)
					,1)
				,9,'0') AS numerodesde,
				CHAR(ASCII(IFNULL(SUBSTRING(MAX(id),13,1),'A'))+IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,0)) AS letra
				FROM guiasventanilla WHERE SUBSTRING(id,1,3) = (SELECT idsucursal FROM catalogosucursal WHERE id = $_SESSION[IDSUCURSAL])
				AND id NOT LIKE '%Z'
			) AS t1";
		$r = mysql_query($s,$l) or die($s);
		$fx = mysql_fetch_object($r);
		$newfolio = $fx->newfolio;
		
		$s = "insert into historial_cancelacionysustitucion
		set guia = '$f->folioguia', accion='SUSTITUCION REALIZADA', tipo='FORANEA', sustitucion='$newfolio', 
		sucursal='$_SESSION[IDSUCURSAL]', fecha=current_date,
		hora=current_time, usuario = '$_SESSION[IDUSUARIO]';";		
		mysql_query($s,$l) or die($s);
		
		
		$s = "INSERT INTO seguimiento_guias SET 
		guia='$f->folioguia', ubicacion='$_SESSION[IDSUCURSAL]', unidad='',estado='CANCELADA',
		fecha=CURRENT_DATE, hora=CURRENT_TIME,
		usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "update guiasventanilla set estado = 'CANCELADO' where id='$f->folioguia'";
		mysql_query($s,$l) or die($s);
		
		#para ingresar el movimiento de ventas de guias a ventas contra presupuesto
		$s = "CALL proc_VentasVsPresupuesto('CAN_GUIA_VE','$f->folioguia',$_SESSION[IDSUCURSAL]);";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		
		$s = "call proc_RegistroFranquiciasConceciones('ponercomisionguia','$f->folioguia',null,0)";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		
		$s = "call proc_RegistroAuditorias('GC','$f->folioguia',$_SESSION[IDSUCURSAL])";
		$d = mysql_query($s, $l);
		
		$s = "INSERT INTO guiasventanilla (id,evaluacion,fecha,fechaentrega,factura,estado,ubicacion,entradasalida,
		tipoflete,ocurre,idsucursalorigen,iddestino,idsucursaldestino,condicionpago,idremitente,
		iddireccionremitente,iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,
		restrinccion,totalpaquetes,totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,
		avisocelular,celular,valordeclarado,acuserecibo,cod,recoleccion,observaciones,tflete,tdescuento,
		ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,tcombustible,subtotal,tiva,
		ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,tarjeta,trasferencia,sector,clienteconvenio,
		sucursalconvenio,idvendedorconvenio,nvendedorconvenio,convenioaplicado,idusuario,
		usuario,fecha_registro,hora_registro,devolucion,
		con_esconcesion, con_comision, con_comisionead, con_comisionrad, con_comisionfleteenviado, con_comisionfleterecibido)
		SELECT '$newfolio',evaluacion,CURRENT_DATE,fechaentrega,factura,'$estado',ubicacion,entradasalida,
		tipoflete,ocurre,idsucursalorigen,iddestino,idsucursaldestino,condicionpago,idremitente,
		iddireccionremitente,iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,
		restrinccion,totalpaquetes,totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,
		avisocelular,celular,valordeclarado,acuserecibo,cod,recoleccion,observaciones,tflete,tdescuento,
		ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,tcombustible,subtotal,tiva,
		ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,tarjeta,trasferencia,sector,clienteconvenio,
		sucursalconvenio,idvendedorconvenio,nvendedorconvenio,convenioaplicado,idusuario,
		usuario,fecha_registro,hora_registro,devolucion,
		con_esconcesion, con_comision, con_comisionead, con_comisionrad, con_comisionfleteenviado, con_comisionfleterecibido
		FROM guiasventanilla_cs WHERE id = '$_GET[folio]'";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
		<datos>
		<guardado>0</guardado>
		<consulta>".str_replace("''","null",$s)."</consulta>
		</datos>
		</xml>");
		
		$s = "call proc_RegistroAuditorias('LG','$newfolio',$_SESSION[IDSUCURSAL])";
		mysql_query($s, $l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
		<datos>
		<guardado>0</guardado>
		<consulta>".str_replace("''","null",$s).mysql_error($l)."</consulta>
		</datos>
		</xml>");
		
		$s = "call proc_RegistroFranquiciasConceciones('inertarforanea','$newfolio',null,0)";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		
		#para ingresar el movimiento de ventas de guias a ventas contra presupuesto
		$s = "call proc_VentasVsPresupuesto('GUIA_VE','$newfolio',$_SESSION[IDSUCURSAL]);";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		
		//registrar el abono
		$s = "INSERT INTO pagoguias
		(guia,tipo,total,fechacreo,usuariocreo,sucursalcreo,cliente,sucursalacobrar)
		SELECT '$newfolio','NORMAL',total,CURRENT_DATE,'$_SESSION[IDUSUARIO]',
		'$_SESSION[IDSUCURSAL]',IF(tipoflete=0,idremitente,iddestinatario),
		IF(tipoflete=0,idsucursalorigen,idsucursaldestino)
		FROM guiasventanilla_cs WHERE id = '$_GET[folio]'";
		$r = @mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"utf-8\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$s = "INSERT INTO guiaventanilla_detalle
		SELECT '$newfolio',cantidad,descripcion,contenido,pesou,alto,ancho,largo,peso,volumen,importe,excedente,kgexcedente,idusuario
		FROM guiaventanilla_detalle_cs WHERE idguia = $_GET[folio]";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		
		$s = "INSERT INTO guiaventanilla_unidades
		SELECT null,'$newfolio',descripcion,contenido,peso,paquete,depaquetes,estado,proceso,unidad,ubicacion,codigobarras
		FROM guiaventanilla_unidades_cs WHERE idguia = $_GET[folio]";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>".str_replace("''","null",$s)."</consulta>
				</datos>
				</xml>");
		
		$s = "INSERT INTO seguimiento_guias SET 
		guia='$newfolio', ubicacion='$_SESSION[IDSUCURSAL]', unidad='',estado='$estado',
		fecha=CURRENT_DATE, hora=CURRENT_TIME,
		usuario=$_SESSION[IDUSUARIO]";
		@mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
		<datos>
		<guardado>0</guardado>
		<consulta>$s</consulta>
		</datos>
		</xml>");
		
		$s = "UPDATE guiasventanilla_cs SET estado = 'GENERADA' WHERE id=$_GET[folio]; ";
		mysql_query($s,$l) or die("<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>0</guardado>
				<consulta>$s</consulta>
				</datos>
				</xml>");
		
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<guardado>1</guardado>
				<folioguia>$newfolio</folioguia>
				<estado>$estado</estado>
				</datos>
				</xml>";
	}
	
	echo $xml;
	
?>
