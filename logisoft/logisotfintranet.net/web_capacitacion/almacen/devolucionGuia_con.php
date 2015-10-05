<?	session_start();	
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == "0"){
		$s = "SELECT DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') AS fecha,
		(SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal].") AS sucursal";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		$row = ObtenerFolio('devolucionguia','webpmm');
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->sucursal);
				$f->folio	 = $row[0];
				$registros[] = $f;
			}
			
		$s = "DELETE FROM devolucionguiadetalle
		WHERE usuario=".$_SESSION[IDUSUARIO]." AND devolucion IS NULL";
		mysql_query($s,$l) or die($s);
		
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion] == 1){//SOLICITAR GUIA
		$s = "SELECT
		gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
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
		FROM guiasventanilla as gv
		inner join catalogosucursal as csd on gv.idsucursaldestino = csd.id
		inner join catalogodestino as cd on gv.iddestino = cd.id
		inner join catalogocliente as ccr on gv.idremitente = ccr.id
		left join direccion as dr on gv.iddireccionremitente = dr.id
		inner join catalogocliente as ccd on gv.iddestinatario = ccd.id
		inner join direccion as dd on gv.iddirecciondestinatario = dd.id
		where gv.id= '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->emp = 0;
				$f->id = cambio_texto($f->id);
				$f->fechaactual=cambio_texto($f->fechaactual);
				$f->evaluacion=cambio_texto($f->evaluacion);
				$f->fecha=cambio_texto($f->fecha);
				$f->estado=cambio_texto($f->estado);
				$f->fecha=cambio_texto($f->fecha);
				$f->factura=cambio_texto($f->factura);
				$f->tipoflete=cambio_texto($f->tipoflete);
				$f->ocurre=cambio_texto($f->ocurre);
				$f->idsucursalorigen=cambio_texto($f->idsucursalorigen);
				$f->ndestino=cambio_texto($f->ndestino);
				$f->nsucdestino=cambio_texto($f->nsucdestino);
				$f->condicionpago=cambio_texto($f->condicionpago);
				$f->idremitente=cambio_texto($f->idremitente);
				$f->rncliente=cambio_texto($f->rncliente);
				$f->rrfc=cambio_texto($f->rrfc);
				$f->rcelular=cambio_texto($f->rcelular);
				$f->rcalle=cambio_texto($f->rcalle);
				$f->rnumero=cambio_texto($f->rnumero);
				$f->rcp=cambio_texto($f->rcp);
				$f->rpoblacion=cambio_texto($f->rpoblacion);
				$f->rtelefono=cambio_texto($f->rtelefono);
				$f->rcolonia=cambio_texto($f->rcolonia);
				$f->iddestinatario=cambio_texto($f->iddestinatario);
				$f->dncliente=cambio_texto($f->dncliente);
				$f->drfc=cambio_texto($f->drfc);
				$f->dcelular=cambio_texto($f->dcelular);
				$f->dcalle=cambio_texto($f->dcalle);
				$f->dnumero=cambio_texto($f->dnumero);
				$f->dcp=cambio_texto($f->dcp);
				$f->dpoblacion=cambio_texto($f->dpoblacion);
				$f->dtelefono=cambio_texto($f->dtelefono);
				$f->dcolonia=cambio_texto($f->dcolonia);
				$f->entregaocurre=cambio_texto($f->entregaocurre);
				$f->entregaead=cambio_texto($f->entregaead);
				$f->restrinccion=cambio_texto($f->restrinccion);
				$f->totalpaquetes=cambio_texto($f->totalpaquetes);
				$f->totalpeso=cambio_texto($f->totalpeso);
				$f->totalvolumen=cambio_texto($f->totalvolumen);
				$f->emplaye=cambio_texto($f->emplaye);
				$f->bolsaempaque=cambio_texto($f->bolsaempaque);
				$f->totalbolsaempaque=cambio_texto($f->totalbolsaempaque);
				$f->avisocelular=cambio_texto($f->avisocelular);
				$f->celular=cambio_texto($f->celular);
				$f->valordeclarado=cambio_texto($f->valordeclarado);
				$f->acuserecibo=cambio_texto($f->acuserecibo);
				$f->cod=cambio_texto($f->cod);
				$f->recoleccion=cambio_texto($f->recoleccion);
				$f->observaciones=cambio_texto($f->observaciones);
				$f->tflete=cambio_texto($f->tflete);
				$f->tdescuento=cambio_texto($f->tdescuento);
				$f->ttotaldescuento=cambio_texto($f->ttotaldescuento);
				$f->tcostoead=cambio_texto($f->tcostoead);
				$f->trecoleccion=cambio_texto($f->trecoleccion);
				$f->tseguro=cambio_texto($f->tseguro);
				$f->totros=cambio_texto($f->totros);
				$f->texcedente=cambio_texto($f->texcedente);
				$f->tcombustible=cambio_texto($f->tcombustible);
				$f->subtotal=cambio_texto($f->subtotal);
				$f->tiva=cambio_texto($f->tiva);
				$f->ivaretenido=cambio_texto($f->ivaretenido);
				$f->total=cambio_texto($f->total);
				$f->efectivo=cambio_texto($f->efectivo);
				$f->cheque=cambio_texto($f->cheque);
				$f->banco=cambio_texto($f->banco);
				$f->ncheque=cambio_texto($f->ncheque);
				$f->tarjeta=cambio_texto($f->tarjeta);
				$f->trasferencia=cambio_texto($f->trasferencia);
				$f->usuario=cambio_texto($f->usuario);
				$f->fecha_registro = cambio_texto($f->fecha_registro);
				$f->hora_registro = cambio_texto($f->hora_registro);
				$f->valor_totalimporte =cambio_texto($totalimporte+$tenvase);
				$registros[] = $f;
			}
		}
		
		$s = "SELECT
		gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
		gv.estado, IF(gv.tipoflete='PAGADA',0,1) tipoflete, gv.ocurre, gv.idsucursalorigen, 
		CONCAT(cd.descripcion,' - ',csd.prefijo) AS ndestino, csd.descripcion AS nsucdestino,
		gv.idsucursalorigen,gv.idsucursaldestino,
		IF(gv.tipopago='CONTADO',0,1) condicionpago, gv.tipoguia, 
		
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
		FROM guiasempresariales as gv
		INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id
		INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id
		INNER JOIN catalogocliente AS ccr ON gv.idremitente = ccr.id
		LEFT JOIN direccion AS dr ON ccr.id = dr.codigo AND origen = 'cl'
		INNER JOIN catalogocliente AS ccd ON gv.iddestinatario = ccd.id
		INNER JOIN direccion AS dd ON gv.iddirecciondestinatario = dd.id
		WHERE gv.id= '$_GET[folio]'
		GROUP BY id";
		//echo $s;
		$re = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($re)>0){
			while($f = mysql_fetch_object($re)){
				$f->emp = 1;
				$f->id = cambio_texto($f->id);
				$f->fechaactual=cambio_texto($f->fechaactual);
				$f->evaluacion=cambio_texto($f->evaluacion);
				$f->fecha=cambio_texto($f->fecha);
				$f->estado=cambio_texto($f->estado);
				$f->fecha=cambio_texto($f->fecha);
				$f->factura=cambio_texto($f->factura);
				$f->tipoflete=cambio_texto($f->tipoflete);				
				$f->ocurre=cambio_texto($f->ocurre);
				$f->idsucursalorigen=cambio_texto($f->idsucursalorigen);
				$f->ndestino=cambio_texto($f->ndestino);
				$f->nsucdestino=cambio_texto($f->nsucdestino);
				$f->condicionpago=cambio_texto($f->condicionpago);
				$f->tipoguia=cambio_texto($f->tipoguia);
				$f->idremitente=cambio_texto($f->idremitente);
				$f->rncliente=cambio_texto($f->rncliente);
				$f->rrfc=cambio_texto($f->rrfc);
				$f->rcelular=cambio_texto($f->rcelular);
				$f->rcalle=cambio_texto($f->rcalle);
				$f->rnumero=cambio_texto($f->rnumero);
				$f->rcp=cambio_texto($f->rcp);
				$f->rpoblacion=cambio_texto($f->rpoblacion);
				$f->rtelefono=cambio_texto($f->rtelefono);
				$f->rcolonia=cambio_texto($f->rcolonia);
				$f->iddestinatario=cambio_texto($f->iddestinatario);
				$f->dncliente=cambio_texto($f->dncliente);
				$f->drfc=cambio_texto($f->drfc);
				$f->dcelular=cambio_texto($f->dcelular);
				$f->dcalle=cambio_texto($f->dcalle);
				$f->dnumero=cambio_texto($f->dnumero);
				$f->dcp=cambio_texto($f->dcp);
				$f->dpoblacion=cambio_texto($f->dpoblacion);
				$f->dtelefono=cambio_texto($f->dtelefono);
				$f->dcolonia=cambio_texto($f->dcolonia);
				$f->entregaocurre=cambio_texto($f->entregaocurre);
				$f->entregaead=cambio_texto($f->entregaead);
				$f->restrinccion=cambio_texto($f->restrinccion);
				$f->totalpaquetes=cambio_texto($f->totalpaquetes);
				$f->totalpeso=cambio_texto($f->totalpeso);
				$f->totalvolumen=cambio_texto($f->totalvolumen);
				$f->emplaye=cambio_texto($f->emplaye);
				$f->bolsaempaque=cambio_texto($f->bolsaempaque);
				$f->totalbolsaempaque=cambio_texto($f->totalbolsaempaque);
				$f->avisocelular=cambio_texto($f->avisocelular);
				$f->celular=cambio_texto($f->celular);
				$f->valordeclarado=cambio_texto($f->valordeclarado);
				$f->acuserecibo=cambio_texto($f->acuserecibo);
				$f->cod=cambio_texto($f->cod);
				$f->recoleccion=cambio_texto($f->recoleccion);
				$f->observaciones=cambio_texto($f->observaciones);
				$f->tflete=cambio_texto($f->tflete);
				$f->tdescuento=cambio_texto($f->tdescuento);
				$f->ttotaldescuento=cambio_texto($f->ttotaldescuento);
				$f->tcostoead=cambio_texto($f->tcostoead);
				$f->trecoleccion=cambio_texto($f->trecoleccion);
				$f->tseguro=cambio_texto($f->tseguro);
				$f->totros=cambio_texto($f->totros);
				$f->texcedente=cambio_texto($f->texcedente);
				$f->tcombustible=cambio_texto($f->tcombustible);
				$f->subtotal=cambio_texto($f->subtotal);
				$f->tiva=cambio_texto($f->tiva);
				$f->ivaretenido=cambio_texto($f->ivaretenido);
				$f->total=cambio_texto($f->total);
				$f->efectivo=cambio_texto($f->efectivo);
				$f->cheque=cambio_texto($f->cheque);
				$f->banco=cambio_texto($f->banco);
				$f->ncheque=cambio_texto($f->ncheque);
				$f->tarjeta=cambio_texto($f->tarjeta);
				$f->trasferencia=cambio_texto($f->trasferencia);
				$f->usuario=cambio_texto($f->usuario);
				$f->fecha_registro = cambio_texto($f->fecha_registro);
				$f->hora_registro = cambio_texto($f->hora_registro);				
				$registros[] = $f;
			}			
		}
		
		
		$s = "delete from devolucionguiadetalle where usuario = '$_SESSION[IDUSUARIO]' AND isnull(devolucion)";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO devolucionguiadetalle
		(sucursal,cantidad,descripcion,iddescripcion,contenido,peso,
		largo,alto,ancho,volumen,pesototal,pesounit,usuario,fecha)
		SELECT '$_SESSION[IDSUCURSAL]',gd.cantidad,gd.descripcion, cd.id, gd.contenido,gd.peso, 
		em.largo,em.alto,em.ancho,gd.volumen,gd.peso,gd.peso,'$_SESSION[IDUSUARIO]',CURRENT_DATE 
		FROM guiaventanilla_detalle gd 
		INNER JOIN catalogodescripcion cd ON gd.descripcion = cd.descripcion 
		INNER JOIN guiasventanilla gv ON gd.idguia = gv.id
		LEFT JOIN evaluacionmercanciadetalle em ON gv.evaluacion = em.evaluacion
		AND gv.idsucursalorigen = em.sucursal 
		AND cd.id = em.descripcion AND em.contenido = gd.contenido
		AND em.cantidad = gd.cantidad AND gd.pesou = em.pesounit AND gd.volumen = em.volumen
		WHERE gd.idguia = '$_GET[folio]'
		UNION
		SELECT '$_SESSION[IDSUCURSAL]',gd.cantidad,gd.descripcion, cd.id, gd.contenido,gd.peso, 
		em.largo,em.alto,em.ancho,gd.volumen,gd.peso,gd.peso,'$_SESSION[IDUSUARIO]',CURRENT_DATE 
		FROM guiasempresariales_detalle gd 
		INNER JOIN catalogodescripcion cd ON gd.descripcion = cd.descripcion 
		INNER JOIN guiasempresariales ge ON gd.id = ge.id
		LEFT JOIN evaluacionmercanciadetalle em ON ge.evaluacion = em.evaluacion
		AND ge.idsucursalorigen = em.sucursal 
		AND cd.id = em.descripcion AND em.contenido = gd.contenido
		AND em.cantidad = gd.cantidad AND gd.volumen = em.volumen
		WHERE gd.id = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		$s = "select * from devolucionguiadetalle where usuario = '$_SESSION[IDUSUARIO]' AND isnull(devolucion)";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$detallado[] = $f;
		}
		
		$reg = str_replace('&#32;','',str_replace('null','""',json_encode($registros)));
		$det = str_replace('null','""',json_encode($detallado));
		
		
		echo '({
			"reg":'.$reg.',
			"det":'.$det.'
		})';
		
	}else if($_GET[accion] == 2){
		$s = "select cs.id, cs.descripcion, cd.poblacion, cd.sucursal as iddestino
		from catalogosucursal as cs 
		inner join catalogodestino as cd on cs.id = cd.sucursal
		where cd.id = $_GET[iddestino]";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){				
				$f->descripcion = cambio_texto($f->descripcion);
				$f->poblacion = cambio_texto($f->poblacion);
				$registros[] = $f;
			}
			
			echo str_replace('null','""',json_encode($registros));
			
	}else if($_GET[accion] == 3){
		$s = "SELECT * FROM guiaventanilla_detalle where idguia='$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){			
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$f->contenido = cambio_texto($f->contenido);
				$f->volumen = round($fx->volumen,2);
				$f->peso	= round($fx->peso,2);
				$f->importe	= round($fx->importe,2);
				$registros[] = $f;					
			}
			echo str_replace('null','""',json_encode($registros));
		}
				
	}else if($_GET[accion] == 4){
		if($_GET[tipo] == "grabar"){
		
			$row = split(",",$_GET[arre]);
			$s = "INSERT INTO devolucionguiadetalle SET
			sucursal=".$row[0].", cantidad=".$row[1].", descripcion=UCASE('".$_GET[descripcion]."'),
			iddescripcion=".trim($row[2]).", contenido=UCASE('".$_GET[contenido]."'), 
			peso=".$row[3].", largo=".$row[4].", ancho=".$row[6].",alto=".$row[5].",
			volumen=".$row[7].",pesototal=".$row[8].", pesounit=".$row[9].", 
			usuario=".$_SESSION[IDUSUARIO].",fecha='".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			
			echo "ok,grabar";
		}else if($_GET[tipo] == "modificar"){		
			$row = split(",",$_GET[arre]);
			$s = "UPDATE devolucionguiadetalle SET
			sucursal=".$row[0].", cantidad=".$row[1].", descripcion=UCASE('".$_GET[descripcion]."'),
			iddescripcion=".trim($row[2]).", contenido=UCASE('".$_GET[contenido]."'), 
			peso=".$row[3].", largo=".$row[4].", ancho=".$row[6].",alto=".$row[5].",
			volumen=".$row[7].",pesototal=".$row[8].", pesounit=".$row[9].", 
			usuario=".$_SESSION[IDUSUARIO].",fecha='".$_GET[fecha]."'
			WHERE usuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."' AND devolucion IS NULL";
			mysql_query($s,$l) or die($s);
			
			echo "ok,modificar";
		}
		
	}else if($_GET[accion] == 5){
		$s = "DELETE FROM devolucionguiadetalle
		WHERE usuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."' AND devolucion IS NULL";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion] == 6){	
		$s = "select * from catalogodestino where id=".$_GET[destino]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$totalhorasincre = 0;
		$arredias = array("lunes","martes","miercoles","jueves","viernes","sabado");
			
		$rest2= "<restr2>0</restr2>";
			if($f->todasemana==0){//
				$valordias[0]	= ($f->lunes==1)?0:1;
				$valordias[1] 	= ($f->martes==1)?0:1;
				$valordias[2] 	= ($f->miercoles==1)?0:1;
				$valordias[3] 	= ($f->jueves==1)?0:1;
				$valordias[4] 	= ($f->viernes==1)?0:1;
				$valordias[5] 	= ($f->sabado==1)?0:1;
				
				$totaldias = 0;
				for($i=0; $i<6; $i++){
					
					$valarre 	= $f->diasemana+$i;
					
					$totaldias	+= $valordias[(($valarre>5)?($valarre-6):($valarre))];
					
					if($valordias[(($valarre>5)?($valarre-6):($valarre))]==0)
						break;
				}
				
				$totalhorasincre 	= 24*$totaldias;
				$ultvalor			= (($valarre>5)?($valarre-6):($valarre));
				$rest2= "<restr2>".$arredias[$ultvalor]."</restr2>";
			}
			
			$s = "select * from configuradorgeneral";
			$rcg = mysql_query($s,$l) or die($s);
			$fcg = mysql_fetch_object($rcg);
			$por_combustible 	= ($fcg->cargocombustible=="")?0:$fcg->cargocombustible;
			$max_des 			= ($fcg->descuento=="")?0:$fcg->descuento;
			$iva_retenido		= ($fcg->ivaretenido=="")?0:$fcg->ivaretenido;
			$pagominimocheques	= ($fcg->pagominimocheques=="")?0:$fcg->pagominimocheques;
			$pesominimodesc		= ($fcg->pesominaplicardescuento=="")?0:$fcg->pesominaplicardescuento;
			
			$s = "select catalogotiempodeentregas.*, hour(current_time) as tiempo 
			from catalogotiempodeentregas where idorigen = $_GET[idsucorigen] and iddestino = $_GET[destino]";
			$rm = mysql_query($s,$l) or die($s);
			$fm = mysql_fetch_object($rm);
			
			$horasparamensaje = array(0,12,14,16,8,9,10,11,13,15,17);
			
			if($fm->incrementartiempo==1){
				$s = "select if(current_time>'".$horasparamensaje[$fm->siocurre]."',1,0) as validacion";
				$rpr = mysql_query($s,$l) or die($s);
				$fpr = mysql_fetch_object($rpr);
				if($fpr->validacion=="1"){
					$restrinccion = "Si documenta despues de las ".$horasparamensaje[$fm->siocurre]." hrs se incrementaran ".$fm->aincrementar. "hrs";	
					$mashoras = $fm->aincrementar;
				}else{
					$restrinccion = 0;
					$mashoras = 0;
				}
			}else{
				$restrinccion = 0;
				$mashoras = 0;
			}
						
			echo cambio_texto($fm->tentrega+$mashoras).",".cambio_texto($fm->tentregaad+$mashoras+$totalhorasincre).",".$restrinccion;			
	
	}else if($_GET[accion] == 7){
		$row = split(",",$_GET[arre]);
		//echo print_r($row);
		
		$s = "SELECT CONCAT(cuentac,numerodesde,CHAR(ASCII(letra)+aumento)) AS newfolio
		FROM (
		SELECT 
			(777) AS cuentac,					
		LPAD(
			IFNULL(
				IF(SUBSTRING(MAX(nuevaguia),4,9)+1=1000000000,1,SUBSTRING(MAX(nuevaguia),4,9)+1)
			,1)
		,9,'0') AS numerodesde,
		IFNULL(SUBSTRING(MAX(nuevaguia),13,1),'A') AS letra,
		IF(SUBSTRING(MAX(nuevaguia),4,9)+1=1000000000,1,0) AS aumento
		FROM devolucionguia 
		WHERE SUBSTRING(nuevaguia,1,3) = (777)
		) AS t1";
		
		$rr = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($rr);
		$newfolio = $f->newfolio;
				
		$s = "INSERT INTO devolucionguia 
		(fechadevolucion,sucursal,guia,nuevaguia,destino,sucursaldestino,ocurre,tocurre,tead,
		restrinccion,tpaquete,tpeso,tvolumen,observaciones,valordeclarado,
		tvalordeclarado,usuario,fecha)
		VALUES
		('".cambiaf_a_mysql($row[0])."',".trim($row[1]).",'".trim($row[2])."','".$newfolio."',".trim($row[3]).",
		".trim($row[13]).",1, ".trim($row[4]).",".trim($row[5]).",'".trim($row[6])."',
		".trim($row[7]).",".trim($row[8]).",".trim($row[9]).",'".trim($row[10])."',".$row[12].",
		".trim($row[11]).", ".$_SESSION[IDUSUARIO].",CURRENT_TIMESTAMP())";
		$r = mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();

		$s = "SELECT * FROM devolucionguiadetalle WHERE usuario=$_SESSION[IDUSUARIO] AND devolucion IS NULL";
		$d = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($d)>0){
			while($f = mysql_fetch_object($d)){
				$s = "UPDATE devolucionguiadetalle SET devolucion = ".$folio."
				WHERE usuario=$_SESSION[IDUSUARIO] AND devolucion IS NULL";
				mysql_query($s,$l) or die($s);				
			}
		}
		
		$s = "SELECT * FROM guiasventanilla WHERE id = '".trim($row[2])."'";		
		$rf = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rf)>0){
				
				$s = "INSERT INTO guiasventanilla
				(id,evaluacion,fecha,fechaentrega,factura,estado,ubicacion,entradasalida,tipoflete,ocurre,
				idsucursalorigen,iddestino,idsucursaldestino,condicionpago,idremitente,iddireccionremitente,
				iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,restrinccion,totalpaquetes,
				totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,avisocelular,celular,
				valordeclarado,acuserecibo,cod,recoleccion,observaciones,tflete,tdescuento,
				ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,tcombustible,subtotal,tiva,
				ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,tarjeta,trasferencia,sector,clienteconvenio,
				sucursalconvenio,idvendedorconvenio,nvendedorconvenio,convenioaplicado,idusuario,
				usuario,fecha_registro,hora_registro,devolucion,recibio)
				SELECT '".$newfolio."' AS id,evaluacion, CURDATE() AS fecha, fechaentrega, factura,
				'ALMACEN ORIGEN' AS estado, ubicacion, 'ENTRADA', tipoflete, 1 AS ocurre,
				".$row[1]." AS idsucursalorigen, ".$row[3]." AS iddestino,
				".$row[13]." AS idsucursaldestino, condicionpago, idremitente,
				iddireccionremitente,iddestinatario, iddirecciondestinatario,
				".$row[4]." AS entregaocurre, ".$row[5]." AS entregaead,
				'".$row[6]."' AS restrinccion, ".$row[7]." AS totalpaquetes,
				".$row[8]." AS totalpeso, ".$row[9]." AS totalvolumen,
				emplaye, bolsaempaque, totalbolsaempaque,
				avisocelular,celular, 0 AS valordeclarado,acuserecibo,
				0,0,observaciones,0,0,0,
				0,0,0,0,0,0,
				0,0,0,0,nivel,0 AS efectivo,0 AS cheque,
				0 AS banco,'' AS ncheque,0 AS tarjeta,0 AS trasferencia, 
				sector,clienteconvenio,sucursalconvenio, idvendedorconvenio,
				nvendedorconvenio,convenioaplicado,".$_SESSION[IDUSUARIO].",
				'".$_SESSION[NOMBREUSUARIO]."', CURDATE() AS fecha_registro, CURTIME() AS hora_registro,'SI' AS devolucion,recibio
				FROM guiasventanilla WHERE id ='".trim($row[2])."'";
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT * FROM devolucionguiadetalle WHERE devolucion =".$folio."";
				$ds = mysql_query($s,$l) or die($s);
				$cantidad = 1;
				if(mysql_num_rows($ds)>0){
					$s = "INSERT INTO guiaventanilla_detalle
					(idguia,cantidad,descripcion,contenido,
					pesou,alto,ancho,largo,
					peso,volumen,importe,excedente,
					kgexcedente,idusuario)
					SELECT '".$newfolio."' AS idguia, cantidad, descripcion, contenido, 
					pesounit, alto, ancho, largo, 
					pesototal, volumen,0 AS importe, 0 AS excedente, 
					0 AS kgexcedente,".$_SESSION[IDUSUARIO]." 
					FROM devolucionguiadetalle
					WHERE devolucion=".$folio."";
					mysql_query($s,$l) or die($s);
					//$fs = mysql_fetch_object($ds);
					while($fs = mysql_fetch_object($ds)){
						$pesousado = ($fs->peso > $fs->volumen)?$fs->peso:$fs->volumen;
						for($i=0;$i<$fs->cantidad;$i++){						
							$s = "INSERT INTO guiaventanilla_unidades SET 
							idguia='".$newfolio."', descripcion='$fs->descripcion', 
							contenido='$fs->contenido', peso=$pesousado/$fs->cantidad,
							paquete=$cantidad, depaquetes=".$row[7].",
							ubicacion = ".$row[1].",
							codigobarras='".trim($newfolio).str_pad($cantidad,4,"0",STR_PAD_LEFT).
							str_pad($row[7],4,"0",STR_PAD_LEFT)."'";						
							@mysql_query($s,$l) or die($s);							
							$cantidad++;						
						}
					}
				}
				
			}
		
		$s = "SELECT * FROM guiasempresariales WHERE id = '".$row[2]."'";
		$rg = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rg)>0){				
				
				$s = "INSERT INTO guiasempresariales
				 (id,evaluacion,fecha,fechaentrega,factura,estado,ubicacion,entradasalida,tipoguia,
				 tipoflete,tipopago,ocurre,idsucursalorigen,iddestino,idsucursaldestino,idremitente,
				 iddireccionremitente,iddestinatario,iddirecciondestinatario,entregaocurre,entregaead,
				 restrinccion,totalpaquetes,totalpeso,totalvolumen,emplaye,bolsaempaque,totalbolsaempaque,
				 avisocelular,celular,valordeclarado,acuserecibo,cod,recoleccion,observaciones,tflete,
				 tdescuento,ttotaldescuento,tcostoead,trecoleccion,tseguro,totros,texcedente,tcombustible,
				 subtotal,tiva,ivaretenido,total,nivel,efectivo,cheque,banco,ncheque,tarjeta,trasferencia,
				 sector,clienteconvenio,sucursalconvenio,idvendedorconvenio,nvendedorconvenio,convenioaplicado,
				 idsolicitudguia,idusuario,usuario,fecha_registro,hora_registro,devolucion,recibio)
				SELECT '".$newfolio."' AS id,evaluacion, CURDATE() AS fecha, fechaentrega, factura,
				'ALMACEN DESTINO' AS estado, ubicacion, entradasalida, tipoguia, tipoflete, tipopago, ocurre,
				".$row[1]." AS idsucursalorigen, ".$row[3]." AS iddestino,
				".$row[13]." AS idsucursaldestino, idremitente,
				iddireccionremitente,iddestinatario, iddirecciondestinatario,
				".$row[4]." AS entregaocurre, ".$row[5]." AS entregaead,
				'".$row[6]."' AS restrinccion, ".$row[7]." AS totalpaquetes,
				".$row[8]." AS totalpeso, ".$row[9]." AS totalvolumen,
				emplaye, bolsaempaque, totalbolsaempaque,
				avisocelular,celular, ".$row[11]." AS valordeclarado,acuserecibo,
				cod,recoleccion,observaciones,tflete,tdescuento,ttotaldescuento,
				tcostoead,trecoleccion,tseguro,totros,texcedente,tcombustible,
				subtotal,tiva,ivaretenido,total,nivel,0 AS efectivo,0 AS cheque,
				0 AS banco,'' AS ncheque,0 AS tarjeta,0 AS trasferencia, 
				sector,clienteconvenio,sucursalconvenio, idvendedorconvenio,
				nvendedorconvenio,convenioaplicado, NULL,idusuario,
				usuario,CURDATE() AS fecha_registro,CURDATE() AS hora_registro,'SI' AS devolucion,recibio
				FROM guiasempresariales WHERE id ='".$row[2]."'";
				
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT * FROM devolucionguiadetalle WHERE devolucion =".$folio."";
				$ds = mysql_query($s,$l) or die($s);
				$cantidad = 1;
				if(mysql_num_rows($ds)>0){
					$s = "INSERT INTO guiasempresariales_detalle
					SELECT '".$newfolio."' AS idguia, cantidad,
					descripcion, contenido, peso, largo, ancho, alto, volumen,
					0 AS importe, 0 AS excedente, 0 AS kgexcedente,
					".$_SESSION[IDUSUARIO]." FROM devolucionguiadetalle
					WHERE devolucion=".$folio."";
					mysql_query($s,$l) or die($s);
					
					while($fs = mysql_fetch_object($ds)){					
						$pesousado = ($fs->peso>$fs->volumen)?$fs->peso:$fs->volumen;
						for($i=0;$i<$fs->cantidad;$i++){						
							$s = "INSERT INTO guiasempresariales_unidades SET 
							idguia='".$newfolio."', descripcion='$fs->descripcion', 
							contenido='$fs->contenido', peso=$pesousado/$fs->cantidad,
							paquete=$cantidad, depaquetes=".$row[7].",
							ubicacion = ".$row[1].",
							codigobarras='".trim($newfolio).str_pad($cantidad,4,"0",STR_PAD_LEFT).
							str_pad($row[7],4,"0",STR_PAD_LEFT)."'";
							@mysql_query($s,$l) or die($s);
					
							$cantidad++;
						}
					}
				}
			}
		echo "ok,".$newfolio;
		
	}else if($_GET[accion] == 8){
		$s = "SELECT DATE_FORMAT(d.fechadevolucion,'%d/%m/%Y') AS fechadevolucion,
		d.sucursal, cs.descripcion AS dessucursal,
		d.guia, d.destino, cd.descripcion AS ddestino, d.ocurre, d.tocurre,
		d.tead, d.restrinccion, d.tpaquete, d.tpeso, d.tvolumen, d.observaciones,
		d.valordeclarado, d.tvalordeclarado, d.sucursaldestino,
		csd.descripcion AS dessucdestino, d.nuevaguia
		FROM devolucionguia d
		INNER JOIN catalogosucursal cs ON d.sucursal = cs.id
		INNER JOIN catalogodestino cd ON d.destino = cd.id
		LEFT JOIN catalogosucursal csd ON d.sucursaldestino = csd.id
		WHERE folio=".$_GET[devolucion]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$f->dessucursal = cambio_texto($f->dessucursal);
			$f->ddestino	= cambio_texto($f->ddestino);
			$f->dessucdestino = cambio_texto($f->dessucdestino);
		$s = "SELECT
		gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
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
		FROM guiasventanilla as gv
		inner join catalogosucursal as csd on gv.idsucursaldestino = csd.id
		inner join catalogodestino as cd on gv.iddestino = cd.id
		inner join catalogocliente as ccr on gv.idremitente = ccr.id
		left join direccion as dr on gv.iddireccionremitente = dr.id
		inner join catalogocliente as ccd on gv.iddestinatario = ccd.id
		inner join direccion as dd on gv.iddirecciondestinatario = dd.id
		where gv.id= '$f->nuevaguia'";
		$rg = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($rg)>0){
			//echo "entro";
			while($fg = mysql_fetch_object($rg)){
				$f->emp = 0;
				$f->id = cambio_texto($fg->id);
				$f->fechaactual=cambio_texto($fg->fechaactual);
				$f->evaluacion=cambio_texto($fg->evaluacion);
				$f->fecha=cambio_texto($fg->fecha);
				$f->estado=cambio_texto($fg->estado);
				$f->fecha=cambio_texto($fg->fecha);
				$f->factura=cambio_texto($fg->factura);
				$f->tipoflete=cambio_texto($fg->tipoflete);
				$f->ocurre=cambio_texto($fg->ocurre);
				$f->idsucursalorigen=cambio_texto($fg->idsucursalorigen);
				$f->ndestino=cambio_texto($fg->ndestino);
				$f->nsucdestino=cambio_texto($fg->nsucdestino);
				$f->condicionpago=cambio_texto($fg->condicionpago);
				$f->idremitente=cambio_texto($fg->idremitente);
				$f->rncliente=cambio_texto($fg->rncliente);
				$f->rrfc=cambio_texto($fg->rrfc);
				$f->rcelular=cambio_texto($fg->rcelular);
				$f->rcalle=cambio_texto($fg->rcalle);
				$f->rnumero=cambio_texto($fg->rnumero);
				$f->rcp=cambio_texto($fg->rcp);
				$f->rpoblacion=cambio_texto($fg->rpoblacion);
				$f->rtelefono=cambio_texto($fg->rtelefono);
				$f->rcolonia=cambio_texto($fg->rcolonia);
				$f->iddestinatario=cambio_texto($fg->iddestinatario);
				$f->dncliente=cambio_texto($fg->dncliente);
				$f->drfc=cambio_texto($fg->drfc);
				$f->dcelular=cambio_texto($fg->dcelular);
				$f->dcalle=cambio_texto($fg->dcalle);
				$f->dnumero=cambio_texto($fg->dnumero);
				$f->dcp=cambio_texto($fg->dcp);
				$f->dpoblacion=cambio_texto($fg->dpoblacion);
				$f->dtelefono=cambio_texto($fg->dtelefono);
				$f->dcolonia=cambio_texto($fg->dcolonia);
				$f->entregaocurre=cambio_texto($fg->entregaocurre);
				$f->entregaead=cambio_texto($fg->entregaead);
				$f->restrinccion=cambio_texto($fg->restrinccion);
				$f->totalpaquetes=cambio_texto($fg->totalpaquetes);
				$f->totalpeso=cambio_texto($fg->totalpeso);
				$f->totalvolumen=cambio_texto($fg->totalvolumen);
				$f->emplaye=cambio_texto($fg->emplaye);
				$f->bolsaempaque=cambio_texto($fg->bolsaempaque);
				$f->totalbolsaempaque=cambio_texto($fg->totalbolsaempaque);
				$f->avisocelular=cambio_texto($fg->avisocelular);
				$f->celular=cambio_texto($fg->celular);
				$f->valordeclarado=cambio_texto($fg->valordeclarado);
				$f->acuserecibo=cambio_texto($fg->acuserecibo);
				$f->cod=cambio_texto($fg->cod);
				$f->recoleccion=cambio_texto($fg->recoleccion);
				$f->observaciones=cambio_texto($fg->observaciones);
				$f->tflete=cambio_texto($fg->tflete);
				$f->tdescuento=cambio_texto($fg->tdescuento);
				$f->ttotaldescuento=cambio_texto($fg->ttotaldescuento);
				$f->tcostoead=cambio_texto($fg->tcostoead);
				$f->trecoleccion=cambio_texto($fg->trecoleccion);
				$f->tseguro=cambio_texto($fg->tseguro);
				$f->totros=cambio_texto($fg->totros);
				$f->texcedente=cambio_texto($fg->texcedente);
				$f->tcombustible=cambio_texto($fg->tcombustible);
				$f->subtotal=cambio_texto($fg->subtotal);
				$f->tiva=cambio_texto($fg->tiva);
				$f->ivaretenido=cambio_texto($fg->ivaretenido);
				$f->total=cambio_texto($fg->total);
				$f->efectivo=cambio_texto($fg->efectivo);
				$f->cheque=cambio_texto($fg->cheque);
				$f->banco=cambio_texto($fg->banco);
				$f->ncheque=cambio_texto($fg->ncheque);
				$f->tarjeta=cambio_texto($fg->tarjeta);
				$f->trasferencia=cambio_texto($fg->trasferencia);
				$f->usuario=cambio_texto($fg->usuario);
				$f->fecha_registro = cambio_texto($fg->fecha_registro);
				$f->hora_registro = cambio_texto($fg->hora_registro);
				$f->valor_totalimporte =cambio_texto($totalimporte+$tenvase);
			}
		}
		
		$s = "SELECT
		gv.id, gv.evaluacion, date_format(gv.fecha, '%d/%m/%Y') as fecha, gv.fechaentrega, gv.factura, 
		gv.estado, gv.tipoflete, gv.ocurre, gv.idsucursalorigen, 
		concat(cd.descripcion,' - ',csd.prefijo) as ndestino, csd.descripcion as nsucdestino,
		gv.idsucursalorigen,gv.idsucursaldestino,
		gv.tipopago, gv.tipoguia, 
		
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
		FROM guiasempresariales as gv
		INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id
		INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id
		INNER JOIN catalogocliente AS ccr ON gv.idremitente = ccr.id
		LEFT JOIN direccion AS dr ON ccr.id = dr.codigo AND origen = 'cl'
		INNER JOIN catalogocliente AS ccd ON gv.iddestinatario = ccd.id
		INNER JOIN direccion AS dd ON gv.iddirecciondestinatario = dd.id
		WHERE gv.id= '$f->nuevaguia'
		GROUP BY id";
		//echo $s;
		$re = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($re)>0){
			while($fe = mysql_fetch_object($re)){
				$f->emp = 1;
				$f->id = cambio_texto($fe->id);
				$f->fechaactual=cambio_texto($fe->fechaactual);
				$f->evaluacion=cambio_texto($fe->evaluacion);
				$f->fecha=cambio_texto($fe->fecha);
				$f->estado=cambio_texto($fe->estado);
				$f->fecha=cambio_texto($fe->fecha);
				$f->factura=cambio_texto($fe->factura);
				$f->tipoflete=cambio_texto($fe->tipoflete);				
				$f->ocurre=cambio_texto($fe->ocurre);
				$f->idsucursalorigen=cambio_texto($fe->idsucursalorigen);
				$f->ndestino=cambio_texto($fe->ndestino);
				$f->nsucdestino=cambio_texto($fe->nsucdestino);
				$f->condicionpago=cambio_texto($fe->tipopago);
				$f->tipoguia=cambio_texto($fe->tipoguia);
				$f->idremitente=cambio_texto($fe->idremitente);
				$f->rncliente=cambio_texto($fe->rncliente);
				$f->rrfc=cambio_texto($fe->rrfc);
				$f->rcelular=cambio_texto($fe->rcelular);
				$f->rcalle=cambio_texto($fe->rcalle);
				$f->rnumero=cambio_texto($fe->rnumero);
				$f->rcp=cambio_texto($fe->rcp);
				$f->rpoblacion=cambio_texto($fe->rpoblacion);
				$f->rtelefono=cambio_texto($fe->rtelefono);
				$f->rcolonia=cambio_texto($fe->rcolonia);
				$f->iddestinatario=cambio_texto($fe->iddestinatario);
				$f->dncliente=cambio_texto($fe->dncliente);
				$f->drfc=cambio_texto($fe->drfc);
				$f->dcelular=cambio_texto($fe->dcelular);
				$f->dcalle=cambio_texto($fe->dcalle);
				$f->dnumero=cambio_texto($fe->dnumero);
				$f->dcp=cambio_texto($fe->dcp);
				$f->dpoblacion=cambio_texto($fe->dpoblacion);
				$f->dtelefono=cambio_texto($fe->dtelefono);
				$f->dcolonia=cambio_texto($fe->dcolonia);
				$f->entregaocurre=cambio_texto($fe->entregaocurre);
				$f->entregaead=cambio_texto($fe->entregaead);
				$f->restrinccion=cambio_texto($fe->restrinccion);
				$f->totalpaquetes=cambio_texto($fe->totalpaquetes);
				$f->totalpeso=cambio_texto($fe->totalpeso);
				$f->totalvolumen=cambio_texto($fe->totalvolumen);
				$f->emplaye=cambio_texto($fe->emplaye);
				$f->bolsaempaque=cambio_texto($fe->bolsaempaque);
				$f->totalbolsaempaque=cambio_texto($fe->totalbolsaempaque);
				$f->avisocelular=cambio_texto($fe->avisocelular);
				$f->celular=cambio_texto($fe->celular);
				$f->valordeclarado=cambio_texto($fe->valordeclarado);
				$f->acuserecibo=cambio_texto($fe->acuserecibo);
				$f->cod=cambio_texto($fe->cod);
				$f->recoleccion=cambio_texto($fe->recoleccion);
				$f->observaciones=cambio_texto($fe->observaciones);
				$f->tflete=cambio_texto($fe->tflete);
				$f->tdescuento=cambio_texto($fe->tdescuento);
				$f->ttotaldescuento=cambio_texto($fe->ttotaldescuento);
				$f->tcostoead=cambio_texto($fe->tcostoead);
				$f->trecoleccion=cambio_texto($fe->trecoleccion);
				$f->tseguro=cambio_texto($fe->tseguro);
				$f->totros=cambio_texto($fe->totros);
				$f->texcedente=cambio_texto($fe->texcedente);
				$f->tcombustible=cambio_texto($fe->tcombustible);
				$f->subtotal=cambio_texto($fe->subtotal);
				$f->tiva=cambio_texto($fe->tiva);
				$f->ivaretenido=cambio_texto($fe->ivaretenido);
				$f->total=cambio_texto($fe->total);
				$f->efectivo=cambio_texto($fe->efectivo);
				$f->cheque=cambio_texto($fe->cheque);
				$f->banco=cambio_texto($fe->banco);
				$f->ncheque=cambio_texto($fe->ncheque);
				$f->tarjeta=cambio_texto($fe->tarjeta);
				$f->trasferencia=cambio_texto($fe->trasferencia);
				$f->usuario=cambio_texto($fe->usuario);
				$f->fecha_registro = cambio_texto($fe->fecha_registro);
				$f->hora_registro = cambio_texto($fe->hora_registro);
			}			
		}			
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion] == 9){
		$s = "SELECT * FROM devolucionguiadetalle WHERE devolucion=".$_GET[devolucion]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$registros[] = $f;
		}
		
		echo str_replace('null','""',json_encode($registros));
	}
	
?>