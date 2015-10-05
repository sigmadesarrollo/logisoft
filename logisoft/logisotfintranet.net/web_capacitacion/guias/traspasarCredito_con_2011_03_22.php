<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion] == 1){
		$row = ObtenerFolio('traspasocredito','webpmm');
		$fecha = date('d/m/Y');
		
		echo $row[0].",".$fecha;
		
	}else if($_GET[accion] == 2){
		$row = split(",",$_GET[arre]);
		
		$s = "SELECT * FROM solicitudcredito WHERE cliente = (
			SELECT IF(tipoflete=0,idremitente,iddestinatario) FROM guiasventanilla WHERE id = '".$row[1]."'
			UNION
			SELECT IF(tipoflete=0,idremitente,iddestinatario) FROM guiasempresariales WHERE id = '".$row[1]."')
		AND estado = 'ACTIVADO'";
		$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$s = "SELECT * FROM solicitudcreditosucursaldetalle WHERE solicitud = $f->folio AND (idsucursal = '".$row[7]."' OR sucursal = 'TODAS')";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)==0)
					die("no aplica credito,".$f->ncliente);
			}
		
		$s = "INSERT INTO traspasocredito
		(fechatraspaso,guia,importe,remitente,destinatario,origen,destino,sucursaltraspaso,idusuario,idsucursal,fecha)
		VALUES
		('".cambiaf_a_mysql($row[0])."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."',
		'".$row[6]."', ".$row[7].", ".$_SESSION[IDUSUARIO].",".$_SESSION[IDSUCURSAL].",CURRENT_TIMESTAMP)";
		$r = mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "SELECT IF(gv.tipoflete=0,CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno),
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno)) AS cliente, gv.total FROM guiasventanilla gv
		INNER JOIN catalogocliente rm ON gv.idremitente = rm.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.id = '".$row[1]."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "INSERT INTO historialdetraspaso SET
		foliotraspaso 		= ".$folio.",
		sucursalsolicita 	= ".$_SESSION[IDSUCURSAL].",
		sucursalacepta 		= ".$row[7].",
		fechasolicitud 		= CURDATE(),		
		guia 				= '".$row[1]."',
		cliente 			= '".$f->cliente."',
		importe 			= '".$f->total."',
		idusuario 			= ".$_SESSION[IDUSUARIO].",
		fecha 				= CURRENT_TIMESTAMP";
		mysql_query($s,$l) or die($s);
		
		echo "ok,".$folio;
		
	}else if($_GET[accion] == 3){
		$s = "SELECT DATE_FORMAT(t.fechatraspaso,'%d/%m/%Y') AS fecha,t.guia,t.importe,t.remitente,t.destinatario,
		t.origen,t.destino,t.sucursaltraspaso FROM traspasocredito t
		INNER JOIN catalogosucursal cs ON t.sucursaltraspaso = cs.id
		WHERE t.folio=".$_GET[traspaso];
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS sucursal FROM catalogosucursal
				where id=".$f->sucursaltraspaso;
				$t = mysql_query($s,$l) or die($s);
				$tt = mysql_fetch_object($t);
				
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$f->sucursal = cambio_texto($tt->sucursal);
				$registros[] = $f;
			}
			echo str_replace('""','null',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion] == 4){
		$s = "SELECT IF(gv.tipoflete=0,gv.idremitente, gv.iddestinatario) AS cliente,
		CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno) AS ncliente FROM guiasventanilla gv
		INNER JOIN catalogocliente rm ON IF(tipoflete=0, gv.idremitente, gv.iddestinatario) = rm.id
		WHERE gv.id = '".$_GET[guia]."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT * FROM solicitudcredito WHERE cliente = '".$f->cliente."' AND estado = 'ACTIVADO'";
		$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)==0){
				die("no tiene credito,".$f->ncliente);
			}
		$s = "SELECT * FROM (
		SELECT gv.id as guia, IFNULL(gv.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS origen,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.condicionpago=1 
		AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = gv.id) AND isnull(gv.factura)
		AND ((gv.tipoflete=0 AND gv.idsucursalorigen = '$_SESSION[IDSUCURSAL]' and gv.fecha=current_Date) OR 
		(gv.tipoflete=1 AND (gv.estado='ALMACEN DESTINO' or gv.estado='ENTREGADA') AND gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]'))
		UNION
		SELECT ge.id as guia, IFNULL(ge.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS sucursalorigen,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario
		FROM guiasempresariales ge
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.tipopago='CREDITO' 
		and tipoguia = 'CONSIGNACION' AND isnull(ge.factura)
		AND ((ge.tipoflete='PAGADA' AND ge.idsucursalorigen = '$_SESSION[IDSUCURSAL]' and ge.fecha=current_Date) OR 
		(ge.tipoflete<>'PAGADA' AND (ge.estado='ALMACEN DESTINO' OR ge.estado='ENTREGADA') AND ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]'))
		AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = ge.id))
		t WHERE guia='".$_GET[guia]."'";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$registros[] = $f;
			}
			echo str_replace('""','null',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
?>