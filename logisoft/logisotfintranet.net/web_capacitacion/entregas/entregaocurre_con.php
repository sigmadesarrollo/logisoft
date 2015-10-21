<?	session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		if($_GET[buscar]=="1"){
			$s = "SELECT bs.id_cliente as iddestinatario
					FROM cartaporte cp 
					inner join recoleccion r on cp.IDRecoleccion = r.Folio
					inner join bitacorasalida bs on r.foliobitacora = bs.folio and r.folio = bs.Foliorecoleccion
					inner join programacionrecepciondiaria p on bs.folio = p.idbitacora
					 where bs.status = 1  AND cp.Folio = '$_GET[folioguia]' 
					 ";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
			}else{
				$s = "SELECT r.cliente as iddestinatario
					FROM cartaporte cp 
					inner join recoleccion r on cp.IDRecoleccion = r.Folio
					inner join bitacorasalida bs on r.foliobitacora = bs.folio and r.folio = bs.Foliorecoleccion
					inner join programacionrecepciondiaria p on bs.folio = p.idbitacora
					 where bs.status = 1  AND cp.Folio = '$_GET[folioguia]' ";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
				}
			}
			
			$idcliente = $f->iddestinatario;
			
/*			if(substr($_GET[folioguia],0,3)=='888'){
				$campos = "CONCAT_WS(' ',cc1.nombre,cc1.apellidopaterno,cc1.apellidomaterno) AS remitente, 
						CONCAT_WS(' ',cc2.nombre,cc2.apellidopaterno,cc2.apellidomaterno) AS destinatario,";
				$and1 = "INNER JOIN catalogoempleado AS cc1 ON gv.idremitente = cc1.id
						INNER JOIN catalogoempleado AS cc2 ON gv.iddestinatario = cc2.id";
				$and2 = "INNER JOIN catalogoempleado AS cc1 ON ge.idremitente = cc1.id
						INNER JOIN catalogoempleado AS cc2 ON ge.iddestinatario = cc2.id";
				$where = " AND SUBSTRING(gv.id,1,3)='888'";	
				$where2 = " AND SUBSTRING(ge.id,1,3)='888'";			
	
			}else{
				$campos = "CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, 
						CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario,";
				$and1 = "INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id
						INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id";
				$and2 = "INNER JOIN catalogocliente AS cc1 ON ge.idremitente = cc1.id
						INNER JOIN catalogocliente AS cc2 ON ge.iddestinatario = cc2.id";
				
				$where = " AND SUBSTRING(gv.id,1,3)!='888'";	
				$where2 = " AND SUBSTRING(ge.id,1,3)!='888'"; guia			
			}*/
		$s = "SELECT  cp.Folio AS guia,cp.OrigenNombre as origen , cp.DestinoNombre,'TINSA' as remitente,Nombre_Cliente as destinatario, sum(largo) as importe,DATE_FORMAT(cp.fecha, '%d/%m/%Y') AS fecha, 
				cp.TipoViaje as tipoflete
				FROM cartaporte cp 
				inner join recoleccion r on cp.IDRecoleccion = r.Folio
				inner join recolecciondetalle rd on r.folio = rd.recoleccion
				inner join bitacorasalida bs on r.foliobitacora = bs.folio and r.folio = bs.Foliorecoleccion
				where bs.status = 1  AND cp.Folio = '$_GET[folioguia]'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		$total = 0;
		while($f = mysql_fetch_object($r)){
			$f->guia 			=  cambio_texto($f->guia);
			$f->origen 			=  cambio_texto($f->origen);
			$f->remitente 		=  cambio_texto($f->remitente);
			$f->destinatario 	=  cambio_texto($f->destinatario);
			$total				+= $f->importe;
			$arre[] = $f;
		}
		$guias = str_replace("null",'""',json_encode($arre));
		if(substr($_GET[folioguia],0,3)=='888'){
			$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS cliente
			FROM catalogoempleado WHERE id = $idcliente";
		}else{
			$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente
			FROM catalogocliente WHERE id = $idcliente";
		}
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->cliente = cambio_texto($f->cliente);
		$f->idcliente = $idcliente;
		$dcliente = str_replace("null",'""',json_encode($f));
		
		echo "({guias:$guias, cliente:$dcliente, total:$total})";
		
		}else{
			$campos = "CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, 
						CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario,";
			$and1 = "INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id
						INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id";
			$and2 = "INNER JOIN catalogocliente AS cc1 ON ge.idremitente = cc1.id
						INNER JOIN catalogocliente AS cc2 ON ge.iddestinatario = cc2.id";
			
			$where = " AND SUBSTRING(gv.id,1,3)!='888'";	
			$where2 = " AND SUBSTRING(ge.id,1,3)!='888'";						
			$idcliente = $_GET[cliente];
		}
		$s = "SELECT 1 AS sel, gv.id AS guia, IF(gv.condicionpago=0,'CONTADO','PAGADO') as tipopago,  
		cs.descripcion AS origen, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
		$campos
		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, gv.total AS importe, gv.estado
		FROM guiasventanilla AS gv
		$and1
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		WHERE iddestinatario = '$idcliente' AND ocurre = 1 $where
		AND idsucursaldestino = $_SESSION[IDSUCURSAL] AND gv.estado = 'ALMACEN DESTINO'
		UNION
		SELECT 1 AS sel, ge.id AS guia, ge.tipopago, cs.descripcion AS origen, 
		DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha,
		$campos
		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, ge.total AS importe, ge.estado
		FROM guiasempresariales AS ge
		$and2
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		WHERE iddestinatario = '$idcliente' AND ocurre = 1 $where2
		AND idsucursaldestino = $_SESSION[IDSUCURSAL] AND ge.estado = 'ALMACEN DESTINO'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		$total = 0;
		while($f = mysql_fetch_object($r)){
			$f->origen 			=  cambio_texto($f->origen);
			$f->remitente 		=  cambio_texto($f->remitente);
			$f->destinatario 	=  cambio_texto($f->destinatario);
			$total				+= $f->importe;
			$arre[] = $f;
		}
		$guias = str_replace("null",'""',json_encode($arre));
		if(substr($_GET[folioguia],0,3)=='888'){
			$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS cliente
			FROM catalogoempleado WHERE id = $idcliente";
		}else{
			$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente
			FROM catalogocliente WHERE id = $idcliente";
		}
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->cliente = cambio_texto($f->cliente);
		$f->idcliente = $idcliente;
		$dcliente = str_replace("null",'""',json_encode($f));
		
		
		
	}else if($_GET[accion]==2){
		
		$losfolios = "'".str_replace(",","','",$_GET['folios'])."'";
		
		$s = "SELECT id FROM guiasventanilla 
		WHERE id IN($losfolios) AND 
		estado IN('ENTREGADA','POR ENTREGAR')
		UNION
		SELECT id FROM guiasempresariales 
		WHERE id IN($losfolios) AND 
		estado IN('ENTREGADA','POR ENTREGAR')";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("Existen guias que ya han sido entregadas");
		}
		
		
		$slq="INSERT INTO entregasocurre (folio,idsucursal,sucursal,nguia,cliente,nombre,recepcion, chkliste, cartaporte, contrarecibocarga, facturascarga, recibomaniobras,
			total,efectivo,cheque,banco,nocheque,tarjeta,transferencia, tipodeidentificacion,numeroidentificacion,personaquerecibe,usuario, idusuario,fecha)	VALUES
			(obtenerFolio('entregasocurre',".$_SESSION[IDSUCURSAL]."),'".$_GET['idsucursal']."',
			UCASE('".$_GET['sucursal']."'),UCASE('".$_GET['nguia']."'),'".$_GET['cliente']."',
			UCASE('".$_GET['nombre']."'),
			UCASE('".$_GET['recepcion']."'),
			UCASE('".$_GET['chkliste']."'),
			UCASE('".$_GET['cartaporte']."'),
			UCASE('".$_GET['contrarecibocarga']."'),
			UCASE('".$_GET['facturascarga']."'),
			UCASE('".$_GET['recibomaniobras']."'),
			'".$_GET['total']."',	'".$_GET['efectivo']."', '".$_GET['cheque']."',
			'".$_GET['banco']."', '".$_GET['ncheque']."', '".$_GET['tarjeta']."',
			'".$_GET['transferencia']."',	'".$_GET['identificacion']."',
			'".$_GET['nidentificacion']."', UCASE('".$_GET['precibe']."'), '".$_SESSION['NOMBREUSUARIO']."',
			'".$_SESSION['IDUSUARIO']."', CURRENT_DATE)";
		$s=mysql_query(str_replace("''","NULL",$slq),$l) or die($slq." <BR> Error en la linea ".__LINE__);
		$folio=mysql_insert_id();
 
		
		$s = "UPDATE entregasocurre SET firma = (SELECT firma FROM entregasocurrefirma WHERE id = '".$_GET[firma]."')
		WHERE id = ".$folio.""; 
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT folio FROM entregasocurre WHERE id = ".$folio;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
		
		if($_GET[total] > 0){
			$s = "INSERT INTO formapago SET guia='$fo->folio',procedencia='O',tipo='X',
			total='$_GET[total]',efectivo='$_GET[efectivo]',tarjeta='$_GET[tarjeta]',
			transferencia='$_GET[transferencia]',cheque='$_GET[cheque]',
			ncheque='$_GET[ncheque]',banco='$_GET[banco]',
			notacredito='$_GET[nc]',nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',
			usuario='$_SESSION[IDUSUARIO]',fecha=current_date, cliente = '".$_GET[cliente]."'";
			mysql_query(str_replace("''","null",$s),$l) or die($s);
		}
			$guia=split(",",$_GET['folios']);
			$num=count($guia);
			for($i=0;$i<$num;$i++){
				$detalle="INSERT INTO entregasocurre_detalle(entregaocurre,guia,usuario,idusuario,fecha,sucursal)
				VALUES 
				('".$fo->folio."','".$guia[$i]."','".$_SESSION['NOMBREUSUARIO']."',
				'".$_SESSION['IDUSUARIO']."',CURRENT_DATE,".$_SESSION[IDSUCURSAL].")";
				
				$res_detalle=mysql_query(str_replace("''","NULL",$detalle),$l)or die($detalle." <BR> Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasventanilla SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasempresariales SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
			}
			
			$s = "UPDATE pagoguias AS pg
			INNER JOIN entregasocurre_detalle AS eo ON pg.guia = eo.guia
			SET pg.pagado = 'S', pg.sucursalcobro='$_SESSION[IDSUCURSAL]', fechapago = CURRENT_DATE,
			pg.usuariocobro = '$_SESSION[IDUSUARIO]'
			WHERE eo.sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_GET[folio]'
			AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='NO'";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM entregasocurre_detalle 
			WHERE entregaocurre = ".$fo->folio." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$e = mysql_query($s,$l) or die($s);
			while($ead = mysql_fetch_object($e)){
				$s = "CALL proc_ReporteProductividad('OCURRE','','".$ead->guia."','".$_GET[fechahora]."',
				".$_SESSION[IDSUCURSAL].")";
				mysql_query($s,$l) or die($s);
			}
			
		echo "guardado,".$fo->folio;
		
	}else if($_GET[accion]==3){
		$s = "SELECT folio,entregadas,idsucursal,sucursal,nguia,cliente,nombre,
		total,efectivo,cheque,banco,nocheque,tarjeta,transferencia,
		tipodeidentificacion,numeroidentificacion,personaquerecibe,
		usuario,idusuario,fecha, if(current_date=fecha,'SI','NO') modificable,
		recepcion, chkliste, cartaporte, contrarecibocarga, facturascarga, recibomaniobras,observacion
		FROM entregasocurre
		WHERE folio = $_GET[folio] AND idsucursal =  ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$f->nombre = cambio_texto($f->nombre);
		$f->sucursal = cambio_texto($f->sucursal);
		$f->personaquerecibe = cambio_texto($f->personaquerecibe);
		$f->tipodeidentificacion = cambio_texto($f->tipodeidentificacion);
		
		$s = "SELECT * FROM entregasocurrealmacen eo 
			WHERE eo.folio = $_GET[folio] AND eo.sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f->registradoalmacen = mysql_num_rows($r);
		
		$datoscliente = str_replace("null",'""', json_encode($f));
		
		/*$s = "SELECT 1 AS sel, gv.id AS guia, cs.descripcion AS origen, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario,
		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, gv.total AS importe, gv.estado
		FROM guiasventanilla AS gv
		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id
		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN entregasocurre_detalle AS eod ON gv.id = eod.guia AND eod.entregaocurre = $_GET[folio] AND eod.sucursal = ".$_SESSION[IDSUCURSAL]."
		where idsucursaldestino = (SELECT id FROM catalogosucursal WHERE descripcion = '$_GET[sucursal]')
		UNION
		SELECT 1 AS sel, gv.id AS guia, cs.descripcion AS origen, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno) AS remitente, CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno) AS destinatario,
		IF(tipoflete=1,'POR COBRAR','PAGADO') AS tipoflete, gv.total AS importe, gv.estado
		FROM guiasempresariales AS gv
		INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id
		INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN entregasocurre_detalle AS eod ON gv.id = eod.guia AND eod.entregaocurre = $_GET[folio] AND eod.sucursal = ".$_SESSION[IDSUCURSAL]."
		WHERE idsucursaldestino = (SELECT id FROM catalogosucursal WHERE descripcion = '$_GET[sucursal]')";*/
		$s = "SELECT  cp.Folio AS guia,cp.OrigenNombre as origen , cp.DestinoNombre,'TINSA' as remitente,Nombre_Cliente as destinatario, sum(largo) as importe,DATE_FORMAT(cp.fecha, '%d/%m/%Y') AS fecha, 
				cp.TipoViaje as tipoflete
				FROM cartaporte cp 
				inner join recoleccion r on cp.IDRecoleccion = r.Folio
				inner join recolecciondetalle rd on r.folio = rd.recoleccion
				inner join bitacorasalida bs on r.foliobitacora = bs.folio and r.folio = bs.Foliorecoleccion
				where cp.IDEntregaOcurre =".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->destinatario 	= cambio_texto($f->destinatario);
			$f->remitente 		= cambio_texto($f->remitente);
			$arre[] = $f;
		}
		$datostabla = str_replace("null",'""', json_encode($arre));
		
		echo "({
			datoscliente:$datoscliente,
			datostabla:$datostabla
			})";
	}else if($_GET[accion]==4){
		
		
		
		$slq="UPDATE entregasocurre
			SET total='$_GET[total]', efectivo='$_GET[efectivo]', cheque='$_GET[cheque]', banco='$_GET[banco]',
			recepcion='$_GET[recepcion]',chkliste='$_GET[chkliste]',cartaporte='$_GET[cartaporte]',contrarecibocarga='$_GET[contrarecibocarga]',facturascarga='$_GET[facturascarga]',recibomaniobras='$_GET[recibomaniobras]', 
			nocheque='$_GET[ncheque]', tarjeta='$_GET[tarjeta]', transferencia='$_GET[transferencia]'
			where folio = '$_GET[folio]' and idsucursal = '$_SESSION[IDSUCURSAL]'";
		$s=mysql_query(str_replace("''","NULL",$slq),$l) or die($slq." <BR> Error en la linea ".__LINE__);
		
		$s = "select id from entregasocurre where folio = '$_GET[folio]' and idsucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$idocurre = $f->id;
		
		$s = "delete from formapago where guia='$_GET[folio]' and sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		if($_GET[total] > 0){
			
			$s = "INSERT INTO formapago SET guia='$_GET[folio]',procedencia='O',tipo='X',
			total='$_GET[total]',efectivo='$_GET[efectivo]',tarjeta='$_GET[tarjeta]',
			transferencia='$_GET[transferencia]',cheque='$_GET[cheque]',
			ncheque='$_GET[ncheque]',banco='$_GET[banco]',
			notacredito='$_GET[nc]',nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',
			usuario='$_SESSION[IDUSUARIO]',fecha=current_date, cliente = '".$_GET[cliente]."'";
			mysql_query(str_replace("''","null",$s),$l) or die($s);
		}
		
		$s = "UPDATE guiasventanilla AS gv
			INNER JOIN entregasocurre_detalle AS eo ON gv.id = eo.guia
			SET gv.estado = 'ALMACEN DESTINO'
			WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_GET[folio]'";
			mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales AS gv
			INNER JOIN entregasocurre_detalle AS eo ON gv.id = eo.guia
			SET gv.estado = 'ALMACEN DESTINO'
			WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_GET[folio]'";
			mysql_query($s,$l) or die($s);
		
		$s = "UPDATE pagoguias AS pg
		INNER JOIN entregasocurre_detalle AS eo ON pg.guia = eo.guia
		SET pg.pagado = 'N', pg.sucursalcobro=null, fechapago = null,
		pg.usuariocobro = null
		WHERE eo.sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_GET[folio]'
		AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='NO'";
		mysql_query($s,$l) or die($s);
		
		$s = "delete from entregasocurre_detalle where sucursal = '$_SESSION[IDSUCURSAL]' AND entregaocurre = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
	
		if($_GET['folios']==""){
			$guia=split(",",$_GET['folios']);
			$num=count($guia);
			for($i=0;$i<$num;$i++){
				$detalle="INSERT INTO entregasocurre_detalle(entregaocurre,guia,usuario,idusuario,fecha,sucursal)
				VALUES 
				('".$idocurre."','".$guia[$i]."','".$_SESSION['NOMBREUSUARIO']."',
				'".$_SESSION['IDUSUARIO']."',CURRENT_DATE,".$_SESSION[IDSUCURSAL].")";
				
				$res_detalle=mysql_query(str_replace("''","NULL",$detalle),$l)or die($detalle." <BR> Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasventanilla SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasempresariales SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
			}
		}
			$s = "UPDATE pagoguias AS pg
			INNER JOIN entregasocurre_detalle AS eo ON pg.guia = eo.guia
			SET pg.pagado = 'S', pg.sucursalcobro='$_SESSION[IDSUCURSAL]', fechapago = CURRENT_DATE,
			pg.usuariocobro = '$_SESSION[IDUSUARIO]'
			WHERE eo.sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_GET[folio]'
			AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='NO'";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM entregasocurre_detalle 
			WHERE entregaocurre = ".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$e = mysql_query($s,$l) or die($s);
			while($ead = mysql_fetch_object($e)){
				$s = "CALL proc_ReporteProductividad('OCURRE','','".$ead->guia."','".$_GET[fechahora]."',
				".$_SESSION[IDSUCURSAL].")";
				mysql_query($s,$l) or die($s);
			}
		
		if($_GET['folios']==""){
			$s = "delete from entregasocurre where folio = '$_GET[folio]' and idsucursal = '$_SESSION[IDSUCURSAL]'";
			mysql_query($s,$l) or die($s);
		}
			
		
			echo "guardado,".$_GET[folio];
	}
?>