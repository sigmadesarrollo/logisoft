<?	session_start();
/*	require_once '../swiftMailer/lib/swift_required.php';
	require_once "../swiftMailer/lib/classes/Swift.php";*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){//OBTENER DATOS GENERALES
		$fecha = date("d/m/Y");		
		$row = ObtenerFolio('solicitudtelefonica','webpmm');		
		echo $fecha.",".$row[0];
		
	}else if($_GET[accion]==2){//OBTENER RESPONSABLE Y SUPERVISOR
		$s = "SELECT CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) AS empleado,
		email FROM catalogoempleado WHERE id=".$_GET[empleado]."";
		$r = mysql_query($s, $l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$f->caja = $_GET[caja];
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==3){//OBTENER FOLIO DE ATENCION TELEFONICA(RECOLECCION)
		$s = "SELECT * FROM solicitudtelefonica 
		WHERE folioatencion='".$_GET[folio]."' AND estado='POR SOLUCIONAR'";
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
		if(mysql_num_rows($r)>0){
			die("ya existe,".$f->folio);
		}
		
		/*$s = "SELECT cliente FROM recoleccion 
		WHERE folio='".$_GET[folio]."' AND sucursal=".$_GET[sucursal]."
		AND realizo='NO' AND estado<>'CANCELADO'";*/
		
		$s = "SELECT r.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		r.calle, r.numero, r.colonia, r.cp, r.poblacion, r.municipio, r.telefono2,
		IF(r.realizo='NO' OR r.realizo IS NULL,'NO REALIZADO',IF(r.realizo='SI','REALIZADO',IF(r.transmitida = 'NO' OR r.transmitida IS NULL,'NO TRANSMITIDA',IF(r.transmitida = 'SI','TRANSMITIDA','')))) AS estador
		FROM recoleccion r
		INNER JOIN catalogocliente cc ON r.cliente = cc.id
		WHERE r.folio='".$_GET[folio]."' AND r.sucursal=".$_GET[sucursal]."
		AND (r.realizo='NO' OR r.realizo IS NULL) AND r.estado<>'CANCELADO'";		
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
		if(mysql_num_rows($r)>0){
			$f->nombre = cambio_texto($f->nombre);
			$f->calle = cambio_texto($f->calle);
			$f->colonia = cambio_texto($f->colonia);
			$f->poblacion = cambio_texto($f->poblacion);
			$f->municipio = cambio_texto($f->municipio);
			$f->estador = cambio_texto($f->estador);
			$principal = str_replace('null','""',json_encode($f));
			
			echo "({principal:$principal})";
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==4){//OBTENER FOLIO RECOLECCION EVALUACION MERCANCIA
		$s = "SELECT * FROM solicitudtelefonica 
		WHERE recoleccion='".$_GET[folio]."' AND estado='POR SOLUCIONAR'";
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
		if(mysql_num_rows($r)>0){
			die("ya existe,".$f->folio);
		}
		
		$s = "SELECT g.iddestinatario FROM evaluacionmercancia e
		INNER JOIN guiasventanilla g ON g.evaluacion = e.folio
		WHERE e.recoleccion=".$_GET[folio]." AND e.sucursal=".$_GET[sucursal]."";
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
		if(mysql_num_rows($r)>0){
			echo "ok,".$f->iddestinatario;
		}
		$s = "SELECT g.iddestinatario FROM evaluacionmercancia e
		INNER JOIN guiasempresariales g ON g.evaluacion = e.folio
		WHERE e.recoleccion=".$_GET[folio]." AND e.sucursal=".$_GET[sucursal]."";
		$ro = mysql_query($s, $l) or die($s);
		$fo = mysql_fetch_object($ro);
		if(mysql_num_rows($ro)>0){
			echo "ok,".$fo->iddestinatario;
		}
	}else if($_GET[accion]==5){//REGISTRAR SOLICITUD TELEFONICA
		$row = split(",",$_GET[arre]);				
		$folioactividad = "";
		
		if($_GET[queja] == "RECOLECCION"){
			$s = "call proc_VentasVsPresupuesto('CA RECOLECCION','',$_SESSION[IDSUCURSAL]);";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT fecharecoleccion FROM recoleccion 
			WHERE folio = '".$row[3]."' AND sucursal = ".$row[1]."";
			$re = mysql_query($s,$l) or die($s); $rec = mysql_fetch_object($re);
			
			$s = "INSERT actividadusuario SET 
			cliente = '".$row[13]."', recoleccion = '".$row[3]."', fechareferencia = '".$rec->fecharecoleccion."',
			tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), idsucursal = ".$_SESSION[IDSUCURSAL].",
			razonqueja = UCASE('".$row[2]."'), personaqueja = UCASE('".$row[6]."')";
			mysql_query($s,$l) or die($s);
			$folioactividad = mysql_insert_id();
			
		}else if($_GET[queja] == "EAD MAL EFECTUADAS"){
			$s = "call proc_VentasVsPresupuesto('CA EAD','',$_SESSION[IDSUCURSAL]);";
			mysql_query($s,$l) or die($s);
			
			if(!empty($row[4])){
				$s = "SELECT fecha, total FROM guiasventanilla WHERE id = '".$row[4]."' 
				UNION
				SELECT fecha, total FROM guiasempresariales WHERE id = '".$row[4]."'";
				$rt = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($rt);
			
				$s = "INSERT actividadusuario SET 
				cliente = '".$row[13]."', danofaltante = '',
				tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
				idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$row[4]."',
				fechareferencia = '".$ff->fecha."', importe = '".$ff->total."', idsucursal = ".$_SESSION[IDSUCURSAL].",
				razonqueja = UCASE('".$row[2]."'), personaqueja = UCASE('".$row[6]."')";
				mysql_query($s,$l) or die($s);
				$folioactividad = mysql_insert_id();
			}
		}else if($_GET[queja] == "CONVENIOS NO APLICADOS"){
			if(!empty($row[4])){
				$s = "SELECT fecha, total FROM guiasventanilla WHERE id = '".$row[4]."' 
				UNION
				SELECT fecha, total FROM guiasempresariales WHERE id = '".$row[4]."'";
				$rt = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($rt);
			
				$s = "INSERT actividadusuario SET 
				cliente = '".$row[13]."', danofaltante = '',
				tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
				idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$row[4]."',
				fechareferencia = '".$ff->fecha."', importe = '".$ff->total."', idsucursal = ".$_SESSION[IDSUCURSAL].",
				razonqueja = UCASE('".$row[2]."'), personaqueja = UCASE('".$row[6]."')";
				mysql_query($s,$l) or die($s);
				$folioactividad = mysql_insert_id();
			}
		}else if($_GET[queja] == "OTROS SERVICIOS"){
				$s = "INSERT actividadusuario SET 
				cliente = '".$row[13]."', danofaltante = '',
				tipo = 'otros servicios', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
				idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$row[4]."',
				fechareferencia = current_date, importe = '0.00', idsucursal = ".$_SESSION[IDSUCURSAL].",
				razonqueja = UCASE('".$row[2]."'), personaqueja = UCASE('".$row[6]."')";
				mysql_query($s,$l) or die($s);
				$folioactividad = mysql_insert_id();
		}else if($_GET[queja] == "QUEJAS DANOS Y FALTANTES"){
			$s = "SELECT nguia FROM moduloquejasdanosfaltantes WHERE nguia = '".$row[4]."'";
			$rr = mysql_query($s,$l) or die($s); 
			$fo = mysql_fetch_object($rr);
			
			$s = "SELECT fecha, total FROM guiasventanilla WHERE id = '".$fo->nguia."' 
			UNION
			SELECT fecha, total FROM guiasempresariales WHERE id = '".$fo->nguia."'";
			$rt = mysql_query($s,$l) or die($s); 
			$ff = mysql_fetch_object($rt);
			
			$s = "INSERT actividadusuario SET 
			cliente = '".$row[13]."', danofaltante = '".$row[14]."',
			tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$fo->nguia."',
			fechareferencia = '".$ff->fecha."', importe = '".$ff->total."', idsucursal = ".$_SESSION[IDSUCURSAL].",
			razonqueja = UCASE('QUEJAS DAÑOS Y FALTANTES'), personaqueja = UCASE('".$row[6]."')";
			mysql_query($s,$l) or die($s);
			$folioactividad = mysql_insert_id();
		}
		
		$s = "INSERT INTO solicitudtelefonica (fechaqueja,estado,sucursal,queja,
		folioatencion,guia,recoleccion, foliofaltante, cliente, nombre,telefono,email,empresa,
		observaciones,responsable,supervisor,idusuario,fecha,folioactividad)
		VALUES
		('".cambiaf_a_mysql($row[0])."','POR SOLUCIONAR','".$row[1]."',UCASE('".$row[2]."'),
		'".$row[3]."', '".$row[4]."','".$row[5]."', '".$row[14]."', '".$row[13]."', UCASE('".$row[6]."'),'".$row[7]."',
		'".$row[8]."', UCASE('".$row[9]."'),UCASE('".$row[10]."'),".$row[11].",".$row[12].",
		".$_SESSION[IDUSUARIO].",CURRENT_TIMESTAMP(),'".$folioactividad."')";
		$r = mysql_query(str_replace("''",'null', $s), $l) or die($s);
		$folio = mysql_insert_id();
		
		$correos = envioMeil($folio,$row[8],$_GET[direccion2],$_GET[direccion3]);
		
		echo "ok,".$folio.",".$correos.",".$folioactividad;
		
	}else if($_GET[accion]==6){//MODIFICAR SOLICITUD TELEFONICA
		
		$row = split(",",$_GET[arre]);		
		$s = "UPDATE solicitudtelefonica SET
		fechaqueja='".cambiaf_a_mysql($row[0])."', estado='POR SOLUCIONAR', sucursal=".$row[1].",
		queja='".$row[2]."', folioatencion='".$row[3]."', guia='".$row[4]."',
		recoleccion='".$row[5]."', foliofaltante='".$row[14]."', cliente=".$row[13].",
		nombre=UCASE('".$row[6]."'),telefono='".$row[7]."',
		email='".$row[8]."',empresa=UCASE('".$row[9]."'), observaciones=UCASE('".$row[10]."'),
		responsable=".$row[11].",supervisor=".$row[12].",
		idusuario=".$_SESSION[IDUSUARIO].", fecha=CURRENT_TIMESTAMP()
		WHERE folio=".$_GET[folio]."";
		$r = mysql_query(str_replace("''",'null', $s), $l) or die($s);
		
		/*if($row[14]!=0 && $_GET[responsable]!=$row[11]){
			$s = "SELECT nguia FROM moduloquejasdanosfaltantes WHERE folio = '".$row[14]."'";
			$rr = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($rr);
			
			$s = "SELECT fecha, total FROM guiasventanilla WHERE id = '".$fo->guia."' 
			UNION
			SELECT fecha, total FROM guiasempresariales WHERE id = '".$fo->guia."'";
			$rt = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($rt);
		
			$s = "UPDATE actividadusuario SET 
			cliente = '".$row[13]."', danofaltante = '".$row[14]."',
			tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$fo->guia."',
			fechareferencia = '".$ff->fecha."', importe = '".$ff->total."', idsucursal = ".$_SESSION[IDSUCURSAL].",
			razonqueja = UCASE('".$row[9]."')
			WHERE id = ".$_GET[actividad];
			mysql_query($s,$l) or die($s);
		}
		
		if($row[3]!="" && $_GET[responsable]!=$row[11]){
			$s = "UPDATE actividadusuario SET 
			cliente = '".$row[13]."', recoleccion = '".$row[3]."',
			tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), idsucursal = ".$_SESSION[IDSUCURSAL]."
			WHERE id = ".$_GET[actividad];
			mysql_query($s,$l) or die($s);
		}*/
		
		
		if($_GET[queja] == "RECOLECCION"){
			$s = "UPDATE actividadusuario SET 
			cliente = '".$row[13]."', recoleccion = '".$row[3]."',
			tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), idsucursal = ".$_SESSION[IDSUCURSAL].",
			razonqueja = UCASE('".$row[9]."'), personaqueja = UCASE('".$row[6]."')
			WHERE id = ".$_GET[actividad]."";
			
			mysql_query($s,$l) or die($s);
			$folioactividad = mysql_insert_id();
			
		}else if($_GET[queja] == "EAD MAL EFECTUADAS"){
			if(!empty($row[4])){
				$s = "SELECT fecha, total FROM guiasventanilla WHERE id = '".$row[4]."' 
				UNION
				SELECT fecha, total FROM guiasempresariales WHERE id = '".$row[4]."'";
				$rt = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($rt);
			
				$s = "UPDATE actividadusuario SET 
				cliente = '".$row[13]."', danofaltante = '',
				tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
				idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$row[4]."',
				fechareferencia = '".$ff->fecha."', importe = '".$ff->total."', idsucursal = ".$_SESSION[IDSUCURSAL].",
				razonqueja = UCASE('".$row[9]."'), personaqueja = UCASE('".$row[6]."')
				WHERE id = ".$_GET[actividad]."";
				mysql_query($s,$l) or die($s);
				$folioactividad = mysql_insert_id();
			}
		}else if($_GET[queja] == "CONVENIOS NO APLICADOS"){
			if(!empty($row[4])){
				$s = "SELECT fecha, total FROM guiasventanilla WHERE id = '".$row[4]."' 
				UNION
				SELECT fecha, total FROM guiasempresariales WHERE id = '".$row[4]."'";
				$rt = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($rt);
			
				$s = "UPDATE actividadusuario SET 
				cliente = '".$row[13]."', danofaltante = '',
				tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
				idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$row[4]."',
				fechareferencia = '".$ff->fecha."', importe = '".$ff->total."', idsucursal = ".$_SESSION[IDSUCURSAL].",
				razonqueja = UCASE('".$row[9]."'), personaqueja = UCASE('".$row[6]."')
				WHERE id = ".$_GET[actividad]."";
				mysql_query($s,$l) or die($s);
				$folioactividad = mysql_insert_id();
			}
		}else if($_GET[queja] == "OTROS SERVICIOS"){
			$folioactividad = 0;
		}else if($_GET[queja] == "QUEJAS DANOS Y FALTANTES"){
			$s = "SELECT nguia FROM moduloquejasdanosfaltantes WHERE nguia = '".$row[4]."'";
			$rr = mysql_query($s,$l) or die($s); 
			$fo = mysql_fetch_object($rr);
			
			$s = "SELECT fecha, total FROM guiasventanilla WHERE id = '".$fo->nguia."' 
			UNION
			SELECT fecha, total FROM guiasempresariales WHERE id = '".$fo->nguia."'";
			$rt = mysql_query($s,$l) or die($s); 
			$ff = mysql_fetch_object($rt);
		
			$s = "UPDATE actividadusuario SET 
			cliente = '".$row[13]."', danofaltante = '".$row[14]."',
			tipo = 'cat', estado = 0, asignado = 'SI', fechaasignada = CURDATE(), empleado = ".$row[11].",
			idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP(), referencia = '".$fo->nguia."',
			fechareferencia = '".$ff->fecha."', importe = '".$ff->total."', idsucursal = ".$_SESSION[IDSUCURSAL].",
			razonqueja = UCASE('".$row[9]."'), personaqueja = UCASE('".$row[6]."')
			WHERE id = ".$_GET[actividad]."";
			mysql_query($s,$l) or die($s);
			$folioactividad = mysql_insert_id();
		}
		
		
		echo "ok";
	}else if($_GET[accion]==7){//MOSTRAR DETALLE BITACORA DE QUEJAS
		$s = "SELECT st.folio, DATE_FORMAT(st.fechaqueja,'%d/%m/%Y') AS fecha,
		cs.descripcion AS sucursal, st.queja, st.observaciones,
		DATE_FORMAT(IF(ISNULL(st.fechaposible),mq.fechaposible,st.fechaposible),'%d/%m/%Y') AS solucion,
		IF(st.fechaposible IS NULL,'',DATE_FORMAT(st.fechaposible,'%d/%m/%Y')) AS comparar,
		st.observacionesposible AS comentarios,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
		CASE st.queja WHEN 'RECOLECCION' THEN st.folioatencion
		WHEN 'EAD MAL EFECTUADAS' THEN IF(st.guia IS NULL,st.recoleccion,st.guia)
		WHEN 'CONVENIOS NO APLICADOS' THEN st.guia
		WHEN 'QUEJAS DANOS Y FALTANTES' THEN st.guia
		WHEN 'OTROS SERVICIOS' THEN '' END AS foliodoc,
		r.estado,st.estado estqueja, st.folioactividad
		FROM solicitudtelefonica st
		INNER JOIN catalogosucursal cs ON st.sucursal = cs.id
		INNER JOIN catalogoempleado ce ON st.responsable=ce.id
		LEFT JOIN recoleccion r ON st.folioatencion = r.folio AND r.sucursal = st.sucursal
		LEFT JOIN moduloquejasdanosfaltantes mq ON st.foliofaltante = mq.folio
		LEFT JOIN guiasventanilla g ON st.guia=g.id
		WHERE st.estado='POR SOLUCIONAR' AND (ISNULL(r.realizo) OR r.realizo='NO')
		AND (ISNULL(g.estado) OR g.estado <>'ENTREGADA')
		UNION
		SELECT st.folio, DATE_FORMAT(st.fechaqueja,'%d/%m/%Y') AS fecha,
		cs.descripcion AS sucursal, st.queja, st.observaciones,
		DATE_FORMAT(IF(ISNULL(st.fechaposible),mq.fechaposible,st.fechaposible),'%d/%m/%Y') AS solucion,
		IF(st.fechaposible IS NULL,'',DATE_FORMAT(st.fechaposible,'%d/%m/%Y')) AS comparar,
		st.observacionesposible AS comentarios,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
		CASE st.queja WHEN 'RECOLECCION' THEN st.folioatencion
		WHEN 'EAD MAL EFECTUADAS' THEN IF(st.guia IS NULL,st.recoleccion,st.guia)
		WHEN 'CONVENIOS NO APLICADOS' THEN st.guia
		WHEN 'QUEJAS DANOS Y FALTANTES' THEN st.guia
		WHEN 'OTROS SERVICIOS' THEN '' END AS foliodoc,
		r.estado,st.estado estqueja, st.folioactividad
		FROM solicitudtelefonica st
		INNER JOIN catalogosucursal cs ON st.sucursal = cs.id
		INNER JOIN catalogoempleado ce ON st.responsable=ce.id
		LEFT JOIN recoleccion r ON st.folioatencion = r.folio AND r.sucursal = st.sucursal
		LEFT JOIN guiasempresariales g ON st.guia=g.id
		LEFT JOIN moduloquejasdanosfaltantes mq ON st.foliofaltante = mq.folio
		WHERE st.estado='POR SOLUCIONAR' AND (ISNULL(r.realizo) OR r.realizo='NO')
		AND (ISNULL(g.estado) OR g.estado <>'ENTREGADA')";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$f->solucion = (($f->solucion=="00/00/0000")? "" : $f->solucion);
			$f->sucursal = cambio_texto($f->sucursal);
			$f->observaciones = cambio_texto($f->observaciones);
			$f->comentarios = cambio_texto($f->comentarios);
			$f->responsable = cambio_texto($f->responsable);
			$f->foliodoc = cambio_texto($f->foliodoc);
			$f->estqueja = cambio_texto($f->estqueja);
			$registros[] = $f;
		}
		
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==8){//REGISTRAR FECHA POSIBLE Y OBSERVACIONES
		$row = split(",",$_GET[arre]);
		$s = "UPDATE solicitudtelefonica SET fechaposible='".cambiaf_a_mysql($row[0])."', observacionesposible='".$row[1]."'
		WHERE folio=".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion]==9){//OBTENER DATOS SOLICITUD QUEJA
		$s = "SELECT DATE_FORMAT(st.fechaqueja,'%d/%m/%Y') AS fechaqueja, st.estado,
		cs.descripcion AS descripcionsucursal, st.sucursal, st.queja, st.folioatencion, st.guia,
		st.recoleccion, st.cliente, st.nombre, st.telefono, st.email,
		st.empresa, st.observaciones, st.responsable,
		CONCAT(re.nombre,' ',re.apellidopaterno,' ',re.apellidomaterno) AS nombreresponsable,
		re.email AS emailresponsable, st.supervisor,
		CONCAT(su.nombre,' ',su.apellidopaterno,' ',su.apellidomaterno) AS nombresupervisor,
		su.email AS emailsupervisor, st.folioactividad
		FROM solicitudtelefonica st
		INNER JOIN catalogosucursal cs ON st.sucursal = cs.id
		INNER JOIN catalogoempleado re ON st.responsable=re.id
		INNER JOIN catalogoempleado su ON st.supervisor=su.id
		WHERE st.folio=".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		$registro = array();
		while($f = mysql_fetch_object($r)){
			$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
			FROM catalogosucursal WHERE id = ".$f->sucursal;
			$t = mysql_query($s,$l) or die($s);
			$tt = mysql_fetch_object($t);
			
			$f->estado = cambio_texto($f->estado);
			$f->descripcionsucursal = cambio_texto($tt->descripcion);
			$f->queja = cambio_texto($f->queja);
			$f->nombre = cambio_texto($f->nombre);
			$f->email = cambio_texto($f->email);
			$f->empresa = cambio_texto($f->empresa);
			$f->observaciones = cambio_texto($f->observaciones);
			$f->nombreresponsable = cambio_texto($f->nombreresponsable);
			$f->emailresponsable = cambio_texto($f->emailresponsable);
			$f->nombresupervisor = cambio_texto($f->nombresupervisor);
			$f->emailsupervisor = cambio_texto($f->emailsupervisor);
			$registro[] = $f;
		}		
		echo str_replace('null','""',json_encode($registro));
	}else if($_GET[accion]==10){
		$s = "SELECT DATE_FORMAT(mq.fecharegistro,'%d/%m/%Y') AS fecha, mq.estado,
		mq.idsucursal, cs.descripcion AS sucursal, mq.nguia, mq.idcliente AS cliente,
		mq.nombre, mq.observaciones, mq.idresponsable,
		CONCAT(re.nombre,' ',re.apellidopaterno,' ',re.apellidomaterno) AS nombreresponsable,
		re.email AS emailresponsable
		FROM moduloquejasdanosfaltantes mq
		INNER JOIN catalogosucursal cs ON mq.idsucursal = cs.id
		INNER JOIN catalogoempleado re ON mq.idresponsable= re.id
		WHERE mq.idsucursal=".$_GET[sucursal]." AND mq.folio=".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		$registro = array();
		while($f = mysql_fetch_object($r)){
			
			$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
			FROM catalogosucursal WHERE id = ".$f->idsucursal;
			$t = mysql_query($s,$l) or die($s);
			$tt = mysql_fetch_object($t);
		
		
			$f->estado = cambio_texto($f->estado);
			$f->sucursal = cambio_texto($tt->descripcion);			
			$f->nombre = cambio_texto($f->nombre);
			$f->observaciones = cambio_texto($f->observaciones);
			$f->nombreresponsable = cambio_texto($f->nombreresponsable);
			$f->emailresponsable = cambio_texto($f->emailresponsable);			
			$registro[] = $f;
		}		
		echo str_replace('null','""',json_encode($registro));
	
	}else if($_GET[accion]==11){
		$s = "SELECT * FROM solicitudtelefonica 
		WHERE guia='".$_GET[guia]."' AND estado='POR SOLUCIONAR'";
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
		if(mysql_num_rows($r)>0){
			die("ya existe,".$f->folio);
		}
		
		$s = "SELECT guia, estado, idremitente, iddireccionremitente FROM 
		(SELECT id as guia, estado, idremitente,iddireccionremitente FROM guiasventanilla
		WHERE id = '".$_GET[guia]."'
		UNION
		SELECT id as guia, estado, idremitente, iddireccionremitente FROM guiasempresariales
		WHERE id = '".$_GET[guia]."') t";
		$r = mysql_query($s,$l) or die($s);
		$registro = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->estado = cambio_texto($f->estado);				
				$registro[] = $f;
			}
			echo str_replace('null','""',json_encode($registro));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==12){//CAMBIAR ESTADO SOLICITUD TELEFONICA
		$row = split(",",$_GET[folios]);
		for($i=0;$i<count($row);$i++){
			$s = "UPDATE solicitudtelefonica SET estado = 'SOLUCIONADO'
			WHERE folio = ".$row[$i]."";
			$r = mysql_query($s,$l) or die($s);
		}
		$s = "UPDATE actividadusuario SET estado = 1 WHERE id IN(".$_GET[actividad].")";
		mysql_query($s,$l) or die($s);
		echo "ok";
	}else if($_GET[accion]==13){
		$fecha = calcularHabiles(date('d'),date('m'),date('Y'),'30');
		echo $fecha;
	}
	
	function enviarMeil($folio){
		$s = "SELECT s.folio, DATE_FORMAT(s.fechaqueja,'%d/%m/%Y') AS fecha, s.queja,
	s.guia, s.recoleccion, s.foliofaltante, s.folioatencion, 
	CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
	CONCAT_WS(' ',ce2.nombre,ce2.apellidopaterno,ce2.apellidomaterno) AS supervisor,
	s.nombre, s.observaciones, s.email, d.poblacion, d.telefono, CONCAT(d.calle,' #',d.numero,', ',d.colonia) as domicilio,
	ce.email as emailresponsable, ce2.email as emailsupervisor
	FROM solicitudtelefonica s
	INNER JOIN catalogocliente cc ON s.cliente = cc.id
	INNER JOIN catalogoempleado ce ON s.responsable = ce.id
	INNER JOIN catalogoempleado ce2 ON s.supervisor = ce2.id
	INNER JOIN direccion d ON s.cliente = d.codigo AND d.origen = 'cl'
	WHERE s.folio = ".$folio."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$documento = "";
	if(!empty($f->guia)){
		$documento = $f->guia;
	}
	
	if(!empty($f->recoleccion)){
		$documento = $f->recoleccion;
	}
	
	if(!empty($f->foliofaltante)){
		$documento = $f->foliofaltante;
	}
	
	if(!empty($f->folioatencion)){
		$documento = $f->folioatencion;
	}
	
	//Creamos el cuerpo
	$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100" rowspan="6"><img src="http://www.pmmintranet.net/web_pruebas/fpdf/logo.jpg" width="95" height="100" /></td>
    <td width="450" height="30" align="center"><strong>PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</strong></td>
  </tr>
  <tr>
    <td height="19" align="center"><strong>Centro de Atenci&oacute;n Telef&oacute;nica</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Estimado(a):</strong> '.$f->nombrecliente.' </td>
  </tr>
  <tr>
    <td>Su Solicitud de Atenci&oacute;n fue registrada con los siguientes datos:</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><p>Fecha: '.$f->fecha.'<br />
N&uacute;mero Solicitud de Atenci&oacute;n: '.$f->folio.'<br />
Tipo de Solicitud de Atenci&oacute;n: &quot;QUEJA&quot;<br />
Documento: '.$documento.'<br />
Comentario: '.$f->observaciones.'<br />
Cliente: '.$f->nombre.'<br />
Domicilio: '.$f->domicilio.'<br />
Ciudad: '.$f->poblacion.'<br />
Tel&eacute;fono: '.$f->poblacion.'<br />
Correo: '.$f->email.'<br />
Ejecutivo de servicio asignado: '.$f->responsable.'</p>
      <p>Su solicitud de Atenci&oacute;n ser&aacute; atendida a la brevedad por  personal calificado.</p>
      <p>Los datos proporcionados quedan sujetos a validaci&oacute;n. En  caso de existir alg&uacute;n problema, se le enviar&aacute; un correo electr&oacute;nico.</p>
      <p>Saludos.<br />
        Centro de Atenci&oacute;n Telef&oacute;nica PMM<br />
  &nbsp;<br />
        Este es un correo de car&aacute;cter informativo, favor de no  responderlo.</p>
    Si tienes alguna duda, puedes dirigirte a nuestra secci&oacute;n de Asistencia  y Soporte en: www.pmm.com.mx</td>
  </tr>
</table>
</body>
</html>';
	
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                                              ->setUsername('pruebaspmm@gmail.com')
                                              ->setPassword('pmm123456789');

		//Creamos el mailer pasándole el transport con la configuración de gmail
		$mailer = Swift_Mailer::newInstance($transport);	
		
		//Creamos el mensaje
		$message = Swift_Message::newInstance("Centro de Atención Telefonica PMM")
                                     ->setFrom(array('pmm@pmm.com.mx' => 'Paqueteria y Mensajeria en Movimiento'))
									 ->setTo(array($f->email => $f->nombre))
									 ->setCc(array($f->emailresponsable,$f->emailsupervisor))
                                     ->setBody($body, "text/html");		
		//Enviamos
		$result = $mailer->send($message);
	}
	
	
	function envioMeil($folio,$direccion,$direccion2,$direccion3){
		$l = Conectarse("webpmm");
		$correos = "";
		
		$s = "SELECT s.folio, DATE_FORMAT(s.fechaqueja,'%d/%m/%Y') AS fecha, s.queja,
	s.guia, s.recoleccion, s.foliofaltante, s.folioatencion, 
	CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
	CONCAT_WS(' ',ce2.nombre,ce2.apellidopaterno,ce2.apellidomaterno) AS supervisor,
	s.nombre, s.observaciones, s.email, d.poblacion, d.telefono, CONCAT(d.calle,' #',d.numero,', ',d.colonia) as domicilio,
	ce.email as emailresponsable, ce2.email as emailsupervisor
	FROM solicitudtelefonica s
	INNER JOIN catalogocliente cc ON s.cliente = cc.id
	INNER JOIN catalogoempleado ce ON s.responsable = ce.id
	INNER JOIN catalogoempleado ce2 ON s.supervisor = ce2.id
	INNER JOIN direccion d ON s.cliente = d.codigo AND d.origen = 'cl'
	WHERE s.folio = ".$folio."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$documento = "";
	if(!empty($f->guia)){
		$documento = $f->guia;
	}
	
	if(!empty($f->recoleccion)){
		$documento = $f->recoleccion;
	}
	
	if(!empty($f->foliofaltante)){
		$documento = $f->foliofaltante;
	}
	
	if(!empty($f->folioatencion)){
		$documento = $f->folioatencion;
	}
	
	//Creamos el cuerpo
	$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100" rowspan="6"><img src="http://www.pmmintranet.net/web_pruebas/fpdf/logo.jpg" width="95" height="100" /></td>
    <td width="450" height="30" align="center"><strong>PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</strong></td>
  </tr>
  <tr>
    <td height="19" align="center"><strong>Centro de Atenci&oacute;n Telef&oacute;nica</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Estimado(a):</strong> '.$f->nombrecliente.' </td>
  </tr>
  <tr>
    <td>Su Solicitud de Atenci&oacute;n fue registrada con los siguientes datos:</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><p>Fecha: '.$f->fecha.'<br />
N&uacute;mero Solicitud de Atenci&oacute;n: '.$f->folio.'<br />
Tipo de Solicitud de Atenci&oacute;n: &quot;QUEJA&quot;<br />
Documento: '.$documento.'<br />
Comentario: '.$f->observaciones.'<br />
Cliente: '.$f->nombre.'<br />
Domicilio: '.$f->domicilio.'<br />
Ciudad: '.$f->poblacion.'<br />
Tel&eacute;fono: '.$f->poblacion.'<br />
Correo: '.$f->email.'<br />
Ejecutivo de servicio asignado: '.$f->responsable.'</p>
      <p>Su solicitud de Atenci&oacute;n ser&aacute; atendida a la brevedad por  personal calificado.</p>
      <p>Los datos proporcionados quedan sujetos a validaci&oacute;n. En  caso de existir alg&uacute;n problema, se le enviar&aacute; un correo electr&oacute;nico.</p>
      <p>Saludos.<br />
        Centro de Atenci&oacute;n Telef&oacute;nica PMM<br />
  &nbsp;<br />
        Este es un correo de car&aacute;cter informativo, favor de no  responderlo.</p>
    Si tienes alguna duda, puedes dirigirte a nuestra secci&oacute;n de Asistencia  y Soporte en: www.pmm.com.mx</td>
  </tr>
</table>
</body>
</html>';				
		$asunto = 'Solicitud Telefonica';
		$cabeceras = "From: solicitudcat@pmmintranet.net\r\nContent-type: text/html\r\n";

		if(!empty($direccion)){
		
			mail($direccion,$asunto,$body,$cabeceras);
			
			$correos = $direccion;
		}

		

		if(!empty($direccion2)){

			//mail($direccion2,"Solicitud Telefonica",$body,"FROM: PMM <webmaster@pmm.com.mx>\n");
			mail($direccion2,$asunto,$body,$cabeceras);
			if(!empty($direccion)){

				$correos .= $direccion2;

			}else{

				$correos = $direccion2;

			}

		}

		

		if(!empty($direccion3)){

			//mail($direccion3,"Solicitud Telefonica",$body,"FROM: PMM <webmaster@pmm.com.mx>\n"); 
			mail($direccion3,$asunto,$body,$cabeceras);
			
			if(!empty($direccion)){

				$correos .= $direccion3;

			}else{

				$correos = $direccion3;

			}

		}	

		return $correos;

	}
	
	if($_GET[accion]==14){
		envioMeil("","","");
	}
	
?>